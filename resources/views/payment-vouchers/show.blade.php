@extends('layouts.sapta')
@section('title', 'Payment Voucher Details')
@section('page-title', 'Payment Voucher Details')

@section('content')

@include('payment-vouchers._css')

<div style="max-width:1000px; margin:0 auto; padding:20px;">

    {{-- ACTION BUTTONS --}}
    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px;">

        {{-- BACK --}}
        <a href="{{ route('payment-vouchers.index') }}" style="padding:10px 20px; background:#f1f5f9; color:#334155; text-decoration:none; border-radius:12px; font-weight:bold; font-size:14px;">Back</a>

        {{-- PRINT --}}
        <a href="{{ route('payment-vouchers.print', $voucher->id) }}" target="_blank" style="padding:10px 20px; background:#475569; color:white; text-decoration:none; border-radius:12px; font-weight:bold; font-size:14px;">Print</a>

        {{-- PDF --}}
        <a href="{{ route('payment-vouchers.pdf', $voucher->id) }}" style="padding:10px 20px; background:#dc2626; color:white; text-decoration:none; border-radius:12px; font-weight:bold; font-size:14px;">PDF</a>

        {{-- EDIT (draft/returned) --}}
        @if(in_array($voucher->status, ['draft', 'returned']))
            <a href="{{ route('payment-vouchers.edit', $voucher->id) }}" style="padding:10px 20px; background:#d97706; color:white; text-decoration:none; border-radius:12px; font-weight:bold; font-size:14px;">Edit</a>
        @endif

        {{-- CHECK (draft) --}}
        @if($voucher->status === 'draft')
            <form action="{{ route('payment-vouchers.check', $voucher->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="padding:10px 20px; background:#0284c7; color:white; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Check</button>
            </form>
        @endif

        {{-- AUTHORIZE (checked) --}}
        @if($voucher->status === 'checked')
            <form action="{{ route('payment-vouchers.authorize', $voucher->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="padding:10px 20px; background:#059669; color:white; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Authorize</button>
            </form>
        @endif

        {{-- APPROVE + RETURN (pending_approval) --}}
        @if($voucher->status === 'pending_approval')
            <form action="{{ route('payment-vouchers.approve', $voucher->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'approve', item: 'Voucher {{ $voucher->voucher_number }}'})">
                @csrf
                <button type="submit" style="padding:10px 20px; background:#059669; color:white; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Approve</button>
            </form>
            <form action="{{ route('payment-vouchers.return', $voucher->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'return', item: 'Voucher {{ $voucher->voucher_number }}'})">
                @csrf
                <button type="submit" style="padding:10px 20px; background:#dc2626; color:white; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Return</button>
            </form>
        @endif

        {{-- MARK AS PAID (approved) --}}
        @if($voucher->status === 'approved')
            <form action="{{ route('payment-vouchers.paid', $voucher->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'markPaid', item: 'Voucher {{ $voucher->voucher_number }}'})">
                @csrf
                <button type="submit" style="padding:10px 20px; background:#7c3aed; color:white; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Mark as Paid</button>
            </form>
        @endif

        {{-- DELETE --}}
        <form action="{{ route('payment-vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline;" onsubmit="SAPTA.confirm(this, {action: 'delete', item: 'Voucher {{ $voucher->voucher_number }}'})">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding:10px 20px; background:#f1f5f9; color:#475569; border:none; border-radius:12px; font-weight:bold; font-size:14px; cursor:pointer;">Delete</button>
        </form>

    </div>

    {{-- VOUCHER --}}
    <div style="background:#fff; border:2px solid #0f172a; padding:30px;">
        @include('payment-vouchers._voucher')
    </div>

</div>

@endsection
