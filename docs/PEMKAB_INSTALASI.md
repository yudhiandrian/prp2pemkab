# PRP2 Pemkab terintegrasi — pemasangan dan uji

Target PHP 7.4 x64, CodeIgniter 3, MySQL/MariaDB. Modul baru pada `/pemkab-baru` menggunakan tabel InnoDB baru. Tabel kegiatan lama tidak dihapus atau diubah. API penerima PRP2 Sumut belum menjadi bagian perubahan repo ini.

## 1. Pasang pada salinan aplikasi daerah

Backup database dan `uploads/dokumentasi`. Gunakan salinan database untuk pengujian pertama. Atur `application/config/database.php` seperti pemasangan lama. Pilih **PHP 7.4**, bukan PHP 5.6 yang mungkin juga terpasang pada XAMPP lain.

```bat
cd C:\xampp\htdocs\prp2pemkab
C:\xampp\php\php.exe -v
C:\xampp\php\php.exe index.php pemkab_cli install ID_KABUPATEN "Nama kabupaten/kota" ID_USER_MASTER
```

Ganti `ID_KABUPATEN` dengan ID 1–33 yang sudah diverifikasi di PRP2 Sumut, bukan kode wilayah Kemendagri. Ganti `ID_USER_MASTER` dengan ID akun master aktif dari tabel `users`. Identitas daerah/installation UUID tidak boleh berubah setelah instalasi. Menjalankan perintah ulang dengan identitas sama aman. Jika tabel dengan nama tujuan sudah ada tetapi skemanya berbeda, installer berhenti agar tidak mencampurkan dua struktur.

Buka login biasa, lalu menu **PRP2 Pemkab Terintegrasi**, atau langsung:

```
http://localhost/prp2pemkab/pemkab-baru
```

Untuk aplikasi pada port 8074, gunakan port yang sama pada URL. `base_url` mendukung HTTP lokal dan HTTPS langsung. Bila menggunakan reverse proxy, tetapkan URL lengkap melalui `PRP2_BASE_URL` atau konfigurasi `base_url` di server agar pembentukan tautan sesuai alamat publik.

## 2. OPD dan akun

Buat OPD pada halaman **OPD per tahun**, lalu aktifkan setiap tahun pelaporan. Pilih OPD yang sudah ada untuk menambah snapshot tahun berikutnya. Nama tahun sebelumnya tidak berubah.

Berikan akses kepada akun yang telah dibuat melalui pengelolaan pengguna lama:

```bat
C:\xampp\php\php.exe index.php pemkab_cli grant ID_USER admin
C:\xampp\php\php.exe index.php pemkab_cli grant ID_USER opd ID_OPD_PEMKAB
C:\xampp\php\php.exe index.php pemkab_cli grant ID_USER viewer
```

`admin`: semua data daerah dan persetujuan. `opd`: kegiatan, realisasi, dan foto OPD yang dipetakan. `viewer`: baca data daerah. Setiap permintaan memeriksa ulang akun aktif dan akses database, bukan hanya tombol atau session role. Pemindahan kegiatan lintas OPD hanya untuk admin. Penonaktifan akun dilakukan di pengelolaan pengguna lama.

## 3. Uji alur kegiatan

1. Tambah kegiatan, OPD/tahun, pagu, kontrak, status B/P/S, lokasi, dan klasifikasi BKP/infrastruktur/TKD.
2. Masukkan realisasi fisik kumulatif dengan tanggal aktual. Catatan terbaru dalam periode menentukan progres; nilai tertinggi tidak dipakai.
3. Masukkan pembayaran sebagai transaksi. Sistem menjumlahkan pembayaran sejak awal tahun sampai akhir bulan, dan menolak total di atas kontrak.
4. Unggah JPEG/PNG/WebP maksimal 5 MB pada catatan fisik. Tanggal foto boleh kosong. Atur PHP `upload_max_filesize=6M`, `post_max_size=8M` atau lebih.
5. Periksa rekap per OPD, filter BKP/PSD/PHTC/TKD, CSV, dan cetak.
6. Login akun OPD lain dan pastikan kegiatan/foto tidak dapat diakses lewat ID langsung.
7. Tinjau versi di Sinkronisasi dan setujui. Ubah kegiatan: versi baru harus meminta persetujuan lagi.

PSD/PHTC memilih kode dari referensi pusat, bukan memasukkan ID yang diasumsikan sama. Referensi baru dapat ditarik setelah API penerima tersedia.

Foto disimpan di luar direktori web. Default: folder `prp2pemkab-data/<hash-lokasi-aplikasi>` dua tingkat di atas direktori aplikasi. Tetapkan `PRP2_STORAGE_PATH` ke lokasi data tetap pada server produksi agar perpindahan folder kode tidak mengganti lokasi penyimpanan. Jika ada beberapa instalasi dalam direktori induk yang sama, **wajib** tetapkan `PRP2_STORAGE_PATH` yang berbeda dan berada di luar document root untuk masing-masing instalasi. Beri proses PHP izin baca/tulis. Akses foto melalui controller yang memeriksa OPD; foto tidak disajikan sebagai URL statis. Simpan database dan folder ini dalam backup yang sama. Foto/tombstone dipertahankan agar pengiriman versi lama tetap dapat diulang.

## 4. Simulasi migrasi data lama

```bat
C:\xampp\php\php.exe index.php pemkab_cli legacy 2026 dry-run > migrasi-2026.json
```

Dry-run tidak mengubah database atau menyalin foto. Laporan mencakup jumlah sumber, kelayakan, nilai, pengecualian per kegiatan, foto valid, dan relasi tanpa induk. Tidak ada tanggal fisik/status yang dikarang. Kegiatan tanpa keputusan eksplisit ditahan.

