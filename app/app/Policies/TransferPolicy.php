<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class TransferPolicy
 * @package App\Policies
 */
class TransferPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * @param  User  $user
     * @param  Transfer  $transfer
     *
     * @return bool
     */
    public function view(User $user, Transfer $transfer): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager()) {
            $senderManager    = $transfer->sender->manager;
            $recipientManager = $transfer->recipient->manager;

            return ($senderManager && $user->id === $senderManager->id) ||
                   ($recipientManager && $user->id === $recipientManager->id);
        }

        return ($user->id === $transfer->sender->id) || ($user->id === $transfer->recipient->id);
    }

    /**
     * @param  User  $user
     * @param  int  $recipientId
     *
     * @return bool
     */
    public function create(User $user, int $recipientId = 0): bool
    {
        return $user->id !== $recipientId;
    }
}
