# Kontrak penerima PRP2 Sumut — versi 1.0

Status: **kontrak pengirim telah diimplementasikan di prp2pemkab; penerima Sumut belum dibuat**. Dokumen ini menjadi bahan obrolan/repo PRP2 Sumut berikutnya. Tidak ada endpoint produksi yang diasumsikan sudah tersedia.

Base URL dikonfigurasi, contoh `https://prp2sumut.sumutprov.go.id/api/v1/pemkab`. Setiap request memakai `Authorization: Bearer <token-daerah>`, Content-Type/Accept application/json. Identitas kabupaten ditentukan oleh kredensial server dan installation yang terdaftar, bukan dipercaya dari `id_kabupaten` body. Batasi ukuran body minimal 8 MB untuk foto 5 MB yang di-base64. Nonaktifkan redirect endpoint.

## POST /events

```json
{
  "schema_version": "1.0",
  "installation_id": "UUID-instalasi-tetap",
  "event_id": "UUID-per-versi",
  "entity_type": "kegiatan",
  "source_uuid": "UUID-kegiatan-tetap",
  "source_version": 3,
  "expected_source_version": 2,
  "expected_remote_version": 5,
  "payload_sha256": "SHA256-atas-byte-UTF8-payload_json",
  "payload_json": "{...snapshot JSON sebagai string...}"
}
```

`payload_json` sengaja string agar byte yang di-hash tetap sama antara PHP dan bahasa lain. Hitung SHA256 atas string UTF-8 hasil decoding envelope, bukan re-encode object. String berasal dari JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES. Body request disimpan tetap untuk semua retry.

Jenis entitas:

| entity_type | Isi payload_json | Penerapan pusat |
|---|---|---|
| opd | `opd` dan array `tahun` | Peta UUID ke `pemkab_opd`, snapshot `pemkab_opd_tahun` |
| kegiatan | `kegiatan`, `opd`, `opd_tahun`, array `fisik`, array `keuangan`; setiap fisik punya `dokumentasi` | `infrastruktur_wilayah`, `pemkab_kegiatan_opd`, `infrastruktur_detail`, `infrastruktur_keuangan`, `infrastruktur_dokumentasi` |
| keuangan_daerah | Baris `jenis,tahun,bulan,status_apbd,kode,nama,nilai,source_uuid,deleted_at` | Adapter register; jangan menulis langsung tanpa pemetaan kategori/satuan ke tabel APBD/LRA/mandatory/TKDD/kawasan pusat |

Kolom kegiatan mengikuti modul Pemkab Sumut: `tahun,keperluan,pagu,nilai,tgl_kontrak,waktu,st_kontrak,lokasi_pekerjaan,koordinat,status_1,status_3,tkd,psd,phtc`. Local integer IDs hanya metadata sumber. **Seluruh ID foreign key wajib dipetakan lewat UUID; jangan menyalin integer ID lokal ke pusat.** Tahun/daerah/OPD diverifikasi sebelum setiap perubahan. UUID OPD dan snapshot tahun tertanam membuat kegiatan bisa diterima tanpa bergantung urutan event OPD global. Update kegiatan tidak boleh menimpa snapshot OPD yang sudah memiliki versi event lebih baru.

Fisik: `source_uuid,tanggal,realisasi,keterangan,deleted_at`. Keuangan: `source_uuid,tahun,tgl_realisasi,nilai,keterangan,deleted_at`. Dokumentasi: `source_uuid,sha256,mime,ukuran,tanggal,keterangan,deleted_at`, tanpa path/filename server. Tombstone induk juga menonaktifkan anak untuk laporan, walaupun anak belum punya tombstone sendiri. Snapshot lengkap **tidak berarti menghapus data yang tidak dikenali tanpa pemeriksaan**; gunakan UUID dan tombstone eksplisit.

Dalam satu transaksi pusat:

1. Verifikasi token, daerah instalasi, schema, checksum dan validasi seluruh data/berkas.
2. Cari `(installation_id,event_id)`. Jika pernah diterima dengan checksum sama, kembalikan receipt asli sebagai duplicate; jangan menulis ulang. Checksum berbeda untuk event sama menghasilkan 409.
3. Periksa source_version monoton sesuai expected_source_version dan remote_version sesuai expected_remote_version. Konflik perubahan pusat menghasilkan 409; tidak ditimpa otomatis.
4. Pasangkan entitas pusat yang sudah ada **hanya melalui peta yang diverifikasi**. Nama mirip menghasilkan kandidat tinjauan, bukan auto-merge. Siapkan peta existing-data sebelum pengiriman pertama agar data ganda lama tidak bertambah.
5. Upsert seluruh snapshot dan peta dalam transaksi; hitung laporan dari rincian menggunakan model realisasi Sumut. Simpan receipt di transaksi yang sama.
6. Baru kirim 200/201 setelah commit.

