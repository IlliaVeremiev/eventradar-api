<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property string|null $image
 * @property string|null $timezone
 * @property string|null $venue_name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country_code
 * @property string|null $postal_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\EventSession> $sessions
 * @property-read int|null $sessions_count
 * @property-read Collection<int, \App\Models\EventSource> $sources
 * @property-read int|null $sources_count
 *
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event whereAddress($value)
 * @method static Builder<static>|Event whereCity($value)
 * @method static Builder<static>|Event whereCountryCode($value)
 * @method static Builder<static>|Event whereCreatedAt($value)
 * @method static Builder<static>|Event whereDescription($value)
 * @method static Builder<static>|Event whereId($value)
 * @method static Builder<static>|Event whereImage($value)
 * @method static Builder<static>|Event wherePostalCode($value)
 * @method static Builder<static>|Event whereState($value)
 * @method static Builder<static>|Event whereTimezone($value)
 * @method static Builder<static>|Event whereTitle($value)
 * @method static Builder<static>|Event whereUpdatedAt($value)
 * @method static Builder<static>|Event whereVenueName($value)
 *
 * @mixin Eloquent
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
