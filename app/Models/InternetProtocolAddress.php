<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class InternetProtocolAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_abuser' => 'boolean',
        'is_bogon' => 'boolean',
        'is_datacenter' => 'boolean',
        'is_noisy' => 'boolean',
        'is_proxy' => 'boolean',
        'is_rioter' => 'boolean',
        'is_tor' => 'boolean',
        'is_vpn' => 'boolean',
    ];

    public static function findByAddress(string $address): ?self
    {
        return self::where('address', $address)->first();
    }

    public function autonomousSystem(): BelongsTo
    {
        return $this->belongsTo(AutonomousSystem::class);
    }

    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function subdivisionPrimary(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_primary_id');
    }

    public function subdivisionSecondary(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_secondary_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function riskyAddress(): BelongsTo
    {
        return $this->belongsTo(RiskyAddress::class);
    }
}
