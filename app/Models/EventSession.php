<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $event_id
 * @property Carbon $date
 * @property Carbon|null $start_time
 * @property Carbon|null $end_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 *
 * @method static Builder<static>|EventSession newModelQuery()
 * @method static Builder<static>|EventSession newQuery()
 * @method static Builder<static>|EventSession query()
 * @method static Builder<static>|EventSession whereCreatedAt($value)
 * @method static Builder<static>|EventSession whereDate($value)
 * @method static Builder<static>|EventSession whereEndTime($value)
 * @method static Builder<static>|EventSession whereEventId($value)
 * @method static Builder<static>|EventSession whereId($value)
 * @method static Builder<static>|EventSession whereStartTime($value)
 * @method static Builder<static>|EventSession whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class EventSession extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
