<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();

            $table->string('name', 200);

            $table->string('short_name', 100)
                ->nullable();

            $table->unsignedBigInteger('contract_amount')
                ->nullable();

            $table->date('starts_on')
                ->nullable();

            $table->date('planned_ends_on')
                ->nullable();

            $table->date('ended_on')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index(['is_active', 'starts_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
