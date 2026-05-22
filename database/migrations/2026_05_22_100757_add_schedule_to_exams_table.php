<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('duration_minutes');
            $table->dateTime('start_time')->nullable()->after('is_published');
            $table->dateTime('end_time')->nullable()->after('start_time');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'start_time', 'end_time']);
        });
    }
};
