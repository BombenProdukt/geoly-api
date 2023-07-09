<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'array',
        'tld' => 'array',
        'independent' => 'boolean',
        'unMember' => 'boolean',
        'currencies' => 'array',
        'idd' => 'array',
        'capital' => 'array',
        'altSpellings' => 'array',
        'languages' => 'array',
        'translations' => 'array',
        'latlng' => 'array',
        'borders' => 'array',
        'demonyms' => 'array',
        'callingCodes' => 'array',
    ];

    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class);
    }

    public function internetProtocolBlocks(): HasMany
    {
        return $this->hasMany(InternetProtocolBlock::class);
    }
}
