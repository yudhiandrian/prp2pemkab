<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['login'] = 'login';
$route['logout'] = 'login/logout';
$route['blocked'] = 'login/blocked';

$route['apbd-([.0-9]+)'] = 'publik/dashboard/index/$1';
$route['apbd-opd/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'publik/skpd/detail/$1/$2';

$route['dana-desa-([.0-9]+)'] = 'publik/danadesa/index/$1';
$route['kegiatan-fisik-([.0-9]+)'] = 'publik/kegiatan_strategis/index/$1';

$route['dashboard'] = 'dashboard';

$route['pdf-preview-laporan-keuangan/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan_keuangan/preview/$1/$2';
$route['pdf-cetak-laporan-keuangan/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan_keuangan/cetak/$1/$2';
$route['pdf-preview-laporan-fisik/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan_fisik/preview/$1/$2';
$route['pdf-cetak-laporan-fisik/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan_fisik/cetak/$1/$2';

$route['realisasi-keuangan'] = 'laporan/realisasi_keuangan';
$route['realisasi-keuangan/load'] = 'laporan/realisasi_keuangan/load';

$route['laporan-realisasi-keuangan'] = 'laporan/laporan_realisasi';
$route['laporan-realisasi-keuangan/load'] = 'laporan/laporan_realisasi/load';

$route['laporan-realisasi-fisik'] = 'laporan/laporan_fisik';
$route['laporan-realisasi-fisik/detail/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'laporan/laporan_fisik/detail/$1/$2';

// ================================== ROUTING ADMIN ===================================
$route['nama-daerah'] = 'master/nama_daerah';
$route['nama-daerah/load'] = 'master/nama_daerah/load';
$route['nama-daerah/form'] = 'master/nama_daerah/form';
$route['nama-daerah/edit'] = 'master/nama_daerah/edit';

$route['data-skpd'] = 'master/skpd';
$route['data-skpd/detail/([.0-9]+)'] = 'master/skpd_detail/index/$1';
$route['data-skpd/load'] = 'master/skpd/load';
$route['data-skpd/form'] = 'master/skpd/form';
$route['data-skpd/add'] = 'master/skpd/add';
$route['data-skpd/edit'] = 'master/skpd/edit';
$route['data-skpd/delete'] = 'master/skpd/delete';

//--------------skpd_detail------------------------------
$route['data-skpd/load_detail'] = 'master/skpd_detail/load';
$route['data-skpd/form_detail'] = 'master/skpd_detail/form';
$route['data-skpd/add_detail'] = 'master/skpd_detail/add';
$route['data-skpd/edit_detail'] = 'master/skpd_detail/edit';
$route['data-skpd/delete_detail'] = 'master/skpd_detail/delete';

$route['jenis-pelaksanaan'] = 'master/jenis_pelaksanaan';
$route['jenis-pelaksanaan/load'] = 'master/jenis_pelaksanaan/load';
$route['jenis-pelaksanaan/form'] = 'master/jenis_pelaksanaan/form';
$route['jenis-pelaksanaan/add'] = 'master/jenis_pelaksanaan/add';
$route['jenis-pelaksanaan/edit'] = 'master/jenis_pelaksanaan/edit';
$route['jenis-pelaksanaan/delete'] = 'master/jenis_pelaksanaan/delete';

$route['kepala-skpd'] = 'master/kepala_skpd';
$route['kepala-skpd/load'] = 'master/kepala_skpd/load';
$route['kepala-skpd/form'] = 'master/kepala_skpd/form';
$route['kepala-skpd/add'] = 'master/kepala_skpd/add';
$route['kepala-skpd/new_ttd'] = 'master/kepala_skpd/new_ttd';
$route['kepala-skpd/edit'] = 'master/kepala_skpd/edit';
$route['kepala-skpd/delete'] = 'master/kepala_skpd/delete';

$route['mandatory'] = 'daerah/mandatory';
$route['mandatory/load'] = 'daerah/mandatory/load';
$route['mandatory/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'daerah/mandatory/detail/$1/$2';
$route['mandatory/load_anggaran'] = 'daerah/mandatory/load_anggaran';
$route['mandatory/form'] = 'daerah/mandatory/form';
$route['mandatory/add'] = 'daerah/mandatory/add';
$route['mandatory/edit'] = 'daerah/mandatory/edit';
$route['mandatory/delete'] = 'daerah/mandatory/delete';

$route['postur-anggaran'] = 'daerah/postur_anggaran';
$route['postur-anggaran/load'] = 'daerah/postur_anggaran/load';
$route['postur-anggaran/form'] = 'daerah/postur_anggaran/form';
$route['postur-anggaran/add'] = 'daerah/postur_anggaran/add';
$route['postur-anggaran/edit'] = 'daerah/postur_anggaran/edit';
$route['postur-anggaran/delete'] = 'daerah/postur_anggaran/delete';

$route['dana-transfer'] = 'daerah/dana_transfer';
$route['dana-transfer/load'] = 'daerah/dana_transfer/load';
$route['dana-transfer/form'] = 'daerah/dana_transfer/form';
$route['dana-transfer/add'] = 'daerah/dana_transfer/add';
$route['dana-transfer/edit'] = 'daerah/dana_transfer/edit';
$route['dana-transfer/delete'] = 'daerah/dana_transfer/delete';

$route['dana-dak'] = 'daerah/danadak';
$route['dana-dak/load'] = 'daerah/danadak/load';
$route['dana-dak/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'daerah/danadak/detail/$1/$2';
$route['dana-dak/load_detail'] = 'daerah/danadak/load_detail';
$route['dana-dak/form'] = 'daerah/danadak/form';
$route['dana-dak/add'] = 'daerah/danadak/add';
$route['dana-dak/edit'] = 'daerah/danadak/edit';
$route['dana-dak/delete'] = 'daerah/danadak/delete';

$route['dana-dekonsentrasi'] = 'daerah/danadekon';
$route['dana-dekonsentrasi/load'] = 'daerah/danadekon/load';
$route['dana-dekonsentrasi/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'daerah/danadekon/detail/$1/$2';
$route['dana-dekonsentrasi/load_anggaran'] = 'daerah/danadekon/load_anggaran';
$route['dana-dekonsentrasi/form'] = 'daerah/danadekon/form';
$route['dana-dekonsentrasi/add'] = 'daerah/danadekon/add';
$route['dana-dekonsentrasi/edit'] = 'daerah/danadekon/edit';
$route['dana-dekonsentrasi/delete'] = 'daerah/danadekon/delete';

$route['dana-tp'] = 'daerah/danatp';
$route['dana-tp/load'] = 'daerah/danatp/load';
$route['dana-tp/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'daerah/danatp/detail/$1/$2';
$route['dana-tp/load_anggaran'] = 'daerah/danatp/load_anggaran';
$route['dana-tp/form'] = 'daerah/danatp/form';
$route['dana-tp/add'] = 'daerah/danatp/add';
$route['dana-tp/edit'] = 'daerah/danatp/edit';
$route['dana-tp/delete'] = 'daerah/danatp/delete';

$route['data-apbd'] = 'daerah/data_apbd';
$route['data-apbd/load'] = 'daerah/data_apbd/load';
$route['data-apbd/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'daerah/data_apbd/detail/$1/$2';

$route['data-apbd/realisasi_pad_opd'] = 'widget/data_peropd/realisasi_pad_opd';
$route['data-apbd/realisasi_belanja_opd'] = 'widget/data_peropd/realisasi_belanja_opd';
$route['data-apbd/arus_kas_opd'] = 'widget/data_peropd/arus_kas_opd';

$route['kegiatan'] = 'kegiatan/kegiatan';
$route['kegiatan/skpd/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'kegiatan/kegiatan/skpd/$1/$2';
$route['kegiatan/load_kegiatan'] = 'kegiatan/kegiatan/load_kegiatan';
$route['kegiatan/form'] = 'kegiatan/kegiatan/form';
$route['kegiatan/add'] = 'kegiatan/kegiatan/add';
$route['kegiatan/edit'] = 'kegiatan/kegiatan/edit';
$route['kegiatan/delete'] = 'kegiatan/kegiatan/delete';

$route['kegiatan/detail/load'] = 'kegiatan/detail/load';
$route['kegiatan/detail/form'] = 'kegiatan/detail/form';
$route['kegiatan/detail/realisasi'] = 'kegiatan/detail/realisasi';
$route['kegiatan/detail/add'] = 'kegiatan/detail/add';
$route['kegiatan/detail/edit'] = 'kegiatan/detail/edit';
$route['kegiatan/detail/delete'] = 'kegiatan/detail/delete';
$route['kegiatan/detail/([.0-9a-zA-Z-]+)'] = 'kegiatan/detail/index/$1';
$route['kegiatan/dokumentasi/load'] = 'kegiatan/dokumentasi/load';
$route['kegiatan/dokumentasi/form'] = 'kegiatan/dokumentasi/form';
$route['kegiatan/dokumentasi/upload'] = 'kegiatan/dokumentasi/upload';
$route['kegiatan/dokumentasi/delete'] = 'kegiatan/dokumentasi/delete';
$route['kegiatan/dokumentasi/([.0-9a-zA-Z-]+)'] = 'kegiatan/dokumentasi/index/$1';
$route['kegiatan/dokumentasi/load_dk'] = 'kegiatan/dokumentasi/load_dk';

$route['upload-lra-opd'] = 'upload_lra/skpd';
$route['upload-lra-opd/load'] = 'upload_lra/skpd/load';
$route['upload-lra-opd/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_lra/skpd/detail/$1/$2';
$route['upload-lra-opd/load-apbd'] = 'upload_lra/skpd/load_apbd';
$route['upload-lra-opd/form-upload'] = 'upload_lra/skpd/form_upload';
$route['upload-lra-opd/upload'] = 'upload_lra/skpd/upload';
$route['upload-lra-opd/delete'] = 'upload_lra/skpd/delete';
$route['upload-lra-opd/detail2/([.0-9a-zA-Z-]+)/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_lra/skpd/detail2/$1/$2/$3';
$route['upload-lra-opd/load-apbd2'] = 'upload_lra/skpd/load_apbd2';
$route['upload-lra-opd/skpd/form'] = 'upload_lra/skpd/form';
$route['upload-lra-opd/skpd/add'] = 'upload_lra/skpd/add';
$route['upload-lra-opd/skpd/edit'] = 'upload_lra/skpd/edit';
$route['upload-lra-opd/skpd/delete_data'] = 'upload_lra/skpd/delete_data';

$route['data-anggaran-defenitif'] = 'upload_anggaran_defenitif/skpd';
$route['data-anggaran-defenitif/load'] = 'upload_anggaran_defenitif/skpd/load';
$route['data-anggaran-defenitif/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_anggaran_defenitif/skpd/detail/$1/$2';
$route['data-anggaran-defenitif/load-apbd'] = 'upload_anggaran_defenitif/skpd/load_apbd';
$route['data-anggaran-defenitif/form-upload'] = 'upload_anggaran_defenitif/skpd/form_upload';
$route['data-anggaran-defenitif/upload'] = 'upload_anggaran_defenitif/skpd/upload';
$route['data-anggaran-defenitif/delete'] = 'upload_anggaran_defenitif/skpd/delete';
$route['data-anggaran-defenitif/detail2/([.0-9a-zA-Z-]+)/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_anggaran_defenitif/skpd/detail2/$1/$2/$3';
$route['data-anggaran-defenitif/load-apbd2'] = 'upload_anggaran_defenitif/skpd/load_apbd2';
$route['data-anggaran-defenitif/skpd/form'] = 'upload_anggaran_defenitif/skpd/form';
$route['data-anggaran-defenitif/skpd/add'] = 'upload_anggaran_defenitif/skpd/add';
$route['data-anggaran-defenitif/skpd/edit'] = 'upload_anggaran_defenitif/skpd/edit';
$route['data-anggaran-defenitif/skpd/delete_data'] = 'upload_anggaran_defenitif/skpd/delete_data';

$route['upload-anggaran-opd'] = 'upload_anggaran/skpd';
$route['upload-anggaran-opd/load'] = 'upload_anggaran/skpd/load';
$route['upload-anggaran-opd/form'] = 'upload_anggaran/skpd/form';
$route['upload-anggaran-opd/form-upload'] = 'upload_anggaran/skpd/form_upload';
$route['upload-anggaran-opd/upload'] = 'upload_anggaran/skpd/upload';
$route['upload-anggaran-opd/add'] = 'upload_anggaran/skpd/add';
$route['upload-anggaran-opd/edit'] = 'upload_anggaran/skpd/edit';
$route['upload-anggaran-opd/delete'] = 'upload_anggaran/skpd/delete';
$route['upload-anggaran-opd/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_anggaran/skpd/detail/$1/$2';
$route['upload-anggaran-opd/load-anggaran'] = 'upload_anggaran/skpd/load_anggaran';

// ====================================== ROUTING DANA DESA ==========================================
$route['upload-dana-desa'] = 'upload/danadesa';
$route['upload-dana-desa/([.0-9]+)'] = 'upload/danadesa/index/$1';
$route['upload-dana-desa/load'] = 'upload/danadesa/load';
$route['upload-dana-desa/form_upload'] = 'upload/danadesa/form_upload';
$route['upload-dana-desa/upload'] = 'upload/danadesa/upload';
$route['upload-dana-desa/delete'] = 'upload/danadesa/delete';

// ====================================== ROUTING DANA DESA ==========================================
$route['upload-anggaran-kas'] = 'upload_anggaran_kas/anggarankas';
$route['upload-anggaran-kas/load'] = 'upload_anggaran_kas/anggarankas/load';
$route['upload-anggaran-kas/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'upload_anggaran_kas/anggarankas/detail/$1/$2';
$route['upload-anggaran-kas/load_anggaran'] = 'upload_anggaran_kas/anggarankas/load_anggaran';
$route['upload-anggaran-kas/form_upload'] = 'upload_anggaran_kas/anggarankas/form_upload';
$route['upload-anggaran-kas/upload'] = 'upload_anggaran_kas/anggarankas/upload';
$route['upload-anggaran-kas/delete'] = 'upload_anggaran_kas/anggarankas/delete';

$route['realisasi-dana-dak'] = 'realisasi/dak';
$route['realisasi-dana-dak/load'] = 'realisasi/dak/load';
$route['realisasi-dana-dak/load_apbd2'] = 'realisasi/dak/load_apbd2';
$route['realisasi-dana-dak/form'] = 'realisasi/dak/form';
$route['realisasi-dana-dak/edit'] = 'realisasi/dak/edit';
$route['realisasi-dana-dak/delete'] = 'realisasi/dak/delete';
$route['realisasi-dana-dak/load_apbd'] = 'realisasi/dak/load_apbd';
$route['realisasi-dana-dak/form_upload'] = 'realisasi/dak/form_upload';
$route['realisasi-dana-dak/upload'] = 'realisasi/dak/upload';
$route['realisasi-dana-dak/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'realisasi/dak/detail/$1/$2';
$route['realisasi-dana-dak/detail2/([.0-9a-zA-Z-]+)/([.0-9]+)/([.0-9]+)'] = 'realisasi/dak/detail2/$1/$2/$3';

$route['realisasi-dana-dekon'] = 'realisasi/dekon';
$route['realisasi-dana-dekon/load'] = 'realisasi/dekon/load';
$route['realisasi-dana-dekon/load_apbd2'] = 'realisasi/dekon/load_apbd2';
$route['realisasi-dana-dekon/form'] = 'realisasi/dekon/form';
$route['realisasi-dana-dekon/edit'] = 'realisasi/dekon/edit';
$route['realisasi-dana-dekon/delete'] = 'realisasi/dekon/delete';
$route['realisasi-dana-dekon/load_apbd'] = 'realisasi/dekon/load_apbd';
$route['realisasi-dana-dekon/form_upload'] = 'realisasi/dekon/form_upload';
$route['realisasi-dana-dekon/upload'] = 'realisasi/dekon/upload';
$route['realisasi-dana-dekon/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'realisasi/dekon/detail/$1/$2';
$route['realisasi-dana-dekon/detail2/([.0-9a-zA-Z-]+)/([.0-9]+)/([.0-9]+)'] = 'realisasi/dekon/detail2/$1/$2/$3';

$route['realisasi-dana-tp'] = 'realisasi/tugas';
$route['realisasi-dana-tp/load'] = 'realisasi/tugas/load';
$route['realisasi-dana-tp/load_apbd2'] = 'realisasi/tugas/load_apbd2';
$route['realisasi-dana-tp/form'] = 'realisasi/tugas/form';
$route['realisasi-dana-tp/edit'] = 'realisasi/tugas/edit';
$route['realisasi-dana-tp/delete'] = 'realisasi/tugas/delete';
$route['realisasi-dana-tp/load_apbd'] = 'realisasi/tugas/load_apbd';
$route['realisasi-dana-tp/form_upload'] = 'realisasi/tugas/form_upload';
$route['realisasi-dana-tp/upload'] = 'realisasi/tugas/upload';
$route['realisasi-dana-tp/detail/([.0-9]+)/([.0-9a-zA-Z-]+)'] = 'realisasi/tugas/detail/$1/$2';
$route['realisasi-dana-tp/detail2/([.0-9a-zA-Z-]+)/([.0-9]+)/([.0-9]+)'] = 'realisasi/tugas/detail2/$1/$2/$3';

$route['data-user'] = 'setting/users';
$route['data-user/load'] = 'setting/users/load';
$route['data-user/form'] = 'setting/users/form';
$route['data-user/add'] = 'setting/users/add';
$route['data-user/edit'] = 'setting/users/edit';
$route['data-user/delete'] = 'setting/users/delete';

$route['profil-user'] = 'setting/profile';
$route['profil-user/load'] = 'setting/profile/load';
$route['profil-user/form_foto'] = 'setting/profile/form_foto';
$route['profil-user/edit_foto'] = 'setting/profile/edit_foto';
$route['profil-user/form_password'] = 'setting/profile/form_password';
$route['profil-user/edit_password'] = 'setting/profile/edit_password';

// ========================= Module untuk list menu ===========================
$route['list-menu'] = 'konfigurasi/menu';
$route['list-menu/load'] = 'konfigurasi/menu/load';
$route['list-menu/form-add'] = 'konfigurasi/menu/form_add';
$route['list-menu/add'] = 'konfigurasi/menu/add';
$route['list-menu/form-edit'] = 'konfigurasi/menu/form_edit';
$route['list-menu/edit'] = 'konfigurasi/menu/edit';
$route['list-menu/delete'] = 'konfigurasi/menu/delete';
// ========================= Module untuk akses menu ===========================
$route['akses-menu'] = 'konfigurasi/akses';
$route['akses-menu/form-edit'] = 'konfigurasi/akses/form_edit';
$route['akses-menu/edit'] = 'konfigurasi/akses/edit';

$route['pdf-preview-jumlah-laporan-keuangan/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/jumlah_laporan_keuangan/$1';
$route['pdf-cetak-jumlah-laporan-keuangan/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/jumlah_laporan_keuangan/$1';
$route['pdf-preview-data-lra-opd/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/data_lra_opd/$1';
$route['pdf-cetak-data-lra-opd/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/data_lra_opd/$1';
$route['upload-lra-opd/load_rekap'] = 'upload_lra/skpd_rekap';
$route['upload-lra-opd/detail_rekap/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'upload_lra/skpd_rekap/detail_rekap/$1/$2';
$route['upload-lra-opd/load_detail_rekap'] = 'upload_lra/skpd_rekap/load_detail_rekap';
$route['upload-lra-opd/detail_rekap2/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'upload_lra/skpd_rekap/detail_rekap2/$1/$2';
$route['upload-lra-opd/load_detail_rekap2'] = 'upload_lra/skpd_rekap/load_detail_rekap2';
$route['pdf-preview-detail-rekap/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/detail_rekap/$1/$2';
$route['pdf-cetak-detail-rekap/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/detail_rekap/$1/$2';
$route['pdf-preview-detail-rekap2/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/detail_rekap2/$1/$2';
$route['pdf-cetak-detail-rekap2/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/detail_rekap2/$1/$2';
$route['pdf-preview-detail-lra/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/detail_lra/$1/$2';
$route['pdf-cetak-detail-lra/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/detail_lra/$1/$2';
$route['pdf-preview-detail2-lra/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/preview/laporan/detail2_lra/$1/$2/$3';
$route['pdf-cetak-detail2-lra/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/detail2_lra/$1/$2/$3';
$route['pdf-cetak-dana-desa/([.0-9a-zA-Z-]+)'] = 'pdf/cetak/laporan/dana_desa/$1';

$route['skpd-tahun'] = 'master/skpd_tahun';
$route['skpd-tahun/([.0-9]+)'] = 'master/skpd_tahun/index/$1';
$route['skpd-tahun/load'] = 'master/skpd_tahun/load';
$route['skpd-tahun/form'] = 'master/skpd_tahun/form';
$route['skpd-tahun/add'] = 'master/skpd_tahun/add';
$route['skpd-tahun/edit'] = 'master/skpd_tahun/edit';
$route['skpd-tahun/delete'] = 'master/skpd_tahun/delete';

$route['tampilan-aplikasi'] = 'master/tampilan_aplikasi';
$route['tampilan-aplikasi/load'] = 'master/tampilan_aplikasi/load';
$route['tampilan-aplikasi/form'] = 'master/tampilan_aplikasi/form';
$route['tampilan-aplikasi/edit'] = 'master/tampilan_aplikasi/edit';
$route['tampilan-aplikasi/edit_foto'] = 'master/tampilan_aplikasi/edit_foto';

$route['404_override'] = '';
$route['translate_uri_dashes/([.0-9a-zA-Z-]+)'] = FALSE;