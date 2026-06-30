<?php

namespace Tests\Unit;

use App\Rules\ValidPhoneNumber;
use Tests\TestCase;

class PhoneValidationTest extends TestCase
{
    private ValidPhoneNumber $phoneRule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->phoneRule = new ValidPhoneNumber();
    }

    // Indonesia (+62) tests
    public function test_valid_indonesian_phone_with_leading_zero(): void
    {
        $this->assertTrue($this->validatePhone('08123456789'));
    }

    public function test_valid_indonesian_phone_with_country_code(): void
    {
        $this->assertTrue($this->validatePhone('+628123456789'));
    }

    public function test_valid_indonesian_phone_with_62_prefix(): void
    {
        $this->assertTrue($this->validatePhone('628123456789'));
    }

    public function test_indonesian_phone_with_spaces(): void
    {
        $this->assertTrue($this->validatePhone('+62 812 345 6789'));
    }

    public function test_indonesian_phone_with_dashes(): void
    {
        $this->assertTrue($this->validatePhone('+62-812-345-6789'));
    }

    // USA (+1) tests
    public function test_valid_usa_phone_with_country_code(): void
    {
        $this->assertTrue($this->validatePhone('+12025551234'));
    }

    public function test_valid_usa_phone_without_country_code(): void
    {
        $this->assertTrue($this->validatePhone('2025551234'));
    }

    public function test_usa_phone_with_dashes(): void
    {
        $this->assertTrue($this->validatePhone('+1-202-555-1234'));
    }

    // UK (+44) tests
    public function test_valid_uk_phone(): void
    {
        $this->assertTrue($this->validatePhone('+442071838750'));
    }

    public function test_valid_uk_phone_without_country_code(): void
    {
        $this->assertTrue($this->validatePhone('2071838750'));
    }

    // Japan (+81) tests
    public function test_valid_japan_phone(): void
    {
        $this->assertTrue($this->validatePhone('+81312345678'));
    }

    // China (+86) tests
    public function test_valid_china_phone(): void
    {
        $this->assertTrue($this->validatePhone('+8613912345678'));
    }

    // Invalid phone numbers
    public function test_invalid_phone_with_letters(): void
    {
        $this->assertFalse($this->validatePhone('0812ABC6789'));
    }

    public function test_invalid_empty_phone(): void
    {
        $this->assertFalse($this->validatePhone(''));
    }

    public function test_invalid_phone_too_short(): void
    {
        $this->assertFalse($this->validatePhone('081234'));
    }

    public function test_invalid_phone_with_special_characters(): void
    {
        $this->assertFalse($this->validatePhone('081234#6789'));
    }

    public function test_invalid_usa_phone_wrong_length(): void
    {
        $this->assertFalse($this->validatePhone('+1202555'));
    }

    // Helper method to validate phone
    private function validatePhone(string $phone): bool
    {
        $valid = true;
        $this->phoneRule->validate('phone', $phone, function () use (&$valid) {
            $valid = false;
        });
        return $valid;
    }
}
