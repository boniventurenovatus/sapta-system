<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Tanzanian phone number rule.
 * 
 * Valid formats:
 *   0712345678     (10 digits, leading 0)
 *   255712345678   (12 digits, country code)
 *   +255712345678  (+255, 12 digits)
 * 
 * Invalid:
 *   071234567      (9 digits)
 *   07123456789    (11 digits)
 *   0712-345-678   (hyphens)
 */
class TanzanianPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $value = (string) $value;

        // Remove + if present
        $cleaned = ltrim($value, '+');

        // Must be digits only
        if (!preg_match('/^[0-9]+$/', $cleaned)) {
            $fail('Enter a valid phone number.');
            return;
        }

        $len = strlen($cleaned);

        // Accept: 0XXXXXXXXX (10), 255XXXXXXXXX (12)
        if ($len === 10 && str_starts_with($cleaned, '0')) {
            return; // Valid: 0712345678
        }

        if ($len === 12 && str_starts_with($cleaned, '255')) {
            return; // Valid: 255712345678
        }

        $fail('Enter a valid phone number (e.g., 0712345678).');
    }
}