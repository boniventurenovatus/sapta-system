<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        $users = $query->orderBy('username')->paginate(10)->withQueryString();

        $totalUsers = User::count();
        $activeUsers = User::where('account_status', 'active')->count();
        $suspendedUsers = User::where('account_status', 'suspended')->count();

        return view('users.index', compact('users', 'totalUsers', 'activeUsers', 'suspendedUsers'));
    }

public function create()
    {
        $roles = Role::all();
        $employees = \App\Models\Employee::where('employment_status', 'active')
            ->orderBy('first_name')
            ->get();
        return view('users.create', compact('roles', 'employees'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // ============================================================
        // AUTO-GENERATE USERNAME
        // ============================================================
        $firstName = $validated['first_name'] ?? '';
        $lastName = $validated['last_name'] ?? '';
        
        // Kama first_name/last_name hazipo, tumia username
        if (empty($firstName) && !empty($validated['username'])) {
            $parts = explode('.', $validated['username']);
            $firstName = $parts[0] ?? 'user';
            $lastName = $parts[1] ?? '';
        }
        
        $username = $this->generateUsername($firstName, $lastName, $validated['username'] ?? null);

        // ============================================================
        // AUTO-GENERATE PASSWORD
        // ============================================================
        $plainPassword = $validated['password'] ?? $this->generatePassword();

        // ============================================================
        // CREATE USER
        // ============================================================
        $user = User::create([
            'employee_id' => $validated['employee_id'] ?? null,
            'username' => $username,
            'email' => $validated['email'],
            'password_hash' => Hash::make($plainPassword),
            'account_status' => 'active',
            'is_first_login' => true,  // Lazimisha kubadilisha password
            'first_password_expires_at' => now()->addDays(7),  // Expire baada ya siku 7
            'credentials_sent_at' => now(),
            'credentials_expires_at' => now()->addDays(7),
            'credentials_channel' => 'manual',  // Au 'email', 'sms'
        ]);

        // ============================================================
        // ATTACH ROLE
        // ============================================================
        if ($request->role_id) {
            $user->roles()->attach($request->role_id);
        }

        // ============================================================
        // LOG ACTIVITY
        // ============================================================
        \App\Models\UserActivityLog::log(
            action: 'user_create',
            module: 'user',
            description: 'Created user: ' . $username,
            subjectId: $user->id,
            subjectType: 'App\Models\User'
        );

        // ============================================================
        // REDIRECT NA CREDENTIALS
        // ============================================================
        return redirect()->route('users.show', $user->id)
            ->with('success', 'User created successfully!')
            ->with('generated_credentials', [
                'username' => $username,
                'password' => $plainPassword,
                'email' => $validated['email'],
                'expires_at' => now()->addDays(7)->format('d M Y H:i'),
            ]);
    }

    /**
     * Generate unique username
     */
    private function generateUsername(string $firstName, string $lastName, ?string $custom = null): string
    {
        // Kama admin ameweka username manually
        if ($custom && !User::where('username', $custom)->exists()) {
            return $custom;
        }

        // Auto-generate: firstname.lastname
        // strtolower KWANZA, kisha preg_replace
        $firstClean = preg_replace('/[^a-z0-9]/', '', strtolower($firstName));
        $lastClean = preg_replace('/[^a-z0-9]/', '', strtolower($lastName));
        $base = $firstClean . '.' . $lastClean;

        if (empty($base) || $base === '.') {
            $base = 'user';
        }

        $username = $base;
        $counter = 1;

        // Hakikisha ni unique
        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate strong password
     */
    private function generatePassword(): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lowercase = 'abcdefghjkmnpqrstuvwxyz';
        $numbers = '23456789';
        $symbols = '!@#$%';

        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];

        // Ongeza characters za random
        $all = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 0; $i < 5; $i++) {
            $password .= $all[rand(0, strlen($all) - 1)];
        }

        return str_shuffle($password);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $employees = \App\Models\Employee::where('employment_status', 'active')
            ->orderBy('first_name')
            ->get();
        $user->load('employee');
        return view('users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'employee_id' => $validated['employee_id'] ?? null,
            'username' => $validated['username'],
            'email' => $validated['email'],
            'account_status' => $validated['account_status'],
        ];

        if (!empty($validated['password'])) {
            $data['password_hash'] = Hash::make($validated['password']);
        }

        $user->update($data);
        
        if ($request->role_id) {
            $user->roles()->sync([$request->role_id]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function suspend(User $user)
    {
        $user->update(['account_status' => 'suspended']);
                UserActivityLog::log(
            action: 'user_suspend',
            module: 'user',
            description: 'Suspended user: ' . $user->username,
            subjectId: $user->id,
            subjectType: 'App\Models\User'
        );

        return redirect()->route('users.index')->with('success', 'User suspended successfully');
    }

    public function activate(User $user)
    {
        $user->update(['account_status' => 'active']);
        return redirect()->route('users.index')->with('success', 'User activated successfully');
    }
}




