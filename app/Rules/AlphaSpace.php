<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Letters and spaces only (no numbers, no symbols).
 * Use for: names, titles.
 */
class AlphaSpace implements ValidationRule
{
    public function __construct(
        protected int $min = 2,
        protected int $max = 255
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $value = (string) $value;

        if (!preg_match('/^[a-zA-Z\s\-\'\.]+$/u', $value)) {
            $fail('Enter letters only (no numbers or symbols).');
            return;
        }

        if (strlen($value) < $this->min) {
            $fail('Enter at least ' . $this->min . ' characters.');
            return;
        }

        if (strlen($value) > $this->max) {
            $fail('Do not exceed ' . $this->max . ' characters.');
            return;
        }
    }
}