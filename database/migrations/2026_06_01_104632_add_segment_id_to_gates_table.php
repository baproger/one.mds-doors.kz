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
        Schema::table('gates', function (Blueprint $table) {
            $table->foreignId('segment_id')->nullable()->constrained('segments')->nullOnDelete()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Segment::class);
            $table->dropColumn('segment_id');
        });
    }
};
