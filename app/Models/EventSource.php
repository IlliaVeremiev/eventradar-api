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
 * @property string $url
 * @property string $domain
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 *
 * @method static Builder<static>|EventSource newModelQuery()
 * @method static Builder<static>|EventSource newQuery()
 * @method static Builder<static>|EventSource query()
 * @method static Builder<static>|EventSource whereCreatedAt($value)
 * @method static Builder<static>|EventSource whereDomain($value)
 * @method static Builder<static>|EventSource whereEventId($value)
 * @method static Builder<static>|EventSource whereId($value)
 * @method static Builder<static>|EventSource whereUpdatedAt($value)
 * @method static Builder<static>|EventSource whereUrl($value)
 *
 * @mixin Eloquent
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
