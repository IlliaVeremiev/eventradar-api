<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventSession> $sessions
 * @property-read int|null $sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventSource> $sources
 * @property-read int|null $sources_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenueName($value)
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

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
