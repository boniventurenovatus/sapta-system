<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProcurementController extends Controller
{
    public function index()
    {
        $stats = [
            'requests'  => Schema::hasTable('procurement_requests') ? DB::table('procurement_requests')->count() : 0,
            'pending'   => Schema::hasTable('procurement_requests') ? DB::table('procurement_requests')->where('status', 'pending')->count() : 0,
            'orders'    => Schema::hasTable('purchase_orders') ? DB::table('purchase_orders')->count() : 0,
            'suppliers' => Schema::hasTable('suppliers') ? DB::table('suppliers')->count() : 0,
        ];

        $recentRequests = Schema::hasTable('procurement_requests')
            ? DB::table('procurement_requests')->orderBy('created_at', 'desc')->limit(5)->get()
            : collect();

        return view('procurement.index', compact('stats', 'recentRequests'));
    }

    public function requests(Request $request)
    {
        if (!Schema::hasTable('procurement_requests')) {
            $requests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('procurement.requests', compact('requests'));
        }

        $query = DB::table('procurement_requests');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('procurement.requests', compact('requests'));
    }

    public function requestsCreate()
    {
        return view('procurement.requests-create');
    }

    public function requestsStore(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'category'       => 'required|string',
            'quantity'       => 'required|integer|min:1',
            'estimated_cost' => 'required|numeric|min:0',
            'priority'       => 'required|in:low,medium,high,critical',
            'required_date'  => 'required|date',
        ]);

        DB::table('procurement_requests')->insert([
            'request_number' => 'PR-' . date('Y') . '-' . str_pad(DB::table('procurement_requests')->count() + 1, 4, '0', STR_PAD_LEFT),
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'category'       => $validated['category'],
            'quantity'       => $validated['quantity'],
            'estimated_cost' => $validated['estimated_cost'],
            'priority'       => $validated['priority'],
            'required_date'  => $validated['required_date'],
            'requested_by'   => Auth::id(),
            'status'         => 'pending',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('procurement.requests')->with('success', 'Procurement request created successfully.');
    }

    public function orders(Request $request)
    {
        if (!Schema::hasTable('purchase_orders')) {
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('procurement.orders', compact('orders'));
        }

        $orders = DB::table('purchase_orders')->orderBy('created_at', 'desc')->paginate(20);
        return view('procurement.orders', compact('orders'));
    }

    public function ordersCreate()
    {
        $suppliers = Schema::hasTable('suppliers')
            ? DB::table('suppliers')->where('status', 'active')->get()
            : collect();

        return view('procurement.orders-create', compact('suppliers'));
    }

    public function ordersStore(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|integer',
            'order_date'    => 'required|date',
            'delivery_date' => 'required|date|after:order_date',
            'total_amount'  => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        DB::table('purchase_orders')->insert([
            'order_number'  => 'PO-' . date('Y') . '-' . str_pad(DB::table('purchase_orders')->count() + 1, 4, '0', STR_PAD_LEFT),
            'supplier_id'   => $validated['supplier_id'],
            'order_date'    => $validated['order_date'],
            'delivery_date' => $validated['delivery_date'],
            'total_amount'  => $validated['total_amount'],
            'notes'         => $validated['notes'] ?? null,
            'created_by'    => Auth::id(),
            'status'        => 'pending',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('procurement.orders')->with('success', 'Purchase order created successfully.');
    }

    public function suppliers(Request $request)
    {
        if (!Schema::hasTable('suppliers')) {
            $suppliers = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('procurement.suppliers', compact('suppliers'));
        }

        $suppliers = DB::table('suppliers')->orderBy('created_at', 'desc')->paginate(20);
        return view('procurement.suppliers', compact('suppliers'));
    }

    public function suppliersCreate()
    {
        return view('procurement.suppliers-create');
    }

    public function suppliersStore(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address'        => 'nullable|string',
            'category'       => 'required|string',
            'status'         => 'required|in:active,inactive',
        ]);

        DB::table('suppliers')->insert([
            'name'           => $validated['name'],
            'contact_person' => $validated['contact_person'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'address'        => $validated['address'] ?? null,
            'category'       => $validated['category'],
            'status'         => $validated['status'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('procurement.suppliers')->with('success', 'Supplier created successfully.');
    }

    // ================================================================
    // REQUESTS — Show, Approve, Reject, Destroy
    // ================================================================
    public function requestsShow($id)
    {
        $request = DB::table('procurement_requests')->where('id', $id)->first();
        if (!$request) abort(404);
        return view('procurement.requests-show', compact('request'));
    }

    public function requestsApprove($id)
    {
        DB::table('procurement_requests')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);
        return redirect()->route('procurement.requests')->with('success', 'Request approved successfully.');
    }

    public function requestsReject($id)
    {
        DB::table('procurement_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);
        return redirect()->route('procurement.requests')->with('success', 'Request rejected successfully.');
    }

    public function requestsDestroy($id)
    {
        DB::table('procurement_requests')->where('id', $id)->delete();
        return redirect()->route('procurement.requests')->with('success', 'Request deleted successfully.');
    }

    // ================================================================
    // ORDERS — Approve, Deliver, Destroy
    // ================================================================
    public function ordersApprove($id)
    {
        DB::table('purchase_orders')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);
        return redirect()->route('procurement.orders')->with('success', 'Order approved successfully.');
    }

    public function ordersDeliver($id)
    {
        DB::table('purchase_orders')->where('id', $id)->update([
            'status' => 'delivered',
            'updated_at' => now(),
        ]);
        return redirect()->route('procurement.orders')->with('success', 'Order marked as delivered.');
    }

    public function ordersDestroy($id)
    {
        DB::table('purchase_orders')->where('id', $id)->delete();
        return redirect()->route('procurement.orders')->with('success', 'Order deleted successfully.');
    }

    // ================================================================
    // SUPPLIERS — Destroy
    // ================================================================
    public function suppliersDestroy($id)
    {
        DB::table('suppliers')->where('id', $id)->delete();
        return redirect()->route('procurement.suppliers')->with('success', 'Supplier deleted successfully.');
    }

    // ================================================================
    // REQUESTS — PDF + Print
    // ================================================================
    public function requestsPdf($id)
    {
        $request = DB::table('procurement_requests')->where('id', $id)->first();
        if (!$request) abort(404);

        $requester = DB::table('users')->where('id', $request->requested_by)->first();
        $approver = $request->approved_by ? DB::table('users')->where('id', $request->approved_by)->first() : null;

        $data = [
            'procRequest' => (object) $request,
            'requester' => $requester ? (object) $requester : null,
            'approver' => $approver ? (object) $approver : null,
            'generated_at' => now(),
        ];

        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF package haipo.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('procurement-requests.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Procurement-Request-' . $request->request_number . '.pdf');
    }

    public function requestsPrint($id)
    {
        $request = DB::table('procurement_requests')->where('id', $id)->first();
        if (!$request) abort(404);

        $requester = DB::table('users')->where('id', $request->requested_by)->first();
        $approver = $request->approved_by ? DB::table('users')->where('id', $request->approved_by)->first() : null;

        $data = [
            'procRequest' => (object) $request,
            'requester' => $requester ? (object) $requester : null,
            'approver' => $approver ? (object) $approver : null,
            'generated_at' => now(),
        ];

        return view('procurement-requests.print', $data);
    }
}