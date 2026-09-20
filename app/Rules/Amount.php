<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Amount rule — positive decimal with range.
 * Use for: money, prices, quantities.
 */
class Amount implements ValidationRule
{
    public function __construct(
        protected float $min = 0,
        protected float $max = 999999999999.99,
        protected int $decimals = 2
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return;
        }

        if (!is_numeric($value)) {
            $fail('Enter a valid number.');
            return;
        }

        $num = (float) $value;

        if ($num < $this->min) {
            $fail('Amount must be at least ' . number_format($this->min, 2) . '.');
            return;
        }

        if ($num > $this->max) {
            $fail('Amount cannot exceed ' . number_format($this->max, 2) . '.');
            return;
        }

        // Check decimal places
        if ($this->decimals > 0) {
            $parts = explode('.', (string) $value);
            if (isset($parts[1]) && strlen($parts[1]) > $this->decimals) {
                $fail('Amount can have at most ' . $this->decimals . ' decimal places.');
                return;
            }
        }
    }
}