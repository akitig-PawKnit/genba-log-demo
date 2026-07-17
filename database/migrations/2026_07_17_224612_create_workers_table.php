<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();

            $table->string('employee_code', 50)
                ->nullable()
                ->unique();

            $table->string('name', 100);

            $table->string('pin_hash');

            $table->unsignedInteger('display_order')
                ->default(0);

            $table->date('joined_on')
                ->nullable();

            $table->date('left_on')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamp('last_login_at')
                ->nullable();

            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
