<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('url');
            $table->text('domain');

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sources');
    }
};
