<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();

        // Unda matrix: role vs permissions (kama zipo)
        $matrix = [];
        foreach ($roles as $role) {
            $matrix[$role->name] = [
                'users' => $role->users_count,
                'permissions' => method_exists($role, 'permissions') 
                    ? $role->permissions->pluck('name')->toArray() 
                    : [],
            ];
        }

        return view('roles.index', compact('roles', 'matrix'));
    }

    public function show(Role $role)
    {
        $role->load('users', 'permissions');
        
        $users = $role->users()->with('employee')->paginate(20);

        return view('roles.show', compact('role', 'users'));
    }
}