<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 *
 * @method static Builder<static>|RefreshToken newModelQuery()
 * @method static Builder<static>|RefreshToken newQuery()
 * @method static Builder<static>|RefreshToken onlyTrashed()
 * @method static Builder<static>|RefreshToken query()
 * @method static Builder<static>|RefreshToken whereCreatedAt($value)
 * @method static Builder<static>|RefreshToken whereDeletedAt($value)
 * @method static Builder<static>|RefreshToken whereExpiresAt($value)
 * @method static Builder<static>|RefreshToken whereId($value)
 * @method static Builder<static>|RefreshToken whereToken($value)
 * @method static Builder<static>|RefreshToken whereUpdatedAt($value)
 * @method static Builder<static>|RefreshToken whereUserId($value)
 * @method static Builder<static>|RefreshToken withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|RefreshToken withoutTrashed()
 *
 * @mixin Eloquent
 */
class RefreshToken extends Model
{
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
