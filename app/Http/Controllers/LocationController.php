<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LocationController extends Controller
{
    public function regions()
    {
        if (!Schema::hasTable('regions')) return response()->json([]);
        return response()->json(
            DB::table('regions')->orderBy('name')->get(['id', 'name', 'code'])
        );
    }

    public function districts(Request $request)
    {
        if (!Schema::hasTable('districts')) return response()->json([]);
        $query = DB::table('districts');
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        return response()->json(
            $query->orderBy('name')->get(['id', 'name', 'region_id'])
        );
    }

    public function wards(Request $request)
    {
        if (!Schema::hasTable('wards')) return response()->json([]);
        $query = DB::table('wards');
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        return response()->json(
            $query->orderBy('name')->get(['id', 'name', 'district_id'])
        );
    }
}