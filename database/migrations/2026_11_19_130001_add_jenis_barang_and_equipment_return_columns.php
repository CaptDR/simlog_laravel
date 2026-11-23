<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom jenis_barang pada tabel tb_barang (atau barangs jika ada)
        $barangTable = Schema::hasTable('tb_barang') ? 'tb_barang' : 'barangs';

        if (Schema::hasTable($barangTable) && !Schema::hasColumn($barangTable, 'jenis_barang')) {
            Schema::table($barangTable, function (Blueprint $table) use ($barangTable) {
                if (Schema::hasColumn($barangTable, 'id_kategori')) {
                    $table->string('jenis_barang', 30)->default('logistik')->after('id_kategori');
                } else {
                    $table->string('jenis_barang', 30)->default('logistik');
                }
            });
        }

        // 2. Tambahkan kolom siklus peminjaman peralatan pada tabel barang_keluar_details
        if (Schema::hasTable('barang_keluar_details')) {
            Schema::table('barang_keluar_details', function (Blueprint $table) {
                if (!Schema::hasColumn('barang_keluar_details', 'tipe_item')) {
                    $table->string('tipe_item', 30)->default('logistik')->after('jumlah');
                }
                if (!Schema::hasColumn('barang_keluar_details', 'status_item')) {
                    $table->string('status_item', 40)->default('Didistribusikan')->after('tipe_item');
                }
                if (!Schema::hasColumn('barang_keluar_details', 'kondisi_kembali')) {
                    $table->string('kondisi_kembali', 40)->nullable()->after('status_item');
                }
                if (!Schema::hasColumn('barang_keluar_details', 'catatan_kembali')) {
                    $table->text('catatan_kembali')->nullable()->after('kondisi_kembali');
                }
                if (!Schema::hasColumn('barang_keluar_details', 'returned_at')) {
                    $table->timestamp('returned_at')->nullable()->after('catatan_kembali');
                }
                if (!Schema::hasColumn('barang_keluar_details', 'returned_by')) {
                    $table->foreignId('returned_by')
                          ->nullable()
                          ->after('returned_at')
                          ->constrained('users')
                          ->nullOnDelete();
                }
            });
        }

        // 3. Standarisasi Nama Kategori pada tabel tb_kategori
        if (Schema::hasTable('tb_kategori')) {
            $hasPrefix = Schema::hasColumn('tb_kategori', 'kode_prefix');

            // Daftar pemetaan nama lama / kata kunci ke nama kategori standar baru
            $mappings = [
                ['like' => '%Pangan%',     'nama' => 'Logistik Pangan & Air Bersih',    'prefix' => 'PGN'],
                ['like' => '%Sandang%',    'nama' => 'Sandang & Perlengkapan Tidur',    'prefix' => 'SDG'],
                ['like' => '%Hunian%',     'nama' => 'Hunian Sementara (Shelter)',      'prefix' => 'HNI'],
                ['like' => '%Shelter%',    'nama' => 'Hunian Sementara (Shelter)',      'prefix' => 'HNI'],
                ['like' => '%Kebersihan%', 'nama' => 'Kebersihan & Sanitasi',           'prefix' => 'KBR'],
                ['like' => '%Sanitasi%',   'nama' => 'Kebersihan & Sanitasi',           'prefix' => 'KBR'],
                ['like' => '%Rentan%',     'nama' => 'Kebutuhan Kelompok Rentan',       'prefix' => 'RTN'],
                ['like' => '%Medis%',      'nama' => 'Kesehatan & Medis',               'prefix' => 'MDS'],
                ['like' => '%Kesehatan%',  'nama' => 'Kesehatan & Medis',               'prefix' => 'MDS'],
                ['like' => '%SAR%',        'nama' => 'Peralatan Evakuasi & SAR',        'prefix' => 'SAR'],
                ['like' => '%Evakuasi%',   'nama' => 'Peralatan Evakuasi & SAR',        'prefix' => 'SAR'],
                ['like' => '%Dapur%',      'nama' => 'Peralatan Dapur Umum',            'prefix' => 'DPR'],
                ['like' => '%Komunikasi%', 'nama' => 'Peralatan Komunikasi & Navigasi', 'prefix' => 'KOM'],
                ['like' => '%Navigasi%',   'nama' => 'Peralatan Komunikasi & Navigasi', 'prefix' => 'KOM'],
            ];

            foreach ($mappings as $map) {
                $updateData = ['nama_kategori' => $map['nama']];
                if ($hasPrefix) {
                    $updateData['kode_prefix'] = $map['prefix'];
                }
                DB::table('tb_kategori')
                    ->where('nama_kategori', 'LIKE', $map['like'])
                    ->update($updateData);
            }

            // Jika ada kategori umum/pendukung lama (misal id 10), arahkan ke Peralatan Pendukung Lapangan atau Logistik Pendukung Lainnya
            DB::table('tb_kategori')
                ->where(function ($q) {
                    $q->where('nama_kategori', 'LIKE', '%Peralatan%Umum%')
                      ->orWhere('nama_kategori', 'LIKE', '%Peralatan%Pendukung%')
                      ->orWhere('nama_kategori', 'LIKE', '%Operasional%');
                })
                ->update($hasPrefix
                    ? ['nama_kategori' => 'Peralatan Pendukung Lapangan', 'kode_prefix' => 'PPL']
                    : ['nama_kategori' => 'Peralatan Pendukung Lapangan']
                );

            DB::table('tb_kategori')
                ->where(function ($q) {
                    $q->where('nama_kategori', 'LIKE', '%Logistik%Lain%')
                      ->orWhere('nama_kategori', '=', 'Umum')
                      ->orWhere('nama_kategori', '=', 'Lainnya');
                })
                ->update($hasPrefix
                    ? ['nama_kategori' => 'Logistik Pendukung Lainnya', 'kode_prefix' => 'LPL']
                    : ['nama_kategori' => 'Logistik Pendukung Lainnya']
                );

            // Pastikan ke-11 kategori standar lengkap tersedia di tb_kategori
            $standardCategories = [
                // Logistik (Habis Pakai)
                ['nama' => 'Logistik Pangan & Air Bersih',    'prefix' => 'PGN'],
                ['nama' => 'Sandang & Perlengkapan Tidur',    'prefix' => 'SDG'],
                ['nama' => 'Hunian Sementara (Shelter)',      'prefix' => 'HNI'],
                ['nama' => 'Kebersihan & Sanitasi',           'prefix' => 'KBR'],
                ['nama' => 'Kebutuhan Kelompok Rentan',       'prefix' => 'RTN'],
                ['nama' => 'Kesehatan & Medis',               'prefix' => 'MDS'],
                ['nama' => 'Logistik Pendukung Lainnya',      'prefix' => 'LPL'],
                // Peralatan (Pinjam-Kembali)
                ['nama' => 'Peralatan Evakuasi & SAR',        'prefix' => 'SAR'],
                ['nama' => 'Peralatan Dapur Umum',            'prefix' => 'DPR'],
                ['nama' => 'Peralatan Komunikasi & Navigasi', 'prefix' => 'KOM'],
                ['nama' => 'Peralatan Pendukung Lapangan',    'prefix' => 'PPL'],
            ];

            foreach ($standardCategories as $std) {
                $exists = DB::table('tb_kategori')
                    ->where('nama_kategori', $std['nama'])
                    ->exists();

                if (!$exists) {
                    $insertData = ['nama_kategori' => $std['nama']];
                    if ($hasPrefix) {
                        $insertData['kode_prefix'] = $std['prefix'];
                    }
                    DB::table('tb_kategori')->insert($insertData);
                }
            }

            // 4. Set jenis_barang = 'peralatan' pada tb_barang untuk semua kategori peralatan, dan 'logistik' untuk kategori logistik
            $peralatanNames = [
                'Peralatan Evakuasi & SAR',
                'Peralatan Dapur Umum',
                'Peralatan Komunikasi & Navigasi',
                'Peralatan Pendukung Lapangan',
            ];

            $peralatanKategoriIds = DB::table('tb_kategori')
                ->whereIn('nama_kategori', $peralatanNames)
                ->orWhere('nama_kategori', 'LIKE', 'Peralatan%')
                ->pluck('id_kategori')
                ->all();

            if (Schema::hasTable($barangTable) && Schema::hasColumn($barangTable, 'jenis_barang')) {
                DB::table($barangTable)->update(['jenis_barang' => 'logistik']);

                if (!empty($peralatanKategoriIds)) {
                    DB::table($barangTable)
                        ->whereIn('id_kategori', $peralatanKategoriIds)
                        ->update(['jenis_barang' => 'peralatan']);
                }
            }

            // 5. Sinkronkan data lama pada barang_keluar_details jika sudah ada
            if (Schema::hasTable('barang_keluar_details') && Schema::hasTable($barangTable) && !empty($peralatanKategoriIds)) {
                $peralatanBarangIds = DB::table($barangTable)
                    ->where('jenis_barang', 'peralatan')
                    ->pluck('id_barang')
                    ->all();

                if (!empty($peralatanBarangIds)) {
                    DB::table('barang_keluar_details')
                        ->whereIn('barang_id', $peralatanBarangIds)
                        ->update([
                            'tipe_item'   => 'peralatan',
                            'status_item' => 'Sedang Digunakan',
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('barang_keluar_details')) {
            Schema::table('barang_keluar_details', function (Blueprint $table) {
                if (Schema::hasColumn('barang_keluar_details', 'returned_by')) {
                    $table->dropForeign(['returned_by']);
                }
                $cols = array_filter(
                    ['tipe_item', 'status_item', 'kondisi_kembali', 'catatan_kembali', 'returned_at', 'returned_by'],
                    fn ($c) => Schema::hasColumn('barang_keluar_details', $c)
                );
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }

        $barangTable = Schema::hasTable('tb_barang') ? 'tb_barang' : 'barangs';
        if (Schema::hasTable($barangTable) && Schema::hasColumn($barangTable, 'jenis_barang')) {
            Schema::table($barangTable, function (Blueprint $table) {
                $table->dropColumn('jenis_barang');
            });
        }
    }
};
