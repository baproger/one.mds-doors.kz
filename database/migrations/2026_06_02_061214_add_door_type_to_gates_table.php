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
            $table->string('leaf_type')->nullable()->after('type');
            // single = одностворчатая, double = двухстворчатая, triple = трёхстворчатая
        });
    }

    public function down(): void
    {
        Schema::table('gates', function (Blueprint $table) {
            $table->dropColumn('leaf_type');
        });
    }
};
