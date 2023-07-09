<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AutonomousSystem extends Model
{
    use HasFactory;

    public function contacts(): HasMany
    {
        return $this->hasMany(AutonomousSystemContact::class);
    }

    public function neighbours(): HasMany
    {
        return $this->hasMany(AutonomousSystemNeighbour::class);
    }

    public function prefixes(): HasMany
    {
        return $this->hasMany(AutonomousSystemPrefix::class);
    }
}
