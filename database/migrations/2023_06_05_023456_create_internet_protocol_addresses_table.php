<?php

declare(strict_types=1);

use App\Models\AutonomousSystem;
use App\Models\City;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Location;
use App\Models\RiskyAddress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('internet_protocol_addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(AutonomousSystem::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Continent::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Country::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subdivision_primary_id')->nullable()->constrained('subdivisions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('subdivision_secondary_id')->nullable()->constrained('subdivisions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Location::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(RiskyAddress::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->ipAddress('address')->unique();
            $table->boolean('is_abuser')->default(false);
            $table->boolean('is_bogon')->default(false);
            $table->boolean('is_datacenter')->default(false);
            $table->boolean('is_noisy')->default(false);
            $table->boolean('is_proxy')->default(false);
            $table->boolean('is_rioter')->default(false);
            $table->boolean('is_tor')->default(false);
            $table->boolean('is_vpn')->default(false);
            $table->timestamps();
        });
    }
};
