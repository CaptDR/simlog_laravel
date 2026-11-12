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
        Schema::table('tb_barang_masuk', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_barang_masuk', 'expire_date')) {
                $table->date('expire_date')->nullable()->after('tanggal_masuk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_barang_masuk', function (Blueprint $table) {
            if (Schema::hasColumn('tb_barang_masuk', 'expire_date')) {
                $table->dropColumn('expire_date');
            }
        });
    }
};
