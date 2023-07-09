<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\InternetProtocolBlock;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexInternetProtocolBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-internet-protocol-blocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index internet protocol blocks from herrbischoff/country-ip-blocks';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Country::cursor() as $country) {
            try {
                $this->ipv4($country);
            } catch (\Throwable $th) {
                $this->error(\sprintf('Failed to index IPv4 blocks  for %s: %s', $country->cca2, $th->getMessage()));
            }

            try {
                $this->ipv6($country);
            } catch (\Throwable $th) {
                $this->error(\sprintf('Failed to index IPv6 blocks for %s: %s', $country->cca2, $th->getMessage()));
            }
        }
    }

    private function ipv4(Country $country): void
    {
        $ipv4 = Http::get(\sprintf('https://raw.githubusercontent.com/herrbischoff/country-ip-blocks/master/ipv4/%s.cidr', \mb_strtolower($country->cca2)))->throw()->body();

        collect(\explode(\PHP_EOL, $ipv4))
            ->reject(static fn (string $route): bool => empty($route))
            ->map(static fn (string $route): array => ['country_id' => $country->id, 'route' => $route, 'is_ipv4' => true, 'is_ipv6' => false])
            ->chunk(1000)
            ->each(static fn (Collection $chunk): int => InternetProtocolBlock::upsert($chunk->toArray(), ['route']));
    }

    private function ipv6(Country $country): void
    {
        $ipv6 = Http::get(\sprintf('https://raw.githubusercontent.com/herrbischoff/country-ip-blocks/master/ipv6/%s.cidr', \mb_strtolower($country->cca2)))->throw()->body();

        collect(\explode(\PHP_EOL, $ipv6))
            ->reject(static fn (string $route): bool => empty($route))
            ->map(static fn (string $route): array => ['country_id' => $country->id, 'route' => $route, 'is_ipv4' => false, 'is_ipv6' => true])
            ->chunk(1000)
            ->each(static fn (Collection $chunk): int => InternetProtocolBlock::upsert($chunk->toArray(), ['route']));
    }
}
