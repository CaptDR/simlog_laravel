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
        Schema::table('tb_barang', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_barang', 'merk')) {
                $table->string('merk', 100)->nullable()->after('satuan');
            }
            if (!Schema::hasColumn('tb_barang', 'gambar')) {
                $table->string('gambar')->nullable()->after('satuan');
            }
            if (!Schema::hasColumn('tb_barang', 'kondisi')) {
                $table->string('kondisi', 50)->default('Baik')->after('stok_minimum');
            }
            if (!Schema::hasColumn('tb_barang', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('kondisi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_barang', function (Blueprint $table) {
            $cols = [];
            foreach (['merk', 'gambar', 'kondisi', 'is_active'] as $col) {
                if (Schema::hasColumn('tb_barang', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
