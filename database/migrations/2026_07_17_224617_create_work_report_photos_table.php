<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_report_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('work_report_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_path');

            $table->string('original_name');

            $table->string('mime_type', 100);

            $table->unsignedBigInteger('file_size');

            $table->unsignedInteger('width')
                ->nullable();

            $table->unsignedInteger('height')
                ->nullable();

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->decimal('location_accuracy', 10, 2)
                ->nullable();

            $table->timestamp('location_captured_at')
                ->nullable();

            $table->timestamp('uploaded_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_report_photos');
    }
};
