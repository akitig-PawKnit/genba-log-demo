<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('worker_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedInteger('daily_rate');

            $table->date('effective_from');

            $table->date('effective_to')
                ->nullable();

            $table->timestamps();

            $table->index([
                'worker_id',
                'effective_from',
                'effective_to',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_rates');
    }
};
