<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalUnit;

class OrganogramController extends Controller
{
    public function index()
    {
        $bod = OrganizationalUnit::with('positions')->where('code', 'BOD')->first();
        $ceo = OrganizationalUnit::with('positions')->where('code', 'CEO')->first();

        $departments = OrganizationalUnit::with(['positions' => function($q) {
                $q->orderByRaw("CASE 
                    WHEN title LIKE '%Director%' THEN 1
                    WHEN title LIKE '%Manager%' THEN 2
                    WHEN title LIKE '%Officer%' THEN 3
                    WHEN title LIKE '%Accountant%' THEN 4
                    ELSE 5
                END");
            }])
            ->whereIn('code', ['ADMIN', 'PROG', 'MEAL', 'ICT'])
            ->orderBy('name')
            ->get();

        return view('organogram.index', compact('bod', 'ceo', 'departments'));
    }
}
