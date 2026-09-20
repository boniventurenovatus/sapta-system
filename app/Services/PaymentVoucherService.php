<?php

namespace App\Services;

use App\Models\PaymentVoucher;
use Illuminate\Support\Facades\DB;

class PaymentVoucherService
{
    /**
     * Generate next Trans No (PY00001, PY00002, ...).
     * Format: PY + 5 digits.
     */
    public function generateTransNo(): string
    {
        $prefix = 'PY';

        // Pata trans_no ya mwisho
        $last = PaymentVoucher::withTrashed()
            ->where('trans_no', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(trans_no, 3) AS UNSIGNED) DESC')
            ->first();

        if ($last && $last->trans_no) {
            $lastNumber = (int) substr($last->trans_no, 2);
            $next = $lastNumber + 1;
        } else {
            $next = 1;
        }

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Batch (placeholder).
     * Format: [code]/[rate] e.g. 186400/3.00
     * Inaweza kubadilishwa baadaye kwa logic kamili.
     */
    /**
     * Generate Batch number.
     * Format: [code]/[rate] e.g. 186400/3.00
     *
     * Inatumia:
     * - account_code: 6-digit code (default: 000000, mabadiliko kwenye form)
     * - rate: exchange rate (default: 1.00)
     */
    public function generateBatch(?string $code = null, ?float $rate = null): string
    {
        // Kama code haipo, tumia default
        $code = $code ?? '000000';

        // Kama rate haipo, tumia 1.00
        $rate = $rate ?? 1.00;

        // Format: [code]/[rate]
        return $code . '/' . number_format($rate, 2, '.', '');
    }

    /**
     * Convert amount to words.
     * Mfano: 300000.00 → "THREE HUNDRED THOUSAND ONLY"
     */
    public function amountInWords(float $amount, string $currency = 'TZS'): string
    {
        $amount = round($amount, 2);
        $whole = (int) floor($amount);
        $cents = (int) round(($amount - $whole) * 100);

        if ($whole === 0) {
            $words = 'ZERO';
        } else {
            $words = strtoupper(trim($this->numberToWords($whole)));
        }

        $result = $words;

        if ($cents > 0) {
            $result .= ' AND ' . strtoupper($this->numberToWords($cents)) . ' CENTS';
        }

        return $result . ' ONLY';
    }

    /**
     * Convert number to words (English).
     */
    protected function numberToWords(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        $units = [
            0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen',
            17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
        ];

        $tens = [
            2 => 'twenty', 3 => 'thirty', 4 => 'forty',
            5 => 'fifty', 6 => 'sixty', 7 => 'seventy',
            8 => 'eighty', 9 => 'ninety',
        ];

        if ($number < 20) {
            return $units[$number];
        }

        if ($number < 100) {
            $ten = (int) floor($number / 10);
            $unit = $number % 10;
            return $tens[$ten] . ($unit ? '-' . $units[$unit] : '');
        }

        if ($number < 1000) {
            $hundreds = (int) floor($number / 100);
            $remainder = $number % 100;
            return $units[$hundreds] . ' hundred'
                . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000) {
            $thousands = (int) floor($number / 1000);
            $remainder = $number % 1000;
            return $this->numberToWords($thousands) . ' thousand'
                . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000000) {
            $millions = (int) floor($number / 1000000);
            $remainder = $number % 1000000;
            return $this->numberToWords($millions) . ' million'
                . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        if ($number < 1000000000000) {
            $billions = (int) floor($number / 1000000000);
            $remainder = $number % 1000000000;
            return $this->numberToWords($billions) . ' billion'
                . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }

        return (string) $number;
    }

    /**
     * Calculate total from items.
     */
    public function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += (float) ($item['amount'] ?? 0);
        }
        return $total;
    }

    /**
     * Generate full voucher data (trans_no + batch + amount_in_words).
     */
    public function prepareVoucherData(array $data, array $items): array
    {
        // Auto-generate trans_no kama haipo
        if (empty($data['trans_no'])) {
            $data['trans_no'] = $this->generateTransNo();
        }

        // Auto-generate batch kama haipo
        if (empty($data['batch'])) {
            $data['batch'] = $this->generateBatch();
        }

        // Calculate total kutoka items
        $total = $this->calculateTotal($items);
        $data['amount'] = $total;

        // Amount in words
        $data['amount_in_words'] = $this->amountInWords(
            $total,
            $data['currency'] ?? 'TZS'
        );

        return $data;
    }
}