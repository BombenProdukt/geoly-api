<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AutonomousSystemNeighbour extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'asn' => 'integer',
        'power' => 'integer',
        'v4_peers' => 'integer',
        'v6_peers' => 'integer',
        'is_left' => 'boolean',
        'is_right' => 'boolean',
        'is_unique' => 'boolean',
        'is_uncertain' => 'boolean',
    ];

    /**
     * Get the autonomous system that owns the neighbour.
     */
    public function autonomousSystem(): BelongsTo
    {
        return $this->belongsTo(AutonomousSystem::class);
    }
}
