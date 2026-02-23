<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $event_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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
