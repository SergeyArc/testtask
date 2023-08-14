<?php
namespace Tests;

use PHPUnit\Framework\TestCase,
    Services\Currency,
    Services\Exchanger,
    Services\Account;

require_once '../Services/Exchanger.php';
require_once '../Services/Currency.php';
require_once '../Services/Account.php';


class AccountTest extends TestCase {
    public function testDepositAndWithdraw(): void {
        $usd = new Currency('USD', 'доллар США');
        $eur = new Currency('EUR', 'евро');
        $exchanger = new Exchanger();

        $exchanger->addExchangeRate($usd, $eur, 0.85);

        $account = new Account(123456, $exchanger);

        $account->addCurrency($usd, 1000);

        $this->assertSame(1000.0, $account->getBalance());
        $this->assertSame(1000.0, $account->getBalance($usd));

        $account->deposit($usd, 200);
        $this->assertSame(1200.0, $account->getBalance($usd));

        $account->withdraw($usd, 100);
        $this->assertSame(1100.0, $account->getBalance($usd));

        $account->addCurrency($eur, 100);
        $account->transfer($usd, $eur, 100);
        $this->assertSame(1000.0, $account->getBalance($usd));
        $this->assertSame(185.0, $account->getBalance($eur));
    }
}