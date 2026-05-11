<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\User $user
 *
 * @method static Builder<static>|EventFollower newModelQuery()
 * @method static Builder<static>|EventFollower newQuery()
 * @method static Builder<static>|EventFollower query()
 * @method static Builder<static>|EventFollower whereCreatedAt($value)
 * @method static Builder<static>|EventFollower whereEventId($value)
 * @method static Builder<static>|EventFollower whereId($value)
 * @method static Builder<static>|EventFollower whereUpdatedAt($value)
 * @method static Builder<static>|EventFollower whereUserId($value)
 *
 * @mixin Eloquent
 */
class EventFollower extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'event_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
