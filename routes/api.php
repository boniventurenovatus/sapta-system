<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\Support\Facades\Log;

Route::get('/search', function (Request $request) {
    $query = $request->input('q');
    
    // Log the search query
    Log::info('Search query: ' . $query);
    
    if (empty($query) || strlen($query) < 2) {
        Log::info('Search query too short');
        return response()->json([]);
    }
    
    $results = [];
    
    // Search Employees
    $employees = Employee::where('first_name', 'like', "%{$query}%")
        ->orWhere('last_name', 'like', "%{$query}%")
        ->orWhere('email', 'like', "%{$query}%")
        ->orWhere('employee_number', 'like', "%{$query}%")
        ->limit(5)
        ->get();
    
    Log::info('Employees found: ' . $employees->count());
    
    foreach ($employees as $emp) {
        $results[] = [
            'type' => 'Employee',
            'title' => $emp->first_name . ' ' . $emp->last_name,
            'description' => $emp->employee_number ?? $emp->email ?? '',
            'url' => route('employees.show', $emp->id),
            'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
        ];
    }
    
    // Search Organizations
    $organizations = Organization::where('name', 'like', "%{$query}%")
        ->orWhere('code', 'like', "%{$query}%")
        ->orWhere('email', 'like', "%{$query}%")
        ->limit(5)
        ->get();
    
    foreach ($organizations as $org) {
        $results[] = [
            'type' => 'Organization',
            'title' => $org->name,
            'description' => $org->code ?? $org->email ?? '',
            'url' => route('organizations.show', $org->id),
            'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"/></svg>'
        ];
    }
    
    // Search Users
    $users = User::where('username', 'like', "%{$query}%")
        ->orWhere('email', 'like', "%{$query}%")
        ->limit(5)
        ->get();
    
    foreach ($users as $user) {
        $results[] = [
            'type' => 'User',
            'title' => $user->username,
            'description' => $user->email ?? '',
            'url' => route('users.show', $user->id),
            'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>'
        ];
    }
    
    // Search Leave Requests
    $leaves = LeaveRequest::with('employee')
        ->whereHas('employee', function($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%");
        })
        ->limit(5)
        ->get();
    
    foreach ($leaves as $leave) {
        $results[] = [
            'type' => 'Leave Request',
            'title' => 'Leave: ' . ($leave->employee->first_name ?? '') . ' ' . ($leave->employee->last_name ?? ''),
            'description' => ucfirst($leave->status ?? 'pending'),
            'url' => route('leave-requests.show', $leave->id),
            'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>'
        ];
    }
    
    // Search Attendance
    $attendances = Attendance::with('employee')
        ->whereHas('employee', function($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%");
        })
        ->limit(5)
        ->get();
    
    foreach ($attendances as $att) {
        $results[] = [
            'type' => 'Attendance',
            'title' => 'Attendance: ' . ($att->employee->first_name ?? '') . ' ' . ($att->employee->last_name ?? ''),
            'description' => $att->attendance_date?->format('d M Y') ?? '',
            'url' => route('attendances.show', $att->id),
            'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>'
        ];
    }
    
    Log::info('Total results: ' . count($results));
    
    // Sort by priority
    $priority = ['Employee' => 1, 'Organization' => 2, 'User' => 3, 'Leave Request' => 4, 'Attendance' => 5];
    usort($results, function($a, $b) use ($priority) {
        return ($priority[$a['type']] ?? 99) - ($priority[$b['type']] ?? 99);
    });
    
    return response()->json(array_slice($results, 0, 15));
});
