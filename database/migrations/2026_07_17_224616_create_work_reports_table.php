<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_attendance_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('labor_units', 3, 1);

            $table->string('work_shift', 20)
                ->default('day');

            $table->string('work_role', 20)
                ->default('regular');

            $table->decimal('overtime_hours', 4, 1)
                ->default(0);

            $table->unsignedInteger('highway_cost')
                ->default(0);

            $table->unsignedInteger('parking_cost')
                ->default(0);

            $table->unsignedInteger('other_cost')
                ->default(0);

            $table->string('other_cost_note', 255)
                ->nullable();

            $table->text('notes')
                ->nullable();

            /*
             * 計算時点の単価を保存するスナップショット
             */
            $table->unsignedInteger('daily_rate_snapshot');

            $table->decimal('night_multiplier_snapshot', 4, 2)
                ->default(1.00);

            $table->unsignedInteger('overtime_rate_snapshot')
                ->default(1000);

            /*
             * 計算結果
             */
            $table->unsignedInteger('base_labor_cost');

            $table->unsignedInteger('overtime_cost')
                ->default(0);

            $table->unsignedInteger('labor_cost');

            $table->unsignedInteger('expense_total')
                ->default(0);

            $table->unsignedInteger('total_cost');

            $table->timestamps();

            $table->index([
                'daily_attendance_id',
                'site_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};