Receipt wajib:

```json
{
  "status": "accepted",
  "installation_id": "UUID-instalasi-tetap",
  "event_id": "UUID-per-versi",
  "source_uuid": "UUID-kegiatan-tetap",
  "source_version": 3,
  "payload_sha256": "checksum-sama-dengan-request",
  "remote_id": "ID-pusat-yang-tetap",
  "remote_version": 6,
  "received_at": "2026-11-17T10:00:00Z"
}
```

`status` dapat `accepted`/`duplicate`. ID pusat harus stabil; remote_version wajib lebih besar dari expected_remote_version pada request. Pengirim menolak receipt yang kurang, UUID/checksum/versinya tidak cocok, ID pusat berubah, atau versi pusat mundur. Respons duplicate harus tetap mengacu pada penerimaan event yang sama, walau versi pusat sudah bertambah setelahnya.

HTTP: 409 konflik; 400/401/403/404/413/415/422 rejected; 408/429/5xx/network retry. Pesan server tidak disalin mentah ke log agar tidak membocorkan token/data sensitif. UI menunjukkan kode hasil dan status. Simpan detail validasi pada log pusat dan tinjau melalui pengelola pusat.

## POST /files

Dikirim sebelum event snapshot yang merujuk foto:

```json
{
  "installation_id":"UUID-instalasi",
  "source_uuid":"UUID-foto",
  "sha256":"SHA256-isi-berkas",
  "mime":"image/jpeg",
  "content_base64":"BASE64-BERKAS"
}
```

Penerima mengautentikasi daerah, memvalidasi ukuran <=5 MB setelah decode, MIME aktual JPEG/PNG/WebP, signature gambar, dan SHA256. Simpan di luar webroot. Idempotensi `(installation_id,source_uuid,sha256)`. UUID yang sama dengan checksum berbeda ditolak. Respons 200/201 wajib `{"source_uuid":"...","sha256":"..."}`. Foto boleh sudah ada sebelum metadata; gunakan staging agar kegagalan event tidak memunculkan foto tanpa induk. Bersihkan staging hanya dengan kebijakan retensi yang mempertimbangkan retry.

## GET /references

```json
{
  "version":"2026-11-17.1",
  "items":[
    {"jenis":"psd","kode":"KODE-RESMI","nama":"Nama master pusat","aktif":"Y"},
    {"jenis":"phtc","kode":"KODE-RESMI","nama":"Nama master pusat","aktif":"N"}
  ]
}
```

Kode wajib mengikuti master pusat aktual. Item tidak aktif dikirim eksplisit sebagai `aktif=N`. Referensi pusat tidak menyamakan angka ID lokal/daerah secara asumtif.

## POST /reconcile

Request: `installation_id,tahun,entities[]`, setiap entitas memuat `source_uuid,entity_type,ack_source_version,remote_id,remote_version,checksum`. Saat ini manifest berisi semua entitas yang pernah diakui (tahun menjadi konteks laporan), bukan hanya kegiatan tahun terpilih. Respons 200: `{"differences":[...]}`. Perbedaan memuat UUID, jenis selisih, versi/checksum pusat. Endpoint hanya membandingkan; tidak mengubah data otomatis. Tambahkan perbandingan jumlah/pagu/kontrak/realisasi per OPD/tahun di sisi pusat untuk uji operasional.

## Skenario uji bersama

Pengiriman pertama; duplicate setelah respons hilang; putus saat unggah foto; putus sesudah commit pusat; dua worker; versi terbalik; persetujuan versi lama lalu edit; konflik perubahan pusat; invalid receipt; lintas daerah/OPD; tanggal invalid; kontrak di bawah realisasi; fisik turun pada tanggal lebih baru; delete fisik beserta foto; delete kegiatan; data pusat existing tanpa peta. Uji menggunakan satu daerah/salinan data sebelum mengaktifkan otomatis di produksi.
