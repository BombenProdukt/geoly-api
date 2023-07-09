<?php

declare(strict_types=1);

namespace App\Jobs\ASN;

use App\Models\AutonomousSystem;
use App\Models\AutonomousSystemNeighbour;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexNeighbours implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public AutonomousSystem $autonomousSystem)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $neighbours = Http::baseurl('https://stat.ripe.net/')
            ->withUrlParameters(['resource' => $this->autonomousSystem->code])
            ->get('data/asn-neighbours/data.json?resource={resource}')
            ->throw()
            ->json('data.neighbours');

        collect($neighbours)
            ->map(fn (array $neighbour): array => [
                'autonomous_system_id' => $this->autonomousSystem->id,
                'asn' => $neighbour['asn'],
                'power' => $neighbour['power'],
                'v4_peers' => $neighbour['v4_peers'],
                'v6_peers' => $neighbour['v6_peers'],
                'is_left' => $neighbour['type'] === 'left',
                'is_right' => $neighbour['type'] === 'right',
                'is_unique' => $neighbour['type'] === 'unique',
                'is_uncertain' => $neighbour['type'] === 'uncertain',
            ])
            ->chunk(1000)
            ->each(fn (Collection $neighbours): int => AutonomousSystemNeighbour::upsert($neighbours->toArray(), ['autonomous_system_id', 'asn']));
    }
}
