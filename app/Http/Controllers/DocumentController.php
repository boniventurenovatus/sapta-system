<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

use Illuminate\View\View;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
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
    public function index(Request $request): View
    {
        $query = Document::with(['uploader', 'employee', 'project', 'approvedBy']);

        // ============================================================
        // SEARCH
        // ============================================================
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('document_number', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('file_name', 'like', "%{$s}%");
            });
        }

        // ============================================================
        // CATEGORY FILTER
        // ============================================================
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ============================================================
        // STATUS FILTER
        // ============================================================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ============================================================
        // EMPLOYEE FILTER
        // ============================================================
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // ============================================================
        // PROJECT FILTER
        // ============================================================
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // ============================================================
        // VISIBILITY FILTER
        // ============================================================
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // ============================================================
        // STATS
        // ============================================================
        $stats = [
            'total'    => Document::count(),
            'active'   => Document::where('status', 'active')->count(),
            'draft'    => Document::where('status', 'draft')->count(),
            'expired'  => Document::where('status', 'expired')->count(),
            'archived' => Document::where('status', 'archived')->count(),
        ];

        return view('documents.index', compact('documents', 'stats'));
    }public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::take(100)->get();
        $projects = Project::take(100)->get();
        $regions = \App\Models\Region::orderBy('name')->get(['id', 'name']);
        $districts = \App\Models\District::orderBy('name')->get(['id', 'name', 'region_id']);
        $wards = \App\Models\Ward::orderBy('name')->get(['id', 'name', 'district_id']);
        return view('documents.create', compact('employees', 'projects', 'regions', 'districts', 'wards'));
    }

    /**
     * Store new document
     */
    public function store(Request $request): RedirectResponse
    {
        // ============================================================
        // VALIDATION
        // ============================================================
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'visibility' => 'required|in:team,private,public,department,organization',
            'employee_id' => 'nullable|integer|exists:employees,id',
            'project_id' => 'nullable|integer|exists:projects,id',
            'region_id' => 'nullable|integer|exists:regions,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'ward_id' => 'nullable|integer|exists:wards,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'tags' => 'nullable|string',
            'notes' => 'nullable|string',
            'file' => 'required|file|max:10240',
        ]);

        // ============================================================
        // HANDLE FILE UPLOAD
        // ============================================================
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('documents/' . date('Y/m'), config('filesystems.default', 'public'));

        // ============================================================
        // AUTO-GENERATE DOCUMENT NUMBER
        // Format: DOC-YYYY-XXXX
        // ============================================================
        $year = date('Y');
        $prefix = 'DOC-' . $year . '-';

        // Tafuta number ya mwisho kwa mwaka huu
        $lastDoc = Document::withTrashed()
            ->where('document_number', 'LIKE', $prefix . '%')
            ->orderBy('document_number', 'desc')
            ->first();

        if ($lastDoc) {
            // Chukua namba ya mwisho
            $lastNumber = (int) substr($lastDoc->document_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $docNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Hakikisha ni unique — kama ipo, ongeza
        while (Document::withTrashed()->where('document_number', $docNumber)->exists()) {
            $newNumber++;
            $docNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        // ============================================================
        // TAGS
        // ============================================================
        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = json_encode(array_map('trim', explode(',', $validated['tags'])));
        }

        // ============================================================
        // UNDA DOCUMENT
        // ============================================================
        $document = Document::create([
            'document_number' => $docNumber,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'employee_id' => $validated['employee_id'] ?? null,
            'project_id' => $validated['project_id'] ?? null,
            'region_id' => $validated['region_id'] ?? null,
            'district_id' => $validated['district_id'] ?? null,
            'ward_id' => $validated['ward_id'] ?? null,
            'status' => 'active',
            'visibility' => $validated['visibility'],
            'issue_date' => $validated['issue_date'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'uploaded_by' => auth()->id(),
            'version' => 1.0,
            'tags' => $tags,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document imeundwa kikamilifu. Document Number: ' . $docNumber);
    }public function show(Document $document): View
    {
        $document->load(['uploader', 'approvedBy', 'employee', 'project', 'region', 'district', 'ward']);
        return view('documents.show', compact('document'));
    }public function edit(Document $document)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
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
            'visibility'   => 'required|in:team,private,public,department,organization',
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
        return redirect()->route('documents.raw-download', $document->id);
    }

    /**
     * Preview document
     */
    public function preview(Document $document)
    {
        $mimeType = $document->file_type ?? 'application/octet-stream';

        $inlineTypes = [
            'application/pdf',
            'image/jpeg', 'image/jpg', 'image/png',
            'image/gif', 'image/webp', 'image/svg+xml',
            'text/plain', 'text/html',
        ];

        if (in_array($mimeType, $inlineTypes)) {
            return redirect()->route('documents.raw', $document->id);
        }

        return redirect()->route('documents.raw-download', $document->id);
    }

    /**
     * Serve raw document (proxy) — display inline
     */
    public function raw(Document $document)
    {
        $disk = Storage::disk(config('filesystems.default', 'public'));
        $mimeType = $document->file_type ?? 'application/octet-stream';
        $fileName = $document->file_name ?? basename($document->file_path);

        try {
            // Tumia get() — haitumii exists() (ambayo inashindwa kwa B2)
            $contents = $disk->get($document->file_path);

            return response($contents, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'private, max-age=300',
            ]);
        } catch (\Exception $e) {
            \Log::error('Raw get error: ' . $e->getMessage());
            abort(404, 'File haipatikani: ' . $e->getMessage());
        }
    }

    /**
     * Serve raw document (proxy) — download
     */
    public function rawDownload(Document $document)
    {
        $disk = Storage::disk(config('filesystems.default', 'public'));
        $fileName = $document->file_name ?? basename($document->file_path);
        $mimeType = $document->file_type ?? 'application/octet-stream';

        try {
            // Tumia get() — haitumii exists()
            $contents = $disk->get($document->file_path);

            return response($contents, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Raw download error: ' . $e->getMessage());
            return back()->with('error', 'File haipatikani: ' . $e->getMessage());
        }
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
        if (Storage::disk(config('filesystems.default', 'public'))->exists($document->file_path)) {
            Storage::disk(config('filesystems.default', 'public'))->delete($document->file_path);
        }

        // Upload file mpya
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . date('Y/m'), $storedName, config('filesystems.default', 'public'));

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
        if (Storage::disk(config('filesystems.default', 'public'))->exists($document->file_path)) {
            Storage::disk(config('filesystems.default', 'public'))->delete($document->file_path);
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