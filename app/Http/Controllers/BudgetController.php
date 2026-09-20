<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['project', 'department', 'createdBy'])
            ->orderBy('fiscal_year', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('budget_number', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', $request->fiscal_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $budgets = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Budget::count(),
            'total_allocated' => Budget::sum('allocated_amount'),
            'total_spent' => Budget::sum('spent_amount'),
            'active' => Budget::where('status', 'active')->count(),
        ];

        $years = Budget::select('fiscal_year')->distinct()->orderBy('fiscal_year', 'desc')->pluck('fiscal_year');

        return view('budgets.index', compact('budgets', 'stats', 'years'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $nextNumber = Budget::generateNumber();
        return view('budgets.create', compact('projects', 'departments', 'nextNumber', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_number' => 'required|string|max:50|unique:budgets,budget_number',
            'name' => 'required|string|max:200',
            'fiscal_year' => 'required|integer|min:2020|max:2100',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'category' => 'required|in:salaries,operations,supplies,travel,training,equipment,other',
            'allocated_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        Budget::create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    public function show(Budget $budget)
    {
        $budget->load(['project', 'department', 'createdBy', 'approvedBy']);
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $projects = Project::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('budgets.edit', compact('budget', 'projects', 'departments'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'fiscal_year' => 'required|integer|min:2020|max:2100',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'category' => 'required|in:salaries,operations,supplies,travel,training,equipment,other',
            'allocated_amount' => 'required|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('budgets.index')
            ->with('success', 'Budget deleted.');
    }

    public function approve(Budget $budget)
    {
        $budget->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Budget approved.');
    }

    public function activate(Budget $budget)
    {
        $budget->update(['status' => 'active']);
        return back()->with('success', 'Budget activated.');
    }

    public function close(Budget $budget)
    {
        $budget->update(['status' => 'closed']);
        return back()->with('success', 'Budget closed.');
    }

    // ========== EXPORTS ==========

    public function printPdf(Budget $budget)
    {
        $budget->load(['project', 'department', 'createdBy', 'approvedBy']);
        return view('budgets.pdf.single', compact('budget'));
    }

    public function exportCsv(Request $request)
    {
        $query = Budget::with(['project', 'department'])->orderBy('fiscal_year', 'desc');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('fiscal_year')) $query->where('fiscal_year', $request->fiscal_year);

        $budgets = $query->get();
        $filename = 'budgets-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($budgets) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Budget #', 'Name', 'Fiscal Year', 'Category', 'Project', 'Department', 'Allocated', 'Spent', 'Remaining', 'Currency', 'Start', 'End', 'Status']);

            foreach ($budgets as $b) {
                fputcsv($file, [
                    $b->budget_number,
                    $b->name,
                    $b->fiscal_year,
                    $b->category,
                    $b->project->name ?? '',
                    $b->department->name ?? '',
                    $b->allocated_amount,
                    $b->spent_amount,
                    $b->remaining_amount,
                    $b->currency,
                    $b->start_date->format('Y-m-d'),
                    $b->end_date->format('Y-m-d'),
                    $b->status,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $query = Budget::with(['project', 'department'])->orderBy('fiscal_year', 'desc');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('fiscal_year')) $query->where('fiscal_year', $request->fiscal_year);

        $budgets = $query->get();
        $filename = 'budgets-' . date('Y-m-d') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($budgets) {
            echo '<html><head><meta charset="utf-8"></head><body>';
            echo '<table border="1"><thead><tr style="background:#2563eb; color:white; font-weight:bold;">';
            echo '<th>Budget #</th><th>Name</th><th>Fiscal Year</th><th>Category</th><th>Project</th><th>Department</th><th>Allocated</th><th>Spent</th><th>Remaining</th><th>Currency</th><th>Start</th><th>End</th><th>Status</th>';
            echo '</tr></thead><tbody>';

            foreach ($budgets as $b) {
                echo '<tr>';
                echo '<td>' . $b->budget_number . '</td>';
                echo '<td>' . $b->name . '</td>';
                echo '<td>' . $b->fiscal_year . '</td>';
                echo '<td>' . $b->category . '</td>';
                echo '<td>' . ($b->project->name ?? '') . '</td>';
                echo '<td>' . ($b->department->name ?? '') . '</td>';
                echo '<td>' . number_format($b->allocated_amount, 2, '.', '') . '</td>';
                echo '<td>' . number_format($b->spent_amount, 2, '.', '') . '</td>';
                echo '<td>' . number_format($b->remaining_amount, 2, '.', '') . '</td>';
                echo '<td>' . $b->currency . '</td>';
                echo '<td>' . $b->start_date->format('Y-m-d') . '</td>';
                echo '<td>' . $b->end_date->format('Y-m-d') . '</td>';
                echo '<td>' . $b->status . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}


