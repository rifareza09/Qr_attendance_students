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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('student_ip', 45);
            $table->string('qr_code');
            $table->date('attendance_date');
            $table->time('attendance_time');
            $table->string('session_name', 100)->nullable();
            $table->string('wifi_ssid', 100)->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamp('marked_at')->useCurrent();
            $table->foreignId('modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('modified_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'attendance_date']);
            $table->index('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
