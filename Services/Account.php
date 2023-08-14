<?php
namespace Services;


class Account {
    private ?Currency $primaryCurrency = null;
    private array $currencies = [];
    private array $balances = [];

    public function __construct(private int $number, private Exchanger $exchanger) {}

    public function addCurrency(Currency $currency, float $balance = 0): void {
        if ($this->currencyAvalable($currency) === false) {
            $this->currencies[$currency->getCode()] = $currency;
            $this->balances[$currency->getCode()] = $balance;

            if (!$this->primaryCurrency) {
                $this->primaryCurrency = $currency;
            }
        }
    }

    public function removeCurrency(Currency $currency): void {
        if ($this->currencyAvalable($currency) === true) {
            unset($this->currencies[$currency->getCode()], $this->balances[$currency->getCode()]);

            if ($this->primaryCurrency === $currency) {
                $this->primaryCurrency = reset($this->currencies);
            }
        }
    }

    public function setPrimaryCurrency(Currency $currency): void {
        if ($this->currencyAvalable($currency) === true) {
            $this->primaryCurrency = $currency;
        } else {
            throw new \RuntimeException("Валюта недоступна");
        }
    }

    public function getPrimaryCurrency(): Currency {
        if (!$this->primaryCurrency) {
            throw new \RuntimeException("Основная валюта не задана");
        }

        return $this->primaryCurrency;
    }

    public function deposit(Currency $currency, float $amount): void {
        if ($this->currencyAvalable($currency) === true) {
            $this->balances[$currency->getCode()] += $amount;
        }
    }

    public function withdraw(Currency $currency, float $amount): void {
        if ($this->currencyAvalable($currency) === true) {
            if ($this->balances[$currency->getCode()] >= $amount) {
                $this->balances[$currency->getCode()] -= $amount;
            } else {
                throw new \RuntimeException("Недостаточно средств");
            }
        }
    }

    public function getNumber(): int {
        return $this->number;
    }

    public function getBalance(?Currency $currency = null): float {
        $primaryCurrency = $currency ?? $this->getPrimaryCurrency();

       if ($this->currencyAvalable($primaryCurrency) === true) {
            return $this->balances[$primaryCurrency->getCode()];
        }

        throw new \RuntimeException("Данная валюта недоступна");
    }

    public function getTotalBalance(?Currency $currency = null): float {
        $totalBalance = 0;
        $primaryCurrency = $currency ?? $this->getPrimaryCurrency();

        foreach ($this->balances as $currencyCode => $balance) {
            $currency = $this->currencies[$currencyCode];
            $totalBalance += $this->exchanger->convert($currency, $primaryCurrency, $balance);
        }

        return $totalBalance;
    }

    public function transfer(Currency $fromCurrency, Currency $toCurrency, float $amount): void {
        $convertedAmount = $this->exchanger->convert($fromCurrency, $toCurrency, $amount);

        if ($this->balances[$fromCurrency->getCode()] >= $amount) {
            $this->balances[$fromCurrency->getCode()] -= $amount;
            $this->deposit($toCurrency, $convertedAmount);
        } else {
            var_dump($this->balances[$fromCurrency->getCode()]);
            var_dump($amount);
            throw new \RuntimeException("Недостаточно средств");
        }
    }

    private function currencyAvalable(Currency $currency): bool {
        return isset($this->currencies[$currency->getCode()]);
    }

    public function getCurrencies(): array {
        return array_keys($this->currencies);
    }

    public function getBalances(): array {
        return $this->balances;
    }
}
