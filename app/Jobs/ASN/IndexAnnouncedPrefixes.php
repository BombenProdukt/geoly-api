<?php

declare(strict_types=1);

namespace App\Jobs\ASN;

use App\Models\AutonomousSystem;
use App\Models\AutonomousSystemPrefix;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexAnnouncedPrefixes implements ShouldQueue
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
        $prefixes = Http::baseurl('https://stat.ripe.net/')
            ->withUrlParameters(['resource' => $this->autonomousSystem->code])
            ->get('data/announced-prefixes/data.json?resource={resource}')
            ->throw()
            ->json('data.prefixes');

        collect($prefixes)
            ->map(fn (array $prefix): array => [
                'autonomous_system_id' => $this->autonomousSystem->id,
                'prefix' => $prefix['prefix'],
                'timelines' => \json_encode($prefix['prefix']),
            ])
            ->chunk(1000)
            ->each(fn (Collection $prefixes): int => AutonomousSystemPrefix::upsert($prefixes->toArray(), ['autonomous_system_id', 'prefix']));
    }
}
