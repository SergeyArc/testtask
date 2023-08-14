<?php
require_once('Services\Currency.php');
require_once('Services\Account.php');
require_once('Services\Exchanger.php');


use Services\Currency,
    Services\Account,
    Services\Exchanger;

$usd = new Currency("USD","доллар США");
$eur = new Currency("EUR","евро");
$rub = new Currency("RUB","российский рубль");

$exchanger = new Exchanger();
$exchanger->addExchangeRate($eur, $rub, 80);
$exchanger->addExchangeRate($usd, $rub, 70);
$exchanger->addExchangeRate($eur, $usd, 1);

// --------- 1
$account = new Account(123456, $exchanger);
$account->addCurrency($rub, 0);
$account->addCurrency($usd, 0);
$account->addCurrency($eur, 0);

print_r($account->getCurrencies());

$account->deposit($rub, 1000);
$account->deposit($usd, 50);
$account->deposit($eur, 40);

// --------- 2
echo number_format($account->getBalance(), 2, '.', ' ')." rub\n";
echo number_format($account->getBalance($usd), 2, '.', ' ')." usd\n";
echo number_format($account->getBalance($eur), 2, '.', ' ')." eur\n";

// --------- 3
$account->deposit($rub, 1000);
$account->deposit($eur, 50);
$account->withdraw($usd, 10);

// --------- 4
$exchanger->addExchangeRate($eur, $rub, 150);
$exchanger->addExchangeRate($usd, $rub, 100);

// --------- 5
echo number_format($account->getTotalBalance(), 2, '.', ' ')." rub\n";

// --------- 6
$account->setPrimaryCurrency($eur);
echo number_format($account->getTotalBalance(), 2, '.', ' ')." eur\n";

// --------- 7
$account->transfer($rub, $eur, 1000);
echo number_format($account->getTotalBalance(), 2, '.', ' ')." eur\n";

// --------- 8
$exchanger->addExchangeRate($eur, $rub, 120);

// --------- 9
echo number_format($account->getTotalBalance(), 2, '.', ' ')." eur\n";

// --------- 10
$account->setPrimaryCurrency($rub);
$account->transfer($eur, $rub, $account->getBalance($eur));
$account->transfer($usd, $rub, $account->getBalance($usd));

$account->removeCurrency($eur);
$account->removeCurrency($usd);

print_r($account->getCurrencies());
echo number_format($account->getTotalBalance(), 2, '.', ' ')." rub\n";