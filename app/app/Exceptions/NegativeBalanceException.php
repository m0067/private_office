<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class NegativeBalanceException extends Exception
{
    protected $message = 'Balance can not be less 0';
}
