<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cek apakah index dengan nama tertentu sudah ada pada tabel.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        try {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =====================================================================
        // 1. TABEL DATA BARANG (tb_barang / barangs)
        //    Index pada kolom kategori (id_kategori / kategori) dan jenis_barang
        // =====================================================================
        $barangTables = array_filter(['tb_barang', 'barangs'], fn ($t) => Schema::hasTable($t));

        foreach ($barangTables as $barangTable) {
            Schema::table($barangTable, function (Blueprint $table) use ($barangTable) {
                if (
                    Schema::hasColumn($barangTable, 'id_kategori')
                    && !$this->indexExists($barangTable, "idx_{$barangTable}_id_kategori")
                ) {
                    $table->index('id_kategori', "idx_{$barangTable}_id_kategori");
                }

                if (
                    Schema::hasColumn($barangTable, 'kategori')
                    && !$this->indexExists($barangTable, "idx_{$barangTable}_kategori")
                ) {
                    $table->index('kategori', "idx_{$barangTable}_kategori");
                }

                if (
                    Schema::hasColumn($barangTable, 'jenis_barang')
                    && !$this->indexExists($barangTable, "idx_{$barangTable}_jenis_barang")
                ) {
                    $table->index('jenis_barang', "idx_{$barangTable}_jenis_barang");
                }
            });
        }

        // =====================================================================
        // 2. TABEL BARANG KELUAR HEADER (barang_keluars)
        //    Index pada kolom approval_status, status, dan created_at
        // =====================================================================
        if (Schema::hasTable('barang_keluars')) {
            Schema::table('barang_keluars', function (Blueprint $table) {
                if (
                    Schema::hasColumn('barang_keluars', 'approval_status')
                    && !$this->indexExists('barang_keluars', 'idx_barang_keluars_approval_status')
                ) {
                    $table->index('approval_status', 'idx_barang_keluars_approval_status');
                }

                if (
                    Schema::hasColumn('barang_keluars', 'status')
                    && !$this->indexExists('barang_keluars', 'idx_barang_keluars_status')
                ) {
                    $table->index('status', 'idx_barang_keluars_status');
                }

                if (
                    Schema::hasColumn('barang_keluars', 'created_at')
                    && !$this->indexExists('barang_keluars', 'idx_barang_keluars_created_at')
                ) {
                    $table->index('created_at', 'idx_barang_keluars_created_at');
                }
            });
        }

        // =====================================================================
        // 3. TABEL BARANG KELUAR DETAIL (barang_keluar_details)
        //    Index pada kolom tipe_item dan status_item
        // =====================================================================
        if (Schema::hasTable('barang_keluar_details')) {
            Schema::table('barang_keluar_details', function (Blueprint $table) {
                if (
                    Schema::hasColumn('barang_keluar_details', 'tipe_item')
                    && !$this->indexExists('barang_keluar_details', 'idx_barang_keluar_details_tipe_item')
                ) {
                    $table->index('tipe_item', 'idx_barang_keluar_details_tipe_item');
                }

                if (
                    Schema::hasColumn('barang_keluar_details', 'status_item')
                    && !$this->indexExists('barang_keluar_details', 'idx_barang_keluar_details_status_item')
                ) {
                    $table->index('status_item', 'idx_barang_keluar_details_status_item');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('barang_keluar_details')) {
            Schema::table('barang_keluar_details', function (Blueprint $table) {
                if ($this->indexExists('barang_keluar_details', 'idx_barang_keluar_details_tipe_item')) {
                    $table->dropIndex('idx_barang_keluar_details_tipe_item');
                }
                if ($this->indexExists('barang_keluar_details', 'idx_barang_keluar_details_status_item')) {
                    $table->dropIndex('idx_barang_keluar_details_status_item');
                }
            });
        }

        if (Schema::hasTable('barang_keluars')) {
            Schema::table('barang_keluars', function (Blueprint $table) {
                if ($this->indexExists('barang_keluars', 'idx_barang_keluars_approval_status')) {
                    $table->dropIndex('idx_barang_keluars_approval_status');
                }
                if ($this->indexExists('barang_keluars', 'idx_barang_keluars_status')) {
                    $table->dropIndex('idx_barang_keluars_status');
                }
                if ($this->indexExists('barang_keluars', 'idx_barang_keluars_created_at')) {
                    $table->dropIndex('idx_barang_keluars_created_at');
                }
            });
        }

        $barangTables = array_filter(['tb_barang', 'barangs'], fn ($t) => Schema::hasTable($t));
        foreach ($barangTables as $barangTable) {
            Schema::table($barangTable, function (Blueprint $table) use ($barangTable) {
                if ($this->indexExists($barangTable, "idx_{$barangTable}_id_kategori")) {
                    $table->dropIndex("idx_{$barangTable}_id_kategori");
                }
                if ($this->indexExists($barangTable, "idx_{$barangTable}_kategori")) {
                    $table->dropIndex("idx_{$barangTable}_kategori");
                }
                if ($this->indexExists($barangTable, "idx_{$barangTable}_jenis_barang")) {
                    $table->dropIndex("idx_{$barangTable}_jenis_barang");
                }
            });
        }
    }
};
