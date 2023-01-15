<?php

declare(strict_types=1);

namespace App\Services;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Money;

class CurrencyService
{
    /**
     * @var CurrencyRateInterface
     */
    private $currencyRate;

    public function __construct(?CurrencyRateInterface $currencyRate = null)
    {
        $this->currencyRate = $currencyRate;
    }

    public function convert(string $from, string $to, int $amount): int
    {
        $rate = $this->currencyRate->getRate($from, $to, $amount);
        $exchange = new FixedExchange([
            $from => [
                $to => $rate
            ]
        ]);
        $converter = new Converter(new ISOCurrencies(), $exchange);
        $fromMoney = Money::$from($amount);
        $toMoney = $converter->convert($fromMoney, new Currency($to));

        return (int)$toMoney->getAmount();
    }
}
