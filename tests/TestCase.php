<?php

namespace {
    if (!class_exists(\Xendit\Configuration::class)) {
        eval('namespace Xendit; class Configuration { public static function setXenditKey($key): void {} }');
    }

	if (!class_exists(\Xendit\Invoice\CreateInvoiceRequest::class)) {
		eval('namespace Xendit\\Invoice; class CreateInvoiceRequest { public function __construct(array $data = []) {} }');
	}
}

namespace Tests {
	use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

	abstract class TestCase extends BaseTestCase
	{
		//
	}
}