Buat berkas keputusan JSON berdasarkan pemeriksaan sumber aktual:

```json
{
  "activities": {
    "123": {"st_kontrak":"P", "status_1":"N", "status_3":"Y"}
  },
  "physical_dates": {"456":"2026-08-15"}
}
```

`123` adalah `ta_kontrak.id_kontrak`; `456` adalah `data_kegiatan_detail.id_kegiatan_detail`. `physical_dates` harus tanggal realisasi aktual yang sudah diverifikasi; jangan sekadar menyalin `jadwal_target`. `status_1` = BKP, `status_3` = infrastruktur setelah maknanya dikonfirmasi. Nilai realisasi memakai kolom `realisasi`, bukan `target`.

```bat
C:\xampp\php\php.exe index.php pemkab_cli legacy 2026 dry-run C:\data-uji\keputusan.json > simulasi-terverifikasi.json
C:\xampp\php\php.exe index.php pemkab_cli legacy 2026 apply C:\data-uji\keputusan.json > hasil-migrasi.json
```

Apply memigrasikan hanya kegiatan layak beserta seluruh anaknya dalam satu transaksi. Jika ada kegagalan database/berkas, database di-rollback. Salinan berkas yang belum direferensikan mungkin tertinggal dan tidak tampil; jangan menghapus blob secara otomatis karena snapshot lama masih memerlukannya. Peta ID/checksum mencegah duplikasi saat diulang dan mendeteksi perubahan sumber setelah migrasi. Sumber asli tetap utuh. Nama OPD tujuan yang sudah ada tanpa peta migrasi memerlukan tinjauan, tidak dipasangkan otomatis.

Migrasi diuji menggunakan dump repo dan data simulasi. Database/foto aktual salah satu daerah **belum diberikan**, sehingga validasi operasional satu daerah masih harus dilakukan dengan salinannya.

## 5. Pengirim API (setelah penerima Sumut tersedia)

Aktifkan ekstensi `curl`, `openssl`, `mysqli`, `mbstring`, `fileinfo` pada PHP CLI dan web. HTTPS wajib; verifikasi sertifikat tidak dimatikan dan redirect tidak diikuti.

Konfigurasi `application/config/pemkab_sync.php`, atau environment server:

```bat
set PRP2_SUMUT_API_URL=https://prp2sumut.sumutprov.go.id/api/v1/pemkab
set PRP2_SUMUT_API_TOKEN=TOKEN_KHUSUS_DAERAH
set PRP2_SYNC_ENABLED=1
set PRP2_STORAGE_PATH=C:\prp2-data\nama-daerah
C:\xampp\php\php.exe index.php pemkab_cli references
C:\xampp\php\php.exe index.php pemkab_cli send 20
C:\xampp\php\php.exe index.php pemkab_cli reconcile 2026
```

Environment pada jendela CMD tidak otomatis berlaku untuk service Apache atau Task Scheduler. Atur pada akun/service yang menjalankan proses atau konfigurasi server. Jangan memasukkan token ke Git, dokumentasi publik, atau URL browser.

Persetujuan manual menjadi default. Jika sudah diputuskan otomatis:

```bat
C:\xampp\php\php.exe index.php pemkab_cli policy auto
```

Kembalikan dengan `policy manual`. Perubahan kebijakan hanya memengaruhi versi baru. Versi menunggu persetujuan sebelumnya tidak otomatis disetujui. Jadwalkan `send 20` melalui Task Scheduler setiap menit dengan working directory aplikasi dan PHP/config/environment yang sama. Linux menggunakan cron dengan direktori aplikasi dan PHP 7.4 yang benar.

Antrean menyimpan versi immutable. Retry menggunakan event/body yang sama. Versi berikut menunggu versi sebelumnya diterima. Lease kedaluwarsa dipulihkan otomatis. Gangguan jaringan/5xx memakai jeda bertahap. 401/403/404/422 menjadi rejected agar tidak memenuhi log. Setelah memperbaiki kredensial/endpoint, ulangi:

```bat
C:\xampp\php\php.exe index.php pemkab_cli retry EVENT_UUID
```

Konflik 409 ditahan dan memblokir versi berikut. Tinjau rekonsiliasi bersama pengelola pusat; pengirim tidak menimpa perubahan pusat secara paksa. Penolakan payload memerlukan perbaikan/keputusan pusat, bukan melewati versi yang ditolak.

## Keuangan daerah tambahan

Halaman Keuangan Daerah menyediakan register input kategori anggaran/APBD/target/LRA/mandatory/TKDD/kawasan, revisi, tombstone, dan antrean versi. LRA dan target dicatat sebagai nilai kumulatif rupiah per kode/bulan, tidak dijumlahkan lintas bulan. Ini **bukan port penuh formulir dan laporan APBD/LRA Sumut**: rincian kategori, satuan mandatory, detail kawasan, dan adapter tabel pusat masih perlu ditetapkan bersama implementasi penerima. Modul keuangan lama tetap tersedia. Jangan menganggap register ini menggantikan seluruh pelaporan keuangan lama.

## Lokal lalu online

Tahap pertama: Pemkab lokal, penerima Sumut online HTTPS. Tahap berikut: aplikasi boleh berada di server sama, dengan document root/database/storage/kredensial per instalasi. Jangan menghubungkan Pemkab langsung ke database pusat. Backup menyertakan identitas instalasi, peta sinkronisasi, outbox, audit, dan blob foto. Jangan menjalankan salinan instalasi aktif secara bersamaan dengan UUID yang sama.
