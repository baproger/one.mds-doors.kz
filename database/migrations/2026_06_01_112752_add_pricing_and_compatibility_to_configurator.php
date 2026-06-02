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
        // Сегменты — базовая цена
        Schema::table('segments', function (Blueprint $table) {
            $table->unsignedBigInteger('base_price')->default(0)->after('specs');
        });

        // Текстуры — доп. стоимость (0 = включено в базу)
        Schema::table('textures', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->default(0)->after('type');
        });

        // Ручки — доп. стоимость
        Schema::table('door_handles', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->default(0)->after('name');
        });

        // Двери — совместимость и slug
        Schema::table('gates', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->json('allowed_segment_ids')->nullable()->after('configurations');   // null = все сегменты
            $table->json('allowed_frame_types')->nullable()->after('allowed_segment_ids'); // null = все типы
        });
    }

    public function down(): void
    {
        Schema::table('segments',     fn ($t) => $t->dropColumn('base_price'));
        Schema::table('textures',     fn ($t) => $t->dropColumn('price'));
        Schema::table('door_handles', fn ($t) => $t->dropColumn('price'));
        Schema::table('gates',        fn ($t) => $t->dropColumn(['slug', 'allowed_segment_ids', 'allowed_frame_types']));
    }
};
