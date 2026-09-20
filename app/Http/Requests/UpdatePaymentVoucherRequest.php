<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpace;
use App\Rules\Amount;
use App\Rules\PhoneNumber;
use App\Rules\TanzanianPhone;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // ===== HEADER FIELDS =====
            'trans_no' => [
                'nullable', 'string', 'max:20',
                'regex:/^[A-Z]{2}[0-9]{5}$/',
                'unique:payment_vouchers,trans_no,' . $this->route('paymentVoucher')->id,
            ],
            'payment_date' => 'required|date|before_or_equal:today',
            'currency' => 'required|in:TZS,USD,EUR',
            'payee_name' => [
                'required', 'string', 'min:2', 'max:255',
                'regex:/^[a-zA-Z0-9\s\-\.\'&,()]+$/u',
            ],
            'payee_pobox' => [
                'nullable', 'string', 'max:255',
                'regex:/^[a-zA-Z0-9\s\-\.\,]+$/u',
            ],
            'payee_type' => 'required|in:individual,company,government,ngo',
            'payee_contact' => [
                'nullable', 'string', 'max:20',
                // Namba 10-12 au email
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;

                    // Check email
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return; // Valid email
                    }

                    // Check phone (Tanzania)
                    $cleaned = ltrim($value, '+');
                    if (preg_match('/^[0-9]+$/', $cleaned)) {
                        $len = strlen($cleaned);
                        if (($len === 10 && str_starts_with($cleaned, '0')) ||
                            ($len === 12 && str_starts_with($cleaned, '255'))) {
                            return; // Valid phone
                        }
                    }

                    $fail('Enter a valid phone (0712345678) or email.');
                },
            ],
            'batch' => [
                'nullable', 'string', 'max:50',
                'regex:/^[0-9]{1,10}\/[0-9]{1,5}(\.[0-9]{1,2})?$/',
            ],
            'bank' => [
                'nullable', 'string', 'max:15',
                'regex:/^[0-9]{1,15}$/',
            ],
            'mode' => 'required|in:transfer,cheque,cash,mobile_money',
            'cheque_number' => [
                'nullable', 'string', 'max:15',
                'regex:/^[0-9]{1,15}$/',
            ],

            // ===== ITEMS =====
            'items' => 'required|array|min:1|max:50',
            'items.*.account_invoice_no' => [
                'nullable', 'string', 'max:100',
                'regex:/^[a-zA-Z0-9\s\-\/\.]+$/u',
            ],
            'items.*.details' => [
                'required', 'string', 'min:2', 'max:500',
                'regex:/^[a-zA-Z0-9\s\-\.\,\'&()\/]+$/u',
            ],
            'items.*.amount' => ['required', 'numeric', new Amount(0.01, 999999999999.99, 2)],

            // ===== SIGNATURES =====
            'prepared_by_id' => 'nullable|exists:users,id',
            'checked_by_id' => 'nullable|exists:users,id',
            'authorized_by_id' => 'nullable|exists:users,id',

            // ===== NOTES =====
            'description' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'trans_no.regex' => 'Must be format PY00001.',
            'trans_no.unique' => 'This Trans No already exists.',
            'payment_date.required' => 'Date is required.',
            'payment_date.before_or_equal' => 'Date cannot be in the future.',
            'currency.required' => 'Currency is required.',
            'payee_name.required' => 'Name of Payee is required.',
            'payee_name.min' => 'Enter at least 2 characters.',
            'payee_name.regex' => 'Only letters, numbers, spaces, and basic punctuation.',
            'payee_pobox.regex' => 'Only letters, numbers, spaces, and - . ,',
            'batch.regex' => 'Must be format [number]/[rate] e.g. 186400/3.00.',
            'bank.regex' => 'Bank must contain numbers only.',
            'mode.required' => 'Mode is required.',
            'cheque_number.regex' => 'Cheque Number must contain numbers only.',
            'items.required' => 'At least one line item is required.',
            'items.*.details.required' => 'Details are required for every line item.',
            'items.*.amount.required' => 'Amount is required for every line item.',
            'items.*.amount.min' => 'Amount cannot be negative.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payee_name' => trim($this->payee_name ?? ''),
            'payee_pobox' => trim($this->payee_pobox ?? ''),
            'payee_contact' => trim($this->payee_contact ?? ''),
            'bank' => trim($this->bank ?? ''),
            'cheque_number' => trim($this->cheque_number ?? ''),
        ]);
    }
}