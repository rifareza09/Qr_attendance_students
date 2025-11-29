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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('email');
            $table->string('two_fa_secret')->nullable()->after('password');
            $table->boolean('is_2fa_enabled')->default(false)->after('two_fa_secret');
            $table->timestamp('last_login')->nullable()->after('is_2fa_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'two_fa_secret', 'is_2fa_enabled', 'last_login']);
        });
    }
};
