<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\PerformanceKpi;
use App\Models\Employee;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceReview::with(['employee', 'reviewer', 'approvedBy'])
            ->orderBy('review_date', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('review_number', 'LIKE', "%{$s}%")
                  ->orWhere('review_period', 'LIKE', "%{$s}%")
                  ->orWhereHas('employee', function ($e) use ($s) {
                      $e->where('first_name', 'LIKE', "%{$s}%")
                        ->orWhere('last_name', 'LIKE', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('review_period')) {
            $query->where('review_period', $request->review_period);
        }

        $reviews = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => PerformanceReview::count(),
            'draft' => PerformanceReview::where('status', 'draft')->count(),
            'submitted' => PerformanceReview::where('status', 'submitted')->count(),
            'approved' => PerformanceReview::where('status', 'approved')->count(),
            'average_rating' => PerformanceReview::avg('overall_rating') ?? 0,
        ];

        $periods = PerformanceReview::select('review_period')->distinct()->orderBy('review_period', 'desc')->pluck('review_period');

        return view('performance-reviews.index', compact('reviews', 'stats', 'periods'));
    }

    public function create()
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        $nextNumber = PerformanceReview::generateNumber();
        return view('performance-reviews.create', compact('employees', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'review_number' => 'required|string|max:50|unique:performance_reviews,review_number',
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string|max:50',
            'review_date' => 'required|date',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'overall_rating' => 'nullable|numeric|min:0|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'goals' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $validated['reviewer_id'] = auth()->id();
        $validated['status'] = 'draft';

        PerformanceReview::create($validated);

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review created successfully.');
    }

    public function show(PerformanceReview $performanceReview)
    {
        $performanceReview->load(['employee', 'reviewer', 'approvedBy', 'kpis']);
        return view('performance-reviews.show', compact('performanceReview'));
    }

    public function edit(PerformanceReview $performanceReview)
    {
        $regions = \App\Models\Region::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        return view('performance-reviews.edit', compact('performanceReview', 'employees', 'regions'));
    }

    public function update(Request $request, PerformanceReview $performanceReview)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string|max:50',
            'review_date' => 'required|date',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'overall_rating' => 'nullable|numeric|min:0|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'goals' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $performanceReview->update($validated);

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $performanceReview)
    {
        $performanceReview->delete();
        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review deleted.');
    }

    public function submit(PerformanceReview $performanceReview)
    {
        $performanceReview->update(['status' => 'submitted']);
        return back()->with('success', 'Performance review submitted for approval.');
    }

    public function approve(PerformanceReview $performanceReview)
    {
        $performanceReview->update([
            'status' => 'approved',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Performance review approved.');
    }

    public function reject(Request $request, PerformanceReview $performanceReview)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $performanceReview->update([
            'status' => 'rejected',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'rejection_reason' => $request->rejection_reason,
        ]);
        return back()->with('success', 'Performance review rejected.');
    }

    public function addKpi(Request $request, PerformanceReview $performanceReview)
    {
        $validated = $request->validate([
            'kpi_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'target' => 'nullable|string|max:100',
            'achieved' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'weight' => 'nullable|integer|min:0|max:100',
        ]);

        $performanceReview->kpis()->create($validated);

        return back()->with('success', 'KPI added successfully.');
    }

    public function deleteKpi(PerformanceKpi $kpi)
    {
        $kpi->delete();
        return back()->with('success', 'KPI deleted.');
    }

    // ========== EXPORTS ==========

    public function printPdf(PerformanceReview $performanceReview)
    {
        $performanceReview->load(['employee', 'reviewer', 'approvedBy', 'kpis']);
        return view('performance-reviews.pdf.single', compact('performanceReview'));
    }

    public function exportCsv()
    {
        $reviews = PerformanceReview::with(['employee'])->orderBy('review_date', 'desc')->get();
        $filename = 'performance-reviews-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Review #', 'Employee', 'Period', 'Review Date', 'Rating', 'Status', 'Strengths', 'Improvements', 'Goals']);

            foreach ($reviews as $r) {
                fputcsv($file, [
                    $r->review_number,
                    ($r->employee->first_name ?? '') . ' ' . ($r->employee->last_name ?? ''),
                    $r->review_period,
                    $r->review_date->format('Y-m-d'),
                    $r->overall_rating,
                    $r->status,
                    $r->strengths,
                    $r->improvements,
                    $r->goals,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportExcel()
    {
        $reviews = PerformanceReview::with(['employee'])->orderBy('review_date', 'desc')->get();
        $filename = 'performance-reviews-' . date('Y-m-d') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($reviews) {
            echo '<html><head><meta charset="utf-8"></head><body>';
            echo '<table border="1"><thead><tr style="background:#2563eb; color:white; font-weight:bold;">';
            echo '<th>Review #</th><th>Employee</th><th>Period</th><th>Review Date</th><th>Rating</th><th>Status</th>';
            echo '</tr></thead><tbody>';

            foreach ($reviews as $r) {
                echo '<tr>';
                echo '<td>' . $r->review_number . '</td>';
                echo '<td>' . ($r->employee->first_name ?? '') . ' ' . ($r->employee->last_name ?? '') . '</td>';
                echo '<td>' . $r->review_period . '</td>';
                echo '<td>' . $r->review_date->format('Y-m-d') . '</td>';
                echo '<td>' . $r->overall_rating . '</td>';
                echo '<td>' . $r->status . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}



