<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $event_id
 * @property string $url
 * @property string $domain
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventSource whereUrl($value)
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
