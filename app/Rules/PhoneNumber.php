<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Phone number rule — EXACTLY 10 DIGITS (Tanzania format).
 * 
 * Valid:   0712345678
 * Invalid: 071234567 (9 digits)
 * Invalid: 07123456789 (11 digits)
 * Invalid: 0712-345-678 (hyphens)
 * Invalid: 0712 345 678 (spaces)
 */
class PhoneNumber implements ValidationRule
{
    public function __construct(
        protected int $length = 10,
        protected bool $allowLeadingZero = true
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Let 'required' handle empty
        }

        // Convert to string
        $value = (string) $value;

        // Must be digits only (no spaces, hyphens, +, etc.)
        if (!preg_match('/^[0-9]+$/', $value)) {
            $fail('Enter :attribute in digits only (no spaces or symbols).');
            return;
        }

        // Must be EXACTLY $length digits
        if (strlen($value) !== $this->length) {
            $fail('Enter exactly ' . $this->length . ' digits for :attribute.');
            return;
        }

        // Must start with 0 (Tanzania)
        if ($this->allowLeadingZero && !str_starts_with($value, '0')) {
            $fail(':attribute must start with 0.');
            return;
        }
    }
}