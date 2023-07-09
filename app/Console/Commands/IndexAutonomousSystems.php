<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AutonomousSystem;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexAutonomousSystems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-autonomous-systems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index autonomous systems from ipverse/asn-info';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $listRaw = Http::get('https://raw.githubusercontent.com/ipverse/asn-info/master/as.csv')->body();
        $listRaw = collect(\explode(\PHP_EOL, $listRaw));
        $listRaw->shift();

        $listRaw
            ->reject(static fn (string $address): bool => empty($address))
            ->map(static function (string $address): array {
                [$code, $name, $organization] = \explode(',', $address);

                return [
                    'code' => $code,
                    'name' => \trim($name),
                    'organization' => \trim($organization, '"'),
                ];
            })
            ->chunk(1000)
            ->each(static fn (Collection $chunk): int => AutonomousSystem::upsert($chunk->toArray(), ['code']));
    }
}
