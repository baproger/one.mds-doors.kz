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
        Schema::table('segments', function (Blueprint $table) {
            $table->string('panel_type')->nullable()->after('color'); // панель/панель, панель/металл, металл/металл
            $table->json('specs')->nullable()->after('panel_type');   // характеристики: толщина стали, термослои и т.д.
        });
    }

    public function down(): void
    {
        Schema::table('segments', function (Blueprint $table) {
            $table->dropColumn(['panel_type', 'specs']);
        });
    }
};
