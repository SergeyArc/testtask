<?php
namespace Services;


class Exchanger {
    private array $exchangeRates = [];

    public function addExchangeRate(Currency $fromCurrency, Currency $toCurrency, float $rate): void {
        if ($rate <= 0) {
            throw new Exception("Обменный курс должен быть больше 0");
        }

        $this->exchangeRates[$fromCurrency->getCode()][$toCurrency->getCode()] = $rate;
        $this->exchangeRates[$toCurrency->getCode()][$fromCurrency->getCode()] = 1 / $rate;
    }

    public function convert(Currency $fromCurrency, Currency $toCurrency, float $amount): float {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        if (!isset($this->exchangeRates[$fromCurrency->getCode()][$toCurrency->getCode()])) {
            throw new Exception("Обменный курс недоступен для одной из валют");
        }

        $exchangeRate = $this->exchangeRates[$fromCurrency->getCode()][$toCurrency->getCode()];

        return $amount * $exchangeRate;
    }
}
