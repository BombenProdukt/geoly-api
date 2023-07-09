<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Countries;

use App\Models\Country;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

final class CountrySchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = Country::class;

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('continent'),
            HasMany::make('internetProtocolBlocks'),
            ArrayHash::make('name'),
            ArrayList::make('tld'),
            Str::make('cca2'),
            Str::make('ccn3'),
            Str::make('cca3'),
            Str::make('cioc'),
            Boolean::make('independent'),
            Str::make('status'),
            Boolean::make('unMember'),
            ArrayHash::make('currencies'),
            ArrayHash::make('idd'),
            ArrayList::make('capital'),
            ArrayList::make('altSpellings'),
            Str::make('region'),
            Str::make('subregion'),
            ArrayHash::make('languages'),
            ArrayHash::make('translations'),
            ArrayList::make('latlng'),
            Boolean::make('landlocked'),
            ArrayList::make('borders'),
            Str::make('area'),
            Str::make('flag'),
            ArrayHash::make('demonyms'),
            ArrayList::make('callingCodes'),
            DateTime::make('created_at')->sortable()->readOnly(),
            DateTime::make('updated_at')->sortable()->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
