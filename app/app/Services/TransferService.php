<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    /**
     * @param  User  $sender
     * @param  User  $recipient
     * @param  int  $amount
     *
     * @return Transfer
     * @throws \Exception
     */
    public function transmit(User $sender, User $recipient, int $amount): Transfer
    {
        DB::beginTransaction();

        try {
            $sender->wallet->withdraw($amount)->save();
            $recipient->wallet->deposit($amount)->save();
            $transfer = new Transfer;
            $transfer->sender()->associate($sender);
            $transfer->recipient()->associate($recipient);
            $transfer->amount = $amount;
            $transfer->save();
            DB::commit();

            return $transfer;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}
