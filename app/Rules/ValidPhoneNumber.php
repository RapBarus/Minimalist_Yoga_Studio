<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * List of supported country codes and their phone number patterns
     */
    private const PHONE_PATTERNS = [
        '62'  => '/^(\+62|0)?[0-9]{9,12}$/',        // Indonesia: +62 or 0, 9-12 digits
        '1'   => '/^(\+1)?[0-9]{10}$/',              // USA/Canada: +1, exactly 10 digits
        '44'  => '/^(\+44)?[0-9]{10}$/',             // UK: +44, exactly 10 digits
        '81'  => '/^(\+81)?[0-9]{9,11}$/',           // Japan: +81, 9-11 digits
        '86'  => '/^(\+86)?[0-9]{10,11}$/',          // China: +86, 10-11 digits
        '33'  => '/^(\+33)?[0-9]{9,10}$/',           // France: +33, 9-10 digits
        '39'  => '/^(\+39)?[0-9]{10}$/',             // Italy: +39, exactly 10 digits
        '49'  => '/^(\+49)?[0-9]{10,11}$/',          // Germany: +49, 10-11 digits
        '34'  => '/^(\+34)?[0-9]{9}$/',              // Spain: +34, exactly 9 digits
    ];

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidPhoneNumber($value)) {
            $fail('The :attribute must be a valid phone number.');
        }
    }

    /**
     * Check if the phone number is valid
     *
     * @param  string  $phone
     * @return bool
     */
    private function isValidPhoneNumber(string $phone): bool
    {
        // Remove any spaces, dashes, or parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Check if phone is empty
        if (empty($phone)) {
            return false;
        }

        // Extract country code if phone starts with +
        if (str_starts_with($phone, '+')) {
            // Try to match against all patterns
            foreach (self::PHONE_PATTERNS as $pattern) {
                if (preg_match($pattern, $phone)) {
                    return true;
                }
            }
        } else {
            // If no + prefix, assume Indonesia (+62) format
            // Convert local format (0xxx) to pattern matching
            $indonesianPattern = self::PHONE_PATTERNS['62'];
            if (preg_match($indonesianPattern, $phone)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all supported country codes
     *
     * @return array
     */
    public static function getSupportedCountries(): array
    {
        return array_keys(self::PHONE_PATTERNS);
    }
}
