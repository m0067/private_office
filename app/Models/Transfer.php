<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;

/**
 * Class Transfer
 * @package App\Models
 */
class Transfer extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'amount'];

    protected $visible = ['sender_id', 'recipient_id', 'amount'];

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * @return BelongsTo
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * @param  Builder  $query
     * @param  User  $user
     *
     * @return Builder
     */
    public function scopeByUserRole(Builder $query, User $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query
                ->leftJoin('users as senders', function (JoinClause $join) use ($user) {
                    $join->on('transfers.sender_id', '=', 'senders.id')
                         ->where('senders.parent_id', '=', $user->id);
                })
                ->leftJoin('users as recipients', function (JoinClause $join) use ($user) {
                    $join->on('transfers.recipient_id', '=', 'recipients.id')
                         ->where('recipients.parent_id', '=', $user->id);
                })
                ->where('senders.parent_id', $user->id)
                ->orWhere('recipients.parent_id', $user->id)
                ->groupBy('transfers.id');
        }

        return $query
            ->where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->groupBy('id');
    }
}
