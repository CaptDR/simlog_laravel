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
        if (!Schema::hasTable('pemutihans')) {
            Schema::create('pemutihans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_pemutihan', 50)->unique();
                $table->integer('barang_id')->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->integer('jumlah');
                $table->string('alasan', 50); // Kadaluarsa, Rusak Berat, Hilang/Susut
                $table->text('keterangan')->nullable();
                $table->string('bukti_foto')->nullable();
                $table->string('status', 50)->default('Menunggu Persetujuan'); // Menunggu Persetujuan, Disetujui, Ditolak
                $table->text('catatan_kasie')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemutihans');
    }
};
