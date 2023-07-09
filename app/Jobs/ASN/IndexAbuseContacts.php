<?php

declare(strict_types=1);

namespace App\Jobs\ASN;

use App\Models\AutonomousSystem;
use App\Models\AutonomousSystemContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class IndexAbuseContacts implements ShouldQueue
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
        $contacts = Http::baseurl('https://stat.ripe.net/')
            ->withUrlParameters(['resource' => $this->autonomousSystem->code])
            ->get('data/abuse-contact-finder/data.json?resource={resource}')
            ->throw()
            ->json('data.abuse_contacts');

        collect($contacts)
            ->map(fn (string $contact): array => [
                'autonomous_system_id' => $this->autonomousSystem->id,
                'type' => 'abuse',
                'email' => $contact,
            ])
            ->chunk(1000)
            ->each(fn (Collection $contacts): int => AutonomousSystemContact::upsert($contacts->toArray(), ['autonomous_system_id', 'type', 'email']));
    }
}
