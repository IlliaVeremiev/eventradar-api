<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->text('email')->unique();
            $table->timestampTz('email_verified_at')->nullable();
            $table->text('password');
            $table->rememberToken();
            $table->timestampsTz();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->text('email')->primary();
            $table->text('token');
            $table->timestampTz('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
