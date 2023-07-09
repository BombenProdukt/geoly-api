<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Continent;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index countries from mledoze/countries';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $rows = Http::get('https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json')->throw()->json();

        collect($rows)->chunk(1000)->each(fn (Collection $chunk): int => Country::upsert($this->mapChunk($chunk->toArray()), ['cca2']));
    }

    private function mapChunk(array $countries): array
    {
        return \array_map(
            fn (array $country): array => [
                'continent_id' => $this->getContinentId($country),
                'name' => \json_encode($country['name']),
                'tld' => \json_encode($country['tld']),
                'cca2' => $country['cca2'],
                'ccn3' => $country['ccn3'],
                'cca3' => $country['cca3'],
                'cioc' => $country['cioc'],
                'independent' => $country['independent'] ?? false,
                'status' => $country['status'],
                'unMember' => $country['unMember'] ?? false,
                'currencies' => \json_encode($country['currencies']),
                'idd' => \json_encode($country['idd']),
                'capital' => \json_encode($country['capital']),
                'altSpellings' => \json_encode($country['altSpellings']),
                'region' => $country['region'],
                'subregion' => $country['subregion'],
                'languages' => \json_encode($country['languages']),
                'translations' => \json_encode($country['translations']),
                'latlng' => \json_encode($country['latlng']),
                'landlocked' => $country['landlocked'] ?? false,
                'borders' => \json_encode($country['borders']),
                'area' => $country['area'],
                'flag' => $country['flag'],
                'demonyms' => \json_encode($country['demonyms']),
                'callingCodes' => \json_encode($country['callingCodes']),
            ],
            $countries,
        );
    }

    private function getContinentId(array $country): int
    {
        $region = $country['region'];

        if ($region === 'Americas') {
            $region = 'North America';
        }

        if ($region === 'Antarctic') {
            $region = 'Antarctica';
        }

        return Continent::where('name', $region)->firstOrFail()->id;
    }
}
