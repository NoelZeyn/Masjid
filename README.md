# 🕌 Sistem Informasi Manajemen Masjid (MasjidOne)
**REPOSITORY INI ADALAH COPY TEMPLATE DARI REPOSITORY ASLI**

---

**MasjidOne** adalah aplikasi berbasis web yang dirancang untuk memudahkan pengelolaan kegiatan operasional masjid seperti manajemen admin, inventaris barang, pengajuan, acara, kurban, hingga laporan keuangan. Sistem ini dibangun dengan struktur database relasional yang kompleks dan terorganisir.

---

## 🗂️ Fitur Utama

### 🔐 Manajemen Admin
- Registrasi dan login admin (email/Google ID)
- Data lengkap admin: posisi, tugas, foto KTP, foto profil
- Log aktivitas admin & sistem soft delete

### 📦 Inventaris Barang
- CRUD data barang: nama, deskripsi, harga, stok, gambar, merk, kategori, kondisi
- Supplier barang dan riwayat transaksi masuk/keluar
- Peminjaman barang & histori penggunaan

### 📝 Pengajuan & Kritik Saran
- Pengajuan oleh admin: barang, dana, acara, dll.
- Warga dapat menyampaikan kritik, saran, atau keluhan

### 🐑 Manajemen Kurban
- Data hewan kurban dan relasi dengan warga
- Status penyembelihan dan dokumentasi riwayat

### 🍽️ Acara & Kegiatan
- Manajemen acara: nama, waktu, lokasi, status
- Kehadiran peserta warga
- Dokumentasi kegiatan: foto, video, dokumen

### 💰 Laporan Keuangan
- Laporan bulanan dan mingguan keuangan
- Pemasukan dan pengeluaran masjid
- Infaq warga berdasarkan kategori (zakat, jihad, dsb)

---

## 🧱 Struktur Tabel Utama

| Tabel | Deskripsi |
|------|-----------|
| `admin` | Data utama akun pengelola |
| `data_diri` | Informasi tambahan admin |
| `barang` | Data barang/inventaris masjid |
| `supplier` | Data penyedia barang |
| `transaksi` & `detail_transaksi` | Transaksi barang masuk/keluar |
| `riwayat_penggunaan` | Peminjaman & penggunaan barang |
| `pengajuan` | Pengajuan kebutuhan oleh admin |
| `kritik_saran` | Masukan dari warga |
| `log_aktifitas` | Riwayat aksi admin |
| `softdelete` | Sistem hapus lunak (soft delete) |
| `infaq` | Catatan donasi/infaq warga |
| `kurban`, `kurban_warga`, `riwayat_penyembelihan` | Manajemen dan riwayat kurban |
| `warga` | Data pribadi warga |
| `acara`, `peserta_acara`, `dokumentasi_acara` | Kegiatan/acara masjid |
| `keuangan`, `pemasukan`, `pengeluaran` | Pengelolaan dan laporan keuangan |

---

## 🔗 Relasi Penting

- `admin.id` → `transaksi.admin_id`, `pengajuan.admin_id`, `log_aktifitas.admin_id`, dll.
- `barang.id` → `detail_transaksi.barang_id_fk`, `riwayat_penggunaan.barang_id_fk`
- `transaksi.id` → `detail_transaksi.transaksi_id_fk`
- `supplier.id` → `barang.supplier_id`
- `warga.id` → `kritik_saran.warga_id_fk`, `kurban_warga.warga_id_fk`, `peserta_acara.warga_id_fk`, `infaq.warga_id_fk`
- `kurban.id` → `kurban_warga.kurban_id_fk`, `riwayat_penyembelihan.kurban_id_fk`

---

## 💡 Catatan
- Gunakan enum pada beberapa kolom seperti `status`, `kategori`, `kondisi`, dll. untuk menjaga konsistensi data.
- Sistem soft-delete (`softdelete`) berguna untuk menyimpan jejak penghapusan data.
- Dokumentasi acara dapat dikelola dalam berbagai bentuk (foto, video, dokumen).
- Kategori barang (`A`, `B`, `C`, `D`) bisa di-CRUD secara dinamis sesuai kebutuhan.

---

## 🧑‍💻 Pengembang

Proyek ini dikembangkan untuk membantu pengelolaan kegiatan masjid secara digital, transparan, dan terintegrasi. Siap dikembangkan lebih lanjut untuk kebutuhan komunitas lainnya.

> Silakan fork dan kirimkan pull request jika ingin berkontribusi!

