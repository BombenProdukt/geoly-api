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
        Schema::create('autonomous_system_prefixes', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(AutonomousSystem::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('prefix');
            $table->json('timelines');
            $table->timestamps();

            $table->unique(['autonomous_system_id', 'prefix']);
        });
    }
};
