<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with(['project', 'department', 'receivedBy', 'confirmedBy'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('receipt_number', 'LIKE', "%{$s}%")
                  ->orWhere('payer_name', 'LIKE', "%{$s}%")
                  ->orWhere('description', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payer_type')) {
            $query->where('payer_type', $request->payer_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('receipt_date', $request->date);
        }

        $receipts = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Receipt::count(),
            'draft' => Receipt::where('status', 'draft')->count(),
            'confirmed' => Receipt::where('status', 'confirmed')->count(),
            'cancelled' => Receipt::where('status', 'cancelled')->count(),
            'total_amount' => Receipt::where('status', 'confirmed')->sum('amount'),
        ];

        return view('receipts.index', compact('receipts', 'stats'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $nextNumber = Receipt::generateNumber();
        return view('receipts.create', compact('projects', 'departments', 'nextNumber', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receipt_number' => 'required|string|max:50|unique:receipts,receipt_number',
            'receipt_date' => 'required|date',
            'payer_name' => 'required|string|max:200',
            'payer_type' => 'required|in:donor,client,employee,other',
            'payer_contact' => 'nullable|string|max:200',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,mobile_money',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $validated['received_by'] = auth()->id();
        $validated['status'] = 'draft';

        Receipt::create($validated);

        return redirect()->route('receipts.index')
            ->with('success', 'Receipt created successfully.');
    }

    public function show(Receipt $receipt)
    {
        $receipt->load(['project', 'department', 'receivedBy', 'confirmedBy']);
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        $projects = Project::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('receipts.edit', compact('receipt', 'projects', 'departments'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'payer_name' => 'required|string|max:200',
            'payer_type' => 'required|in:donor,client,employee,other',
            'payer_contact' => 'nullable|string|max:200',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,mobile_money',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $receipt->update($validated);

        return redirect()->route('receipts.index')
            ->with('success', 'Receipt updated successfully.');
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')
            ->with('success', 'Receipt deleted.');
    }

    public function confirm(Receipt $receipt)
    {
        $receipt->update([
            'status' => 'confirmed',
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Receipt confirmed.');
    }

    public function cancel(Request $request, Receipt $receipt)
    {
        $request->validate(['cancellation_reason' => 'required|string']);
        $receipt->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Receipt cancelled.');
    }

    // ========== EXPORTS ==========

    public function printPdf(Receipt $receipt)
    {
        $receipt->load(['project', 'department', 'receivedBy', 'confirmedBy']);
        return view('receipts.pdf.single', compact('receipt'));
    }

    public function exportCsv(Request $request)
    {
        $query = Receipt::with(['project', 'department'])->orderBy('receipt_date', 'desc');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('payer_type')) $query->where('payer_type', $request->payer_type);

        $receipts = $query->get();
        $filename = 'receipts-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($receipts) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Receipt #', 'Date', 'Payer Name', 'Payer Type', 'Contact', 'Project', 'Department', 'Amount', 'Currency', 'Payment Method', 'Reference', 'Status', 'Description']);

            foreach ($receipts as $r) {
                fputcsv($file, [
                    $r->receipt_number,
                    $r->receipt_date->format('Y-m-d'),
                    $r->payer_name,
                    $r->payer_type,
                    $r->payer_contact,
                    $r->project->name ?? '',
                    $r->department->name ?? '',
                    $r->amount,
                    $r->currency,
                    $r->payment_method,
                    $r->reference_number,
                    $r->status,
                    $r->description,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $query = Receipt::with(['project', 'department'])->orderBy('receipt_date', 'desc');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('payer_type')) $query->where('payer_type', $request->payer_type);

        $receipts = $query->get();
        $filename = 'receipts-' . date('Y-m-d') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($receipts) {
            echo '<html><head><meta charset="utf-8"></head><body>';
            echo '<table border="1"><thead><tr style="background:#2563eb; color:white; font-weight:bold;">';
            echo '<th>Receipt #</th><th>Date</th><th>Payer Name</th><th>Payer Type</th><th>Contact</th><th>Project</th><th>Department</th><th>Amount</th><th>Currency</th><th>Payment Method</th><th>Reference</th><th>Status</th><th>Description</th>';
            echo '</tr></thead><tbody>';

            foreach ($receipts as $r) {
                echo '<tr>';
                echo '<td>' . $r->receipt_number . '</td>';
                echo '<td>' . $r->receipt_date->format('Y-m-d') . '</td>';
                echo '<td>' . $r->payer_name . '</td>';
                echo '<td>' . $r->payer_type . '</td>';
                echo '<td>' . ($r->payer_contact ?? '') . '</td>';
                echo '<td>' . ($r->project->name ?? '') . '</td>';
                echo '<td>' . ($r->department->name ?? '') . '</td>';
                echo '<td>' . number_format($r->amount, 2, '.', '') . '</td>';
                echo '<td>' . $r->currency . '</td>';
                echo '<td>' . $r->payment_method . '</td>';
                echo '<td>' . ($r->reference_number ?? '') . '</td>';
                echo '<td>' . $r->status . '</td>';
                echo '<td>' . ($r->description ?? '') . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}


