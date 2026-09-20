<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Groups list — tu groups ambazo user ni member
     */
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $isPrivileged = $user->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod']);

        if ($isPrivileged) {
            // Admin/CEO/BOD wanaona groups zote
            $groups = Group::active()
                ->with(['creator', 'groupMembers.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Staff wanaona tu groups ambazo ni member
            $groups = Group::active()
                ->forUser($userId)
                ->with(['creator', 'groupMembers.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('communication.groups', compact('groups', 'isPrivileged'));
    }

    /**
     * Create form — kwa admin/ceo/bod TU
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod'])) {
            abort(403, 'Only Admin, CEO, or BOD can create groups.');
        }

        $users = User::orderBy('username')->get();
        return view('communication.groups-create', compact('users'));
    }

    /**
     * Store group — kwa admin/ceo/bod TU
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod'])) {
            abort(403, 'Only Admin, CEO, or BOD can create groups.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'members'     => 'array',
            'members.*'   => 'integer|exists:users,id',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:50',
        ]);

        $group = Group::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type'        => 'custom',
            'created_by'  => Auth::id(),
            'members'     => $validated['members'] ?? [],
            'color'       => $validated['color'] ?? 'purple',
            'icon'        => $validated['icon'] ?? 'fa-users',
            'is_active'   => true,
        ]);

        // Ongeza creator kama admin
        GroupMember::create([
            'group_id' => $group->id,
            'user_id'  => Auth::id(),
            'role'     => 'admin',
            'joined_at' => now(),
        ]);

        // Ongeza members wengine
        if (!empty($validated['members'])) {
            foreach ($validated['members'] as $memberId) {
                if ($memberId == Auth::id()) continue;
                GroupMember::create([
                    'group_id' => $group->id,
                    'user_id'  => $memberId,
                    'role'     => 'member',
                    'joined_at' => now(),
                ]);
            }
        }

        return redirect()->route('communication.groups-show', $group->id)
            ->with('success', 'Group created successfully.');
    }

    /**
     * Show group + chat
     */
    public function show(Group $group)
    {
        $userId = Auth::id();
        $user = Auth::user();

        if (!$group->isMember($userId) && !$user->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'You are not a member of this group.');
        }

        $messages = GroupMessage::where('group_id', $group->id)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        $members = $group->groupMembers()->with('user')->get();
        $canManage = $group->canManage($userId);

        // Mark group as read
        GroupMember::where('group_id', $group->id)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        return view('communication.groups-show', compact('group', 'messages', 'members', 'canManage'));
    }

    /**
     * Send message kwenye group
     */
    public function sendMessage(Request $request, Group $group)
    {
        $userId = Auth::id();

        if (!$group->isMember($userId)) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        GroupMessage::create([
            'group_id'  => $group->id,
            'sender_id' => $userId,
            'body'      => $validated['body'],
        ]);

        // Send notifications kwa members wengine
        $members = $group->groupMembers()->where('user_id', '!=', $userId)->get();
        foreach ($members as $member) {
            if ($member->user) {
                $member->user->notify(new \App\Notifications\NewGroupMessageNotification($group, Auth::user(), $validated['body']));
            }
        }

        return back()->with('success', 'Message sent.');
    }

    /**
     * Add member kwenye group — kwa admin/ceo/bod group admin
     */
    public function addMember(Request $request, Group $group)
    {
        $userId = Auth::id();
        if (!$group->canManage($userId)) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if (!$group->isMember($validated['user_id'])) {
            GroupMember::create([
                'group_id' => $group->id,
                'user_id'  => $validated['user_id'],
                'role'     => 'member',
                'joined_at' => now(),
            ]);
        }

        return back()->with('success', 'Member added successfully.');
    }

    /**
     * Remove member — kwa admin/ceo/bod TU
     */
    public function removeMember(Group $group, User $user)
    {
        $authUser = Auth::user();
        if (!$authUser->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod'])) {
            abort(403, 'Only Admin/CEO/BOD can remove members.');
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'You cannot remove yourself.');
        }

        GroupMember::where('group_id', $group->id)->where('user_id', $user->id)->delete();

        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Delete group — kwa admin/ceo/bod
     */
    public function destroy(Group $group)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['super_admin', 'admin', 'ceo', 'bod'])) {
            abort(403);
        }

        $group->delete();
        return redirect()->route('communication.groups')
            ->with('success', 'Group deleted successfully.');
    }
}