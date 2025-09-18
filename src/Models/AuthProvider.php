<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \Waterhole\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthProvider query()
 * @mixin \Eloquent
 */
class AuthProvider extends Model
{
    const UPDATED_AT = null;

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
