<?php

declare(strict_types=1);

use App\Models\Country;
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
        Schema::create('internet_protocol_blocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Country::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('route')->unique();
            $table->string('is_ipv4');
            $table->string('is_ipv6');
            $table->timestamps();
        });
    }
};
