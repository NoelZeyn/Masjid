<?php

namespace Database\Seeders;

use App\Models\Acara\KategoriAcara;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AllTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Warga
        for ($i = 0; $i < 10; $i++) {
            DB::table('warga')->insert([
                'nama_lengkap' => $faker->name,
                'kontak' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Admin
        $status = ['success', 'failed', 'pending'];
        for ($i = 0; $i < 10; $i++) {
            DB::table('admin')->insert([
                'nama_lengkap' => $faker->name,
                'posisi' => 'Ketua',
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'status' => 'success',
                'tugas' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('admin')->insert([
            'nama_lengkap' => 'admin2',
            'posisi' => 'Ketua',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password123'),
            'status' => 'success',
            'tugas' => $faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data Diri
        $admins = DB::table('admin')->get();
        foreach ($admins as $admin) {
            DB::table('data_diri')->insert([
                'admin_id' => $admin->id,
                'kontak' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Supplier
        for ($i = 0; $i < 10; $i++) {
            DB::table('supplier')->insert([
                'nama_supplier' => $faker->company,
                'alamat' => $faker->address,
                'kontak' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Barang
        $kategory = ['A', 'B', 'C', 'D'];
        $kondisi = ['bagus', 'rusak', 'habis', 'kotor', 'perbaikan'];
        $keterangan = ['non-aktif', 'aktif', 'rusak', 'habis'];
        $suppliers = DB::table('supplier')->get();
        foreach ($suppliers as $supplier) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('barang')->insert([
                    'supplier_id' => $supplier->id,
                    'nama_barang' => $faker->word,
                    'deskripsi' => $faker->sentence,
                    'harga' => $faker->numberBetween(50000, 500000),
                    'stock' => $faker->numberBetween(1, 10),
                    'merk' => $faker->company,
                    'kategori' => $faker->randomElement($kategory),
                    'kondisi' => $faker->randomElement($kondisi),
                    'keterangan' => $faker->randomElement($keterangan),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Riwayat Penggunaan
        $barangs = DB::table('barang')->get();
        foreach ($barangs as $barang) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('riwayat_penggunaan')->insert([
                    'barang_id_fk' => $barang->id,
                    'tanggal_pemijaman' => now(),
                    'deskripsi_penggunaan' => $faker->sentence,
                    'tanggal_pengembalian' => now()->addDays(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Transaksi
        $tipe_transaksi = ['masuk', 'keluar'];
        $admins = DB::table('admin')->get();
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('transaksi')->insert([
                    'admin_id' => $admin->id,
                    'tanggal_transaksi' => now(),
                    'tipe_transaksi' => $faker->randomElement($tipe_transaksi),
                    'total_harga' => $faker->numberBetween(100000, 1000000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Detail Transaksi
        $transaksis = DB::table('transaksi')->get();
        $barangs = DB::table('barang')->get();
        foreach ($transaksis as $transaksi) {
            foreach ($barangs as $barang) {
                DB::table('detail_transaksi')->insert([
                    'transaksi_id_fk' => $transaksi->id,
                    'barang_id_fk' => $barang->id,
                    'jumlah' => $faker->numberBetween(1, 5),
                    'harga_satuan' => $barang->harga,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Pengajuan
        $status = ['menunggu', 'disetujui', 'ditolak'];
        $tipe_pengajuan = ['barang', 'dana', 'acara', 'lainnya'];
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('pengajuan')->insert([
                    'admin_id' => $admin->id,
                    'tipe_pengajuan' => $faker->randomElement($tipe_pengajuan),
                    'deskripsi' => $faker->sentence,
                    'status' => $faker->randomElement($status),
                    'tanggal_pengajuan' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Kritik Saran
        $wargas = DB::table('warga')->get();
        foreach ($wargas as $warga) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('kritik_saran')->insert([
                    'warga_id_fk' => $warga->id,
                    'pesan' => $faker->sentence,
                    'tipe' => 'saran',
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Log Aktifitas
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('log_aktifitas')->insert([
                    'admin_id' => $admin->id,
                    'aksi' => 'Menambahkan barang baru',
                    'entitas' => 'barang',
                    'entitas_id' => $barang->id,
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Soft Delete
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('soft_delete')->insert([
                    'admin_id' => $admin->id,
                    'deskripsi' => $faker->sentence,
                    'aksi' => 'delete_barang',
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Infaq
        $kategory_infaq = ['zakat', 'kafarat', 'nazar', 'jihad', 'infaq_membantu', 'infaq_bencana', 'infaq_kemanusiaan', 'mubah', 'haram'];
        foreach ($wargas as $warga) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('infaq')->insert([
                    'nama_infaq' => 'Donasi Jumat',
                    'warga_id_fk' => $warga->id,
                    'tanggal_pemberian' => now()->toDateString(),
                    'kategori_infaq' => $faker->randomElement($kategory_infaq),
                    'jumlah' => $faker->numberBetween(10000, 100000),
                    'satuan' => 'rupiah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Kurban
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('kurban')->insert([
                    'jenis_hewan' => 'kambing',
                    'jumlah' => 2,
                    'status' => 'belum_disembelih',
                    'admin_id' => $admin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Kurban Warga
        $wargas = DB::table('warga')->get();
        foreach ($wargas as $warga) {
            foreach ($barangs as $barang) {
                DB::table('kurban_warga')->insert([
                    'kurban_id_fk' => $barang->id,
                    'warga_id_fk' => $warga->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Riwayat Penyembelihan
        $kurbans = DB::table('kurban')->get();
        foreach ($kurbans as $kurban) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('riwayat_penyembelihan')->insert([
                    'kurban_id_fk' => $kurban->id,
                    'tanggal' => now(),
                    'catatan' => $faker->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Acara
      $faker = Faker::create('id_ID');

        // 1. Seed Kategori Acara
        $kategori_infaq = [
            'zakat', 'kafarat', 'nazar', 'jihad',
            'infaq_membantu', 'infaq_bencana', 'infaq_kemanusiaan',
            'mubah', 'haram'
        ];

        foreach ($kategori_infaq as $kategori) {
            KategoriAcara::firstOrCreate(['nama' => $kategori]);
        }

        $kategori_ids = KategoriAcara::pluck('id')->toArray();

        // 2. Seed 100 Acara
        for ($i = 0; $i < 100; $i++) {
            DB::table('acara')->insert([
                'nama_acara' => $faker->randomElement([
                    'Maulid Nabi', 'Isra Mi’raj', 'Kajian Islam', 'Bakti Sosial', 'Buka Bersama'
                ]),
                'deskripsi' => $faker->sentence(6),
                'kategori_id' => $faker->randomElement($kategori_ids),
                'tanggal_mulai' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(rand(1, 3))->format('Y-m-d'),
                'lokasi' => $faker->randomElement(['Aula Masjid', 'Halaman Masjid', 'Balai Warga']),
                'waktu' => $faker->time('H:i'),
                'status' => 'direncanakan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Ambil data acara & warga
        $acaras = DB::table('acara')->get();
        $wargas = DB::table('warga')->get(); // Pastikan tabel & data warga tersedia

        // 4. Peserta Acara
        foreach ($acaras as $acara) {
            foreach ($wargas->random(min(10, $wargas->count())) as $warga) {
                DB::table('peserta_acara')->insert([
                    'acara_id_fk' => $acara->id,
                    'warga_id_fk' => $warga->id,
                    'status_kehadiran' => $faker->randomElement(['hadir', 'tidak_hadir', 'belum_konfirmasi']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. Dokumentasi Acara
        foreach ($acaras as $acara) {
            DB::table('dokumentasi_acara')->insert([
                'acara_id_fk' => $acara->id,
                'tipe' => 'foto',
                'file_path' => 'contoh/foto.jpg',
                'link' => null,
                'catatan' => 'Foto pembukaan acara',
                'uploaded_at' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        // Keuangan
        foreach ($admins as $admin) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('keuangan')->insert([
                    'tanggal_pelaporan' => now(),
                    'bulan_ke' => now()->month,
                    'minggu_ke' => 1,
                    'saldo_akhir' => 1000000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Pemasukan
        $keuangans = DB::table('keuangan')->get();
        foreach ($keuangans as $keuangan) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('pemasukan')->insert([
                    'keuangan_id_fk' => $keuangan->id,
                    'sumber' => 'donasi',
                    'kategori_pemasukan' => $faker->randomElement($kategory),
                    'jumlah' => $faker->numberBetween(100000, 1000000),
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Pengeluaran
        foreach ($keuangans as $keuangan) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('pengeluaran')->insert([
                    'keuangan_id_fk' => $keuangan->id,
                    'kategori_pengeluaran' => 'operasional',
                    'jumlah' => $faker->numberBetween(10000, 100000),
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
