<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\RiskyAddress;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexRiskyAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-risky-addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index risky addresses from stamparm/ipsum';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $listRaw = Http::get('https://raw.githubusercontent.com/stamparm/ipsum/master/ipsum.txt')->body();

        collect(\explode(\PHP_EOL, $listRaw))
            ->reject(static fn (string $address): bool => \str_starts_with($address, '#'))
            ->reject(static fn (string $address): bool => empty($address))
            ->map(static function (string $address): array {
                [$address, $hits] = \explode("\t", $address);

                return [
                    'address' => $address,
                    'hits' => (int) $hits,
                ];
            })
            ->chunk(1000)
            ->each(static fn (Collection $chunk): int => RiskyAddress::upsert($chunk->toArray(), ['address']));
    }
}
