<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventSession> $sessions
 * @property-read int|null $sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventSource> $sources
 * @property-read int|null $sources_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'description',
        'image',
        'timezone',
        'venue_name',
        'address',
        'city',
        'state',
        'country_code',
        'postal_code',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class)->orderBy('date')->orderBy('start_time');
    }

    public function sources(): HasMany
    {
        return $this->hasMany(EventSource::class);
    }
}
