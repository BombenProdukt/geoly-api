<?php

declare(strict_types=1);

namespace App\JsonApi\V1;

use App\JsonApi\V1\AutonomousSystemContacts\AutonomousSystemContactSchema;
use App\JsonApi\V1\AutonomousSystemNeighbours\AutonomousSystemNeighbourSchema;
use App\JsonApi\V1\AutonomousSystemPrefixes\AutonomousSystemPrefixSchema;
use App\JsonApi\V1\AutonomousSystems\AutonomousSystemSchema;
use App\JsonApi\V1\Cities\CitySchema;
use App\JsonApi\V1\Continents\ContinentSchema;
use App\JsonApi\V1\Countries\CountrySchema;
use App\JsonApi\V1\InternetProtocolAddresses\InternetProtocolAddressSchema;
use App\JsonApi\V1\InternetProtocolBlocks\InternetProtocolBlockSchema;
use App\JsonApi\V1\Locations\LocationSchema;
use App\JsonApi\V1\RiskyAddresses\RiskyAddressSchema;
use App\JsonApi\V1\Subdivisions\SubdivisionSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

final class Server extends BaseServer
{
    /**
     * The base URI namespace for this server.
     */
    protected string $baseUri = '/api/v1';

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            AutonomousSystemSchema::class,
            AutonomousSystemContactSchema::class,
            AutonomousSystemNeighbourSchema::class,
            AutonomousSystemPrefixSchema::class,
            CitySchema::class,
            ContinentSchema::class,
            CountrySchema::class,
            InternetProtocolAddressSchema::class,
            InternetProtocolBlockSchema::class,
            LocationSchema::class,
            RiskyAddressSchema::class,
            SubdivisionSchema::class,
        ];
    }
}
