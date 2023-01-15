<?php

declare(strict_types=1);

namespace App\Services;

interface CurrencyRateInterface
{
    public function getRate(string $from, string $to, int $amount): float;
}
