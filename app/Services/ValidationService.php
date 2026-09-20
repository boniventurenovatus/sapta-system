<?php

namespace App\Services;

class ValidationService
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $r) {
                if ($r === 'required' && (is_null($value) || $value === '')) {
                    $errors[$field][] = "The {$field} field is required.";
                }

                if ($r === 'numeric' && !is_null($value) && !is_numeric($value)) {
                    $errors[$field][] = "The {$field} must be a number.";
                }

                if ($r === 'email' && !is_null($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email address.";
                }

                if (str_starts_with($r, 'min:')) {
                    $min = (int) substr($r, 4);
                    if (!is_null($value) && (is_numeric($value) ? $value < $min : strlen($value) < $min)) {
                        $errors[$field][] = "The {$field} must be at least {$min}.";
                    }
                }

                if (str_starts_with($r, 'max:')) {
                    $max = (int) substr($r, 4);
                    if (!is_null($value) && (is_numeric($value) ? $value > $max : strlen($value) > $max)) {
                        $errors[$field][] = "The {$field} must not exceed {$max}.";
                    }
                }

                if ($r === 'date' && !is_null($value)) {
                    if (strtotime($value) === false) {
                        $errors[$field][] = "The {$field} must be a valid date.";
                    }
                }
            }
        }

        return $errors;
    }

    public function isValid(array $data, array $rules): bool
    {
        return empty($this->validate($data, $rules));
    }
}