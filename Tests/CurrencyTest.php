<?php
namespace Tests;

use PHPUnit\Framework\TestCase,
    Services\Currency;

require_once '../Services/Currency.php';


class CurrencyTest extends TestCase {
    public function testGetters(): void {
        $usd = new Currency('USD', 'US Dollar');
        $this->assertSame('USD', $usd->getCode());
        $this->assertSame('US Dollar', $usd->getName());
    }
}