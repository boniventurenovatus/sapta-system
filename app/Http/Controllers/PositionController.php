<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\OrganizationalUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $unitId = $request->get('unit', '');

        $query = Position::with('organizationalUnit')
            ->withCount('employees')
            ->orderBy('title');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($unitId !== '') {
            $query->where('organizational_unit_id', $unitId);
        }

        $positions = $query->paginate(15)->withQueryString();
        $units = OrganizationalUnit::orderBy('name')->get();

        return view('positions.index', compact('positions', 'search', 'status', 'unitId', 'units'));
    }

    public function create()
    {
        $units = OrganizationalUnit::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $nextCode = $this->generateNextCode();
        return view('positions.create', compact('units', 'regions', 'nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizational_unit_id' => 'nullable|exists:organizational_units,id',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:positions,code',
            'description' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'status' => 'required|in:active,inactive',
        ]);

        Position::create($validated);

        return redirect()->route('positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function show(Position $position)
    {
        $position->load(['organizationalUnit', 'employees', 'permissions']);
        return view('positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        $units = OrganizationalUnit::orderBy('name')->get();
        $regions = \App\Models\Region::orderBy('name')->get();
        $position->load(['district', 'ward', 'region']);
        return view('positions.edit', compact('position', 'units', 'regions'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'organizational_unit_id' => 'nullable|exists:organizational_units,id',
            'title' => 'required|string|max:255',
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('positions', 'code')->ignore($position->id),
            ],
            'description' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'ward_id' => 'nullable|exists:wards,id',
            'status' => 'required|in:active,inactive',
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    private function generateNextCode(): string
    {
        $last = Position::orderBy('id', 'desc')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'POS-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}


