<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('qr_code')->unique();
            $table->string('qr_file_path', 500)->nullable();
            $table->string('session_name', 100)->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index('qr_code');
            $table->index(['student_id', 'is_used']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
