<?php

namespace Domain\Profile\Models;

use Database\Factories\FriendshipFactory;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Friendship extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): FriendshipFactory
    {
        return FriendshipFactory::new();
    }

    /** @return BelongsTo<User, $this> */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function addressee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressee_user_id');
    }

    /** @param Builder<Friendship> $query */
    public function scopeBetween(Builder $query, User $first, User $second): Builder
    {
        return $query->where(function (Builder $builder) use ($first, $second): void {
            $builder->where('requester_user_id', $first->id)
                ->where('addressee_user_id', $second->id);
        })->orWhere(function (Builder $builder) use ($first, $second): void {
            $builder->where('requester_user_id', $second->id)
                ->where('addressee_user_id', $first->id);
        });
    }
}
