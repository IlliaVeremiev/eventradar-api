<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_id')->nullable()->unique()->after('id');
            $table->json('google_payload')->nullable()->after('google_id');
            $table->text('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google_payload']);
            $table->text('password')->nullable(false)->change();
        });
    }
};
