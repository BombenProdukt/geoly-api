<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AutonomousSystem;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\InternetProtocolAddress;
use App\Models\Location;
use App\Models\RiskyAddress;
use App\Models\Subdivision;
use BombenProdukt\GeoIp2\Facades\GeoIp2;
use GeoIp2\Model\Asn as GeoASN;
use GeoIp2\Model\City as GeoCity;
use GeoIp2\Record\Continent as GeoContinent;
use GeoIp2\Record\Country as GeoCountry;
use GeoIp2\Record\Location as GeoLocation;

final class ProcessAddress
{
    public static function execute(string $address): InternetProtocolAddress
    {
        /** @var GeoASN */
        $asn = GeoIp2::asn($address);

        /** @var GeoCity */
        $city = GeoIp2::city($address);

        /** @var GeoCountry */
        $country = GeoIp2::country($address);

        /** @var null|RiskyAddress */
        $riskyAddress = RiskyAddress::where('address', $address)->first();

        /** @var null|AutonomousSystem */
        $autonomousSystem = AutonomousSystem::where('code', $asn->autonomousSystemNumber)->firstOrFail();

        return InternetProtocolAddress::create([
            'autonomous_system_id' => $autonomousSystem->id,
            'continent_id' => self::getContinentModel($country->continent)->id,
            'country_id' => $countryModel = self::getCountryModel($country->country)->id,
            'subdivision_primary_id' => self::getSubdivisionModel($countryModel, data_get($city, 'subdivisions.0'))?->id,
            'subdivision_secondary_id' => self::getSubdivisionModel($countryModel, data_get($city, 'subdivisions.1'))?->id,
            'city_id' => $cityModel = self::getCityModel($countryModel, $city)->id,
            'location_id' => self::getLocationModel($cityModel, $city->location)->id,
            'risky_address_id' => $riskyAddress?->id,
            'address' => $address,
        ]);
    }

    private static function getContinentModel(GeoContinent $data): Continent
    {
        return Continent::where('name', $data->name)->firstOrFail();
    }

    private static function getCountryModel(GeoCountry $data): Country
    {
        return Country::where('cca2', \mb_strtoupper($data->isoCode))->firstOrFail();
    }

    private static function getSubdivisionModel(int $countryId, ?object $data): ?Subdivision
    {
        if (empty($data)) {
            return null;
        }

        return Subdivision::firstOrCreate(
            [
                'code' => $data->isoCode,
            ],
            [
                'country_id' => $countryId,
                'name' => $data->name,
            ],
        );
    }

    private static function getCityModel(int $countryId, GeoCity $data): City
    {
        return City::firstOrCreate(
            [
                'code' => $data->postal->code,
            ],
            [
                'country_id' => $countryId,
                'name' => $data->city->name,
            ],
        );
    }

    private static function getLocationModel(int $cityId, GeoLocation $data): Location
    {
        return Location::firstOrCreate(
            [
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
            ],
            [
                'city_id' => $cityId,
                'accuracy_km' => $data->accuracyRadius,
                'accuracy_mi' => \round($data->accuracyRadius * 0.621371, 2),
                'timezone' => $data->timeZone,
            ],
        );
    }
}
