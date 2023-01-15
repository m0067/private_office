<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Wallet
 * @package App\Models
 */
class Wallet extends Model
{
    protected $fillable = ['balance', 'user_id'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  int  $amount
     *
     * @return Wallet
     */
    public function deposit(int $amount): Wallet
    {
        if ($amount) {
            $this->attributes['balance'] += $amount;
        }

        return $this;
    }

    /**
     * @param  int  $amount
     *
     * @return Wallet
     */
    public function withdraw(int $amount): Wallet
    {
        if ($amount) {
            $this->attributes['balance'] -= $amount;
        }

        return $this;
    }
}
