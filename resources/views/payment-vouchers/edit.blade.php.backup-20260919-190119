@extends('layouts.sapta')
@section('title', 'Edit Payment Voucher')
@section('page-title', 'Edit Payment Voucher')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <x-page-header title="Edit Payment Voucher" subtitle="{{ $paymentVoucher->voucher_number }}" icon="fa-edit" gradient="amber" />

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6">
            <div class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Please fix the following errors:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payment-vouchers.update', $paymentVoucher->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-8">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Payee Name <span class="text-red-500">*</span></label>
                <input type="text" name="payee_name" value="{{ old('payee_name', $paymentVoucher->payee_name) }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Payee Type <span class="text-red-500">*</span></label>
                <select name="payee_type" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach(['individual', 'company', 'government', 'ngo'] as $type)
                        <option value="{{ $type }}" {{ $paymentVoucher->payee_type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Payee Contact</label>
                <input type="text" name="payee_contact" value="{{ old('payee_contact', $paymentVoucher->payee_contact) }}"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Amount <span class="text-red-500">*</span></label>
                <input type="number" name="amount" value="{{ old('amount', $paymentVoucher->amount) }}" min="0.01" step="0.01" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Currency <span class="text-red-500">*</span></label>
                <select name="currency" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach(['TZS', 'USD', 'EUR'] as $cur)
                        <option value="{{ $cur }}" {{ $paymentVoucher->currency === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach(['bank_transfer', 'cash', 'cheque', 'mobile_money'] as $method)
                        <option value="{{ $method }}" {{ $paymentVoucher->payment_method === $method ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $method)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
                <input type="date" name="payment_date" value="{{ old('voucher_date', $paymentVoucher->voucher_date ? \Carbon\Carbon::parse($paymentVoucher->voucher_date)->format('Y-m-d') : '') }}" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Project</label>
                <select name="project_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $paymentVoucher->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Department</label>
                <select name="department_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $paymentVoucher->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $paymentVoucher->description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $paymentVoucher->notes) }}</textarea>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-slate-100">
            <button type="submit" name="action" value="draft" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition">
                <i class="fas fa-save"></i> Save as Draft
            </button>
            <button type="submit" name="action" value="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                <i class="fas fa-paper-plane"></i> {{ $paymentVoucher->status === 'returned' ? 'Resubmit' : 'Submit for Approval' }}
            </button>
            <x-btn href="{{ route('payment-vouchers.show', $paymentVoucher->id) }}" color="slate">Cancel</x-btn>
        </div>
    </form>

</div>
@endsection