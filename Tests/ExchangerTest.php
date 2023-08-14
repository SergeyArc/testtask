<?php
namespace Tests;

use PHPUnit\Framework\TestCase,
    Services\Currency,
    Services\Exchanger;

require_once '../Services/Exchanger.php';
require_once '../Services/Currency.php';


class ExchangerTest extends TestCase {
    public function testConversion(): void {
        $usd = new Currency('USD', 'доллар США');
        $eur = new Currency('EUR', 'евро');
        $exchanger = new Exchanger();

        $exchanger->addExchangeRate($usd, $eur, 0.85);

        $this->assertSame(85.0, $exchanger->convert($usd, $eur, 100));
    }
}