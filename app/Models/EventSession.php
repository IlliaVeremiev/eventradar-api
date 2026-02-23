<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\Event|null $event
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSession query()
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
