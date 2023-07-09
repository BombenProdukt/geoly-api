<?php

declare(strict_types=1);

use App\Models\Continent;
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
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Continent::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('name');
            $table->json('tld');
            $table->string('cca2')->unique();
            $table->string('ccn3');
            $table->string('cca3');
            $table->string('cioc');
            $table->boolean('independent');
            $table->string('status');
            $table->boolean('unMember');
            $table->json('currencies');
            $table->json('idd');
            $table->json('capital');
            $table->json('altSpellings');
            $table->string('region');
            $table->string('subregion');
            $table->json('languages');
            $table->json('translations');
            $table->json('latlng');
            $table->boolean('landlocked');
            $table->json('borders');
            $table->string('area');
            $table->string('flag');
            $table->json('demonyms');
            $table->json('callingCodes');
            $table->timestamps();
        });
    }
};
