# Pengujian modul Pemkab

`pemkab_integration.php` hanya dapat dijalankan dari CLI. Membutuhkan PHP 7.4 x64 dengan mysqli/mbstring/fileinfo dan server MariaDB/MySQL uji di localhost. File ini **menghapus dan membuat ulang database bernama prp2_module_test**, tidak membaca database.php aplikasi.

```bash
PRP2_TEST_ALLOW_RESET=YES PRP2_TEST_DB_PORT=33318 php tests/pemkab_integration.php
```

Gunakan server database disposable. Skema sumber diambil dari dump repo, ditambah fixture kegiatan/realisasi. Suite memeriksa akses, invalid mutation rollback, batas kontrak/pagu/tanggal, laporan berbobot, versi dan persetujuan, worker FIFO/lease, retry identik, invalid receipt/conflict, tombstone, dan migrasi dry-run/apply berulang. Penyimpanan foto sementara berada di direktori sistem sementara.

Uji browser lokal dijalankan pada salinan konfigurasi aplikasi terpisah dengan PHP built-in server dan Chromium/Playwright. Mencakup login, halaman daftar/form/OPD/keuangan/sinkronisasi, tambah kegiatan, catatan fisik, unggah gambar, akses foto, CSRF, dan lebar ponsel 390 px. Ini menguji aplikasi lokal, belum koneksi API Sumut yang sesungguhnya.


Browser suite tersedia pada `pemkab_browser.cjs`. Gunakan akun admin uji yang memiliki akses modul, OPD A aktif tahun 2026, dan instance disposable. Uji ini menambah kegiatan/realisasi/foto. Pasang Playwright atau arahkan runtime module path, lalu:

```bash
PRP2_TEST_ALLOW_WRITES=YES PRP2_TEST_URL=http://127.0.0.1:8088 PRP2_TEST_USER=admin PRP2_TEST_PASSWORD=PASSWORD_UJI node tests/pemkab_browser.cjs
```

Opsional `PRP2_CHROMIUM_PATH` untuk binary Chromium dan `PRP2_SCREENSHOT_DIR` untuk screenshot. Akun/data browser disiapkan pada instance uji, bukan instance produksi.

Hasil pada 17 November 2026: **47 pemeriksaan database/layanan lulus**; HTTP login, 5 halaman utama, tambah kegiatan, input fisik, unggah/akses foto, penolakan CSRF, dan layout 390 px lulus; sintaks file PHP baru pada 7.4.33 lulus. Receipt/foto API memakai transport simulasi, bukan endpoint Sumut online.
