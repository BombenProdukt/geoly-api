<?php

declare(strict_types=1);

namespace App\JsonApi\V1\InternetProtocolAddresses;

use App\Models\InternetProtocolAddress;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

final class InternetProtocolAddressSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = InternetProtocolAddress::class;

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ID::make(),
            BelongsTo::make('autonomousSystem'),
            BelongsTo::make('continent'),
            BelongsTo::make('country'),
            BelongsTo::make('subdivisionPrimary')->type('subdivisions'),
            BelongsTo::make('subdivisionSecondary')->type('subdivisions'),
            BelongsTo::make('city'),
            BelongsTo::make('location'),
            BelongsTo::make('riskyAddress'),
            Str::make('address'),
            Boolean::make('is_abuser'),
            Boolean::make('is_bogon'),
            Boolean::make('is_datacenter'),
            Boolean::make('is_noisy'),
            Boolean::make('is_proxy'),
            Boolean::make('is_rioter'),
            Boolean::make('is_tor'),
            Boolean::make('is_vpn'),
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
