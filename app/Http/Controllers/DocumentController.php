<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * List all documents with filters
     */
    public function index(Request $request)
    {
        $query = Document::query()->with(['uploader', 'employee', 'project']);

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('document_number', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total'    => Document::count(),
            'active'   => Document::where('status', 'active')->count(),
            'draft'    => Document::where('status', 'draft')->count(),
            'expired'  => Document::where('status', 'expired')->count(),
        ];

        return view('documents.index', compact('documents', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $employees = Employee::take(100)->get();
        $projects = Project::take(100)->get();
        return view('documents.create', compact('employees', 'projects'));
    }

    /**
     * Store new document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'category'     => 'required|in:contract,policy,report,invoice,receipt,certificate,memo,other',
            'visibility'   => 'required|in:private,team,public',
            'employee_id'  => 'nullable|integer|exists:employees,id',
            'project_id'   => 'nullable|integer|exists:projects,id',
            'issue_date'   => 'nullable|date',
            'expiry_date'  => 'nullable|date|after:issue_date',
            'tags'         => 'nullable|string|max:500',
            'notes'        => 'nullable|string|max:2000',
            'file'         => 'required|file|max:51200', // 50MB
        ]);

        // Upload file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . date('Y/m'), $storedName, 'public');

        // Generate document number
        $docNumber = 'DOC-' . date('Y') . '-' . str_pad(Document::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        // Tags kama JSON array
        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $validated['tags'])));
        }

        // Unda document
        $document = Document::create([
            'document_number'  => $docNumber,
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'category'         => $validated['category'],
            'file_path'        => $path,
            'file_name'        => $originalName,
            'file_type'        => $file->getMimeType(),
            'file_size'        => $file->getSize(),
            'employee_id'      => $validated['employee_id'] ?? null,
            'project_id'       => $validated['project_id'] ?? null,
            'status'           => 'active',
            'visibility'       => $validated['visibility'],
            'issue_date'       => $validated['issue_date'] ?? now()->format('Y-m-d'),
            'expiry_date'      => $validated['expiry_date'] ?? null,
            'uploaded_by'      => Auth::id(),
            'version'          => '1.0',
            'tags'             => $tags,
            'notes'            => $validated['notes'] ?? null,
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Document "' . $document->title . '" uploaded successfully!');
    }

    /**
     * Show single document
     */
    public function show(Document $document)
    {
        $document->load(['uploader', 'approvedBy', 'employee', 'project']);
        return view('documents.show', compact('document'));
    }

    /**
     * Show edit form
     */
    public function edit(Document $document)
    {
        $employees = Employee::take(100)->get();
        $projects = Project::take(100)->get();
        return view('documents.edit', compact('document', 'employees', 'projects'));
    }

    /**
     * Update document metadata
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'category'     => 'required|in:contract,policy,report,invoice,receipt,certificate,memo,other',
            'visibility'   => 'required|in:private,team,public',
            'status'       => 'required|in:draft,active,archived,expired',
            'employee_id'  => 'nullable|integer|exists:employees,id',
            'project_id'   => 'nullable|integer|exists:projects,id',
            'issue_date'   => 'nullable|date',
            'expiry_date'  => 'nullable|date',
            'tags'         => 'nullable|string|max:500',
            'notes'        => 'nullable|string|max:2000',
        ]);

        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $validated['tags'])));
        }

        $document->update([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'category'     => $validated['category'],
            'visibility'   => $validated['visibility'],
            'status'       => $validated['status'],
            'employee_id'  => $validated['employee_id'] ?? null,
            'project_id'   => $validated['project_id'] ?? null,
            'issue_date'   => $validated['issue_date'] ?? null,
            'expiry_date'  => $validated['expiry_date'] ?? null,
            'tags'         => $tags,
            'notes'        => $validated['notes'] ?? null,
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Download document
     */
    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found on disk.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Preview document
     */
    public function preview(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        $filePath = storage_path('app/public/' . $document->file_path);
        return response()->file($filePath);
    }

    /**
     * Replace document file
     */
    public function replace(Request $request, Document $document)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        // Futa file ya zamani
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Upload file mpya
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . date('Y/m'), $storedName, 'public');

        // Version bump
        $newVersion = number_format((float) $document->version + 0.1, 1);

        $document->update([
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'version'   => $newVersion,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'File replaced successfully. New version: v' . $newVersion);
    }

    /**
     * Delete document
     */
    public function destroy(Document $document)
    {
        // Futa file
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Approve document
     */
    public function approve(Request $request, Document $document)
    {
        $document->update([
            'status' => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Document approved successfully!');
    }

    /**
     * Archive document
     */
    public function archive(Document $document)
    {
        $document->update(['status' => 'archived']);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Document archived successfully!');
    }

    /**
     * Export documents to CSV
     */
    public function exportCsv()
    {
        $documents = Document::with(['uploader', 'employee', 'project'])->get();

        $filename = 'documents-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($documents) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Document #', 'Title', 'Category', 'Status', 'Uploaded By', 'Created']);

            foreach ($documents as $doc) {
                fputcsv($file, [
                    $doc->document_number,
                    $doc->title,
                    $doc->category,
                    $doc->status,
                    $doc->uploader?->username ?? 'N/A',
                    $doc->created_at->format('Y-m-d'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}