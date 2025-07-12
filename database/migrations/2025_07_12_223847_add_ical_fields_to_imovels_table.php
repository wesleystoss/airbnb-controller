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
        Schema::table('imoveis', function (Blueprint $table) {
            $table->string('ical_url')->nullable()->after('nome');
            $table->timestamp('last_ical_sync')->nullable()->after('ical_url');
            $table->json('calendar_events')->nullable()->after('last_ical_sync');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->dropColumn(['ical_url', 'last_ical_sync', 'calendar_events']);
        });
    }
};
