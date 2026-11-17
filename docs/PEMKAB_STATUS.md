# Status implementasi 17 November 2026

## Tersedia pada repo Pemkab

- Skema baru InnoDB, identitas instalasi tetap, pemasangan CLI yang tidak menghapus tabel lama.
- Akses admin/OPD/viewer dengan pembatasan kepemilikan dan token CSRF pada mutasi modul.
- Master OPD/snapshot tahun; CRUD kegiatan, fisik, transaksi keuangan, dokumentasi berkas privat; tombstone, audit.
- Filter BKP/infrastruktur/PSD/PHTC/TKD, rekap per OPD, tanggal/periode dan pembobotan mengikuti model Sumut; CSV/cetak dan tampilan responsif.
- Outbox transaksional, snapshot immutable, persetujuan per versi, FIFO, lease worker, retry, receipt validation, staging pengiriman foto, referensi dan manifest rekonsiliasi.
- Migrasi dry-run/apply dengan keputusan status/tanggal eksplisit, pengecualian, peta ID/checksum, sumber tetap utuh, pemeriksaan foto.
- Register keuangan daerah tambahan; belum setara seluruh formulir/laporan APBD/LRA Sumut.

## Belum bisa dinyatakan selesai secara operasional

1. Penerima API, registrasi token/installation, pemetaan existing-data, dan adapter register keuangan pada PRP2 Sumut. Pekerjaan ini memang ditetapkan untuk obrolan/repo Sumut berikutnya.
2. Uji salinan database dan foto aktual satu kabupaten/kota. Repo hanya menyediakan dump lama; hasil uji simulasi bukan bukti migrasi semua daerah.
3. Uji end-to-end Pemkab lokal → Sumut online, lalu dua aplikasi online. Membutuhkan penerima yang tersedia dan kredensial yang sah.
4. Keputusan tahun mulai, kebijakan persetujuan otomatis/manual, dan penyelesaian konflik pusat. Default modul manual dan pengiriman nonaktif.
5. Port penuh formulir/laporan keuangan dan detail kawasan Sumut belum dilakukan. Register lokal memakai kontrak eksplisit dan tidak boleh dianggap sudah ekuivalen tabel pusat.

Pengujian dilakukan dengan PHP 7.4.33, MariaDB sementara, data simulasi terpisah. Rincian hasil final dicatat pada PR dan `tests/README_PEMKAB.md`.
