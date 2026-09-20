<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Announcement;
use App\Models\Group;
use App\Models\SharedFile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    /**
     * Communication dashboard
     */
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'inbox'         => Message::inbox($userId)->count(),
            'unread'        => Message::unread($userId)->count(),
            'sent'          => Message::sent($userId)->count(),
            'drafts'        => Message::drafts($userId)->count(),
            'conversations' => Conversation::whereHas('participants', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'groups'        => Group::active()->count(),
            'announcements' => Announcement::published()->count(),
            'shared_files'  => SharedFile::visibleTo($userId)->count(),
        ];

        $recentMessages = Message::inbox($userId)
            ->with(['sender'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentAnnouncements = Announcement::published()
            ->with(['creator'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('communication.index', compact('stats', 'recentMessages', 'recentAnnouncements'));
    }

    /**
     * Inbox
     */
    public function inbox(Request $request)
    {
        $query = Message::inbox(Auth::id())->with(['sender']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('body', 'like', "%{$request->search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('communication.inbox', compact('messages'));
    }

    /**
     * Sent messages
     */
    public function sent(Request $request)
    {
        $query = Message::sent(Auth::id())->with(['recipient']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('body', 'like', "%{$request->search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('communication.sent', compact('messages'));
    }

    /**
     * Drafts
     */
    public function drafts()
    {
        $messages = Message::drafts(Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('communication.drafts', compact('messages'));
    }

    /**
     * Show single message
     */
    public function showMessage(Message $message)
    {
        // Kama ni recipient, mark as read
        if ($message->recipient_id === Auth::id() && !$message->read_at) {
            $message->update([
                'read_at' => now(),
                'status' => 'read',
            ]);
        }

        return view('communication.message-show', compact('message'));
    }

    /**
     * Create message form
     */
    public function createMessage(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('username')->get();

        $replyTo = null;
        if ($request->filled('reply_to')) {
            $replyTo = Message::find($request->reply_to);
        }

        return view('communication.message-create', compact('users', 'replyTo'));
    }

    /**
     * Store message (draft or send)
     */
    public function storeMessage(Request $request)
    {
        $action = $request->input('action', 'send');

        $validated = $request->validate([
            'recipient_id' => 'required|integer|exists:users,id',
            'subject'      => 'nullable|string|max:255',
            'body'         => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id'    => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
            'subject'      => $validated['subject'] ?? '(No Subject)',
            'body'         => $validated['body'],
            'is_draft'     => $action === 'draft',
            'status'       => $action === 'draft' ? 'draft' : 'sent',
            'sent_at'      => $action === 'send' ? now() : null,
        ]);

        if ($action === 'draft') {
            return redirect()->route('communication.drafts')
                ->with('success', 'Message saved as draft successfully.');
        }

        // TUMA NOTIFICATION KWA RECIPIENT
        $recipient = User::find($validated['recipient_id']);
        if ($recipient) {
            $recipient->notify(new \App\Notifications\NewMessageNotification($message));
        }

        return redirect()->route('communication.sent')
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Mark message as unread
     */
    public function markUnread(Message $message)
    {
        if ($message->recipient_id === Auth::id()) {
            $message->update(['read_at' => null, 'status' => 'sent']);
        }

        return back()->with('success', 'Message marked as unread.');
    }

    /**
     * Delete message (for me only)
     */
    public function destroyMessage(Message $message)
    {
        $userId = Auth::id();

        if ($message->sender_id === $userId) {
            $message->update(['deleted_for_sender' => true]);
        } elseif ($message->recipient_id === $userId) {
            $message->update(['deleted_for_recipient' => true]);
        } else {
            abort(403);
        }

        return back()->with('success', 'Message deleted successfully.');
    }

    /**
     * Conversations list
     */
    public function conversations()
    {
        $conversations = Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', Auth::id());
        })->with(['users', 'latestMessage'])
          ->orderBy('last_message_at', 'desc')
          ->paginate(20);

        return view('communication.conversations', compact('conversations'));
    }
}