<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Transfer;
use App\Notifications\TransferNotification;

/**
 * Class TransferObserver
 * @package App\Observers
 */
class TransferObserver
{
    /**
     * @param  Transfer  $transfer
     */
    public function created(Transfer $transfer): void
    {
        $sender               = $transfer->sender;
        $recipient            = $transfer->recipient;
        $senderManager        = $sender->manager;
        $recipientManager     = $recipient->manager;
        $transferNotification = new TransferNotification($transfer);

        $sender->notify($transferNotification);
        $recipient->notify($transferNotification);

        if ($senderManager) {
            $senderManager->notify($transferNotification);
        }

        if ($senderManager && $recipientManager && $senderManager->id !== $recipientManager->id) {
            $recipientManager->notify($transferNotification);
        }
    }
}
