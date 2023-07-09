<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\InternetProtocolAddress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

final class IndexInternetProtocolAddressStates implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public InternetProtocolAddress $internetProtocolAddress)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $incolumitas = Http::get('https://api.incolumitas.com/datacenter', [
            'ip' => $this->internetProtocolAddress->address,
        ])->throw();

        $greynoise = Http::withUrlParameters(['ip' => $this->internetProtocolAddress->address])
            ->get('https://api.greynoise.io/v3/community/{ip}');

        $this->internetProtocolAddress->update([
            'is_abuser' => $incolumitas->json('is_abuser'),
            'is_bogon' => $incolumitas->json('is_bogon'),
            'is_datacenter' => $incolumitas->json('is_datacenter'),
            'is_noisy' => $greynoise->json('noise'),
            'is_proxy' => $incolumitas->json('is_proxy'),
            'is_rioter' => $greynoise->json('riot'),
            'is_tor' => $incolumitas->json('is_tor'),
            'is_vpn' => $incolumitas->json('is_vpn'),
        ]);
    }
}
