<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param User $sender
     * @param User $recipient
     * @param int $amount
     * @return Transfer
     * @throws \Exception
     */
    public function transmit(User $sender, User $recipient, int $amount): Transfer
    {
        DB::beginTransaction();

        try {
            $senderWallet = DB::table('wallets')
                ->where('user_id', $sender->id)
                ->lockForUpdate()
                ->first();
            $recipientWallet = DB::table('wallets')
                ->where('user_id', $recipient->id)
                ->lockForUpdate()
                ->first();
            $senderWallet->withdraw($amount)->save();
            $senderCurrencyCode = $senderWallet->currency_code;
            $recipientCurrencyCode = $recipientWallet->currency_code;
            $recipientAmount = $this->currencyService->convert($senderCurrencyCode, $recipientCurrencyCode, $amount);
            $recipientWallet->deposit($recipientAmount)->save();
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
