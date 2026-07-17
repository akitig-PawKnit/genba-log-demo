<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('worker_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('work_date');

            $table->string('status', 20);

            $table->foreignId('submitted_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('submitted_at');

            $table->timestamps();

            $table->unique([
                'worker_id',
                'work_date',
            ]);

            $table->index([
                'work_date',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_attendances');
    }
};
