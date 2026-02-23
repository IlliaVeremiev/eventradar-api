<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\Event|null $event
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource query()
 *
 * @mixin \Eloquent
 */
class EventSource extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'url',
        'domain',
        'last_checked_at',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
