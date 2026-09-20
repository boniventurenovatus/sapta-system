<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::with(['parent', 'children'])->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'LIKE', "%{$s}%")
                  ->orWhere('code', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $organizations = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Organization::count(),
            'headquarters' => Organization::where('type', 'headquarters')->count(),
            'branches' => Organization::where('type', 'branch')->count(),
            'subsidiaries' => Organization::where('type', 'subsidiary')->count(),
        ];

        return view('organizations.index', compact('organizations', 'stats'));
    }

    public function create()
    {
        $parents = Organization::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        return view('organizations.create', compact('parents', 'regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:organizations,code',
            'parent_id' => 'nullable|exists:organizations,id',
            'type' => 'required|in:headquarters,branch,subsidiary',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = true;

        Organization::create($validated);

        return redirect()->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function show(Organization $organization)
    {
        $organization->load(['parent', 'children', 'departments']);
        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        $parents = Organization::where('id', '!=', $organization->id)->orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $organization->load(['district', 'ward', 'region']);
        return view('organizations.edit', compact('organization', 'parents', 'regions'));
    }

    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:organizations,code,' . $organization->id,
            'parent_id' => 'nullable|exists:organizations,id',
            'type' => 'required|in:headquarters,branch,subsidiary',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'country' => 'required|string|max:100',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $organization->update($validated);

        return redirect()->route('organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index')
            ->with('success', 'Organization deleted.');
    }
}



