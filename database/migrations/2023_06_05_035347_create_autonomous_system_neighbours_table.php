<?php

declare(strict_types=1);

use App\Models\AutonomousSystem;
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
        Schema::create('autonomous_system_neighbours', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(AutonomousSystem::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('asn');
            $table->unsignedInteger('power');
            $table->unsignedInteger('v4_peers');
            $table->unsignedInteger('v6_peers');
            $table->boolean('is_left');
            $table->boolean('is_right');
            $table->boolean('is_unique');
            $table->boolean('is_uncertain');
            $table->timestamps();

            $table->unique(['autonomous_system_id', 'asn']);
        });
    }
};
