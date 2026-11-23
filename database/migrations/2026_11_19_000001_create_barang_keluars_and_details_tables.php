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
        Schema::dropIfExists('barang_keluar_details');
        Schema::dropIfExists('barang_keluars');

        // 1. Tabel Header Transaksi Barang Keluar
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique(); // Format: OUT-YYMM-XXXX
            $table->date('tanggal_keluar');
            $table->string('tujuan_bencana');
            $table->string('penerima');
            $table->text('keterangan')->nullable();
            $table->string('status', 50)->default('Diajukan Logistik'); // Diajukan Logistik, Dalam Pengiriman, Diterima di Lokasi, Ditolak
            $table->string('approval_status', 50)->default('Menunggu Approval'); // Menunggu Approval, Disetujui, Ditolak
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('trc_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('lokasi_aktual')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Detail Item Barang Keluar (Multi-Item)
        Schema::create('barang_keluar_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_keluar_id')->constrained('barang_keluars')->cascadeOnDelete();
            $table->integer('barang_id')->index();
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_details');
        Schema::dropIfExists('barang_keluars');
    }
};
