<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payslip::with('employee')->orderBy('year', 'desc')->orderBy('month', 'desc');

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('payslip_number', 'LIKE', "%{$s}%")
                  ->orWhereHas('employee', function ($e) use ($s) {
                      $e->where('first_name', 'LIKE', "%{$s}%")
                        ->orWhere('last_name', 'LIKE', "%{$s}%");
                  });
            });
        }

        $payslips = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Payslip::count(),
            'draft' => Payslip::where('status', 'draft')->count(),
            'approved' => Payslip::where('status', 'approved')->count(),
            'paid' => Payslip::where('status', 'paid')->count(),
            'this_month' => Payslip::where('month', date('n'))->where('year', date('Y'))->sum('net_salary'),
        ];

        return view('payroll.index', compact('payslips', 'stats'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $month = $request->month;
        $year = $request->year;

        $salaries = Salary::with('employee')->where('status', 'active')->get();
        $generated = 0;
        $skipped = 0;

        foreach ($salaries as $salary) {
            $exists = Payslip::where('employee_id', $salary->employee_id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $allowances = [
                'house' => $salary->house_allowance,
                'transport' => $salary->transport_allowance,
                'medical' => $salary->medical_allowance,
                'other' => $salary->other_allowances,
            ];

            $deductions = [
                'tax' => $salary->tax_deduction,
                'nssf' => $salary->nssf_deduction,
                'nhif' => $salary->nhif_deduction,
                'loan' => $salary->loan_deduction,
                'other' => $salary->other_deductions,
            ];

            Payslip::create([
                'employee_id' => $salary->employee_id,
                'salary_id' => $salary->id,
                'payslip_number' => Payslip::generateNumber(),
                'month' => $month,
                'year' => $year,
                'basic_salary' => $salary->basic_salary,
                'total_allowances' => $salary->total_allowances,
                'gross_salary' => $salary->gross_salary,
                'total_deductions' => $salary->total_deductions,
                'net_salary' => $salary->net_salary,
                'allowances_breakdown' => $allowances,
                'deductions_breakdown' => $deductions,
                'status' => 'draft',
            ]);

            $generated++;
        }

        return redirect()->route('payroll.index')
            ->with('success', "Payroll generated: {$generated} payslips created, {$skipped} skipped (already exist).");
    }

    public function show(Payslip $payslip)
    {
        $payslip->load(['employee', 'salary']);
        return view('payroll.show', compact('payslip'));
    }

    public function approve(Payslip $payslip)
    {
        $payslip->update(['status' => 'approved']);
        return back()->with('success', 'Payslip approved.');
    }

    public function markAsPaid(Payslip $payslip)
    {
        $payslip->update(['status' => 'paid', 'payment_date' => now()]);
        return back()->with('success', 'Payslip marked as paid.');
    }

    public function destroy(Payslip $payslip)
    {
        $payslip->delete();
        return back()->with('success', 'Payslip deleted.');
    }

    public function salaries(Request $request)
    {
        $query = Salary::with('employee')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('employee', function ($e) use ($s) {
                $e->where('first_name', 'LIKE', "%{$s}%")
                  ->orWhere('last_name', 'LIKE', "%{$s}%");
            });
        }

        $salaries = $query->paginate(15)->withQueryString();

        return view('payroll.salaries', compact('salaries'));
    }

    public function createSalary()
    {
        $employees = Employee::where('employment_status', 'active')->orderBy('first_name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('payroll.create-salary', compact('employees', 'regions'));
    }

    public function storeSalary(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'nssf_deduction' => 'nullable|numeric|min:0',
            'nhif_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        Salary::create($validated);

        return redirect()->route('payroll.salaries')->with('success', 'Salary created successfully.');
    }

    public function editSalary(Salary $salary)
    {
        $employees = Employee::where('employment_status', 'active')->orderBy('first_name')->get();
        return view('payroll.edit-salary', compact('salary', 'employees'));
    }

    public function updateSalary(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'nssf_deduction' => 'nullable|numeric|min:0',
            'nhif_deduction' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $salary->update($validated);

        return redirect()->route('payroll.salaries')->with('success', 'Salary updated successfully.');
    }

    public function destroySalary(Salary $salary)
    {
        $salary->delete();
        return back()->with('success', 'Salary deleted.');
    }

    /**
     * My Payslips — staff anaona payslips zake tu
     */
    public function myPayslips()
    {
        $user = auth()->user();
        $employeeId = $user->employee_id;

        $payslips = collect();
        $stats = ['total' => 0, 'paid' => 0, 'pending' => 0, 'total_net' => 0];

        if ($employeeId) {
            $payslips = \App\Models\Payslip::where('employee_id', $employeeId)
                ->orderBy('year', 'desc')->orderBy('month', 'desc')
                ->paginate(15);

            $all = \App\Models\Payslip::where('employee_id', $employeeId)->get();

            $stats = [
                'total' => $all->count(),
                'paid' => $all->where('status', 'paid')->count(),
                'pending' => $all->where('status', '!=', 'paid')->count(),
                'total_net' => $all->sum('net_salary'),
            ];
        }

        return view('payroll.my-payslips', compact('payslips', 'stats'));
    }
}