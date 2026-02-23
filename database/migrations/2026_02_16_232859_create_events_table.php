<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('title');
            $table->text('description')->nullable();
            $table->text('image')->nullable();

            $table->text('timezone')->nullable();

            $table->text('venue_name')->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('country_code')->nullable();
            $table->text('postal_code')->nullable();

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
