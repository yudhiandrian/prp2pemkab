-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2024 pada 05.40
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prp2pemkab`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggarankas_detail`
--

CREATE TABLE `anggarankas_detail` (
  `id_skpd` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggaran_kas`
--

CREATE TABLE `anggaran_kas` (
  `id_skpd` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nilai` double NOT NULL,
  `namafile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bulan`
--

CREATE TABLE `bulan` (
  `id_bulan` int(11) NOT NULL,
  `nama_bulan` varchar(20) NOT NULL,
  `b_bawah` int(11) NOT NULL,
  `b_atas` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bulan`
--

INSERT INTO `bulan` (`id_bulan`, `nama_bulan`, `b_bawah`, `b_atas`) VALUES
(1, 'Januari', 1, 1),
(2, 'Februari', 1, 5),
(3, 'Maret', 3, 7),
(4, 'April', 5, 10),
(5, 'Mei', 7, 20),
(6, 'Juni', 10, 30),
(7, 'Juli', 20, 40),
(8, 'Agustus', 30, 50),
(9, 'September', 40, 60),
(10, 'Oktober', 50, 70),
(11, 'November', 60, 80),
(12, 'Desember', 70, 85);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kegiatan`
--

CREATE TABLE `data_kegiatan` (
  `id_kegiatan` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `id_pa` int(11) NOT NULL,
  `no_kontrak` varchar(25) NOT NULL,
  `nama_penyedia` varchar(100) NOT NULL,
  `tgl_kontrak` date NOT NULL,
  `lokasi_pekerjaan` varchar(200) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `nilai_pagu` double NOT NULL,
  `jenis_kegiatan` int(11) NOT NULL,
  `id_jenis_kegiatan` int(11) NOT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `id_jenis_pelaksanaan` int(11) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `target_angka` int(11) NOT NULL,
  `target_tipe` enum('H','M','B','T') NOT NULL COMMENT 'Hari, Minggu, Bulan',
  `user_input` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kegiatan_detail`
--

CREATE TABLE `data_kegiatan_detail` (
  `id_kegiatan_detail` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `jenis_target` enum('H','M','B','T') NOT NULL,
  `tahapan_target` int(11) NOT NULL,
  `jadwal_target` date NOT NULL,
  `target` double NOT NULL,
  `target_keuangan` double NOT NULL,
  `realisasi` double NOT NULL,
  `realisasi_keuangan` double NOT NULL,
  `keterangan_target` varchar(200) NOT NULL,
  `user_input` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kegiatan_fisik`
--

CREATE TABLE `data_kegiatan_fisik` (
  `id_kegiatan_fisik` int(11) NOT NULL,
  `kode_tender` int(11) NOT NULL,
  `kode_rup` int(11) NOT NULL,
  `nama_tender` varchar(255) NOT NULL,
  `tanggal_tayang` varchar(25) NOT NULL,
  `anggaran` varchar(12) NOT NULL,
  `nama_skpd` varchar(75) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `id_metode` int(11) NOT NULL,
  `kualifikasi` varchar(20) NOT NULL,
  `dokumen` varchar(10) NOT NULL,
  `motode_evaluasi` varchar(35) NOT NULL,
  `nilai_pagu` double NOT NULL,
  `nilai_hps` double NOT NULL,
  `nama_pemenang` varchar(50) NOT NULL,
  `harga_penawaran` double NOT NULL,
  `st_jadwal` varchar(100) NOT NULL,
  `nama_metode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kode_rekening`
--

CREATE TABLE `data_kode_rekening` (
  `id_uraian` int(11) NOT NULL,
  `kode_rekening` varchar(16) NOT NULL,
  `uraian` varchar(150) NOT NULL,
  `level` int(11) NOT NULL,
  `jenis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_kode_rekening`
--

INSERT INTO `data_kode_rekening` (`id_uraian`, `kode_rekening`, `uraian`, `level`, `jenis`) VALUES
(1, '4', 'PENDAPATAN DAERAH', 1, 1),
(2, '4.1', 'PENDAPATAN ASLI DAERAH (PAD)', 2, 1),
(3, '4.2', 'PENDAPATAN TRANSFER', 2, 1),
(4, '4.3', 'LAIN-LAIN PENDAPATAN DAERAH YANG SAH', 2, 1),
(5, '5', 'BELANJA DAERAH', 1, 2),
(6, '5.1', 'BELANJA OPERASII', 2, 2),
(7, '5.2', 'BELANJA MODAL', 2, 2),
(8, '5.3', 'BELANJA TIDAK TERDUGA', 2, 2),
(9, '5.4', 'BELANJA TRANSFER', 2, 2),
(10, '4.2.1.01.01', 'Dana Transfer Umum-Dana Bagi Hasil (DBH)', 5, 1),
(11, '4.2.1.01.02', 'Dana Transfer Umum-Dana Alokasi Umum (DAU)', 5, 1),
(12, '4.2.1.01.03', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Fisik', 5, 1),
(13, '4.2.1.01.04', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Non Fisik', 5, 1),
(14, '4.3.4.01.02', 'Dana Insentif Daerah (DID)', 5, 1),
(15, '4.3.5', 'Dana Desa', 3, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kontrak_real`
--

CREATE TABLE `data_kontrak_real` (
  `id_real` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `id_kontrak` int(11) NOT NULL,
  `no_kontrak` varchar(150) NOT NULL,
  `nilai` double NOT NULL,
  `kd_urusan` int(11) NOT NULL,
  `kd_bidang` int(11) NOT NULL,
  `kd_unit` int(11) NOT NULL,
  `kd_sub` int(11) NOT NULL,
  `kd_keg` int(11) NOT NULL,
  `keterangan` varchar(35) NOT NULL,
  `tgl_realisasi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_realisasi_detail_skpd`
--

CREATE TABLE `data_realisasi_detail_skpd` (
  `id_realisasi` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `kode_rekening` varchar(16) NOT NULL,
  `realisasi` double NOT NULL,
  `persen` double NOT NULL COMMENT 'temp: hasrus dibuang',
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_realisasi_tr_pemko`
--

CREATE TABLE `data_realisasi_tr_pemko` (
  `id_realisasi_tr_pemko` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `realisasi_dbh` double NOT NULL,
  `realisasi_dau` double NOT NULL,
  `realisasi_dak` double NOT NULL,
  `realisasi_daknon` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_serapan_skpd`
--

CREATE TABLE `data_serapan_skpd` (
  `id_serapan` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `kode_rekening` varchar(16) NOT NULL,
  `realisasi` double NOT NULL,
  `persen` double NOT NULL COMMENT 'temp: hasrus dibuang',
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_serapan_skpd`
--

INSERT INTO `data_serapan_skpd` (`id_serapan`, `id_skpd`, `kode_rekening`, `realisasi`, `persen`, `tahun`, `bulan`) VALUES
(10, 54, '4', 0, 0, 2023, 11),
(11, 54, '4.1', 0, 0, 2023, 11),
(12, 54, '4.2', 0, 0, 2023, 11),
(13, 54, '4.3', 0, 0, 2023, 11),
(14, 54, '5', 1200000, 12, 2023, 11),
(15, 54, '5.1', 1200000, 24, 2023, 11),
(16, 54, '5.2', 0, 0, 2023, 11),
(17, 54, '5.3', 0, 0, 2023, 11),
(18, 54, '5.4', 0, 0, 2023, 11),
(28, 55, '4', 0, 0, 2023, 11),
(29, 55, '4.1', 0, 0, 2023, 11),
(30, 55, '4.2', 0, 0, 2023, 11),
(31, 55, '4.3', 0, 0, 2023, 11),
(32, 55, '5', 30000000, 0, 2023, 11),
(33, 55, '5.1', 30000000, 0, 2023, 11),
(34, 55, '5.2', 0, 0, 2023, 11),
(35, 55, '5.3', 0, 0, 2023, 11),
(36, 55, '5.4', 0, 0, 2023, 11),
(46, 52, '4', 0, 0, 2023, 10),
(47, 52, '4.1', 0, 0, 2023, 10),
(48, 52, '4.2', 0, 0, 2023, 10),
(49, 52, '4.3', 0, 0, 2023, 10),
(50, 52, '5', 29862312835, 57.96, 2023, 10),
(51, 52, '5.1', 29831699035, 58.79, 2023, 10),
(52, 52, '5.2', 30613800, 3.92, 2023, 10),
(53, 52, '5.3', 0, 0, 2023, 10),
(54, 52, '5.4', 0, 0, 2023, 10),
(73, 53, '4', 0, 0, 2023, 12),
(74, 53, '4.1', 0, 0, 2023, 12),
(75, 53, '4.2', 0, 0, 2023, 12),
(76, 53, '4.3', 0, 0, 2023, 12),
(77, 53, '5', 9750000000, 91.66, 2023, 12),
(78, 53, '5.1', 9000000000, 91.33, 2023, 12),
(79, 53, '5.2', 750000000, 95.85, 2023, 12),
(80, 53, '5.3', 0, 0, 2023, 12),
(81, 53, '5.4', 0, 0, 2023, 12),
(82, 53, '4', 0, 0, 2023, 1),
(83, 53, '4.1', 0, 0, 2023, 1),
(84, 53, '4.2', 0, 0, 2023, 1),
(85, 53, '4.3', 0, 0, 2023, 1),
(86, 53, '5', 147395501, 1.39, 2023, 1),
(87, 53, '5.1', 147395501, 1.5, 2023, 1),
(88, 53, '5.2', 0, 0, 2023, 1),
(89, 53, '5.3', 0, 0, 2023, 1),
(90, 53, '5.4', 0, 0, 2023, 1),
(91, 53, '4', 0, 0, 2023, 2),
(92, 53, '4.1', 0, 0, 2023, 2),
(93, 53, '4.2', 0, 0, 2023, 2),
(94, 53, '4.3', 0, 0, 2023, 2),
(95, 53, '5', 294951041, 2.77, 2023, 2),
(96, 53, '5.1', 294951041, 2.99, 2023, 2),
(97, 53, '5.2', 0, 0, 2023, 2),
(98, 53, '5.3', 0, 0, 2023, 2),
(99, 53, '5.4', 0, 0, 2023, 2),
(100, 53, '4', 0, 0, 2023, 3),
(101, 53, '4.1', 0, 0, 2023, 3),
(102, 53, '4.2', 0, 0, 2023, 3),
(103, 53, '4.3', 0, 0, 2023, 3),
(104, 53, '5', 466698690, 4.39, 2023, 3),
(105, 53, '5.1', 466698690, 4.74, 2023, 3),
(106, 53, '5.2', 0, 0, 2023, 3),
(107, 53, '5.3', 0, 0, 2023, 3),
(108, 53, '5.4', 0, 0, 2023, 3),
(109, 59, '4', 0, 0, 2023, 1),
(110, 59, '4.1', 0, 0, 2023, 1),
(111, 59, '4.2', 0, 0, 2023, 1),
(112, 59, '4.3', 0, 0, 2023, 1),
(113, 59, '5', 167918014, 1.76, 2023, 1),
(114, 59, '5.1', 167918014, 1.78, 2023, 1),
(115, 59, '5.2', 0, 0, 2023, 1),
(116, 59, '5.3', 0, 0, 2023, 1),
(117, 59, '5.4', 0, 0, 2023, 1),
(118, 59, '4', 0, 0, 2023, 2),
(119, 59, '4.1', 0, 0, 2023, 2),
(120, 59, '4.2', 0, 0, 2023, 2),
(121, 59, '4.3', 0, 0, 2023, 2),
(122, 59, '5', 335960456, 3.52, 2023, 2),
(123, 59, '5.1', 335960456, 3.56, 2023, 2),
(124, 59, '5.2', 0, 0, 2023, 2),
(125, 59, '5.3', 0, 0, 2023, 2),
(126, 59, '5.4', 0, 0, 2023, 2),
(127, 59, '4', 0, 0, 2023, 3),
(128, 59, '4.1', 0, 0, 2023, 3),
(129, 59, '4.2', 0, 0, 2023, 3),
(130, 59, '4.3', 0, 0, 2023, 3),
(131, 59, '5', 1174507982, 12.3, 2023, 3),
(132, 59, '5.1', 1174507982, 12.45, 2023, 3),
(133, 59, '5.2', 0, 0, 2023, 3),
(134, 59, '5.3', 0, 0, 2023, 3),
(135, 59, '5.4', 0, 0, 2023, 3),
(136, 58, '4', 0, 0, 2023, 1),
(137, 58, '4.1', 0, 0, 2023, 1),
(138, 58, '4.2', 0, 0, 2023, 1),
(139, 58, '4.3', 0, 0, 2023, 1),
(140, 58, '5', 1080980456, 5.22, 2023, 1),
(141, 58, '5.1', 1080980456, 5.3, 2023, 1),
(142, 58, '5.2', 0, 0, 2023, 1),
(143, 58, '5.3', 0, 0, 2023, 1),
(144, 58, '5.4', 0, 0, 2023, 1),
(145, 58, '4', 0, 0, 2023, 2),
(146, 58, '4.1', 0, 0, 2023, 2),
(147, 58, '4.2', 0, 0, 2023, 2),
(148, 58, '4.3', 0, 0, 2023, 2),
(149, 58, '5', 2153165354, 10.4, 2023, 2),
(150, 58, '5.1', 2153165354, 10.56, 2023, 2),
(151, 58, '5.2', 0, 0, 2023, 2),
(152, 58, '5.3', 0, 0, 2023, 2),
(153, 58, '5.4', 0, 0, 2023, 2),
(154, 58, '4', 0, 0, 2023, 3),
(155, 58, '4.1', 0, 0, 2023, 3),
(156, 58, '4.2', 0, 0, 2023, 3),
(157, 58, '4.3', 0, 0, 2023, 3),
(158, 58, '5', 3252956225, 15.71, 2023, 3),
(159, 58, '5.1', 3252956225, 15.95, 2023, 3),
(160, 58, '5.2', 0, 0, 2023, 3),
(161, 58, '5.3', 0, 0, 2023, 3),
(162, 58, '5.4', 0, 0, 2023, 3),
(163, 57, '4', 5700000, 1.17, 2023, 1),
(164, 57, '4.1', 5700000, 1.17, 2023, 1),
(165, 57, '4.2', 0, 0, 2023, 1),
(166, 57, '4.3', 0, 0, 2023, 1),
(167, 57, '5', 305739394, 0.11, 2023, 1),
(168, 57, '5.1', 305739394, 0.74, 2023, 1),
(169, 57, '5.2', 0, 0, 2023, 1),
(170, 57, '5.3', 0, 0, 2023, 1),
(171, 57, '5.4', 0, 0, 2023, 1),
(172, 57, '4', 7725000, 1.59, 2023, 2),
(173, 57, '4.1', 7725000, 1.59, 2023, 2),
(174, 57, '4.2', 0, 0, 2023, 2),
(175, 57, '4.3', 0, 0, 2023, 2),
(176, 57, '5', 612509288, 0.23, 2023, 2),
(177, 57, '5.1', 612509288, 1.48, 2023, 2),
(178, 57, '5.2', 0, 0, 2023, 2),
(179, 57, '5.3', 0, 0, 2023, 2),
(180, 57, '5.4', 0, 0, 2023, 2),
(181, 57, '4', 7725000, 1.59, 2023, 3),
(182, 57, '4.1', 7725000, 1.59, 2023, 3),
(183, 57, '4.2', 0, 0, 2023, 3),
(184, 57, '4.3', 0, 0, 2023, 3),
(185, 57, '5', 1100175388, 0.41, 2023, 3),
(186, 57, '5.1', 1100175388, 2.65, 2023, 3),
(187, 57, '5.2', 0, 0, 2023, 3),
(188, 57, '5.3', 0, 0, 2023, 3),
(189, 57, '5.4', 0, 0, 2023, 3),
(190, 95, '4', 0, 0, 2023, 1),
(191, 95, '4.1', 0, 0, 2023, 1),
(192, 95, '4.2', 0, 0, 2023, 1),
(193, 95, '4.3', 0, 0, 2023, 1),
(194, 95, '5', 385512816, 1.01, 2023, 1),
(195, 95, '5.1', 385512816, 1.05, 2023, 1),
(196, 95, '5.2', 0, 0, 2023, 1),
(197, 95, '5.3', 0, 0, 2023, 1),
(198, 95, '5.4', 0, 0, 2023, 1),
(199, 95, '4', 0, 0, 2023, 2),
(200, 95, '4.1', 0, 0, 2023, 2),
(201, 95, '4.2', 0, 0, 2023, 2),
(202, 95, '4.3', 0, 0, 2023, 2),
(203, 95, '5', 772095767, 2.02, 2023, 2),
(204, 95, '5.1', 772095767, 2.1, 2023, 2),
(205, 95, '5.2', 0, 0, 2023, 2),
(206, 95, '5.3', 0, 0, 2023, 2),
(207, 95, '5.4', 0, 0, 2023, 2),
(208, 95, '4', 0, 0, 2023, 3),
(209, 95, '4.1', 0, 0, 2023, 3),
(210, 95, '4.2', 0, 0, 2023, 3),
(211, 95, '4.3', 0, 0, 2023, 3),
(212, 95, '5', 2002399026, 5.23, 2023, 3),
(213, 95, '5.1', 2002399026, 5.45, 2023, 3),
(214, 95, '5.2', 0, 0, 2023, 3),
(215, 95, '5.3', 0, 0, 2023, 3),
(216, 95, '5.4', 0, 0, 2023, 3),
(217, 54, '4', 0, 0, 2023, 1),
(218, 54, '4.1', 0, 0, 2023, 1),
(219, 54, '4.2', 0, 0, 2023, 1),
(220, 54, '4.3', 0, 0, 2023, 1),
(221, 54, '5', 25140092741, 3.34, 2023, 1),
(222, 54, '5.1', 25140092741, 3.58, 2023, 1),
(223, 54, '5.2', 0, 0, 2023, 1),
(224, 54, '5.3', 0, 0, 2023, 1),
(225, 54, '5.4', 0, 0, 2023, 1),
(226, 54, '4', 0, 0, 2023, 2),
(227, 54, '4.1', 0, 0, 2023, 2),
(228, 54, '4.2', 0, 0, 2023, 2),
(229, 54, '4.3', 0, 0, 2023, 2),
(230, 54, '5', 50105290120, 6.66, 2023, 2),
(231, 54, '5.1', 50105290120, 7.13, 2023, 2),
(232, 54, '5.2', 0, 0, 2023, 2),
(233, 54, '5.3', 0, 0, 2023, 2),
(234, 54, '5.4', 0, 0, 2023, 2),
(235, 54, '4', 0, 0, 2023, 3),
(236, 54, '4.1', 0, 0, 2023, 3),
(237, 54, '4.2', 0, 0, 2023, 3),
(238, 54, '4.3', 0, 0, 2023, 3),
(239, 54, '5', 76956570045, 10.23, 2023, 3),
(240, 54, '5.1', 76956570045, 10.95, 2023, 3),
(241, 54, '5.2', 0, 0, 2023, 3),
(242, 54, '5.3', 0, 0, 2023, 3),
(243, 54, '5.4', 0, 0, 2023, 3),
(244, 54, '4', 0, 0, 2023, 4),
(245, 54, '4.1', 0, 0, 2023, 4),
(246, 54, '4.2', 0, 0, 2023, 4),
(247, 54, '4.3', 0, 0, 2023, 4),
(248, 54, '5', 126916146663, 16.87, 2023, 4),
(249, 54, '5.1', 126808088163, 18.04, 2023, 4),
(250, 54, '5.2', 108058500, 0.22, 2023, 4),
(251, 54, '5.3', 0, 0, 2023, 4),
(252, 54, '5.4', 0, 0, 2023, 4),
(253, 54, '4', 0, 0, 2023, 5),
(254, 54, '4.1', 0, 0, 2023, 5),
(255, 54, '4.2', 0, 0, 2023, 5),
(256, 54, '4.3', 0, 0, 2023, 5),
(257, 54, '5', 180729759641, 24.02, 2023, 5),
(258, 54, '5.1', 180621701141, 25.7, 2023, 5),
(259, 54, '5.2', 108058500, 0.22, 2023, 5),
(260, 54, '5.3', 0, 0, 2023, 5),
(261, 54, '5.4', 0, 0, 2023, 5),
(262, 54, '4', 0, 0, 2023, 6),
(263, 54, '4.1', 0, 0, 2023, 6),
(264, 54, '4.2', 0, 0, 2023, 6),
(265, 54, '4.3', 0, 0, 2023, 6),
(266, 54, '5', 236904098323, 31.49, 2023, 6),
(267, 54, '5.1', 236428689823, 33.64, 2023, 6),
(268, 54, '5.2', 475408500, 0.96, 2023, 6),
(269, 54, '5.3', 0, 0, 2023, 6),
(270, 54, '5.4', 0, 0, 2023, 6),
(271, 66, '4', 0, 0, 2023, 1),
(272, 66, '4.1', 0, 0, 2023, 1),
(273, 66, '4.2', 0, 0, 2023, 1),
(274, 66, '4.3', 0, 0, 2023, 1),
(275, 66, '5', 136362306, 0.46, 2023, 1),
(276, 66, '5.1', 136362306, 0.46, 2023, 1),
(277, 66, '5.2', 0, 0, 2023, 1),
(278, 66, '5.3', 0, 0, 2023, 1),
(279, 66, '5.4', 0, 0, 2023, 1),
(280, 66, '4', 0, 0, 2023, 2),
(281, 66, '4.1', 0, 0, 2023, 2),
(282, 66, '4.2', 0, 0, 2023, 2),
(283, 66, '4.3', 0, 0, 2023, 2),
(284, 66, '5', 278385863, 0.94, 2023, 2),
(285, 66, '5.1', 278385863, 0.95, 2023, 2),
(286, 66, '5.2', 0, 0, 2023, 2),
(287, 66, '5.3', 0, 0, 2023, 2),
(288, 66, '5.4', 0, 0, 2023, 2),
(289, 66, '4', 0, 0, 2023, 3),
(290, 66, '4.1', 0, 0, 2023, 3),
(291, 66, '4.2', 0, 0, 2023, 3),
(292, 66, '4.3', 0, 0, 2023, 3),
(293, 66, '5', 2679650557, 9.04, 2023, 3),
(294, 66, '5.1', 2679650557, 9.11, 2023, 3),
(295, 66, '5.2', 0, 0, 2023, 3),
(296, 66, '5.3', 0, 0, 2023, 3),
(297, 66, '5.4', 0, 0, 2023, 3),
(298, 66, '4', 0, 0, 2023, 4),
(299, 66, '4.1', 0, 0, 2023, 4),
(300, 66, '4.2', 0, 0, 2023, 4),
(301, 66, '4.3', 0, 0, 2023, 4),
(302, 66, '5', 3214923994, 10.85, 2023, 4),
(303, 66, '5.1', 3214923994, 10.93, 2023, 4),
(304, 66, '5.2', 0, 0, 2023, 4),
(305, 66, '5.3', 0, 0, 2023, 4),
(306, 66, '5.4', 0, 0, 2023, 4),
(307, 66, '4', 0, 0, 2023, 5),
(308, 66, '4.1', 0, 0, 2023, 5),
(309, 66, '4.2', 0, 0, 2023, 5),
(310, 66, '4.3', 0, 0, 2023, 5),
(311, 66, '5', 13259658217, 44.76, 2023, 5),
(312, 66, '5.1', 13259658217, 45.07, 2023, 5),
(313, 66, '5.2', 0, 0, 2023, 5),
(314, 66, '5.3', 0, 0, 2023, 5),
(315, 66, '5.4', 0, 0, 2023, 5),
(316, 66, '4', 0, 0, 2023, 6),
(317, 66, '4.1', 0, 0, 2023, 6),
(318, 66, '4.2', 0, 0, 2023, 6),
(319, 66, '4.3', 0, 0, 2023, 6),
(320, 66, '5', 14019769298, 47.32, 2023, 6),
(321, 66, '5.1', 14019769298, 47.65, 2023, 6),
(322, 66, '5.2', 0, 0, 2023, 6),
(323, 66, '5.3', 0, 0, 2023, 6),
(324, 66, '5.4', 0, 0, 2023, 6),
(325, 57, '4', 43047195, 8.85, 2023, 4),
(326, 57, '4.1', 43047195, 8.85, 2023, 4),
(327, 57, '4.2', 0, 0, 2023, 4),
(328, 57, '4.3', 0, 0, 2023, 4),
(329, 57, '5', 3670395908, 1.37, 2023, 4),
(330, 57, '5.1', 3176095908, 7.66, 2023, 4),
(331, 57, '5.2', 494300000, 0.22, 2023, 4),
(332, 57, '5.3', 0, 0, 2023, 4),
(333, 57, '5.4', 0, 0, 2023, 4),
(334, 57, '4', 72039036, 14.82, 2023, 5),
(335, 57, '4.1', 72039036, 14.82, 2023, 5),
(336, 57, '4.2', 0, 0, 2023, 5),
(337, 57, '4.3', 0, 0, 2023, 5),
(338, 57, '5', 11356034449, 4.23, 2023, 5),
(339, 57, '5.1', 3689186060, 8.9, 2023, 5),
(340, 57, '5.2', 7666848389, 3.37, 2023, 5),
(341, 57, '5.3', 0, 0, 2023, 5),
(342, 57, '5.4', 0, 0, 2023, 5),
(343, 57, '4', 72039036, 14.82, 2023, 6),
(344, 57, '4.1', 72039036, 14.82, 2023, 6),
(345, 57, '4.2', 0, 0, 2023, 6),
(346, 57, '4.3', 0, 0, 2023, 6),
(347, 57, '5', 21075003412, 7.84, 2023, 6),
(348, 57, '5.1', 7962411781, 19.2, 2023, 6),
(349, 57, '5.2', 13112591631, 5.77, 2023, 6),
(350, 57, '5.3', 0, 0, 2023, 6),
(351, 57, '5.4', 0, 0, 2023, 6),
(352, 57, '4', 174178108, 35.82, 2023, 7),
(353, 57, '4.1', 174178108, 35.82, 2023, 7),
(354, 57, '4.2', 0, 0, 2023, 7),
(355, 57, '4.3', 0, 0, 2023, 7),
(356, 57, '5', 53215886939, 19.8, 2023, 7),
(357, 57, '5.1', 10811198703, 26.07, 2023, 7),
(358, 57, '5.2', 42404688236, 18.66, 2023, 7),
(359, 57, '5.3', 0, 0, 2023, 7),
(360, 57, '5.4', 0, 0, 2023, 7),
(361, 57, '4', 174178108, 35.82, 2023, 8),
(362, 57, '4.1', 174178108, 35.82, 2023, 8),
(363, 57, '4.2', 0, 0, 2023, 8),
(364, 57, '4.3', 0, 0, 2023, 8),
(365, 57, '5', 62072635811, 23.1, 2023, 8),
(366, 57, '5.1', 13296222076, 32.06, 2023, 8),
(367, 57, '5.2', 48776413735, 21.46, 2023, 8),
(368, 57, '5.3', 0, 0, 2023, 8),
(369, 57, '5.4', 0, 0, 2023, 8),
(370, 57, '4', 174178108, 35.82, 2023, 9),
(371, 57, '4.1', 174178108, 35.82, 2023, 9),
(372, 57, '4.2', 0, 0, 2023, 9),
(373, 57, '4.3', 0, 0, 2023, 9),
(374, 57, '5', 97427618297, 36.26, 2023, 9),
(375, 57, '5.1', 16624826714, 40.09, 2023, 9),
(376, 57, '5.2', 80802791583, 35.56, 2023, 9),
(377, 57, '5.3', 0, 0, 2023, 9),
(378, 57, '5.4', 0, 0, 2023, 9),
(406, 52, '4', 0, 0, 2024, 1),
(407, 52, '4.1', 0, 0, 2024, 1),
(408, 52, '4.2', 0, 0, 2024, 1),
(409, 52, '4.3', 0, 0, 2024, 1),
(410, 52, '5', 0, 0, 2024, 1),
(411, 52, '5.1', 0, 0, 2024, 1),
(412, 52, '5.2', 0, 0, 2024, 1),
(413, 52, '5.3', 0, 0, 2024, 1),
(414, 52, '5.4', 0, 0, 2024, 1),
(415, 53, '4', 0, 0, 2024, 1),
(416, 53, '4.1', 0, 0, 2024, 1),
(417, 53, '4.2', 0, 0, 2024, 1),
(418, 53, '4.3', 0, 0, 2024, 1),
(419, 53, '5', 0, 0, 2024, 1),
(420, 53, '5.1', 0, 0, 2024, 1),
(421, 53, '5.2', 0, 0, 2024, 1),
(422, 53, '5.3', 0, 0, 2024, 1),
(423, 53, '5.4', 0, 0, 2024, 1),
(442, 38, '4', 0, 0, 2024, 3),
(443, 38, '4.1', 0, 0, 2024, 3),
(444, 38, '4.2', 0, 0, 2024, 3),
(445, 38, '4.3', 0, 0, 2024, 3),
(446, 38, '5', 0, 0, 2024, 3),
(447, 38, '5.1', 0, 0, 2024, 3),
(448, 38, '5.2', 0, 0, 2024, 3),
(449, 38, '5.3', 0, 0, 2024, 3),
(450, 38, '5.4', 0, 0, 2024, 3),
(460, 38, '4', 0, 0, 2024, 2),
(461, 38, '4.1', 0, 0, 2024, 2),
(462, 38, '4.2', 0, 0, 2024, 2),
(463, 38, '4.3', 0, 0, 2024, 2),
(464, 38, '5', 0, 0, 2024, 2),
(465, 38, '5.1', 0, 0, 2024, 2),
(466, 38, '5.2', 0, 0, 2024, 2),
(467, 38, '5.3', 0, 0, 2024, 2),
(468, 38, '5.4', 0, 0, 2024, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_skpd`
--

CREATE TABLE `data_skpd` (
  `id_skpd` int(11) NOT NULL,
  `nama_skpd` varchar(150) NOT NULL,
  `pendapatan` double NOT NULL,
  `belanja` double NOT NULL,
  `st_pendapatan` int(11) NOT NULL,
  `acuan_pendapatan` int(11) NOT NULL COMMENT '0: simda, 1:tabel',
  `acuan_belanja` int(11) NOT NULL COMMENT '0: simda, 1:tabel',
  `kd_urusan` int(11) NOT NULL,
  `kd_bidang` int(11) NOT NULL,
  `kd_unit` int(11) NOT NULL,
  `kd_sub` int(11) NOT NULL,
  `realisasi` double NOT NULL,
  `persen_realisasi` double NOT NULL,
  `persen_fisik` double NOT NULL,
  `jml_input` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_skpd`
--

INSERT INTO `data_skpd` (`id_skpd`, `nama_skpd`, `pendapatan`, `belanja`, `st_pendapatan`, `acuan_pendapatan`, `acuan_belanja`, `kd_urusan`, `kd_bidang`, `kd_unit`, `kd_sub`, `realisasi`, `persen_realisasi`, `persen_fisik`, `jml_input`) VALUES
(38, 'SEKRETARIAT DAERAH', 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0),
(52, 'SEKRETARIAT DPRD', 0, 0, 0, 0, 0, 2, 1, 1, 1, 0, 0, 0, 0),
(53, 'INSPEKTORAT DAERAH', 0, 0, 0, 0, 0, 2, 1, 1, 2, 0, 0, 0, 0),
(54, 'DINAS PENDIDIKAN', 0, 0, 0, 0, 0, 3, 1, 1, 1, 0, 0, 0, 0),
(55, 'DINAS KESEHATAN', 0, 0, 0, 0, 0, 4, 1, 1, 1, 0, 0, 0, 0),
(57, 'DINAS PEKERJAAN UMUM DAN TATA RUANG', 0, 0, 0, 0, 0, 5, 1, 1, 1, 0, 0, 0, 0),
(58, 'DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN SERTA PERTANAHAN', 0, 0, 0, 0, 0, 5, 1, 1, 2, 0, 0, 0, 0),
(59, 'SATUAN POLISI PAMONG PRAJA', 0, 0, 0, 0, 0, 6, 1, 1, 1, 0, 0, 0, 0),
(60, 'DINAS SOSIAL', 0, 0, 0, 0, 0, 7, 1, 1, 1, 0, 0, 0, 0),
(61, 'DINAS KETENAGAKERJAAN', 0, 0, 0, 0, 0, 8, 1, 1, 1, 0, 0, 0, 0),
(62, 'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK', 0, 0, 0, 0, 0, 9, 1, 1, 1, 0, 0, 0, 0),
(63, 'DINAS KETAHANAN PANGAN DAN PERIKANAN', 0, 0, 0, 0, 0, 10, 1, 1, 1, 0, 0, 0, 0),
(64, 'DINAS LINGKUNGAN HIDUP', 0, 0, 0, 0, 0, 11, 1, 1, 1, 0, 0, 0, 0),
(65, 'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL', 0, 0, 0, 0, 0, 12, 1, 1, 1, 0, 0, 0, 0),
(66, 'DINAS PEMBERDAYAAN MASYARAKAT DAN NAGORI', 0, 0, 0, 0, 0, 13, 1, 1, 1, 0, 0, 0, 0),
(67, 'DINAS PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA', 0, 0, 0, 0, 0, 14, 1, 1, 1, 0, 0, 0, 0),
(68, 'DINAS PERHUBUNGAN', 0, 0, 0, 0, 0, 15, 1, 1, 1, 0, 0, 0, 0),
(69, 'DINAS KOMUNIKASI DAN INFORMATIKA', 0, 0, 0, 0, 0, 16, 1, 1, 1, 0, 0, 0, 0),
(70, 'DINAS KOPERASI, USAHA KECIL DAN MENENGAH', 0, 0, 0, 0, 0, 17, 1, 1, 1, 0, 0, 0, 0),
(71, 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU', 0, 0, 0, 0, 0, 18, 1, 1, 1, 0, 0, 0, 0),
(72, 'DINAS PEMUDA DAN OLAHRAGA', 0, 0, 0, 0, 0, 19, 1, 1, 1, 0, 0, 0, 0),
(73, 'DINAS KEBUDAYAAN, PARIWISATA DAN EKONOMI KREATIF', 0, 0, 0, 0, 0, 20, 1, 1, 1, 0, 0, 0, 0),
(74, 'DINAS PERPUSTAKAAN DAN ARSIP', 0, 0, 0, 0, 0, 21, 1, 1, 1, 0, 0, 0, 0),
(75, 'DINAS PERTANIAN', 0, 0, 0, 0, 0, 22, 1, 1, 1, 0, 0, 0, 0),
(76, 'DINAS PERINDUSTRIAN DAN PERDAGANGAN', 0, 0, 0, 0, 0, 23, 1, 1, 1, 0, 0, 0, 0),
(88, 'BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(89, 'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(90, 'BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(91, 'BADAN PENANGGULANGAN BENCANA DAERAH', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(93, 'BADAN KESATUAN BANGSA DAN POLITIK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(94, 'RUMAH SAKIT UMUM DAERAH RONDAHAIM', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(95, 'RUMAH SAKIT UMUM DAERAH PERDAGANGAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(96, 'RUMAH SAKIT UMUM DAERAH PARAPAT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(97, 'KECAMATAN SIANTAR', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(98, 'KECAMATAN GUNUNG MALELA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(99, 'KECAMATAN GUNUNG MALIGAS', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(100, 'KECAMATAN PEMATANG BANDAR', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(101, 'KECAMATAN UJUNG PADANG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(102, 'KECAMATAN BANDAR HULUAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(103, 'KECAMATAN TAPIAN DOLOK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(104, 'KECAMATAN HATONDUHAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(105, 'KECAMATAN HUTA BAYU RAJA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(106, 'KECAMATAN JAWA MARAJA BAH JAMBI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(107, 'KECAMATAN JORLANG HATARAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(108, 'KECAMATAN DOLOK PANRIBUAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(109, 'KECAMATAN GIRSANG SIPANGAN BOLON', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(110, 'KECAMATAN RAYA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(111, 'KECAMATAN PURBA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(112, 'KECAMATAN HARANGGAOL HORISON', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(113, 'KECAMATAN DOLOK SILAU', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(114, 'KECAMATAN PANOMBEIAN PANEI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(115, 'KECAMATAN BANDAR', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(116, 'KECAMATAN BANDAR MASILAM', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(117, 'KECAMATAN DOLOK BATU NANGGAR', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(118, 'KECAMATAN RAYA KAHEAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(119, 'KECAMATAN PAMATANG SILIMAHUTA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(120, 'KECAMATAN SILIMAKUTA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(121, 'KECAMATAN SIDAMANIK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(122, 'KECAMATAN DOLOK PARDAMEAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(123, 'KECAMATAN PANEI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(124, 'KECAMATAN BOSAR MALIGAS', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(125, 'KECAMATAN TANAH JAWA', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(126, 'KECAMATAN PAMATANG SIDAMANIK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(127, 'KECAMATAN DOLOK MASAGAL', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(128, 'KECAMATAN SILOU KAHEAN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_skpd_rekap`
--

CREATE TABLE `data_skpd_rekap` (
  `id_skpd` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nilai` double NOT NULL,
  `realisasi` double NOT NULL,
  `persen_realisasi` double NOT NULL,
  `persen_fisik` double NOT NULL,
  `jml_input` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_skpd_rekap`
--

INSERT INTO `data_skpd_rekap` (`id_skpd`, `tahun`, `nilai`, `realisasi`, `persen_realisasi`, `persen_fisik`, `jml_input`) VALUES
(38, 2023, 0, 0, 0, 0, 0),
(54, 2023, 0, 0, 0, 0, 0),
(52, 2023, 0, 0, 0, 0, 0),
(57, 2023, 0, 0, 0, 0, 0),
(58, 2023, 0, 0, 0, 0, 0),
(64, 2023, 0, 0, 0, 0, 0),
(66, 2023, 0, 0, 0, 0, 0),
(71, 2023, 0, 0, 0, 0, 0),
(73, 2023, 0, 0, 0, 0, 0),
(75, 2023, 0, 0, 0, 0, 0),
(53, 2023, 0, 0, 0, 0, 0),
(54, 2024, 38149196000, 0, 0, 0, 0),
(38, 2024, 0, 0, 0, 0, 0),
(52, 2024, 0, 0, 0, 0, 0),
(53, 2024, 100000000, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_skpd_tahun`
--

CREATE TABLE `data_skpd_tahun` (
  `id_data` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `nama_skpd` varchar(150) NOT NULL,
  `kd_urusan` int(11) NOT NULL,
  `kd_bidang` int(11) NOT NULL,
  `kd_unit` int(11) NOT NULL,
  `kd_sub` int(11) NOT NULL,
  `realisasi` double NOT NULL,
  `persen_realisasi` double NOT NULL,
  `persen_fisik` double NOT NULL,
  `jml_input` int(11) NOT NULL,
  `tahun` int(11) NOT NULL DEFAULT '2023'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_uraian_kegiatan_pemko`
--

CREATE TABLE `data_uraian_kegiatan_pemko` (
  `kode_rekening` varchar(16) NOT NULL,
  `uraian` varchar(150) NOT NULL,
  `level` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `anggaran` double NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1:Pendapatan, 2:Belanja, 3:Penerimaan, 4:Pengeluaran',
  `st_anggaran` int(11) NOT NULL COMMENT '1:APBD, 2:PAPBD'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_uraian_kegiatan_pemko`
--

INSERT INTO `data_uraian_kegiatan_pemko` (`kode_rekening`, `uraian`, `level`, `tahun`, `anggaran`, `jenis`, `st_anggaran`) VALUES
('4', 'PENDAPATAN DAERAH', 1, 2021, 2200000000, 1, 1),
('4.1', 'PENDAPATAN ASLI DAERAH (PAD)', 2, 2021, 2200000000, 1, 1),
('4.1.2', 'Retribusi Daerah', 3, 2021, 2200000000, 1, 1),
('4.1.2.02', 'Retribusi Jasa Usaha', 4, 2021, 2200000000, 1, 1),
('4.1.2.02.01', 'Retribusi Pemakaian Kekayaan Daerah', 5, 2021, 173880000, 1, 1),
('4.1.2.02.01.0003', 'Retribusi Penyewaan Bangunan', 6, 2021, 246498708, 1, 1),
('5', 'BELANJA DAERAH', 1, 2021, 20075000000, 2, 1),
('5.1', 'BELANJA OPERASI', 2, 2021, 17751066100, 2, 1),
('5.1.1', 'Belanja Pegawai', 3, 2021, 181080000, 2, 1),
('5.1.1.01', 'Belanja Gaji dan Tunjangan ASN', 4, 2021, 30860843291, 2, 1),
('5.1.1.01.01', 'Belanja Gaji Pokok ASN', 5, 2021, 22278054000, 2, 1),
('5.1.1.01.01.0001', 'Belanja Gaji Pokok PNS', 6, 2021, 5261927975, 2, 1),
('5.1.1.01.02', 'Belanja Tunjangan Keluarga ASN', 5, 2021, 2079514000, 2, 1),
('5.1.1.01.02.0001', 'Belanja Tunjangan Keluarga PNS', 6, 2021, 515941000, 2, 1),
('5.1.1.01.03', 'Belanja Tunjangan Jabatan ASN', 5, 2021, 1460201000, 2, 1),
('5.1.1.01.03.0001', 'Belanja Tunjangan Jabatan PNS', 6, 2021, 294831000, 2, 1),
('5.1.1.01.04', 'Belanja Tunjangan Fungsional ASN', 5, 2021, 119000000, 2, 1),
('5.1.1.01.04.0001', 'Belanja Tunjangan Fungsional PNS', 6, 2021, 124278000, 2, 1),
('5.1.1.01.05', 'Belanja Tunjangan Fungsional Umum ASN', 5, 2021, 856942996, 2, 1),
('5.1.1.01.05.0001', 'Belanja Tunjangan Fungsional Umum PNS', 6, 2021, 204490000, 2, 1),
('5.1.1.01.06', 'Belanja Tunjangan Beras ASN', 5, 2021, 1080547000, 2, 1),
('5.1.1.01.06.0001', 'Belanja Tunjangan Beras PNS', 6, 2021, 304346000, 2, 1),
('5.1.1.01.07', 'Belanja Tunjangan PPh/Tunjangan Khusus ASN', 5, 2021, 184209000, 2, 1),
('5.1.1.01.07.0001', 'Belanja Tunjangan PPh/Tunjangan Khusus PNS', 6, 2021, 19190000, 2, 1),
('5.1.1.01.08', 'Belanja Pembulatan Gaji ASN', 5, 2021, 321295, 2, 1),
('5.1.1.01.08.0001', 'Belanja Pembulatan Gaji PNS', 6, 2021, 87120, 2, 1),
('5.1.1.01.09', 'Belanja Iuran Jaminan Kesehatan ASN', 5, 2021, 2618737000, 2, 1),
('5.1.1.01.09.0001', 'Belanja Iuran Jaminan Kesehatan PNS', 6, 2021, 658444000, 2, 1),
('5.1.1.01.10', 'Belanja Iuran Jaminan Kecelakaan Kerja ASN', 5, 2021, 45829000, 2, 1),
('5.1.1.01.10.0001', 'Belanja Iuran Jaminan Kecelakaan Kerja PNS', 6, 2021, 10781000, 2, 1),
('5.1.1.01.11', 'Belanja Iuran Jaminan Kematian ASN', 5, 2021, 137488000, 2, 1),
('5.1.1.01.11.0001', 'Belanja Iuran Jaminan Kematian PNS', 6, 2021, 32344000, 2, 1),
('5.1.1.02', 'Belanja Tambahan Penghasilan ASN', 4, 2021, 41768701000, 2, 1),
('5.1.1.02.01', 'Tambahan Penghasilan berdasarkan Beban Kerja ASN', 5, 2021, 41768701000, 2, 1),
('5.1.1.02.01.0001', 'Tambahan Penghasilan berdasarkan Beban Kerja PNS', 6, 2021, 8435497000, 2, 1),
('5.1.1.03', 'Tambahan Penghasilan berdasarkan Pertimbangan Objektif Lainnya ASN', 4, 2021, 181080000, 2, 1),
('5.1.1.03.03', 'Belanja Tunjangan Profesi Guru (TPG) PNSD', 5, 2021, 627207840000, 2, 1),
('5.1.1.03.03.0001', 'Belanja TPG PNSD', 6, 2021, 642224682960, 2, 1),
('5.1.1.03.04', 'Belanja Tunjangan Khusus Guru (TKG) PNSD', 5, 2021, 19940535000, 2, 1),
('5.1.1.03.04.0001', 'Belanja TKG PNSD', 6, 2021, 20970677800, 2, 1),
('5.1.1.03.05', 'Belanja Tambahan Penghasilan (Tamsil) Guru PNSD', 5, 2021, 3090000000, 2, 1),
('5.1.1.03.05.0001', 'Belanja Tamsil Guru PNSD', 6, 2021, 3255000000, 2, 1),
('5.1.1.03.07', 'Belanja Honorarium', 5, 2021, 181080000, 2, 1),
('5.1.1.03.07.0001', 'Belanja Honorarium Penanggungjawaban Pengelola Keuangan', 6, 2021, 111360000, 2, 1),
('5.1.1.03.07.0002', 'Belanja Honorarium Pengadaan Barang/Jasa', 6, 2021, 25920000, 2, 1),
('5.1.2', 'Belanja Barang dan Jasa', 3, 2021, 17569986100, 2, 1),
('5.1.2.01', 'Belanja Barang', 4, 2021, 4206230780, 2, 1),
('5.1.2.01.01', 'Belanja Barang Pakai Habis', 5, 2021, 4206230780, 2, 1),
('5.1.2.01.01.0001', 'Belanja Bahan-Bahan Bangunan dan Konstruksi', 6, 2021, 0, 2, 1),
('5.1.2.01.01.0004', 'Belanja Bahan-Bahan Bakar dan Pelumas', 6, 2021, 662553700, 2, 1),
('5.1.2.01.01.0013', 'Belanja Suku Cadang-Suku Cadang Alat Angkutan', 6, 2021, 39000000, 2, 1),
('5.1.2.01.01.0015', 'Belanja Suku Cadang-Suku Cadang Alat Kedokteran', 6, 2021, 332662820, 2, 1),
('5.1.2.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat Tulis Kantor', 6, 2021, 520095681, 2, 1),
('5.1.2.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover', 6, 2021, 0, 2, 1),
('5.1.2.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak', 6, 2021, 410669050, 2, 1),
('5.1.2.01.01.0027', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Benda Pos', 6, 2021, 11800000, 2, 1),
('5.1.2.01.01.0029', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Komputer', 6, 2021, 10595000, 2, 1),
('5.1.2.01.01.0030', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perabot Kantor', 6, 2021, 65195000, 2, 1),
('5.1.2.01.01.0031', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat Listrik', 6, 2021, 745462830, 2, 1),
('5.1.2.01.01.0032', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perlengkapan Dinas', 6, 2021, 50000000, 2, 1),
('5.1.2.01.01.0035', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Suvenir/Cendera Mata', 6, 2021, 22450000, 2, 1),
('5.1.2.01.01.0038', 'Belanja Obat-Obatan-Obat-Obatan Lainnya', 6, 2021, 468524664, 2, 1),
('5.1.2.01.01.0052', 'Belanja Makanan dan Minuman Rapat', 6, 2021, 1188932000, 2, 1),
('5.1.2.01.01.0053', 'Belanja Makanan dan Minuman Jamuan Tamu', 6, 2021, 253510000, 2, 1),
('5.1.2.01.01.0055', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Pendidikan', 6, 2021, 486000000, 2, 1),
('5.1.2.01.01.0075', 'Belanja Pakaian Batik Tradisional', 6, 2021, 2960000, 2, 1),
('5.1.2.02', 'Belanja Jasa', 4, 2021, 2878887430, 2, 1),
('5.1.2.02.01', 'Belanja Jasa Kantor', 5, 2021, 2148093430, 2, 1),
('5.1.2.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia', 6, 2021, 642966000, 2, 1),
('5.1.2.02.01.0004', 'Honorarium Tim Pelaksana Kegiatan dan Sekretariat Tim Pelaksana Kegiatan', 6, 2021, 242435000, 2, 1),
('5.1.2.02.01.0013', 'Belanja Jasa Tenaga Pendidikan', 6, 2021, 198540000000, 2, 1),
('5.1.2.02.01.0014', 'Belanja Jasa Tenaga Kesehatan', 6, 2021, 189900000, 2, 1),
('5.1.2.02.01.0028', 'Belanja Jasa Tenaga Pelayanan Umum', 6, 2021, 238000000, 2, 1),
('5.1.2.02.01.0029', 'Belanja Jasa Tenaga Ahli', 6, 2021, 521112352, 2, 1),
('5.1.2.02.01.0030', 'Belanja Jasa Tenaga Kebersihan', 6, 2021, 3910275000, 2, 1),
('5.1.2.02.01.0031', 'Belanja Jasa Tenaga Keamanan', 6, 2021, 4525225000, 2, 1),
('5.1.2.02.01.0038', 'Belanja Jasa Tata Rias', 6, 2021, 25000000, 2, 1),
('5.1.2.02.01.0059', 'Belanja Tagihan Telepon', 6, 2021, 50000000, 2, 1),
('5.1.2.02.01.0060', 'Belanja Tagihan Air', 6, 2021, 400000000, 2, 1),
('5.1.2.02.01.0061', 'Belanja Tagihan Listrik', 6, 2021, 1821000000, 2, 1),
('5.1.2.02.01.0062', 'Belanja Langganan Jurnal/Surat Kabar/Majalah', 6, 2021, 32500000, 2, 1),
('5.1.2.02.01.0063', 'Belanja Kawat/Faksimili/Internet/TV Berlangganan', 6, 2021, 14000000, 2, 1),
('5.1.2.02.01.0064', 'Belanja Paket/Pengiriman', 6, 2021, 1155000, 2, 1),
('5.1.2.02.04', 'Belanja Sewa Peralatan dan Mesin', 5, 2021, 2885442500, 2, 1),
('5.1.2.02.04.0036', 'Belanja Sewa Kendaraan Bermotor Penumpang', 6, 2021, 108000000, 2, 1),
('5.1.2.02.04.0123', 'Belanja Sewa Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 81120000, 2, 1),
('5.1.2.02.04.0132', 'Belanja Sewa Peralatan Studio Audio', 6, 2021, 56000000, 2, 1),
('5.1.2.02.05', 'Belanja Sewa Gedung dan Bangunan', 5, 2021, 39000000, 2, 1),
('5.1.2.02.05.0001', 'Belanja Sewa Bangunan Gedung Kantor', 6, 2021, 50000000, 2, 1),
('5.1.2.02.05.0009', 'Belanja Sewa Bangunan Gedung Tempat Pertemuan', 6, 2021, 794200000, 2, 1),
('5.1.2.02.05.0032', 'Belanja Sewa Bangunan Fasilitas Umum', 6, 2021, 65000000, 2, 1),
('5.1.2.02.08', 'Belanja Jasa Konsultansi Konstruksi', 5, 2021, 50000000, 2, 1),
('5.1.2.02.08.0005', 'Belanja Jasa Konsultansi Perencanaan Arsitektur-Jasa Arsitektur Lainnya', 6, 2021, 70000000, 2, 1),
('5.1.2.02.08.0008', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Pekerjaan Teknik Sipil Air', 6, 2021, 94000000, 2, 1),
('5.1.2.02.08.0012', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Nasihat dan Konsultansi Jasa Rekayasa Konstruksi', 6, 2021, 4443500000, 2, 1),
('5.1.2.02.08.0019', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Bangunan Gedung', 6, 2021, 639000000, 2, 1),
('5.1.2.02.12', 'Belanja Kursus/Pelatihan, Sosialisasi, Bimbingan Teknis serta Pendidikan dan Pelatihan', 5, 2021, 680794000, 2, 1),
('5.1.2.02.12.0003', 'Belanja Bimbingan Teknis', 6, 2021, 647645000, 2, 1),
('5.1.2.03', 'Belanja Pemeliharaan', 4, 2021, 224300000, 2, 1),
('5.1.2.03.02', 'Belanja Pemeliharaan Peralatan dan Mesin', 5, 2021, 224300000, 2, 1),
('5.1.2.03.02.0035', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Dinas Bermotor Perorangan', 6, 2021, 108000000, 2, 1),
('5.1.2.03.02.0117', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Kantor-Alat Kantor Lainnya', 6, 2021, 58906000, 2, 1),
('5.1.2.03.02.0121', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Pendingin', 6, 2021, 22000000, 2, 1),
('5.1.2.03.02.0123', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 204639100, 2, 1),
('5.1.2.03.02.0132', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Studio- Peralatan Studio Audio', 6, 2021, 1200000, 2, 1),
('5.1.2.03.02.0181', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Peralatan Pemancar-Peralatan Pemancar dan Penerima VHF', 6, 2021, 0, 2, 1),
('5.1.2.03.02.0406', 'Belanja Pemeliharaan Komputer-Komputer Unit-Komputer Unit Lainnya', 6, 2021, 15000000, 2, 1),
('5.1.2.03.02.0411', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Komputer Lainnya', 6, 2021, 20000000, 2, 1),
('5.1.2.04', 'Belana Perjalanan Dinas', 4, 2021, 10260567890, 2, 1),
('5.1.2.04.01', 'Belanja Perjalanan Dinas Dalam Negeri', 5, 2021, 10260567890, 2, 1),
('5.1.2.04.01.0001', 'Belanja Perjalanan Dinas Biasa', 6, 2021, 2610848000, 2, 1),
('5.1.2.04.01.0003', 'Belanja Perjalanan Dinas Dalam Kota', 6, 2021, 359150000, 2, 1),
('5.1.2.04.01.0005', 'Belanja Perjalanan Dinas Paket Meeting Luar Kota', 6, 2021, 596452000, 2, 1),
('5.1.2.05', 'Belanja Uang dan/atau Jasa untuk Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 4, 2021, 442000000, 2, 1),
('5.1.2.05.01', 'Belanja Uang yang Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 5, 2021, 442000000, 2, 1),
('5.1.2.05.01.0001', 'Belanja Hadiah yang Bersifat Perlombaan', 6, 2021, 0, 2, 1),
('5.1.2.05.01.0003', 'Belanja Beasiswa', 6, 2021, 160443360000, 2, 1),
('5.1.2.88', 'Belanja Barang dan Jasa BOS', 4, 2021, 456815533360, 2, 1),
('5.1.2.88.88', 'Belanja Barang dan Jasa BOS', 5, 2021, 456815533360, 2, 1),
('5.1.2.88.88.8888', 'Belanja Barang dan Jasa BOS', 6, 2021, 456815533360, 2, 1),
('5.1.5', 'Belanja Hibah', 3, 2021, 2500000000, 2, 1),
('5.1.5.06', 'Belanja Hibah Dana BOS', 4, 2021, 2783674794500, 2, 1),
('5.1.5.06.01', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Negeri', 5, 2021, 476553000000, 2, 1),
('5.1.5.06.01.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Negeri', 6, 2021, 476553000000, 2, 1),
('5.1.5.06.02', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Swasta', 5, 2021, 1660224000000, 2, 1),
('5.1.5.06.02.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Swasta', 6, 2021, 1660224000000, 2, 1),
('5.1.5.06.03', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikmen Swasta', 5, 2021, 638412114500, 2, 1),
('5.1.5.06.03.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikmen Swasta', 6, 2021, 638412114500, 2, 1),
('5.1.5.06.04', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdiksus Swasta', 5, 2021, 8485680000, 2, 1),
('5.1.5.06.04.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdiksus Swasta', 6, 2021, 8485680000, 2, 1),
('5.2', 'BELANJA MODAL', 2, 2021, 2323933900, 2, 1),
('5.2.1', 'Belanja Modal Tanah', 3, 2021, 3196000000, 2, 1),
('5.2.1.01', 'Belanja Modal Tanah', 4, 2021, 3196000000, 2, 1),
('5.2.1.01.01', 'Belanja Modal Tanah Persil', 5, 2021, 3730336337, 2, 1),
('5.2.1.01.01.0004', 'Belanja Modal Tanah untuk Bangunan Tempat Kerja', 6, 2021, 2000000000, 2, 1),
('5.2.2', 'Belanja Modal Peralatan dan Mesin', 3, 2021, 2119933900, 2, 1),
('5.2.2.05', 'Belanja Modal Alat Kantor dan Rumah Tangga', 4, 2021, 578595900, 2, 1),
('5.2.2.05.01', 'Belanja Modal Alat Kantor', 5, 2021, 428700000, 2, 1),
('5.2.2.05.01.0004', 'Belanja Modal Alat Penyimpan Perlengkapan Kantor', 6, 2021, 328150000, 2, 1),
('5.2.2.05.01.0005', 'Belanja Modal Alat Kantor Lainnya', 6, 2021, 345993060, 2, 1),
('5.2.2.05.02', 'Belanja Modal Alat Rumah Tangga', 5, 2021, 149895900, 2, 1),
('5.2.2.05.02.0001', 'Belanja Modal Mebel', 6, 2021, 635050000, 2, 1),
('5.2.2.05.02.0004', 'Belanja Modal Alat Pendingin', 6, 2021, 270500000, 2, 1),
('5.2.2.05.02.0006', 'Belanja Modal Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 48890000, 2, 1),
('5.2.2.05.03', 'Belanja Modal Meja dan Kursi Kerja/Rapat Pejabat', 5, 2021, 1077885000, 2, 1),
('5.2.2.05.03.0001', 'Belanja Modal Meja Kerja Pejabat', 6, 2021, 37800000, 2, 1),
('5.2.2.05.03.0003', 'Belanja Modal Kursi Kerja Pejabat', 6, 2021, 493425000, 2, 1),
('5.2.2.06', 'Belanja Modal Alat Studio, Komunikasi, dan Pemancar', 4, 2021, 221088000, 2, 1),
('5.2.2.06.01', 'Belanja Modal Alat Studio', 5, 2021, 135775500, 2, 1),
('5.2.2.06.01.0002', 'Belanja Modal Peralatan Studio Video dan Film', 6, 2021, 60000000, 2, 1),
('5.2.2.07', 'Belanja Modal Alat Kedokteran dan Kesehatan', 4, 2021, 265000000, 2, 1),
('5.2.2.07.02', 'Belanja Modal Alat Kesehatan Umum', 5, 2021, 265000000, 2, 1),
('5.2.2.07.02.0005', 'Belanja Modal Alat Kesehatan Umum Lainnya', 6, 2021, 16376100, 2, 1),
('5.2.2.08', 'Belanja Modal Alat Laboratorium', 4, 2021, 6000000, 2, 1),
('5.2.2.08.01', 'Belanja Modal Unit Alat Laboratorium', 5, 2021, 6000000, 2, 1),
('5.2.2.08.01.0056', 'Belanja Modal Alat Laboratorium Lain', 6, 2021, 3000000, 2, 1),
('5.2.2.08.03', 'Belanja Modal Alat Peraga Praktek Sekolah', 5, 2021, 149253936986, 2, 1),
('5.2.2.08.03.0006', 'Belanja Modal Alat Peraga Praktek Sekolah Bidang Studi:IPA Atas', 6, 2021, 3510000000, 2, 1),
('5.2.2.08.03.0013', 'Belanja Modal Alat Peraga Luar Biasa (Tuna Netra, Terapi Fisik, Tuna Daksa, dan Tuna Rungu)', 6, 2021, 593050000, 2, 1),
('5.2.2.08.03.0014', 'Belanja Modal Alat Peraga Kejuruan', 6, 2021, 65844339486, 2, 1),
('5.2.2.08.03.0016', 'Belanja Modal Alat Peraga Praktik Sekolah Lainnya', 6, 2021, 79306547500, 2, 1),
('5.2.2.10', 'Belanja Modal Komputer', 4, 2021, 1120250000, 2, 1),
('5.2.2.10.01', 'Belanja Modal Komputer Unit', 5, 2021, 39300000, 2, 1),
('5.2.2.10.01.0001', 'Belanja Modal Komputer Jaringan', 6, 2021, 217910000, 2, 1),
('5.2.2.10.01.0003', 'Belanja Modal Komputer Unit Lainnya', 6, 2021, 58950000, 2, 1),
('5.2.2.10.02', 'Belanja Modal Peralatan Komputer', 5, 2021, 1120250000, 2, 1),
('5.2.2.10.02.0003', 'Belanja Modal Peralatan Personal Computer', 6, 2021, 133035000, 2, 1),
('5.2.2.10.02.0005', 'Belanja Modal Peralatan Komputer Lainnya', 6, 2021, 21900000, 2, 1),
('5.2.2.13', 'Belanja Modal Alat Produksi, Pengolahan, dan Pemurnian', 4, 2021, 385020000, 2, 1),
('5.2.2.13.01', 'Belanja Modal Sumur', 5, 2021, 385020000, 2, 1),
('5.2.2.13.01.0002', 'Belanja Modal Sumur Pemboran', 6, 2021, 181795540, 2, 1),
('5.2.3', 'Belanja Modal Gedung dan Bangunan', 3, 2021, 660000000, 2, 1),
('5.2.3.01', 'Belanja Modal Bangunan Gedung', 4, 2021, 660000000, 2, 1),
('5.2.3.01.01', 'Belanja Modal Bangunan Gedung Tempat Kerja', 5, 2021, 660000000, 2, 1),
('5.2.3.01.01.0001', 'Belanja Modal Bangunan Gedung Kantor', 6, 2021, 3611655000, 2, 1),
('5.2.3.01.01.0010', 'Belanja Modal Bangunan Gedung Tempat Pendidikan', 6, 2021, 0, 2, 1),
('5.2.3.01.01.0030', 'Belanja Modal Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 2833368500, 2, 1),
('5.2.5', 'Belanja Modal Aset Tetap Lainnya', 3, 2021, 204000000, 2, 1),
('5.2.5.01', 'Belanja Modal Bahan Perpustakaan', 4, 2021, 204000000, 2, 1),
('5.2.5.01.01', 'Belanja Modal Bahan Perpustakaan Tercetak', 5, 2021, 12000000, 2, 1),
('5.2.5.01.01.0001', 'Belanja Modal Buku Umum', 6, 2021, 12000000, 2, 1),
('5.2.5.01.07', 'Belanja Modal Tarscalt', 5, 2021, 87519182654, 2, 1),
('5.2.5.01.07.0001', 'Belanja Modal Tarscalt', 6, 2021, 87519182654, 2, 1),
('5.2.5.05', 'Belanja Modal Tanaman', 4, 2021, 200000000, 2, 1),
('5.2.5.05.01', 'Belanja Modal Tanaman', 5, 2021, 200000000, 2, 1),
('5.2.5.05.01.0001', 'Belanja Modal Tanaman', 6, 2021, 200000000, 2, 1),
('4.1.2.01', 'Retribusi Jasa Umum', 4, 2021, 200000000, 1, 1),
('4.1.2.01.01', 'Retribusi Pelayanan Kesehatan', 5, 2021, 9900000000, 1, 1),
('4.1.2.01.01.0006', 'Retribusi Pelayanan Kesehatan di Tempat Pelayanan Kesehatan Lainnya yang Sejenis', 6, 2021, 9900000000, 1, 1),
('4.1.2.02.06', 'Retribusi Tempat Penginapan/ Pesanggrahan/Vila', 5, 2021, 2200000000, 1, 1),
('4.1.2.02.06.0001', 'Retribusi Pelayanan Tempat Penginapan/Pesanggrahan/Vila', 6, 2021, 2200000000, 1, 1),
('4.1.4', 'Lain-lain PAD yang Sah', 3, 2021, 69677525781, 1, 1),
('4.1.4.16', 'Pendapatan BLUD', 4, 2021, 62600000000, 1, 1),
('4.1.4.16.01', 'Pendapatan BLUD', 5, 2021, 62600000000, 1, 1),
('4.1.4.16.01.0001', 'Pendapatan BLUD', 6, 2021, 78250000000, 1, 1),
('5.1.1.02.04', 'Tambahan Penghasilan berdasarkan Kelangkaan Profesi ASN', 5, 2021, 18121320000, 2, 1),
('5.1.1.02.04.0001', 'Tambahan Penghasilan berdasarkan Kelangkaan Profesi PNS', 6, 2021, 18121320000, 2, 1),
('5.1.1.03.02', 'Belanja bagi ASN atas Insentif Pemungutan Retribusi Daerah', 5, 2021, 82962500, 2, 1),
('5.1.1.03.02.0001', 'Belanja Insentif bagi ASN atas Pemungutan Retribusi Jasa Umum-Pelayanan Kesehatan', 6, 2021, 0, 2, 1),
('5.1.2.01.01.0002', 'Belanja Bahan-Bahan Kimia', 6, 2021, 7200000, 2, 1),
('5.1.2.01.01.0009', 'Belanja Bahan-Isi Tabung Pemadam Kebakaran', 6, 2021, 5070000, 2, 1),
('5.1.2.01.01.0010', 'Belanja Bahan-Isi Tabung Gas', 6, 2021, 54325000, 2, 1),
('5.1.2.01.01.0012', 'Belanja Bahan-Bahan Lainnya', 6, 2021, 46956500, 2, 1),
('5.1.2.01.01.0016', 'Belanja Suku Cadang-Suku Cadang Alat Laboratorium', 6, 2021, 15180000, 2, 1),
('5.1.2.01.01.0037', 'Belanja Obat-Obatan-Obat', 6, 2021, 549000, 2, 1),
('5.1.2.01.01.0054', 'Belanja Penambah Daya Tahan Tubuh', 6, 2021, 16500000, 2, 1),
('5.1.2.01.01.0056', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Kesehatan', 6, 2021, 3520000, 2, 1),
('5.1.2.02.01.0007', 'Honorarium Rohaniwan', 6, 2021, 8400000, 2, 1),
('5.1.2.02.01.0008', 'Honorarium Tim Penyusunan Jurnal, Buletin, Majalah, Pengelola Teknologi Informasi dan Pengelola Website', 6, 2021, 38000000, 2, 1),
('5.1.2.02.01.0015', 'Belanja Jasa Tenaga Laboratorium', 6, 2021, 4000000, 2, 1),
('5.1.2.02.01.0026', 'Belanja Jasa Tenaga Administrasi', 6, 2021, 66000000, 2, 1),
('5.1.2.02.01.0027', 'Belanja Jasa Tenaga Operator Komputer', 6, 2021, 1053000000, 2, 1),
('5.1.2.02.01.0033', 'Belanja Jasa Tenaga Supir', 6, 2021, 234000000, 2, 1),
('5.1.2.02.01.0034', 'Belanja Jasa Tenaga Juru Masak', 6, 2021, 655500000, 2, 1),
('5.1.2.02.01.0049', 'Belanja Jasa Pencucian Pakaian, Alat Kesenian dan Kebudayaan, serta Alat Rumah Tangga', 6, 2021, 19550000, 2, 1),
('5.1.2.02.01.0050', 'Belanja Jasa Kalibrasi', 6, 2021, 60000000, 2, 1),
('5.1.2.02.01.0051', 'Belanja Jasa Pengolahan Sampah', 6, 2021, 16200000, 2, 1),
('5.1.2.02.01.0055', 'Belanja Jasa Iklan/Reklame, Film, dan Pemotretan', 6, 2021, 6000000, 2, 1),
('5.1.2.02.01.0067', 'Belanja Pembayaran Pajak, Bea, dan Perizinan', 6, 2021, 238000000, 2, 1),
('5.1.2.02.01.0073', 'Belanja Medical Check Up', 6, 2021, 50000000, 2, 1),
('5.1.2.02.02', 'Belanja Iuran Jaminan/Asuransi', 5, 2021, 189000000, 2, 1),
('5.1.2.02.02.0002', 'Belanja Kontribusi Jaminan Kesehatan bagi PBI', 6, 2021, 119288962800, 2, 1),
('5.1.2.02.02.0003', 'Belanja Iuran Jaminan Kesehatan bagi Peserta PBPU dan BP Kelas 3', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0035', 'Belanja Sewa Kendaraan Dinas Bermotor Perorangan', 6, 2021, 20000000, 2, 1),
('5.1.2.02.04.0118', 'Belanja Sewa Mebel', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0201', 'Belanja Sewa Alat Pengatur Telekomunikasi', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0416', 'Belanja Sewa Elektronik/Electric', 6, 2021, 15000000, 2, 1),
('5.1.2.02.05.0036', 'Belanja Sewa Taman', 6, 2021, 0, 2, 1),
('5.1.2.02.05.0043', 'Belanja Sewa Hotel', 6, 2021, 201960000, 2, 1),
('5.1.2.02.08.0028', 'Belanja Jasa Konsultansi Spesialis-Jasa Pengujian dan Analisa Komposisi dan Tingkat Kemurnian', 6, 2021, 50000000, 2, 1),
('5.1.2.02.09', 'Belanja Jasa Konsultansi Non Konstruksi', 5, 2021, 700000000, 2, 1),
('5.1.2.02.09.0008', 'Belanja Jasa Konsultansi Berorientasi Bidang-Kesehatan', 6, 2021, 50000000, 2, 1),
('5.1.2.02.11', 'Belanja Beasiswa Pendidikan PNS', 5, 2021, 110000000, 2, 1),
('5.1.2.02.11.0002', 'Belanja Beasiswa Tugas Belajar S2', 6, 2021, 55000000, 2, 1),
('5.1.2.02.12.0001', 'Belanja Kursus Singkat/Pelatihan', 6, 2021, 516500000, 2, 1),
('5.1.2.03.02.0022', 'Belanja Pemeliharaan Alat Besar-Alat Bantu- Electric Generating Set', 6, 2021, 40500000, 2, 1),
('5.1.2.03.02.0038', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Bermotor Beroda Dua', 6, 2021, 37000000, 2, 1),
('5.1.2.03.02.0118', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Mebel', 6, 2021, 24375000, 2, 1),
('5.1.2.03.02.0138', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Komunikasi- Alat Komunikasi Telephone', 6, 2021, 3000000, 2, 1),
('5.1.2.03.02.0237', 'Belanja Pemeliharaan Alat Kedokteran dan Kesehatan-Alat Kesehatan Umum-Alat Kesehatan Umum Lainnya', 6, 2021, 50000000, 2, 1),
('5.1.2.03.02.0293', 'Belanja Pemeliharaan Alat Laboratorium-Unit Alat Laboratorium-Alat Laboratorium Lain', 6, 2021, 35920000, 2, 1),
('5.1.2.03.02.0405', 'Belanja Pemeliharaan Komputer-Komputer Unit-Personal Computer', 6, 2021, 4000000, 2, 1),
('5.1.2.03.02.0410', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Jaringan', 6, 2021, 2000000, 2, 1),
('5.1.2.03.03', 'Belanja Pemeliharaan Gedung dan Bangunan', 5, 2021, 134214070000, 2, 1),
('5.1.2.03.03.0001', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Kantor', 6, 2021, 180000000, 2, 1),
('5.1.2.03.03.0006', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Kesehatan', 6, 2021, 3684605000, 2, 1),
('5.1.2.03.03.0037', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 2031977749, 2, 1),
('5.1.2.03.04', 'Belanja Pemeliharaan Jalan, Jaringan, dan Irigasi', 5, 2021, 16200000, 2, 1),
('5.1.2.03.04.0126', 'Belanja Pemeliharaan Jaringan-Jaringan Listrik-Jaringan Listrik Lainnya', 6, 2021, 16200000, 2, 1),
('5.1.2.04.01.0004', 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota', 6, 2021, 10200000, 2, 1),
('5.1.5.05', 'Belanja Hibah kepada Badan, Lembaga, Organisasi Kemasyarakatan yang Berbadan Hukum Indonesia', 4, 2021, 500000000, 2, 1),
('5.1.5.05.03', 'Belanja Hibah kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 5, 2021, 300400000000, 2, 1),
('5.1.5.05.03.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 6, 2021, 389607033473, 2, 1),
('5.2.2.01', 'Belanja Modal Alat Besar', 4, 2021, 238000000, 2, 1),
('5.2.2.01.03', 'Belanja Modal Alat Bantu', 5, 2021, 238000000, 2, 1),
('5.2.2.01.03.0004', 'Belanja Modal Electric Generating Set', 6, 2021, 200000000, 2, 1),
('5.2.2.01.03.0005', 'Belanja Modal Pompa', 6, 2021, 103975000, 2, 1),
('5.2.2.03', 'Belanja Modal Alat Bengkel dan Alat Ukur', 4, 2021, 13500000, 2, 1),
('5.2.2.03.03', 'Belanja Modal Alat Ukur', 5, 2021, 13500000, 2, 1),
('5.2.2.03.03.0021', 'Belanja Modal Alat Ukur Lainnya', 6, 2021, 2000000, 2, 1),
('5.2.2.07.01', 'Belanja Modal Alat Kedokteran', 5, 2021, 5000000, 2, 1),
('5.2.2.07.01.0029', 'Belanja Modal Alat Kedokteran Lainnya', 6, 2021, 0, 2, 1),
('5.2.2.08.01.0011', 'Belanja Modal Alat Laboratorium Umum', 6, 2021, 344000000, 2, 1),
('5.2.2.08.01.0013', 'Belanja Modal Alat Laboratorium Kimia', 6, 2021, 1780492000, 2, 1),
('5.2.2.10.01.0002', 'Belanja Modal Personal Computer', 6, 2021, 117437000, 2, 1),
('5.2.5.08', 'Belanja Modal Aset Tidak Berwujud', 4, 2021, 55000000, 2, 1),
('5.2.5.08.01', 'Belanja Modal Aset Tidak Berwujud', 5, 2021, 55000000, 2, 1),
('5.2.5.08.01.0005', 'Belanja Modal Software', 6, 2021, 30000000, 2, 1),
('5.1.1.03.08', 'Belanja Jasa Pengelolaan BMD', 5, 2021, 36600000, 2, 1),
('5.1.1.03.08.0002', 'Belanja Jasa Pengelolaan BMD yang Tidak Menghasilkan Pendapatan', 6, 2021, 36600000, 2, 1),
('5.1.2.01.01.0008', 'Belanja Bahan-Bahan/Bibit Tanaman', 6, 2021, 64236000, 2, 1),
('5.1.2.01.01.0034', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perlengkapan Pendukung Olahraga', 6, 2021, 36000000, 2, 1),
('5.1.2.01.01.0036', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat/Bahan untuk Kegiatan Kantor Lainnya', 6, 2021, 15172500, 2, 1),
('5.1.2.01.01.0043', 'Belanja Natura dan Pakan-Natura', 6, 2021, 4688991750, 2, 1),
('5.1.2.02.01.0035', 'Belanja Jasa Tenaga Teknisi Mekanik dan Listrik', 6, 2021, 39000000, 2, 1),
('5.1.2.02.01.0048', 'Belanja Jasa Kontribusi Asosiasi', 6, 2021, 150000000, 2, 1),
('5.1.2.02.02.0006', 'Belanja Iuran Jaminan Kecelakaan Kerja bagi Non ASN', 6, 2021, 130920052, 2, 1),
('5.1.2.03.02.0103', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pengolahan Tanah dan Tanaman', 6, 2021, 4000000, 2, 1),
('5.1.2.03.02.0409', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Personal Computer', 6, 2021, 20000000, 2, 1),
('5.1.2.03.03.0008', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Tempat Ibadah', 6, 2021, 22350000, 2, 1),
('5.1.2.03.03.0013', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung untuk Pos Jaga', 6, 2021, 207855000, 2, 1),
('5.2.2.03.01', 'Belanja Modal Alat Bengkel Bermesin', 5, 2021, 35000000, 2, 1),
('5.2.2.03.01.0008', 'Belanja Modal Peralatan Las', 6, 2021, 35000000, 2, 1),
('5.2.2.03.03.0001', 'Belanja Modal Alat Ukur Universal', 6, 2021, 77400000, 2, 1),
('5.2.2.03.03.0010', 'Belanja Modal Alat Timbangan/Biara', 6, 2021, 0, 2, 1),
('5.2.2.06.01.0004', 'Belanja Modal Peralatan Cetak', 6, 2021, 12000000, 2, 1),
('5.2.2.07.01.0013', 'Belanja Modal Alat Kedokteran Neurologi (Saraf)', 6, 2021, 984856000, 2, 1),
('5.2.2.08.01.0064', 'Belanja Modal Unit Alat Laboratorium Lainnya', 6, 2021, 1700000, 2, 1),
('5.2.2.10.02.0004', 'Belanja Modal Peralatan Jaringan', 6, 2021, 3000000, 2, 1),
('5.2.3.01.01.0004', 'Belanja Modal Bangunan Gedung Instalasi', 6, 2021, 848850000, 2, 1),
('5.2.3.01.01.0006', 'Belanja Modal Bangunan Kesehatan', 6, 2021, 17719000000, 2, 1),
('5.2.4', 'Belanja Modal Jalan, Jaringan, dan Irigasi', 3, 2021, 399655097, 2, 1),
('5.2.4.04', 'Belanja Modal Jaringan', 4, 2021, 182000000, 2, 1),
('5.2.4.04.02', 'Belanja Modal Jaringan Listrik', 5, 2021, 182000000, 2, 1),
('5.2.4.04.02.0003', 'Belanja Modal Jaringan Listrik Lainnya', 6, 2021, 182000000, 2, 1),
('5.2.5.01.01.0012', 'Belanja Modal Bahan Perpustakaan Tercetak Lainnya', 6, 2021, 5000000, 2, 1),
('5.1.1.03.07.0003', 'Belanja Honorarium Perangkat Unit Kerja Pengadaan Barang dan Jasa (UKPBJ)', 6, 2021, 0, 2, 1),
('5.1.1.99', 'Belanja Pegawai BLUD', 4, 2021, 24731242000, 2, 1),
('5.1.1.99.99', 'Belanja Pegawai BLUD', 5, 2021, 24731242000, 2, 1),
('5.1.1.99.99.9999', 'Belanja Pegawai BLUD', 6, 2021, 37041212472, 2, 1),
('5.1.2.01.01.0039', 'Belanja Barang untuk Dijual/Diserahkan kepada Masyarakat', 6, 2021, 238200000, 2, 1),
('5.1.2.01.01.0058', 'Belanja Makanan dan Minuman Aktivitas Lapangan', 6, 2021, 1102943000, 2, 1),
('5.1.2.02.01.0012', 'Honorarium Tim Anggaran Pemerintah Daerah', 6, 2021, 2240500000, 2, 1),
('5.1.2.03.02.0301', 'Belanja Pemeliharaan Alat Laboratorium-Unit Alat Laboratorium-Unit Alat Laboratorium Lainnya', 6, 2021, 150000000, 2, 1),
('5.1.2.03.03.0046', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal-Rumah Negara dalam Proses Penggolongan', 6, 2021, 1360258250, 2, 1),
('5.1.2.99', 'Belanja Barang dan Jasa BLUD', 4, 2021, 37868758000, 2, 1),
('5.1.2.99.99', 'Belanja Barang dan Jasa BLUD', 5, 2021, 37868758000, 2, 1),
('5.1.2.99.99.9999', 'Belanja Barang dan Jasa BLUD', 6, 2021, 41208787528, 2, 1),
('5.2.2.01.01', 'Belanja Modal Alat Besar Darat', 5, 2021, 20516000, 2, 1),
('5.2.2.01.01.0011', 'Belanja Modal Mesin Proses', 6, 2021, 40250000, 2, 1),
('5.2.2.01.03.0008', 'Belanja Modal Alat Pengolahan Air Kotor', 6, 2021, 120000000, 2, 1),
('5.2.2.02', 'Belanja Modal Alat Angkutan', 4, 2021, 12805200000, 2, 1),
('5.2.2.02.01', 'Belanja Modal Alat Angkutan Darat Bermotor', 5, 2021, 12700000000, 2, 1),
('5.2.2.02.01.0006', 'Belanja Modal Kendaraan Bermotor Khusus', 6, 2021, 0, 2, 1),
('5.2.2.06.02', 'Belanja Modal Alat Komunikasi', 5, 2021, 85312500, 2, 1),
('5.2.2.06.02.0001', 'Belanja Modal Alat Komunikasi Telephone', 6, 2021, 48998000, 2, 1),
('5.2.2.07.01.0004', 'Belanja Modal Alat Kedokteran Bedah', 6, 2021, 10726628000, 2, 1),
('5.2.3.04', 'Belanja Modal Tugu Titik Kontrol/Pasti', 4, 2021, 180000000, 2, 1),
('5.2.3.04.01', 'Belanja Modal Tugu/Tanda Batas', 5, 2021, 180000000, 2, 1),
('5.2.3.04.01.0004', 'Belanja Modal Pagar', 6, 2021, 0, 2, 1),
('5.2.5.01.01.0003', 'Belanja Modal Buku Agama', 6, 2021, 5750000, 2, 1),
('4.1.2.02.01.0002', 'Retribusi Penyewaan Tanah', 6, 2021, 800000000, 1, 1),
('4.1.2.02.01.0004', 'Retribusi Pemakaian Laboratorium', 6, 2021, 650000000, 1, 1),
('4.1.2.02.01.0007', 'Retribusi Pemakaian Alat', 6, 2021, 52404365, 1, 1),
('5.1.2.02.08.0009', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Pekerjaan Teknik Sipil Transportasi', 6, 2021, 350500000, 2, 1),
('5.1.2.02.08.0020', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Teknik Sipil Transportasi', 6, 2021, 395562200, 2, 1),
('5.1.2.03.02.0012', 'Belanja Pemeliharaan Alat Besar-Alat Besar Darat-Alat Besar Darat Lainnya', 6, 2021, 6000000, 2, 1),
('5.1.2.03.02.0404', 'Belanja Pemeliharaan Komputer-Komputer Unit-Komputer Jaringan', 6, 2021, 63000000, 2, 1),
('5.1.2.03.04.0002', 'Belanja Pemeliharaan Jalan dan Jembatan- Jalan-Jalan Provinsi', 6, 2021, 50197209184, 2, 1),
('5.1.2.03.04.0012', 'Belanja Pemeliharaan Jalan dan Jembatan- Jembatan-Jembatan pada Jalan Provinsi', 6, 2021, 4587828575, 2, 1),
('5.2.1.01.03', 'Belanja Modal Lapangan', 5, 2021, 3196000000, 2, 1),
('5.2.1.01.03.0007', 'Belanja Modal Tanah untuk Jalan', 6, 2021, 25000000000, 2, 1),
('5.2.2.01.01.0008', 'Belanja Modal Aggregate and Concrete Equipment', 6, 2021, 0, 2, 1),
('5.2.2.01.03.0003', 'Belanja Modal Compressor', 6, 2021, 0, 2, 1),
('5.2.2.02.01.0003', 'Belanja Modal Kendaraan Bermotor Angkutan Barang', 6, 2021, 192800000, 2, 1),
('5.2.2.05.01.0002', 'Belanja Modal Mesin Hitung/Mesin Jumlah', 6, 2021, 2250000, 2, 1),
('5.2.2.08.01.0008', 'Belanja Modal Alat Laboratorium Mekanika Tanah dan Batuan', 6, 2021, 183137500, 2, 1),
('5.2.2.18', 'Belanja Modal Rambu-Rambu', 4, 2021, 12303700000, 2, 1),
('5.2.2.18.01', 'Belanja Modal Rambu-Rambu Lalu Lintas Darat', 5, 2021, 12303700000, 2, 1),
('5.2.2.18.01.0003', 'Belanja Modal Rambu-Rambu Lalu Lintas Darat Lainnya', 6, 2021, 125000000, 2, 1),
('5.2.4.01', 'Belanja Modal Jalan dan Jembatan', 4, 2021, 2126880000, 2, 1),
('5.2.4.01.01', 'Belanja Modal Jalan', 5, 2021, 1287287500, 2, 1),
('5.2.4.01.01.0002', 'Belanja Modal Jalan Provinsi', 6, 2021, 0, 2, 1),
('5.2.4.01.02', 'Belanja Modal Jembatan', 5, 2021, 2126880000, 2, 1),
('5.2.4.01.02.0002', 'Belanja Modal Jembatan pada Jalan Provinsi', 6, 2021, 33017935110, 2, 1),
('5.1.2.01.01.0023', 'Belanja Suku Cadang-Suku Cadang Lainnya', 6, 2021, 32500000, 2, 1),
('5.1.2.02.08.0021', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Teknik Sipil Air', 6, 2021, 2365000000, 2, 1),
('5.1.2.02.08.0027', 'Belanja Jasa Konsultansi Spesialis-Jasa Pembuatan Peta', 6, 2021, 100000000, 2, 1),
('5.1.2.02.12.0002', 'Belanja Sosialisasi', 6, 2021, 74600000, 2, 1),
('5.1.2.03.02.0120', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Pembersih', 6, 2021, 123925000, 2, 1),
('5.2.2.01.01.0006', 'Belanja Modal Asphalt Equipment', 6, 2021, 0, 2, 1),
('5.2.2.01.01.0012', 'Belanja Modal Alat Besar Darat Lainnya', 6, 2021, 0, 2, 1),
('5.2.2.04', 'Belanja Modal Alat Pertanian', 4, 2021, 3000000, 2, 1),
('5.2.2.04.01', 'Belanja Modal Alat Pengolahan', 5, 2021, 3000000, 2, 1),
('5.2.2.04.01.0001', 'Belanja Modal Alat Pengolahan Tanah dan Tanaman', 6, 2021, 1000000, 2, 1),
('5.2.3.01.01.0009', 'Belanja Modal Bangunan Gedung Tempat Pertemuan', 6, 2021, 310000000, 2, 1),
('5.2.3.01.02', 'Belanja Modal Bangunan Gedung Tempat Tinggal', 5, 2021, 0, 2, 1),
('5.2.3.01.02.0013', 'Belanja Modal Bangunan Gedung Tempat Tinggal Lainnya', 6, 2021, 161710287, 2, 1),
('5.2.4.01.01.0010', 'Belanja Modal Jalan Lainnya', 6, 2021, 537287500, 2, 1),
('5.2.4.02', 'Belanja Modal Bangunan Air', 4, 2021, 199655097, 2, 1),
('5.2.4.02.01', 'Belanja Modal Bangunan Air Irigasi', 5, 2021, 728000000, 2, 1),
('5.2.4.02.01.0002', 'Belanja Modal Bangunan Pengambilan Irigasi', 6, 2021, 8004021552, 2, 1),
('5.2.4.02.01.0003', 'Belanja Modal Bangunan Pembawa Irigasi', 6, 2021, 27438126888, 2, 1),
('5.2.4.02.01.0004', 'Belanja Modal Bangunan Pembuang Irigasi', 6, 2021, 1215111176, 2, 1),
('5.2.4.02.04', 'Belanja Modal Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam', 5, 2021, 199655097, 2, 1),
('5.2.4.02.04.0001', 'Belanja Modal Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam', 6, 2021, 199655097, 2, 1),
('5.2.4.02.04.0002', 'Belanja Modal Bangunan Pengambilan Pengaman Sungai/Pantai', 6, 2021, 48204998502, 2, 1),
('5.2.4.02.06', 'Belanja Modal Bangunan Air Bersih/Air Baku', 5, 2021, 52686860000, 2, 1),
('5.2.4.02.06.0001', 'Belanja Modal Bangunan Waduk Air Bersih/Air Baku', 6, 2021, 1646424156, 2, 1),
('5.2.4.02.06.0002', 'Belanja Modal Bangunan Pengambilan Air Bersih/Air Baku', 6, 2021, 2629840909, 2, 1),
('5.2.4.02.06.0003', 'Belanja Modal Bangunan Pembawa Air Bersih/Air Baku', 6, 2021, 49886860000, 2, 1),
('5.2.4.03', 'Belanja Modal Instalasi', 4, 2021, 200000000, 2, 1),
('5.2.4.03.02', 'Belanja Modal Instalasi Air Kotor', 5, 2021, 300000000, 2, 1),
('5.2.4.03.02.0004', 'Belanja Modal Instalasi Air Kotor Lainnya', 6, 2021, 1410000000, 2, 1),
('5.1.2.01.01.0076', 'Belanja Pakaian Olahraga', 6, 2021, 6720000, 2, 1),
('5.1.2.02.04.0135', 'Belanja Sewa Peralatan Cetak', 6, 2021, 0, 2, 1),
('5.1.5.02', 'Belanja Hibah kepada Pemerintah Daerah Lainnya', 4, 2021, 150600000, 2, 1),
('5.1.5.02.02', 'Belanja Hibah Barang kepada Pemerintah Daerah Lainnya', 5, 2021, 150600000, 2, 1),
('5.1.5.02.02.0001', 'Belanja Hibah Barang kepada Pemerintah Daerah Lainnya', 6, 2021, 150600000, 2, 1),
('5.1.6', 'Belanja Bantuan Sosial', 3, 2021, 810000000, 2, 1),
('5.1.6.03', 'Belanja Bantuan Sosial kepada Kelompok Masyarakat', 4, 2021, 3786720750, 2, 1),
('5.1.6.03.01', 'Belanja Bantuan Sosial Uang yang direncanakan kepada Kelompok Masyarakat', 5, 2021, 1152000000, 2, 1),
('5.1.6.03.01.0001', 'Belanja Bantuan Sosial Uang yang Direncanakan kepada Kelompok Masyarakat', 6, 2021, 1152000000, 2, 1),
('5.2.1.01.01.0007', 'Belanja Modal Tanah Persil Lainnya', 6, 2021, 1563010925, 2, 1),
('5.1.2.01.01.0074', 'Belanja Pakaian Adat Daerah', 6, 2021, 40600000, 2, 1),
('5.1.2.02.01.0025', 'Belanja Jasa Tenaga Kesenian dan Kebudayaan', 6, 2021, 506925000, 2, 1),
('5.1.2.02.01.0046', 'Belanja Jasa Konversi Aplikasi/Sistem Informasi', 6, 2021, 200000000, 2, 1),
('5.1.2.02.01.0071', 'Belanja Lembur', 6, 2021, 0, 2, 1),
('5.1.2.02.02.0008', 'Belanja Asuransi Barang Milik Daerah', 6, 2021, 0, 2, 1),
('5.1.5.05.01', 'Belanja Hibah kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 5, 2021, 500000000, 2, 1),
('5.1.5.05.01.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 83454243000, 2, 1),
('5.1.5.05.02', 'Belanja Hibah kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 5, 2021, 20350000000, 2, 1),
('5.1.5.05.02.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 6, 2021, 23550000000, 2, 1),
('5.1.5.05.03.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 6, 2021, 150000000, 2, 1),
('5.1.5.07', 'Belanja Hibah Bantuan Keuangan kepada Partai Politik', 4, 2021, 7591268400, 2, 1),
('5.1.5.07.01', 'Belanja Hibah Bantuan Keuangan kepada Partai Politik', 5, 2021, 7591268400, 2, 1),
('5.1.5.07.01.0001', 'Belanja Hibah berupa Bantuan Keuangan kepada Partai Politik', 6, 2021, 7591268400, 2, 1),
('5.2.2.05.02.0007', 'Belanja Modal Alat Pemadam Kebakaran', 6, 2021, 2510248000, 2, 1),
('5.2.2.05.03.0002', 'Belanja Modal Meja Rapat Pejabat', 6, 2021, 19500000, 2, 1),
('5.1.2.02.01.0017', 'Belanja Jasa Tenaga Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat', 6, 2021, 11308462500, 2, 1),
('5.1.2.02.08.0016', 'Belanja Jasa Konsultansi Perencanaan Penataan Ruang-Jasa Perencanaan dan Perancangan Lingkungan Bangunan dan Landscape', 6, 2021, 50000000, 2, 1),
('5.1.2.02.08.0023', 'Belanja Jasa Konsultansi Pengawasan Penataan Ruang', 6, 2021, 190000000, 2, 1),
('5.1.5.01', 'Belanja Hibah kepada Pemerintah Pusat', 4, 2021, 2000000000, 2, 1),
('5.1.5.01.01', 'Belanja Hibah Uang kepada Pemerintah Pusat', 5, 2021, 0, 2, 1),
('5.1.5.01.01.0001', 'Belanja Hibah Uang kepada Pemerintah Pusat', 6, 2021, 3000000000, 2, 1),
('5.2.3.01.01.0036', 'Belanja Modal Taman', 6, 2021, 60000000, 2, 1),
('5.1.2.01.01.0045', 'Belanja Natura dan Pakan-Natura dan Pakan Lainnya', 6, 2021, 158760000, 2, 1),
('5.1.2.02.01.0019', 'Belanja Jasa Tenaga Penanganan Bencana', 6, 2021, 5470000000, 2, 1),
('5.1.2.02.04.0034', 'Belanja Sewa Alat Bantu Lainnya', 6, 2021, 30000000, 2, 1),
('5.1.2.02.04.0121', 'Belanja Sewa Alat Pendingin', 6, 2021, 10000000, 2, 1),
('5.1.2.02.09.0014', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Khusus', 6, 2021, 96000000, 2, 1),
('5.1.2.02.12.0004', 'Belanja Diklat Kepemimpinan', 6, 2021, 169420000, 2, 1),
('5.1.2.03.03.0026', 'Belanja Belanja Pemeliharaan Bangunan Gedung-Bangunan Gedung Tempat Kerja-Bangunan Penampung Sekam', 6, 2021, 766605000, 2, 1),
('5.1.2.03.05', 'Belanja Pemeliharaan Aset Tetap Lainnya', 5, 2021, 300000, 2, 1),
('5.1.2.03.05.0065', 'Belanja Pemeliharaan Aset Tetap dalam Renovasi-Aset Tetap dalam Renovasi-Aset Tetap dalam Renovasi', 6, 2021, 192550500, 2, 1),
('5.2.2.06.02.0011', 'Belanja Modal Alat Komunikasi Lainnya', 6, 2021, 450000000, 2, 1),
('5.2.2.06.04', 'Belanja Modal Peralatan Komunikasi Navigasi', 5, 2021, 4000000, 2, 1),
('5.2.2.06.04.0006', 'Belanja Modal Peralatan Komunikasi untuk Dokumentasi', 6, 2021, 300000, 2, 1),
('5.2.2.07.01.0001', 'Belanja Modal Alat Kedokteran Umum', 6, 2021, 0, 2, 1),
('5.2.2.15', 'Belanja Modal Alat Keselamatan Kerja', 4, 2021, 9910200, 2, 1),
('5.2.2.15.03', 'Belanja Modal Alat SAR', 5, 2021, 60356313, 2, 1),
('5.2.2.15.03.0004', 'Belanja Modal Alat SAR Lainnya', 6, 2021, 60356313, 2, 1),
('5.1.2.02.01.0065', 'Belanja Penambahan Daya', 6, 2021, 70349400, 2, 1),
('5.1.2.02.04.0052', 'Belanja Sewa Alat Angkutan Apung Bermotor Lainnya', 6, 2021, 6400000, 2, 1),
('5.1.2.02.04.0075', 'Belanja Sewa Perkakas Bengkel Kerja', 6, 2021, 2000000, 2, 1),
('5.1.2.02.04.0103', 'Belanja Sewa Alat Pengolahan Tanah dan Tanaman', 6, 2021, 4000000, 2, 1),
('5.1.2.02.05.0037', 'Belanja Sewa Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 10000000, 2, 1),
('5.1.6.01', 'Belanja Bantuan Sosial kepada Individu', 4, 2021, 810000000, 2, 1),
('5.1.6.01.01', 'Belanja Bantuan Sosial Uang yang direncanakan kepada Individu', 5, 2021, 810000000, 2, 1),
('5.1.6.01.01.0001', 'Belanja Bantuan Sosial Uang yang Direncanakan kepada Individu', 6, 2021, 405000000, 2, 1),
('5.1.6.01.02', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Individu', 5, 2021, 402800000, 2, 1),
('5.1.6.01.02.0001', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Individu', 6, 2021, 2469080288, 2, 1),
('5.2.2.02.02', 'Belanja Modal Alat Angkutan Darat Tak Bermotor', 5, 2021, 105200000, 2, 1),
('5.2.2.02.02.0001', 'Belanja Modal Kendaraan Tak Bermotor Angkutan Barang', 6, 2021, 1300000, 2, 1),
('4.1.2.03', 'Retribusi Perizinan Tertentu', 4, 2021, 2246000000, 1, 1),
('4.1.2.03.06', 'Retribusi Perpanjangan Izin Mempekerjakan Tenaga Kerja Asing (IMTA)', 5, 2021, 1500000000, 1, 1),
('4.1.2.03.06.0001', 'Retribusi Pemberian Perpanjangan IMTA kepada Pemberi Kerja Tenaga Kerja Asing', 6, 2021, 1900000000, 1, 1),
('5.1.2.02.01.0011', 'Honorarium Penyelenggaraan Kegiatan Pendidikan dan Pelatihan', 6, 2021, 528800000, 2, 1),
('5.1.2.02.03', 'Belanja Sewa Tanah', 5, 2021, 60000000, 2, 1),
('5.1.2.02.03.0004', 'Belanja Sewa Tanah untuk Bangunan Tempat Kerja', 6, 2021, 150000000, 2, 1),
('5.1.2.01.01.0064', 'Belanja Pakaian Dinas Lapangan (PDL)', 6, 2021, 35400000, 2, 1),
('5.1.2.02.05.0016', 'Belanja Sewa Bangunan Gedung Perpustakaan', 6, 2021, 28000000, 2, 1),
('5.1.2.04.02', 'Belanja Perjalanan Dinas Luar Negeri', 5, 2021, 900000000, 2, 1),
('5.1.2.04.02.0001', 'Belanja Perjalanan Dinas BiasaûLuar Negeri', 6, 2021, 0, 2, 1),
('5.1.2.05.02', 'Belanja Jasa yang Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 5, 2021, 20187500, 2, 1),
('5.1.2.05.02.0001', 'Belanja Jasa yang Diberikan kepada Pihak Ketiga/Pihak Lain', 6, 2021, 0, 2, 1),
('5.2.2.06.01.0006', 'Belanja Modal Alat Studio Lainnya', 6, 2021, 3000000, 2, 1),
('5.1.2.01.01.0041', 'Belanja Persediaan untuk Tujuan Strategis/Berjaga-jaga-Persediaan untuk Tujuan Strategis/Berjaga-jaga', 6, 2021, 726000000, 2, 1),
('5.1.2.01.01.0044', 'Belanja Natura dan Pakan-Pakan', 6, 2021, 34392000, 2, 1),
('5.1.2.02.01.0023', 'Belanja Jasa Tenaga Teknis Pertanian dan Pangan', 6, 2021, 8269260000, 2, 1),
('5.1.2.02.01.0032', 'Belanja Jasa Tenaga Caraka', 6, 2021, 78000000, 2, 1),
('5.1.2.02.04.0037', 'Belanja Sewa Kendaraan Bermotor Angkutan Barang', 6, 2021, 133513948, 2, 1),
('5.1.2.02.08.0006', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Nasihat dan Konsultansi Rekayasa Teknik', 6, 2021, 12000000, 2, 1),
('5.1.2.02.09.0001', 'Belanja Jasa Konsultansi Berorientasi Bidang-Pengembangan Pertanian dan Perdesaan', 6, 2021, 1193200000, 2, 1),
('5.1.2.03.02.0001', 'Belanja Pemeliharaan Alat Besar-Alat Besar Darat-Tractor', 6, 2021, 11000000, 2, 1),
('5.1.2.03.02.0039', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Bermotor Beroda Tiga', 6, 2021, 6000000, 2, 1),
('5.1.2.03.02.0107', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Laboratorium Pertanian', 6, 2021, 864000, 2, 1),
('5.1.2.03.02.0446', 'Belanja Pemeliharaan Alat Keselamatan Kerja-Alat Pelindung-Baju Pengaman', 6, 2021, 1600000, 2, 1),
('5.1.2.03.03.0002', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gudang', 6, 2021, 0, 2, 1),
('5.1.2.03.03.0029', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Peternakan/Perikanan', 6, 2021, 191092500, 2, 1),
('5.1.2.03.05.0046', 'Belanja Pemeliharaan Hewan-Hewan Lainnya-Hewan Lainnya', 6, 2021, 11205000, 2, 1),
('5.2.2.01.01.0001', 'Belanja Modal Tractor', 6, 2021, 35000000, 2, 1),
('5.2.2.02.01.0005', 'Belanja Modal Kendaraan Bermotor Beroda Tiga', 6, 2021, 150000000, 2, 1),
('5.2.2.04.01.0009', 'Belanja Modal Alat-Alat Peternakan', 6, 2021, 76050000, 2, 1),
('5.2.3.01.01.0008', 'Belanja Modal Bangunan Gedung Tempat Ibadah', 6, 2021, 200000000, 2, 1),
('5.2.3.01.01.0013', 'Belanja Modal Bangunan Gedung untuk Pos Jaga', 6, 2021, 55875000, 2, 1),
('5.2.3.01.01.0029', 'Belanja Modal Bangunan Peternakan/Perikanan', 6, 2021, 973995000, 2, 1),
('5.2.4.02.01.0008', 'Belanja Modal Bangunan Air Irigasi Lainnya', 6, 2021, 728000000, 2, 1),
('5.2.4.03.02.0003', 'Belanja Modal Instalasi Air Buangan Pertanian', 6, 2021, 300000000, 2, 1),
('5.2.5.03', 'Belanja Modal Hewan', 4, 2021, 2525000000, 2, 1),
('5.2.5.03.02', 'Belanja Modal Ternak', 5, 2021, 2525000000, 2, 1),
('5.2.5.03.02.0004', 'Belanja Modal Ternak Lainnya', 6, 2021, 2877500000, 2, 1),
('5.1.2.01.01.0005', 'Belanja Bahan-Bahan Baku', 6, 2021, 26279880, 2, 1),
('5.1.2.02.01.0069', 'Belanja Pengolahan Air Limbah', 6, 2021, 1350000000, 2, 1),
('5.1.2.02.04.0003', 'Belanja Sewa Excavator', 6, 2021, 48328291, 2, 1),
('5.1.2.02.04.0018', 'Belanja Sewa Alat Besar Apung Lainnya', 6, 2021, 20000000, 2, 1),
('5.1.2.02.04.0050', 'Belanja Sewa Alat Angkutan Apung Bermotor Khusus', 6, 2021, 8000000, 2, 1),
('5.1.2.02.09.0011', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Survei', 6, 2021, 1490000000, 2, 1),
('5.1.2.02.10', 'Belanja Jasa Ketersediaan Layanan (Availibility Payment)', 5, 2021, 21450000, 2, 1),
('5.1.2.02.10.0010', 'Belanja Jasa Ketersediaan Layanan (Availibility Payment) Infrastruktur Minyak dan Gas Bumi dan Energi Terbarukan', 6, 2021, 21450000, 2, 1),
('5.1.2.03.02.0113', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Kantor-Mesin Ketik', 6, 2021, 2000000, 2, 1),
('5.2.2.05.01.0001', 'Belanja Modal Mesin Ketik', 6, 2021, 2500000, 2, 1),
('5.2.3.01.01.0032', 'Belanja Modal Bangunan Fasilitas Umum', 6, 2021, 1456231000, 2, 1),
('5.2.5.01.03', 'Belanja Modal Kartografi, Naskah, dan Lukisan', 5, 2021, 204000000, 2, 1),
('5.2.5.01.03.0003', 'Belanja Modal Lukisan dan Ukiran', 6, 2021, 0, 2, 1),
('5.1.1.02.02', 'Tambahan Penghasilan berdasarkan Tempat Bertugas ASN', 5, 2021, 0, 2, 1),
('5.1.1.02.02.0001', 'Tambahan Penghasilan berdasarkan Tempat Bertugas PNS', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0202', 'Belanja Sewa Peralatan Komunikasi untuk Dokumentasi', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0133', 'Belanja Sewa Peralatan Studio Video dan Film', 6, 2021, 112000000, 2, 1),
('5.1.2.02.07', 'Belanja Sewa Aset Tetap Lainnya', 5, 2021, 24000000, 2, 1),
('5.1.2.02.07.0057', 'Belanja Sewa Tanaman', 6, 2021, 0, 2, 1),
('5.1.5.05.02.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 6, 2021, 0, 2, 1),
('5.1.5.05.01.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 460696000, 2, 1),
('4.1.2.03.03', 'Retribusi Izin Trayek untuk Menyediakan Pelayanan Angkutan Umum', 5, 2021, 300000000, 1, 1),
('4.1.2.03.03.0001', 'Retribusi Izin Trayek untuk Menyediakan Pelayanan Angkutan Umum', 6, 2021, 300000000, 1, 1),
('5.1.2.02.09.0012', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Studi Penelitian dan Bantuan Teknik', 6, 2021, 700000000, 2, 1),
('5.2.2.15.01', 'Belanja Modal Alat Deteksi', 5, 2021, 2564991630, 2, 1),
('5.2.2.15.01.0003', 'Belanja Modal Alat Deteksi Lainnya', 6, 2021, 2372041630, 2, 1),
('5.2.3.01.01.0018', 'Belanja Modal Bangunan Gedung Terminal/Pelabuhan/Bandara', 6, 2021, 0, 2, 1),
('5.2.5.01.05', 'Belanja Modal Karya Grafika (Graphic Material)', 5, 2021, 5000000, 2, 1),
('5.2.5.01.05.0002', 'Belanja Modal Karya Grafika (Graphic Material) Lainnya', 6, 2021, 5000000, 2, 1),
('5.1.2.02.01.0039', 'Belanja Jasa Tenaga Informasi dan Teknologi', 6, 2021, 114000000, 2, 1),
('5.1.2.02.04.0355', 'Belanja Sewa Peralatan Umum', 6, 2021, 20000000, 2, 1),
('5.1.2.02.07.0028', 'Belanja Sewa Alat Musik', 6, 2021, 163900000, 2, 1),
('5.1.2.03.03.0033', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Parkir', 6, 2021, 161000000, 2, 1),
('5.1.5.01.02', 'Belanja Hibah Barang kepada Pemerintah Pusat', 5, 2021, 2000000000, 2, 1),
('5.1.5.01.02.0001', 'Belanja Hibah Barang kepada Pemerintah Pusat', 6, 2021, 2056600000, 2, 1),
('5.2.2.14', 'Belanja Modal Alat Bantu Eksplorasi', 4, 2021, 200000000, 2, 1),
('5.2.2.14.01', 'Belanja Modal Alat Bantu Eksplorasi', 5, 2021, 200000000, 2, 1),
('5.2.2.14.01.0002', 'Belanja Modal Elektrik', 6, 2021, 0, 2, 1),
('5.1.2.01.01.0046', 'Belanja Persediaan Penelitian-Persediaan Penelitian Biologi', 6, 2021, 132600000, 2, 1),
('5.1.2.02.05.0002', 'Belanja Sewa Bangunan Gudang', 6, 2021, 30000000, 2, 1),
('5.1.2.02.08.0013', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa Lainnya', 6, 2021, 9550000, 2, 1),
('5.1.2.03.02.0104', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pemeliharaan Tanaman/ Ikan/Ternak', 6, 2021, 30000000, 2, 1),
('5.1.2.03.03.0036', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Taman', 6, 2021, 200000000, 2, 1),
('5.1.2.03.03.0041', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal- Mess/Wisma/Bungalow/Tempat Peristirahatan', 6, 2021, 500000000, 2, 1),
('5.1.2.03.04.0010', 'Belanja Pemeliharaan Jalan dan Jembatan- Jalan-Jalan Lainnya', 6, 2021, 200000000, 2, 1),
('5.2.2.15.02', 'Belanja Modal Alat Pelindung', 5, 2021, 9910200, 2, 1),
('5.2.2.15.02.0002', 'Belanja Modal Masker', 6, 2021, 0, 2, 1),
('5.2.2.15.02.0006', 'Belanja Modal Alat Pelindung Lainnya', 6, 2021, 0, 2, 1),
('5.1.2.01.01.0028', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Persediaan Dokumen/Administrasi Tender', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0364', 'Belanja Sewa Photo and Film Equipment', 6, 2021, 0, 2, 1),
('5.2.2.11', 'Belanja Modal Alat Eksplorasi', 4, 2021, 250000000, 2, 1),
('5.2.2.11.02', 'Belanja Modal Alat Eksplorasi Geofisika', 5, 2021, 250000000, 2, 1),
('5.2.2.11.02.0002', 'Belanja Modal Elektronik/Electric', 6, 2021, 144450000, 2, 1),
('5.2.5.08.01.0006', 'Belanja Modal Kajian', 6, 2021, 0, 2, 1),
('4.1.2.02.09', 'Retribusi Tempat Rekreasi dan Olahraga', 5, 2021, 109752000, 1, 1),
('4.1.2.02.09.0001', 'Retribusi Pelayanan Tempat Rekreasi dan Olahraga', 6, 2021, 48620000, 1, 1),
('5.1.2.02.01.0054', 'Belanja Jasa Jalan/Tol', 6, 2021, 15000000, 2, 1),
('5.1.2.02.04.0038', 'Belanja Sewa Kendaraan Bermotor Beroda Dua', 6, 2021, 0, 2, 1),
('5.1.2.02.04.0117', 'Belanja Sewa Alat Kantor Lainnya', 6, 2021, 13500000, 2, 1),
('5.1.2.02.04.0406', 'Belanja Sewa Komputer Unit Lainnya', 6, 2021, 1806000, 2, 1),
('5.1.2.05.01.0002', 'Belanja Penghargaan atas Suatu Prestasi', 6, 2021, 0, 2, 1),
('5.1.2.05.01.0004', 'Belanja Penanganan Dampak Sosial Kemasyarakatan', 6, 2021, 8140326052, 2, 1),
('5.2.2.05.03.0006', 'Belanja Modal Kursi Tamu di Ruangan Pejabat', 6, 2021, 46550000, 2, 1),
('5.2.2.05.03.0007', 'Belanja Modal Lemari dan Arsip Pejabat', 6, 2021, 17000000, 2, 1),
('5.2.2.19', 'Belanja Modal Peralatan Olahraga', 4, 2021, 5206892250, 2, 1),
('5.2.2.19.01', 'Belanja Modal Peralatan Olahraga', 5, 2021, 5206892250, 2, 1),
('5.2.2.19.01.0006', 'Belanja Modal Peralatan Olahraga Lainnya', 6, 2021, 10000000, 2, 1);
INSERT INTO `data_uraian_kegiatan_pemko` (`kode_rekening`, `uraian`, `level`, `tahun`, `anggaran`, `jenis`, `st_anggaran`) VALUES
('5.2.3.01.01.0011', 'Belanja Modal Bangunan Gedung Tempat Olahraga', 6, 2021, 15409810870, 2, 1),
('5.2.5.01.01.0007', 'Belanja Modal Buku Ilmu Pengetahuan Praktis', 6, 2021, 7599810, 2, 1),
('5.1.2.02.01.0006', 'Honorarium Penyuluhan atau Pendampingan', 6, 2021, 15400000, 2, 1),
('5.1.2.02.01.0036', 'Belanja Jasa Audit/Surveillance ISO', 6, 2021, 115800000, 2, 1),
('5.1.2.02.01.0037', 'Belanja Jasa Juri Perlombaan/Pertandingan', 6, 2021, 21000000, 2, 1),
('5.1.2.02.01.0041', 'Belanja Jasa Pemasangan Instalasi Telepon, Air, dan Listrik', 6, 2021, 33000000, 2, 1),
('5.1.2.02.04.0012', 'Belanja Sewa Alat Besar Darat Lainnya', 6, 2021, 43000000, 2, 1),
('5.1.2.02.04.0043', 'Belanja Sewa Alat Angkutan Darat Bermotor Lainnya', 6, 2021, 12000000, 2, 1),
('5.1.2.02.04.0049', 'Belanja Sewa Alat Angkutan Apung Bermotor untuk Penumpang', 6, 2021, 8000000, 2, 1),
('5.1.2.02.09.0015', 'Belanja Jasa Konsultansi Bidang Kepariwisataan-Jasa Konsultansi Destinasi Pariwisata', 6, 2021, 94750000, 2, 1),
('5.2.1.01.03.0011', 'Belanja Modal Tanah untuk Bangunan Bersejarah', 6, 2021, 3196000000, 2, 1),
('5.1.2.02.01.0024', 'Belanja Jasa Tenaga Arsip dan Perpustakaan', 6, 2021, 31750000, 2, 1),
('5.1.2.02.01.0045', 'Belanja Jasa Pelayanan Kearsipan', 6, 2021, 3600000, 2, 1),
('5.2.5.01.01.0004', 'Belanja Modal Buku Ilmu Sosial', 6, 2021, 48000000, 2, 1),
('5.2.5.01.01.0005', 'Belanja Modal Buku Ilmu Bahasa', 6, 2021, 198000000, 2, 1),
('4.1.2.02.11', 'Retribusi Penjualan Produksi Usaha Daerah', 5, 2021, 915000000, 1, 1),
('4.1.2.02.11.0003', 'Retribusi Penjualan Produksi hasil Usaha Daerah berupa Bibit atau Benih Ikan', 6, 2021, 500000000, 1, 1),
('4.1.2.02.11.0004', 'Retribusi Penjualan Produksi hasil Usaha Daerah selain Bibit atau Benih Tanaman, Ternak, dan Ikan', 6, 2021, 1000000000, 1, 1),
('4.1.2.03.04', 'Retribusi Izin Usaha Perikanan', 5, 2021, 2246000000, 1, 1),
('4.1.2.03.04.0001', 'Retribusi Pemberian Izin Kegiatan Usaha Penangkapan Ikan', 6, 2021, 2550000000, 1, 1),
('5.1.2.01.01.0011', 'Belanja Bahan-Bahan/Bibit Ternak/Bibit Ikan', 6, 2021, 39200000, 2, 1),
('5.1.2.02.01.0042', 'Belanja Jasa Pelaksanaan Transaksi Keuangan', 6, 2021, 5460000000, 2, 1),
('5.1.2.02.01.0057', 'Belanja Jasa Operator Kapal', 6, 2021, 120000000, 2, 1),
('5.1.2.03.02.0052', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Apung Bermotor-Alat Angkutan Apung Bermotor Lainnya', 6, 2021, 15000000, 2, 1),
('5.1.5.05.04', 'Belanja Hibah kepada Koperasi', 5, 2021, 650000000, 2, 1),
('5.1.5.05.04.0002', 'Belanja Hibah Barang kepada Koperasi', 6, 2021, 604773500, 2, 1),
('5.2.2.01.02', 'Belanja Modal Alat Besar Apung', 5, 2021, 9328095293, 2, 1),
('5.2.2.01.02.0004', 'Belanja Modal Kapal Tarik', 6, 2021, 20000000, 2, 1),
('5.2.2.01.02.0006', 'Belanja Modal Alat Besar Apung Lainnya', 6, 2021, 9247452500, 2, 1),
('5.2.2.01.03.0013', 'Belanja Modal Peralatan Selam', 6, 2021, 536153751, 2, 1),
('5.2.2.01.03.0016', 'Belanja Modal Alat Bantu Lainnya', 6, 2021, 4800000, 2, 1),
('5.2.2.17', 'Belanja Modal Peralatan Proses/Produksi', 4, 2021, 120000000, 2, 1),
('5.2.2.17.01', 'Belanja Modal Unit Peralatan Proses/Produksi', 5, 2021, 120000000, 2, 1),
('4.1.2.02.01.0001', 'Retribusi Penyewaan Tanah dan Bangunan', 6, 2021, 0, 1, 1),
('5.1.2.02.05.0030', 'Belanja Sewa Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 150000000, 2, 1),
('5.1.6.03.02', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Kelompok Masyarakat', 5, 2021, 3786720750, 2, 1),
('5.1.6.03.02.0001', 'Belanja Bantuan Sosial Barang yang direncanakan kepada Kelompok Masyarakat', 6, 2021, 0, 2, 1),
('5.2.3.01.01.0014', 'Belanja Modal Bangunan Gedung Garasi/Pool', 6, 2021, 141230000, 2, 1),
('4.1.2.01.08', 'Retribusi Penggantian Biaya Cetak Peta', 5, 2021, 200000000, 1, 1),
('4.1.2.01.08.0004', 'Retribusi Penyediaan Peta Tematik', 6, 2021, 100000000, 1, 1),
('5.1.2.01.01.0018', 'Belanja Suku Cadang-Suku Cadang Alat Studio dan Komunikasi', 6, 2021, 27387500, 2, 1),
('5.1.2.02.01.0047', 'Belanja Jasa Penyelenggaraan Acara', 6, 2021, 180000000, 2, 1),
('5.1.2.02.08.0031', 'Belanja Jasa Konsultansi Spesialis-Jasa Inspeksi Teknikal', 6, 2021, 0, 2, 1),
('5.1.2.02.09.0006', 'Belanja Jasa Konsultansi Berorientasi Bidang-Keuangan', 6, 2021, 200000000, 2, 1),
('5.2.2.06.01.0001', 'Belanja Modal Peralatan Studio Audio', 6, 2021, 194810000, 2, 1),
('5.1.2.02.04.0405', 'Belanja Sewa Personal Computer', 6, 2021, 4800000, 2, 1),
('4.1.3', 'Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan', 3, 2021, 356265026558, 1, 1),
('4.1.3.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 4, 2021, 526848905, 1, 1),
('4.1.3.01.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 5, 2021, 526848905, 1, 1),
('4.1.3.01.01.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 6, 2021, 526848905, 1, 1),
('4.1.3.02', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD', 4, 2021, 355738177653, 1, 1),
('4.1.3.02.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Lembaga Keuangan)', 5, 2021, 329046680000, 1, 1),
('4.1.3.02.01.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Lembaga Keuangan)', 6, 2021, 329046680000, 1, 1),
('4.1.3.02.02', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Aneka Usaha)', 5, 2021, 1691497653, 1, 1),
('4.1.3.02.02.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Aneka Usaha)', 6, 2021, 1691497653, 1, 1),
('4.1.3.02.03', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Bidang Air Minum)', 5, 2021, 25000000000, 1, 1),
('4.1.3.02.03.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada Perusahaan Milik Daerah/BUMD (Bidang Air Minum)', 6, 2021, 25000000000, 1, 1),
('4.1.4.01', 'Hasil Penjualan BMD yang Tidak Dipisahkan', 4, 2021, 250000000, 1, 1),
('4.1.4.01.03', 'Hasil Penjualan Gedung dan Bangunan', 5, 2021, 50000000, 1, 1),
('4.1.4.01.03.0001', 'Hasil Penjualan Bangunan Gedung', 6, 2021, 50000000, 1, 1),
('4.1.4.01.06', 'Hasil Penjualan Aset Lainnya', 5, 2021, 200000000, 1, 1),
('4.1.4.01.06.0002', 'Hasil Penjualan Aset Lainnya-Aset Lain-Lain', 6, 2021, 5200000000, 1, 1),
('4.1.4.05', 'Jasa Giro', 4, 2021, 30900000000, 1, 1),
('4.1.4.05.01', 'Jasa Giro pada Kas Daerah', 5, 2021, 30900000000, 1, 1),
('4.1.4.05.01.0001', 'Jasa Giro pada Kas Daerah', 6, 2021, 30900000000, 1, 1),
('4.1.4.08', 'Penerimaan atas Tuntutan Ganti Kerugian Keuangan Daerah', 4, 2021, 40000000, 1, 1),
('4.1.4.08.01', 'Tuntutan Ganti Kerugian Daerah terhadap Bendahara', 5, 2021, 0, 1, 1),
('4.1.4.08.01.0001', 'Tuntutan Ganti Kerugian Daerah terhadap Bendahara', 6, 2021, 0, 1, 1),
('4.1.4.08.02', 'Tuntutan Ganti Kerugian Daerah terhadap Pegawai Negeri Bukan Bendahara atau Pejabat Lain', 5, 2021, 40000000, 1, 1),
('4.1.4.08.02.0001', 'Tuntutan Ganti Kerugian Daerah terhadap Pegawai Negeri Bukan Bendahara atau Pejabat Lain', 6, 2021, 40000000, 1, 1),
('4.1.4.11', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 4, 2021, 300000000, 1, 1),
('4.1.4.11.01', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 5, 2021, 300000000, 1, 1),
('4.1.4.11.01.0001', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 6, 2021, 300000000, 1, 1),
('4.1.4.15', 'Pendapatan dari Pengembalian', 4, 2021, 2650600000, 1, 1),
('4.1.4.15.03', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Gaji dan Tunjangan', 5, 2021, 2250600000, 1, 1),
('4.1.4.15.03.0001', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Gaji dan Tunjangan', 6, 2021, 2250600000, 1, 1),
('4.1.4.15.04', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Perjalanan Dinas', 5, 2021, 400000000, 1, 1),
('4.1.4.15.04.0001', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Perjalanan Dinas Dalam Negeri- Perjalanan Dinas Biasa', 6, 2021, 400000000, 1, 1),
('4.2', 'PENDAPATAN TRANSFER', 2, 2021, 7434780086300, 1, 1),
('4.2.1', 'Pendapatan Transfer Pemerintah Pusat', 3, 2021, 7434780086300, 1, 1),
('4.2.1.01', 'Dana Perimbangan', 4, 2021, 7434780086300, 1, 1),
('4.2.1.01.01', 'Dana Transfer Umum-Dana Bagi Hasil (DBH)', 5, 2021, 440441729300, 1, 1),
('4.2.1.01.01.0001', 'DBH Pajak Bumi dan Bangunan', 6, 2021, 91496739000, 1, 1),
('4.2.1.01.01.0002', 'DBH PPh Pasal 21', 6, 2021, 261353359000, 1, 1),
('4.2.1.01.01.0003', 'DBH PPh Pasal 25 dan Pasal 29/WPOPDN', 6, 2021, 28946479000, 1, 1),
('4.2.1.01.01.0004', 'DBH Cukai Hasil Tembakau (CHT)', 6, 2021, 3838485300, 1, 1),
('4.2.1.01.01.0005', 'DBH Sumber Daya Alam (SDA) Minyak Bumi', 6, 2021, 48793000, 1, 1),
('4.2.1.01.01.0006', 'DBH Sumber Daya Alam (SDA) Gas Bumi', 6, 2021, 293132000, 1, 1),
('4.2.1.01.01.0007', 'DBH Sumber Daya Alam (SDA) Pengusahaan Panas Bumi', 6, 2021, 3427158000, 1, 1),
('4.2.1.01.01.0008', 'DBH Sumber Daya Alam (SDA) Mineral dan Batubara-Landrent', 6, 2021, 1672772000, 1, 1),
('4.2.1.01.01.0009', 'Dana Bagi Hasil (DBH) Sumber Daya Alam (SDA) Mineral dan Batubara-Royalty', 6, 2021, 37240967000, 1, 1),
('4.2.1.01.01.0010', 'DBH Sumber Daya Alam (SDA) Kehutanan- Provisi Sumber Daya Hutan (PSDH)', 6, 2021, 3554255000, 1, 1),
('4.2.1.01.01.0012', 'DBH Sumber Daya Alam (SDA) Kehutanan- Dana Reboisasi (DR)', 6, 2021, 8569590000, 1, 1),
('4.2.1.01.02', 'Dana Transfer Umum-Dana Alokasi Umum (DAU)', 5, 2021, 2545202693000, 1, 1),
('4.2.1.01.02.0001', 'DAU', 6, 2021, 2463686589000, 1, 1),
('4.2.1.01.03', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Fisik', 5, 2021, 387905400000, 1, 1),
('4.2.1.01.03.0004', 'DAK Fisik-Bidang Pendidikan-Reguler-SMA', 6, 2021, 194930650000, 1, 1),
('4.2.1.01.03.0005', 'DAK Fisik-Bidang Pendidikan-Reguler-SLB', 6, 2021, 1895050000, 1, 1),
('4.2.1.01.03.0011', 'DAK Fisik-Bidang Pendidikan-Reguler- Perpustakaan Daerah', 6, 2021, 500000000, 1, 1),
('4.2.1.01.03.0013', 'DAK Fisik-Bidang Kesehatan dan KB- Reguler-Pelayanan Kesehatan Dasar', 6, 2021, 1780492000, 1, 1),
('4.2.1.01.03.0014', 'DAK Fisik-Bidang Kesehatan dan KB- Reguler-Pelayanan Kesehatan Rujukan', 6, 2021, 32485929000, 1, 1),
('4.2.1.01.03.0017', 'DAK Fisik-Bidang Kesehatan dan KB- Penugasan-Penguatan Intervensi Stunting', 6, 2021, 19256700000, 1, 1),
('4.2.1.01.03.0031', 'DAK Fisik-Bidang Pertanian-Penugasan-Pembangunan/Renovasi Sarana dan Prasarana Fisik Dasar Pembangunan Pertanian', 6, 2021, 4735000000, 1, 1),
('4.2.1.01.03.0032', 'DAK Fisik-Bidang Kelautan dan Perikanan- Penugasan', 6, 2021, 350000000, 1, 1),
('4.2.1.01.03.0034', 'DAK Fisik-Bidang Jalan-Reguler-Jalan', 6, 2021, 13133236000, 1, 1),
('4.2.1.01.03.0035', 'DAK Fisik-Bidang Jalan-Penugasan-Jalan', 6, 2021, 70294554000, 1, 1),
('4.2.1.01.03.0043', 'DAK Fisik-Bidang Irigasi-Penugasan', 6, 2021, 11999999000, 1, 1),
('4.2.1.01.03.0046', 'DAK Fisik-Bidang Lingkungan Hidup dan Kehutanan-Penugasan-Kehutanan', 6, 2021, 460696000, 1, 1),
('4.2.1.01.04', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Non Fisik', 5, 2021, 4061230264000, 1, 1),
('4.2.1.01.04.0001', 'DAK Non Fisik-BOS Reguler', 6, 2021, 3338953850000, 1, 1),
('4.2.1.01.04.0002', 'DAK Non Fisik-BOS Afirmasi', 6, 2021, 19620000000, 1, 1),
('4.2.1.01.04.0003', 'DAK Non Fisik-BOS Kinerja', 6, 2021, 35280000000, 1, 1),
('4.2.1.01.04.0004', 'DAK Non Fisik-TPG PNSD', 6, 2021, 627207840000, 1, 1),
('4.2.1.01.04.0005', 'DAK Non Fisik-Tamsil Guru PNSD', 6, 2021, 3090000000, 1, 1),
('4.2.1.01.04.0006', 'DAK Non Fisik-TKG PNSD', 6, 2021, 19940535000, 1, 1),
('4.2.1.01.04.0009', 'DAK Non Fisik-BOP Museum dan Taman Budaya-Museum', 6, 2021, 1593200000, 1, 1),
('4.2.1.01.04.0011', 'DAK Non Fisik-BOKKB-BOK', 6, 2021, 9492240000, 1, 1),
('4.2.1.01.04.0014', 'DAK Non Fisik-BOKKB-Jaminan Persalinan', 6, 2021, 558332000, 1, 1),
('4.2.1.01.04.0016', 'DAK Non Fisik-PK2UKM', 6, 2021, 2352080000, 1, 1),
('4.2.1.01.04.0017', 'DAK Non Fisik-Dana Pelayanan Administrasi Kependudukan', 6, 2021, 2279609000, 1, 1),
('4.2.1.01.04.0018', 'DAK Non Fisik-Dana Pelayanan Kepariwisataan', 6, 2021, 862578000, 1, 1),
('4.3', 'LAIN-LAIN PENDAPATAN DAERAH YANG SAH', 2, 2021, 91568000000, 1, 1),
('4.3.1', 'Pendapatan Hibah', 3, 2021, 61068000000, 1, 1),
('4.3.1.01', 'Pendapatan Hibah dari Pemerintah Pusat', 4, 2021, 55000000000, 1, 1),
('4.3.1.01.01', 'Pendapatan Hibah dari Pemerintah Pusat', 5, 2021, 55000000000, 1, 1),
('4.3.1.01.01.0001', 'Pendapatan Hibah dari Pemerintah Pusat', 6, 2021, 53500000000, 1, 1),
('4.3.1.03', 'Pendapatan Hibah dari Kelompok Masyarakat/Perorangan Dalam Negeri', 4, 2021, 500000000, 1, 1),
('4.3.1.03.01', 'Pendapatan Hibah dari Kelompok Masyarakat/Perorangan Dalam Negeri', 5, 2021, 500000000, 1, 1),
('4.3.1.03.01.0001', 'Pendapatan Hibah dari Kelompok Masyarakat Dalam Negeri/Perorangan dalam Negeri', 6, 2021, 500000000, 1, 1),
('4.3.1.04', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri/Luar Negeri', 4, 2021, 5568000000, 1, 1),
('4.3.1.04.01', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri', 5, 2021, 5568000000, 1, 1),
('4.3.1.04.01.0001', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri', 6, 2021, 5568000000, 1, 1),
('4.3.3', 'Lain-lain Pendapatan Sesuai dengan Ketentuan Peraturan Perundang-Undangan', 3, 2021, 30500000000, 1, 1),
('4.3.3.01', 'Lain-lain Pendapatan', 4, 2021, 30500000000, 1, 1),
('4.3.3.01.01', 'Pendapatan Hibah Dana BOS', 5, 2021, 20000000000, 1, 1),
('4.3.3.01.01.0001', 'Pendapatan Hibah Dana BOS', 6, 2021, 20000000000, 1, 1),
('4.3.3.01.02', 'Pendapatan atas Pengembalian Hibah', 5, 2021, 10500000000, 1, 1),
('4.3.3.01.02.0001', 'Pendapatan atas Pengembalian Hibah pada Pemerintah', 6, 2021, 0, 1, 1),
('4.3.3.01.02.0005', 'Pendapatan atas Pengembalian Hibah pada Badan, Lembaga, dan Organisasi Kemasyarakatan yang Berbadan hukum Indonesia', 6, 2021, 10500000000, 1, 1),
('5.1.2.02.04.0116', 'Belanja Sewa Alat Penyimpan Perlengkapan Kantor', 6, 2021, 30000000, 2, 1),
('5.1.2.02.04.0148', 'Belanja Sewa Alat Komunikasi Lainnya', 6, 2021, 45712666, 2, 1),
('5.1.2.02.09.0002', 'Belanja Jasa Konsultansi Berorientasi Bidang-Transportasi', 6, 2021, 350000000, 2, 1),
('5.3', 'BELANJA TIDAK TERDUGA', 2, 2021, 75000000000, 2, 1),
('5.3.1', 'Belanja Tidak Terduga', 3, 2021, 75000000000, 2, 1),
('5.3.1.01', 'Belanja Tidak Terduga', 4, 2021, 75000000000, 2, 1),
('5.3.1.01.01', 'Belanja Tidak Terduga', 5, 2021, 75000000000, 2, 1),
('5.3.1.01.01.0001', 'Belanja Tidak Terduga', 6, 2021, 21058512853, 2, 1),
('5.4', 'BELANJA TRANSFER', 2, 2021, 2337211916455, 2, 1),
('5.4.1', 'Belanja Bagi Hasil', 3, 2021, 2337211916455, 2, 1),
('5.4.1.01', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten/Kota dan Desa', 4, 2021, 2337211916455, 2, 1),
('5.4.1.01.01', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten', 5, 2021, 1293708994588, 2, 1),
('5.4.1.01.01.0001', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten', 6, 2021, 1434444567946, 2, 1),
('5.4.1.01.02', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kota', 5, 2021, 1043502921867, 2, 1),
('5.4.1.01.02.0001', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kota', 6, 2021, 1142502921867, 2, 1),
('6.1', 'PENERIMAAN PEMBIAYAAN', 2, 2021, 439000000000, 3, 1),
('6.1.1', 'Sisa Lebih Perhitungan Anggaran Tahun Sebelumnya', 3, 2021, 439000000000, 3, 1),
('6.1.1.05', 'Penghematan Belanja', 4, 2021, 439000000000, 3, 1),
('6.1.1.05.03', 'Sisa Penggunaan Belanja Tidak Terduga', 5, 2021, 439000000000, 3, 1),
('6.1.1.05.03.0001', 'Sisa Penggunaan Belanja Tidak Terduga', 6, 2021, 476782631305, 3, 1),
('6.2', 'PENGELUARAN PEMBIAYAAN', 2, 2021, 207000000000, 4, 1),
('6.2.2', 'Penyertaan Modal Daerah', 3, 2021, 207000000000, 4, 1),
('6.2.2.02', 'Penyertaan Modal Daerah pada Badan Usaha Milik Daerah (BUMD)', 4, 2021, 207000000000, 4, 1),
('6.2.2.02.01', 'Penyertaan Modal Daerah pada BUMD', 5, 2021, 207000000000, 4, 1),
('6.2.2.02.01.0001', 'Penyertaan Modal Daerah pada BUMD', 6, 2021, 210500000000, 4, 1),
('5.1.2.02.01.0009', 'Honorarium Penyelenggara Ujian', 6, 2021, 22640000, 2, 1),
('5.2.5.01.02', 'Belanja Modal Bahan Perpustakaan Terekam dan Bentuk Mikro', 5, 2021, 3500000000, 2, 1),
('5.2.5.01.02.0001', 'Belanja Modal Audio Visual', 6, 2021, 4428468000, 2, 1),
('5.1.1.03.01', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Daerah', 5, 2021, 119583205982, 2, 1),
('5.1.1.03.01.0001', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Kendaraan Bermotor', 6, 2021, 41118956815, 2, 1),
('5.1.2.02.04.0404', 'Belanja Sewa Komputer Jaringan', 6, 2021, 70000000, 2, 1),
('5.1.2.02.08.0029', 'Belanja Jasa Konsultansi Spesialis-Jasa Pengujian dan Analisa Parameter Fisikal', 6, 2021, 1500000, 2, 1),
('5.1.2.03.02.0112', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pengolahan Lainnya', 6, 2021, 15000000, 2, 1),
('5.1.1.04', 'Belanja Gaji dan Tunjangan DPRD', 4, 2021, 123747510610, 2, 1),
('5.1.1.04.01', 'Belanja Uang Representasi DPRD', 5, 2021, 3222939000, 2, 1),
('5.1.1.04.01.0001', 'Belanja Uang Representasi DPRD', 6, 2021, 3222939000, 2, 1),
('5.1.1.04.02', 'Belanja Tunjangan Keluarga DPRD', 5, 2021, 332867000, 2, 1),
('5.1.1.04.02.0001', 'Belanja Tunjangan Keluarga DPRD', 6, 2021, 368501000, 2, 1),
('5.1.1.04.03', 'Belanja Tunjangan Beras DPRD', 5, 2021, 273904000, 2, 1),
('5.1.1.04.03.0001', 'Belanja Tunjangan Beras DPRD', 6, 2021, 281185000, 2, 1),
('5.1.1.04.04', 'Belanja Uang Paket DPRD', 5, 2021, 396113000, 2, 1),
('5.1.1.04.04.0001', 'Belanja Uang Paket DPRD', 6, 2021, 396113000, 2, 1),
('5.1.1.04.05', 'Belanja Tunjangan Jabatan DPRD', 5, 2021, 4640215000, 2, 1),
('5.1.1.04.05.0001', 'Belanja Tunjangan Jabatan DPRD', 6, 2021, 4640215000, 2, 1),
('5.1.1.04.06', 'Belanja Tunjangan Alat Kelengkapan DPRD', 5, 2021, 671243000, 2, 1),
('5.1.1.04.06.0001', 'Belanja Tunjangan Alat Kelengkapan DPRD', 6, 2021, 671243000, 2, 1),
('5.1.1.04.07', 'Belanja Tunjangan Alat Kelengkapan Lainnya DPRD', 5, 2021, 95306000, 2, 1),
('5.1.1.04.07.0001', 'Belanja Tunjangan Alat Kelengkapan Lainnya DPRD', 6, 2021, 95306000, 2, 1),
('5.1.1.04.08', 'Belanja Tunjangan Komunikasi Intensif Pimpinan dan Anggota DPRD', 5, 2021, 25200000000, 2, 1),
('5.1.1.04.08.0001', 'Belanja Tunjangan Komunikasi Intensif Pimpinan dan Anggota DPRD', 6, 2021, 25200000000, 2, 1),
('5.1.1.04.09', 'Belanja Tunjangan Reses DPRD', 5, 2021, 6300000000, 2, 1),
('5.1.1.04.09.0001', 'Belanja Tunjangan Reses DPRD', 6, 2021, 6300000000, 2, 1),
('5.1.1.04.10', 'Belanja Pembebanan PPh kepada Pimpinan dan Anggota DPRD', 5, 2021, 201530000, 2, 1),
('5.1.1.04.10.0001', 'Belanja Pembebanan PPh kepada Pimpinan dan Anggota DPRD', 6, 2021, 158615000, 2, 1),
('5.1.1.04.12', 'Belanja Tunjangan Kesejahteraan Pimpinan dan Anggota DPRD', 5, 2021, 53929185000, 2, 1),
('5.1.1.04.12.0001', 'Belanja Iuran Jaminan Kesehatan bagi DPRD', 6, 2021, 3588853000, 2, 1),
('5.1.1.04.12.0002', 'Belanja Jaminan Kecelakaan Kerja DPRD', 6, 2021, 6583000, 2, 1),
('5.1.1.04.12.0003', 'Belanja Jaminan Kematian DPRD', 6, 2021, 19749000, 2, 1),
('5.1.1.04.12.0004', 'Belanja Tunjangan Perumahan DPRD', 6, 2021, 50314000000, 2, 1),
('5.1.1.04.13', 'Belanja Tunjangan Transportasi DPRD', 5, 2021, 28356083610, 2, 1),
('5.1.1.04.13.0001', 'Belanja Tunjangan Transportasi DPRD', 6, 2021, 28356083610, 2, 1),
('5.1.1.04.14', 'Belanja Uang Jasa Pengabdian DPRD', 5, 2021, 128125000, 2, 1),
('5.1.1.04.14.0001', 'Belanja Uang Jasa Pengabdian DPRD', 6, 2021, 128125000, 2, 1),
('5.1.1.06', 'Belanja Penerimaan Lainnya Pimpinan DPRD serta KDH/WKDH', 4, 2021, 8030737000, 2, 1),
('5.1.1.06.01', 'Belanja Dana Operasional Pimpinan DPRD', 5, 2021, 676800000, 2, 1),
('5.1.1.06.01.0001', 'Belanja Dana Operasional Pimpinan DPRD', 6, 2021, 676800000, 2, 1),
('5.1.2.02.01.0066', 'Belanja Registrasi/Keanggotaan', 6, 2021, 76000000, 2, 1),
('5.1.2.02.08.0007', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Konstruksi Pondasi serta Struktur Bangunan', 6, 2021, 36210000, 2, 1),
('5.1.2.02.08.0034', 'Belanja Jasa Konsultansi Lainnya-Jasa Manajemen Proyek Terkait Konstruksi Bangunan', 6, 2021, 15490000, 2, 1),
('5.2.4.01.02.0006', 'Belanja Modal Jembatan pada Jalan Tol', 6, 2021, 0, 2, 1),
('5.2.2.02.01.0001', 'Belanja Modal Kendaraan Dinas Bermotor Perorangan', 6, 2021, 0, 2, 1),
('4.1.1', 'Pajak Daerah', 3, 2021, 5438098178279, 1, 1),
('4.1.1.01', 'Pajak Kendaraan Bermotor (PKB)', 4, 2021, 2174033550464, 1, 1),
('4.1.1.01.01', 'PKB-Mobil Penumpang-Sedan', 5, 2021, 30427047373, 1, 1),
('4.1.1.01.01.0001', 'PKB-Mobil Penumpang-Sedan-Pribadi', 6, 2021, 32938191268, 1, 1),
('4.1.1.01.01.0002', 'PKB-Mobil Penumpang-Sedan-Umum', 6, 2021, 52690074, 1, 1),
('4.1.1.01.01.0004', 'PKB-Mobil Penumpang-Sedan-Pemerintah Daerah', 6, 2021, 1495489974, 1, 1),
('4.1.1.01.02', 'PKB-Mobil Penumpang-Jeep', 5, 2021, 254938366544, 1, 1),
('4.1.1.01.02.0001', 'PKB-Mobil Penumpang-Jeep-Pribadi', 6, 2021, 284462179034, 1, 1),
('4.1.1.01.02.0004', 'PKB-Mobil Penumpang-Jeep-Pemerintah Daerah', 6, 2021, 4487946953, 1, 1),
('4.1.1.01.03', 'PKB-Mobil Penumpang-Minibus', 5, 2021, 1017916559184, 1, 1),
('4.1.1.01.03.0001', 'PKB-Mobil Penumpang-Minibus-Pribadi', 6, 2021, 1136537356089, 1, 1),
('4.1.1.01.03.0002', 'PKB-Mobil Penumpang-Minibus-Umum', 6, 2021, 2732571732, 1, 1),
('4.1.1.01.03.0004', 'PKB-Mobil Penumpang-Minibus-Pemerintah Daerah', 6, 2021, 14448604258, 1, 1),
('4.1.1.01.04', 'PKB-Mobil Bus-Microbus', 5, 2021, 8665603809, 1, 1),
('4.1.1.01.04.0001', 'PKB-Mobil Bus-Microbus-Pribadi', 6, 2021, 6298113815, 1, 1),
('4.1.1.01.04.0002', 'PKB-Mobil Bus-Microbus-Umum', 6, 2021, 288142357, 1, 1),
('4.1.1.01.04.0004', 'PKB-Mobil Bus-Microbus-Pemerintah Daerah', 6, 2021, 909983371, 1, 1),
('4.1.1.01.05', 'PKB-Mobil Bus-Bus', 5, 2021, 6654543081, 1, 1),
('4.1.1.01.05.0001', 'PKB-Mobil Bus-Bus-Pribadi', 6, 2021, 3218450071, 1, 1),
('4.1.1.01.05.0002', 'PKB-Mobil Bus-Bus-Umum', 6, 2021, 2219395524, 1, 1),
('4.1.1.01.05.0004', 'PKB-Mobil Bus-Bus-Pemerintah Daerah', 6, 2021, 318712180, 1, 1),
('4.1.1.01.06', 'PKB-Mobil Barang/Beban-Pick Up', 5, 2021, 182073082642, 1, 1),
('4.1.1.01.06.0001', 'PKB-Mobil Barang/Beban-Pick Up-Pribadi', 6, 2021, 161281690854, 1, 1),
('4.1.1.01.06.0002', 'PKB-Mobil Barang/Beban-Pick Up-Umum', 6, 2021, 162434393, 1, 1),
('4.1.1.01.06.0004', 'PKB-Mobil Barang/Beban-Pick Up-Pemerintah Daerah', 6, 2021, 4569970265, 1, 1),
('4.1.1.01.07', 'PKB-Mobil Barang/Beban-Light Truck', 5, 2021, 3780850825, 1, 1),
('4.1.1.01.07.0001', 'PKB-Mobil Barang/Beban-Light Truck-Pribadi', 6, 2021, 2944372688, 1, 1),
('4.1.1.01.07.0002', 'PKB-Mobil Barang/Beban-Light Truck-Umum', 6, 2021, 409420575, 1, 1),
('4.1.1.01.07.0004', 'PKB-Mobil Barang/Beban-Light Truck- Pemerintah Daerah', 6, 2021, 93583587, 1, 1),
('4.1.1.01.08', 'PKB-Mobil Barang/Beban-Truck', 5, 2021, 212034850798, 1, 1),
('4.1.1.01.08.0001', 'PKB-Mobil Barang/Beban-Truck-Pribadi', 6, 2021, 77124207818, 1, 1),
('4.1.1.01.08.0002', 'PKB-Mobil Barang/Beban-Truck-Umum', 6, 2021, 112369970324, 1, 1),
('4.1.1.01.08.0004', 'PKB-Mobil Barang/Beban-Truck-Pemerintah Daerah', 6, 2021, 3839034330, 1, 1),
('4.1.1.01.10', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua', 5, 2021, 456231096996, 1, 1),
('4.1.1.01.10.0001', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Pribadi', 6, 2021, 437341214161, 1, 1),
('4.1.1.01.10.0002', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Umum', 6, 2021, 3353538, 1, 1),
('4.1.1.01.10.0004', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Pemerintah Daerah', 6, 2021, 2020458153, 1, 1),
('4.1.1.01.11', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga', 5, 2021, 771993681, 1, 1),
('4.1.1.01.11.0001', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Pribadi', 6, 2021, 458627030, 1, 1),
('4.1.1.01.11.0002', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Umum', 6, 2021, 103261025, 1, 1),
('4.1.1.01.11.0004', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Pemerintah Daerah', 6, 2021, 181566361, 1, 1),
('4.1.1.01.13', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar', 5, 2021, 539555531, 1, 1),
('4.1.1.01.13.0001', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar-Pribadi', 6, 2021, 259363459, 1, 1),
('4.1.1.01.13.0002', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar-Umum', 6, 2021, 35040479, 1, 1),
('4.1.1.02', 'Bea Balik Nama Kendaraan Bermotor (BBNKB)', 4, 2021, 1197728286623, 1, 1),
('4.1.1.02.01', 'BBNKB-Mobil Penumpang-Sedan', 5, 2021, 11294094352, 1, 1),
('4.1.1.02.01.0001', 'BBNKB-Mobil Penumpang-Sedan', 6, 2021, 16590388973, 1, 1),
('4.1.1.02.02', 'BBNKB-Mobil Penumpang-Jeep', 5, 2021, 94629555420, 1, 1),
('4.1.1.02.02.0001', 'BBNKB-Mobil Penumpang-Jeep', 6, 2021, 139005491166, 1, 1),
('4.1.1.02.03', 'BBNKB-Mobil Penumpang-Minibus', 5, 2021, 377836387501, 1, 1),
('4.1.1.02.03.0001', 'BBNKB-Mobil Penumpang-Minibus', 6, 2021, 555020388626, 1, 1),
('4.1.1.02.04', 'BBNKB-Mobil Bus-Microbus', 5, 2021, 6052893968, 1, 1),
('4.1.1.02.04.0001', 'BBNKB-Mobil Bus-Microbus', 6, 2021, 5138556711, 1, 1),
('4.1.1.02.05', 'BBNKB-Mobil Bus-Bus', 5, 2021, 4648175080, 1, 1),
('4.1.1.02.05.0001', 'BBNKB-Mobil Bus-Bus', 6, 2021, 3946031663, 1, 1),
('4.1.1.02.06', 'BBNKB-Mobil Barang/Beban-Pick Up', 5, 2021, 100083442415, 1, 1),
('4.1.1.02.06.0001', 'BBNKB-Mobil Barang/Beban-Pick Up', 6, 2021, 64129908262, 1, 1),
('4.1.1.02.07', 'BBNKB-Mobil Barang/Beban-Light Truck', 5, 2021, 2078289445, 1, 1),
('4.1.1.02.07.0001', 'BBNKB-Mobil Barang/Beban-Light Truck', 6, 2021, 1331693917, 1, 1),
('4.1.1.02.08', 'BBNKB-Mobil Barang/Beban-Truck', 5, 2021, 116553075676, 1, 1),
('4.1.1.02.08.0001', 'BBNKB-Mobil Barang/Beban-Truck', 6, 2021, 74683063156, 1, 1),
('4.1.1.02.10', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Dua', 5, 2021, 483709592581, 1, 1),
('4.1.1.02.10.0001', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Dua', 6, 2021, 378092168889, 1, 1),
('4.1.1.02.11', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Tiga', 5, 2021, 818490347, 1, 1),
('4.1.1.02.11.0001', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Tiga', 6, 2021, 639773937, 1, 1),
('4.1.1.02.13', 'BBNKB-Kendaraan Khusus Alat Berat', 5, 2021, 24289838, 1, 1),
('4.1.1.02.13.0001', 'BBNKB-Kendaraan Khusus Alat Berat', 6, 2021, 851458, 1, 1),
('4.1.1.03', 'Pajak Bahan Bakar Kendaraan Bermotor (PBBKB)', 4, 2021, 1036674544484, 1, 1),
('4.1.1.03.01', 'PBBKB-Bahan Bakar Bensin', 5, 2021, 500361673351, 1, 1),
('4.1.1.03.01.0001', 'PBBKB Bahan Bakar Bensin', 6, 2021, 500361673351, 1, 1),
('4.1.1.03.02', 'PBBKB-Bahan Bakar Solar', 5, 2021, 309888447109, 1, 1),
('4.1.1.03.02.0001', 'PBBKB Bahan Bakar Solar', 6, 2021, 309888447109, 1, 1),
('4.1.1.03.04', 'PBBKB-Bahan Bakar Lainnya', 5, 2021, 226424424024, 1, 1),
('4.1.1.03.04.0001', 'PBBKB Bahan Bakar Lainnya', 6, 2021, 226424424024, 1, 1),
('4.1.1.04', 'Pajak Air Permukaan', 4, 2021, 76489854175, 1, 1),
('4.1.1.04.01', 'Pajak Air Permukaan', 5, 2021, 76489854175, 1, 1),
('4.1.1.04.01.0001', 'Pajak Air Permukaan', 6, 2021, 76489854175, 1, 1),
('4.1.1.05', 'Pajak Rokok', 4, 2021, 953171942533, 1, 1),
('4.1.1.05.01', 'Pajak Rokok', 5, 2021, 953171942533, 1, 1),
('4.1.1.05.01.0001', 'Pajak Rokok', 6, 2021, 1060735475689, 1, 1),
('4.1.4.12', 'Pendapatan Denda Pajak Daerah', 4, 2021, 69677525781, 1, 1),
('4.1.4.12.01', 'Pendapatan Denda Pajak Kendaraan Bermotor (PKB)', 5, 2021, 67197481083, 1, 1),
('4.1.4.12.01.0001', 'Pendapatan Denda PKB-Mobil Penumpang- Sedan', 6, 2021, 880457284, 1, 1),
('4.1.4.12.01.0002', 'Pendapatan Denda PKB-Mobil Penumpang- Jeep', 6, 2021, 8091766171, 1, 1),
('4.1.4.12.01.0003', 'Pendapatan Denda PKB-Mobil Penumpang- Minibus', 6, 2021, 30884510721, 1, 1),
('4.1.4.12.01.0004', 'Pendapatan Denda PKB-Mobil Bus-Microbus', 6, 2021, 250753677, 1, 1),
('4.1.4.12.01.0005', 'Pendapatan Denda PKB-Mobil Bus-Bus', 6, 2021, 192560285, 1, 1),
('4.1.4.12.01.0006', 'Pendapatan Denda PKB-Mobil Barang/Beban-Pick Up', 6, 2021, 5268587843, 1, 1),
('4.1.4.12.01.0007', 'Pendapatan Denda PKB-Mobil Barang/Beban-Light Truck', 6, 2021, 109405215, 1, 1),
('4.1.4.12.01.0008', 'Pendapatan Denda PKB-Mobil Barang/Beban-Truck', 6, 2021, 6850281498, 1, 1),
('4.1.4.12.01.0010', 'Pendapatan Denda PKB-Sepeda Motor- Sepeda Motor Roda Dua', 6, 2021, 14631206528, 1, 1),
('4.1.4.12.01.0011', 'Pendapatan Denda PKB-Sepeda Motor- Sepeda Motor Roda Tiga', 6, 2021, 22338923, 1, 1),
('4.1.4.12.01.0013', 'Pendapatan Denda PKB-Kendaraan Khusus Alat Berat/Alat Besar', 6, 2021, 15612938, 1, 1),
('4.1.4.12.02', 'Pendapatan Denda Bea Balik Nama Kendaraan Bermotor (BBNKB)', 5, 2021, 2470044698, 1, 1),
('4.1.4.12.02.0001', 'Pendapatan Denda BBNKB-Mobil Penumpang-Sedan', 6, 2021, 23291525, 1, 1),
('4.1.4.12.02.0002', 'Pendapatan Denda BBNKB-Mobil Penumpang-Jeep', 6, 2021, 195152134, 1, 1),
('4.1.4.12.02.0003', 'Pendapatan Denda BBNKB-Mobil Penumpang-Minibus', 6, 2021, 779202408, 1, 1),
('4.1.4.12.02.0004', 'Pendapatan Denda BBNKB-Mobil Bus- Microbus', 6, 2021, 12482730, 1, 1),
('4.1.4.12.02.0005', 'Pendapatan Denda BBNKB-Mobil Bus-Bus', 6, 2021, 9585814, 1, 1),
('4.1.4.12.02.0006', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Pick Up', 6, 2021, 206399547, 1, 1),
('4.1.4.12.02.0007', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Light Truck', 6, 2021, 4286004, 1, 1),
('4.1.4.12.02.0008', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Truck', 6, 2021, 240364455, 1, 1),
('4.1.4.12.02.0010', 'Pendapatan Denda BBNKB-Sepeda Motor- Sepeda Motor Roda Dua', 6, 2021, 997542037, 1, 1),
('4.1.4.12.02.0011', 'Pendapatan Denda BBNKB-Sepeda Motor- Sepeda Motor Roda Tiga', 6, 2021, 1687952, 1, 1),
('4.1.4.12.02.0013', 'Pendapatan Denda BBNKB-Kendaraan Khusus Alat Berat', 6, 2021, 50092, 1, 1),
('4.1.4.12.04', 'Pendapatan Denda Pajak Air Permukaan', 5, 2021, 10000000, 1, 1),
('4.1.4.12.04.0001', 'Pendapatan Denda Pajak Air Permukaan', 6, 2021, 10000000, 1, 1),
('5.1.1.03.01.0002', 'Belanja Insentif bagi ASN atas Pemungutan Bea Balik Nama Kendaraan Bermotor', 6, 2021, 35717946693, 2, 1),
('5.1.1.03.01.0003', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 20492840587, 2, 1),
('5.1.1.03.01.0004', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Air Permukaan', 6, 2021, 2426329265, 2, 1),
('5.1.1.05', 'Belanja Gaji dan Tunjangan KDH/WKDH', 4, 2021, 573554891, 2, 1),
('5.1.1.05.10', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Daerah', 5, 2021, 1739271946, 2, 1),
('5.1.1.05.10.0001', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Kendaraan Bermotor bagi KDH/WKDH', 6, 2021, 641280920, 2, 1),
('5.1.1.05.10.0002', 'Belanja Insentif Pemungutan bagi KDH/WKDH atas Bea Balik Nama Kendaraan Bermotor', 6, 2021, 849753950, 2, 1),
('5.1.1.05.10.0003', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 402026445, 2, 1),
('5.1.1.05.10.0004', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Air Permukaan', 6, 2021, 29663065, 2, 1),
('5.1.2.02.13', 'Belanja Jasa Insentif bagi Pegawai Non ASN atas Pemungutan Pajak Daerah', 5, 2021, 13225309174, 2, 1),
('5.1.2.02.13.0001', 'Belanja Jasa Insentif bagi Pegawai Non ASN atas Pemungutan Pajak Kendaraan Bermotor', 6, 2021, 4922100651, 2, 1),
('5.1.2.02.13.0002', 'Belanja Jasa Insentif Pegawai Non ASN atas Pemungutan Bea Balik Nama Kendaraan Bermotor', 6, 2021, 3593184860, 2, 1),
('5.1.2.02.13.0003', 'Belanja Jasa Insentif Pegawai Non ASN atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 3110023663, 2, 1),
('5.1.2.02.01.0021', 'Belanja Jasa Tenaga Sumber Daya Air', 6, 2021, 17500000, 2, 1),
('5.1.2.02.07.0030', 'Belanja Sewa Alat Peraga Kesenian', 6, 2021, 3500000, 2, 1),
('5.2.3.04.01.0003', 'Belanja Modal Pilar/Tugu/Tanda Lainnya', 6, 2021, 180000000, 2, 1),
('5.1.2.02.04.0122', 'Belanja Sewa Alat Dapur', 6, 2021, 660000, 2, 1),
('5.1.1.05.01', 'Belanja Gaji Pokok KDH/WKDH', 5, 2021, 77112000, 2, 1),
('5.1.1.05.01.0001', 'Belanja Gaji Pokok KDH/WKDH', 6, 2021, 77112000, 2, 1),
('5.1.1.05.02', 'Belanja Tunjangan Keluarga KDH/WKDH', 5, 2021, 9939000, 2, 1),
('5.1.1.05.02.0001', 'Belanja Tunjangan Keluarga KDH/WKDH', 6, 2021, 9939000, 2, 1),
('5.1.1.05.03', 'Belanja Tunjangan Jabatan KDH/WKDH', 5, 2021, 138802000, 2, 1),
('5.1.1.05.03.0001', 'Belanja Tunjangan Jabatan KDH/WKDH', 6, 2021, 138802000, 2, 1),
('5.1.1.05.04', 'Belanja Tunjangan Beras KDH/WKDH', 5, 2021, 6205000, 2, 1),
('5.1.1.05.04.0001', 'Belanja Tunjangan Beras KDH/WKDH', 6, 2021, 6889780, 2, 1),
('5.1.1.05.05', 'Belanja Tunjangan PPh/Tunjangan Khusus KDH/WKDH', 5, 2021, 10588000, 2, 1),
('5.1.1.05.05.0001', 'Belanja Tunjangan PPh/Tunjangan Khusus KDH/WKDH', 6, 2021, 10588000, 2, 1),
('5.1.1.05.06', 'Belanja Pembulatan Gaji KDH/WKDH', 5, 2021, 9891, 2, 1),
('5.1.1.05.06.0001', 'Belanja Pembulatan Gaji KDH/WKDH', 6, 2021, 2098, 2, 1),
('5.1.1.05.07', 'Belanja Iuran Jaminan Kesehatan bagi KDH/WKDH', 5, 2021, 330264000, 2, 1),
('5.1.1.05.07.0001', 'Belanja Iuran Jaminan Kesehatan bagi KDH/WKDH', 6, 2021, 330264000, 2, 1),
('5.1.1.05.08', 'Belanja Iuran Jaminan Kecelakaan Kerja KDH/WKDH', 5, 2021, 159000, 2, 1),
('5.1.1.05.08.0001', 'Belanja Iuran Jaminan Kecelakaan Kerja KDH/WKDH', 6, 2021, 159000, 2, 1),
('5.1.1.05.09', 'Belanja Iuran Jaminan Kematian KDH/WKDH', 5, 2021, 476000, 2, 1),
('5.1.1.05.09.0001', 'Belanja Iuran Jaminan Kematian KDH/WKDH', 6, 2021, 476000, 2, 1),
('5.1.1.06.02', 'Belanja Dana Operasional KDH/WKDH', 5, 2021, 8030737000, 2, 1),
('5.1.1.06.02.0001', 'Belanja Dana Operasional KDH/WKDH', 6, 2021, 9395520381, 2, 1),
('5.1.2.01.01.0059', 'Belanja Pakaian Dinas KDH dan WKDH', 6, 2021, 661000000, 2, 1),
('5.1.2.03.03.0038', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal-Rumah Negara Golongan I', 6, 2021, 0, 2, 1),
('5.1.2.03.04.0048', 'Belanja Pemeliharaan Bangunan Air- Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam-Bangunan Pengaman Sungai/Pantai dan Penanggulangan ', 6, 2021, 400000000, 2, 1),
('5.2.2.01.03.0001', 'Belanja Modal Alat Penarik', 6, 2021, 218000000, 2, 1),
('5.2.2.02.01.0002', 'Belanja Modal Kendaraan Bermotor Penumpang', 6, 2021, 4650000000, 2, 1),
('5.2.2.02.02.0002', 'Belanja Modal Kendaraan Tak Bermotor Penumpang', 6, 2021, 100000000, 2, 1),
('5.2.2.07.02.0004', 'Belanja Modal Alat Kesehatan Olahraga', 6, 2021, 265000000, 2, 1),
('5.2.4.03.10', 'Belanja Modal Instalasi Lain', 5, 2021, 200000000, 2, 1),
('5.2.4.03.10.0001', 'Belanja Modal Instalasi Lain', 6, 2021, 400000000, 2, 1),
('5.1.2.03.02.0133', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Studio- Peralatan Studio Video dan Film', 6, 2021, 200000000, 2, 1),
('5.1.2.03.04.0083', 'Belanja Pemeliharaan Instalasi-Instalasi Air Kotor-Instalasi Air Kotor Lainnya', 6, 2021, 183282000, 2, 1),
('5.2.2.07.01.0007', 'Belanja Modal Alat Kedokteran Mata', 6, 2021, 967443000, 2, 1),
('5.1.2.03.01', 'Belanja Pemeliharaan Tanah', 5, 2021, 198750000, 2, 1),
('5.1.2.03.01.0023', 'Belanja Pemeliharaan Tanah-Lapangan- Tanah untuk Jalan', 6, 2021, 198750000, 2, 1),
('5.1.2.03.03.0016', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Perpustakaan', 6, 2021, 196680000, 2, 1),
('5.2.2.01.01.0002', 'Belanja Modal Grader', 6, 2021, 6831000000, 2, 1),
('5.2.2.01.01.0007', 'Belanja Modal Compacting Equipment', 6, 2021, 6495775000, 2, 1),
('5.2.2.01.01.0009', 'Belanja Modal Loader', 6, 2021, 5085300000, 2, 1),
('5.2.2.02.01.0004', 'Belanja Modal Kendaraan Bermotor Beroda Dua', 6, 2021, 104100000, 2, 1),
('5.2.2.08.01.0006', 'Belanja Modal Alat Laboratorium Bahan Bangunan Konstruksi', 6, 2021, 2566846300, 2, 1),
('5.2.2.08.01.0007', 'Belanja Modal Alat Laboratorium Aspal, Cat, dan Kimia', 6, 2021, 117130000, 2, 1),
('5.1.2.02.09.0013', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Konsultansi Manajemen', 6, 2021, 100000000, 2, 1),
('5.1.2.03.04.0025', 'Belanja Pemeliharaan Bangunan Air- Bangunan Air Irigasi-Bangunan Pengambilan Irigasi', 6, 2021, 12213560570, 2, 1),
('5.2.4.02.05', 'Belanja Modal Bangunan Pengembangan Sumber Air dan Air Tanah', 5, 2021, 1854267405, 2, 1),
('5.2.4.02.05.0001', 'Belanja Modal Bangunan Waduk Pengembangan Sumber Air', 6, 2021, 1854267405, 2, 1),
('5.2.2.06.02.0008', 'Belanja Modal Alat Komunikasi Khusus', 6, 2021, 208725000, 2, 1),
('5.2.1.01.03.0008', 'Belanja Modal Tanah untuk Bangunan Air', 6, 2021, 497944158, 2, 1),
('5.2.2.05.02.0005', 'Belanja Modal Alat Dapur', 6, 2021, 100000000, 2, 1),
('5.2.4.03.05', 'Belanja Modal Instalasi Pembangkit Listrik', 5, 2021, 100998750, 2, 1),
('5.2.4.03.05.0009', 'Belanja Modal Instalasi Pembangkit Listrik Tenaga Surya (PLTS)', 6, 2021, 100998750, 2, 1),
('5.1.2.01.01.0071', 'Belanja Pakaian Kerja Laboratorium', 6, 2021, 8000000, 2, 1),
('5.1.2.01.01.0040', 'Belanja Barang untuk Dijual/Diserahkan kepada Pihak Ketiga/Pihak Lain', 6, 2021, 980681250, 2, 1),
('5.2.4.01.02.0013', 'Belanja Modal Jembatan Lainnya', 6, 2021, 20942005, 2, 1),
('4.1.2.02.11.0001', 'Retribusi Penjualan Produksi Hasil Usaha Daerah berupa Bibit atau Benih Tanaman', 6, 2021, 1552500000, 1, 1),
('5.1.2.01.01.0007', 'Belanja Bahan-Barang dalam Proses', 6, 2021, 72800000, 2, 1),
('5.1.5.05.01.0003', 'Belanja Hibah Jasa kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 0, 2, 1),
('5.2.2.04.01.0005', 'Belanja Modal Alat Laboratorium Pertanian', 6, 2021, 600000000, 2, 1),
('5.2.2.17.01.0026', 'Belanja Modal Unit Peralatan Proses/Produksi Lainnya', 6, 2021, 120000000, 2, 1),
('5.2.4.01.01.0005', 'Belanja Modal Jalan Desa', 6, 2021, 199999200, 2, 1),
('5.1.2.01.01.0070', 'Belanja Pakaian Pelatihan Kerja', 6, 2021, 56000000, 2, 1),
('5.1.2.02.08.0014', 'Belanja Jasa Konsultansi Perencanaan Penataan Ruang-Jasa Perencanaan dan Perancangan Perkotaan', 6, 2021, 90000000, 2, 1),
('4.1.2.01.08.0001', 'Retribusi Penyediaan Peta Dasar (Garis)', 6, 2021, 2000000, 1, 1),
('5.1.2.02.04.0410', 'Belanja Sewa Peralatan Jaringan', 6, 2021, 416058500, 2, 1),
('5.1.2.02.07.0013', 'Belanja Sewa Audio Visual', 6, 2021, 500000000, 2, 1),
('4.1.2.02.01', 'Retribusi Pemakaian Kekayaan Daerah', 5, 2021, 38497100, 1, 2),
('4.1.2.02.01.0003', 'Retribusi Penyewaan Bangunan', 6, 2021, 38497100, 1, 2),
('5.1.1.03.03', 'Belanja Tunjangan Profesi Guru (TPG) PNSD', 5, 2021, 642224682960, 2, 2),
('5.1.1.03.03.0001', 'Belanja TPG PNSD', 6, 2021, 642224682960, 2, 2),
('5.1.1.03.04', 'Belanja Tunjangan Khusus Guru (TKG) PNSD', 5, 2021, 20970677800, 2, 2),
('5.1.1.03.04.0001', 'Belanja TKG PNSD', 6, 2021, 20970677800, 2, 2),
('5.1.1.03.05', 'Belanja Tambahan Penghasilan (Tamsil) Guru PNSD', 5, 2021, 3255000000, 2, 2),
('5.1.1.03.05.0001', 'Belanja Tamsil Guru PNSD', 6, 2021, 3255000000, 2, 2),
('5.1.2.01.01.0055', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Pendidikan', 6, 2021, 486000000, 2, 2),
('5.1.2.01.01.0075', 'Belanja Pakaian Batik Tradisional', 6, 2021, 1500000, 2, 2),
('5.1.2.02.01.0009', 'Honorarium Penyelenggara Ujian', 6, 2021, 22640000, 2, 2),
('5.1.2.02.01.0013', 'Belanja Jasa Tenaga Pendidikan', 6, 2021, 198540000000, 2, 2),
('5.1.2.02.01.0039', 'Belanja Jasa Tenaga Informasi dan Teknologi', 6, 2021, 114000000, 2, 2),
('5.1.2.02.04.0036', 'Belanja Sewa Kendaraan Bermotor Penumpang', 6, 2021, 0, 2, 2),
('5.1.2.02.08.0008', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Pekerjaan Teknik Sipil Air', 6, 2021, 94000000, 2, 2),
('5.1.2.03.02.0411', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Komputer Lainnya', 6, 2021, 0, 2, 2),
('5.1.2.05.01.0003', 'Belanja Beasiswa', 6, 2021, 160443360000, 2, 2),
('5.1.2.88', 'Belanja Barang dan Jasa BOS', 4, 2021, 456815533360, 2, 2),
('5.1.2.88.88', 'Belanja Barang dan Jasa BOS', 5, 2021, 456815533360, 2, 2),
('5.1.2.88.88.8888', 'Belanja Barang dan Jasa BOS', 6, 2021, 456815533360, 2, 2),
('5.1.5.06', 'Belanja Hibah Dana BOS', 4, 2021, 2783674794500, 2, 2),
('5.1.5.06.01', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Negeri', 5, 2021, 476553000000, 2, 2),
('5.1.5.06.01.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Negeri', 6, 2021, 476553000000, 2, 2),
('5.1.5.06.02', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Swasta', 5, 2021, 1660224000000, 2, 2),
('5.1.5.06.02.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikdas Swasta', 6, 2021, 1660224000000, 2, 2),
('5.1.5.06.03', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikmen Swasta', 5, 2021, 638412114500, 2, 2),
('5.1.5.06.03.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdikmen Swasta', 6, 2021, 638412114500, 2, 2),
('5.1.5.06.04', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdiksus Swasta', 5, 2021, 8485680000, 2, 2),
('5.1.5.06.04.0001', 'Belanja Hibah Uang Dana BOS yang Diterima oleh Satdiksus Swasta', 6, 2021, 8485680000, 2, 2),
('5.2.1', 'Belanja Modal Tanah', 3, 2021, 3196000000, 2, 2),
('5.2.1.01', 'Belanja Modal Tanah', 4, 2021, 3196000000, 2, 2),
('5.2.1.01.01.0004', 'Belanja Modal Tanah untuk Bangunan Tempat Kerja', 6, 2021, 2000000000, 2, 2),
('5.2.2.08', 'Belanja Modal Alat Laboratorium', 4, 2021, 6000000, 2, 2),
('5.2.2.08.01', 'Belanja Modal Unit Alat Laboratorium', 5, 2021, 6000000, 2, 2),
('5.2.2.08.03', 'Belanja Modal Alat Peraga Praktek Sekolah', 5, 2021, 149253936986, 2, 2),
('5.2.2.08.03.0006', 'Belanja Modal Alat Peraga Praktek Sekolah Bidang Studi:IPA Atas', 6, 2021, 3510000000, 2, 2),
('5.2.2.08.03.0013', 'Belanja Modal Alat Peraga Luar Biasa (Tuna Netra, Terapi Fisik, Tuna Daksa, dan Tuna Rungu)', 6, 2021, 593050000, 2, 2),
('5.2.2.08.03.0014', 'Belanja Modal Alat Peraga Kejuruan', 6, 2021, 65844339486, 2, 2),
('5.2.2.08.03.0016', 'Belanja Modal Alat Peraga Praktik Sekolah Lainnya', 6, 2021, 79306547500, 2, 2),
('5.2.2.10.02.0003', 'Belanja Modal Peralatan Personal Computer', 6, 2021, 547500000, 2, 2),
('5.2.2.13', 'Belanja Modal Alat Produksi, Pengolahan, dan Pemurnian', 4, 2021, 181795540, 2, 2),
('5.2.2.13.01', 'Belanja Modal Sumur', 5, 2021, 181795540, 2, 2),
('5.2.2.13.01.0002', 'Belanja Modal Sumur Pemboran', 6, 2021, 181795540, 2, 2),
('5.2.3.01.01.0010', 'Belanja Modal Bangunan Gedung Tempat Pendidikan', 6, 2021, 0, 2, 2),
('5.2.3.01.01.0030', 'Belanja Modal Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 0, 2, 2),
('5.2.5', 'Belanja Modal Aset Tetap Lainnya', 3, 2021, 50000000, 2, 2),
('5.2.5.01.02', 'Belanja Modal Bahan Perpustakaan Terekam dan Bentuk Mikro', 5, 2021, 4428468000, 2, 2),
('5.2.5.01.02.0001', 'Belanja Modal Audio Visual', 6, 2021, 4428468000, 2, 2),
('5.2.5.01.07', 'Belanja Modal Tarscalt', 5, 2021, 87519182654, 2, 2),
('5.2.5.01.07.0001', 'Belanja Modal Tarscalt', 6, 2021, 87519182654, 2, 2),
('5.2.5.05', 'Belanja Modal Tanaman', 4, 2021, 200000000, 2, 2),
('5.2.5.05.01', 'Belanja Modal Tanaman', 5, 2021, 200000000, 2, 2),
('4.1.2.01', 'Retribusi Jasa Umum', 4, 2021, 102000000, 1, 2),
('4.1.2.01.01', 'Retribusi Pelayanan Kesehatan', 5, 2021, 9900000000, 1, 2),
('4.1.2.01.01.0006', 'Retribusi Pelayanan Kesehatan di Tempat Pelayanan Kesehatan Lainnya yang Sejenis', 6, 2021, 9900000000, 1, 2),
('4.1.2.02.06.0001', 'Retribusi Pelayanan Tempat Penginapan/Pesanggrahan/Vila', 6, 2021, 2200000000, 1, 2),
('4.1.4.16', 'Pendapatan BLUD', 4, 2021, 78250000000, 1, 2),
('4.1.4.16.01', 'Pendapatan BLUD', 5, 2021, 78250000000, 1, 2),
('4.1.4.16.01.0001', 'Pendapatan BLUD', 6, 2021, 78250000000, 1, 2),
('5.1.1.03.02', 'Belanja bagi ASN atas Insentif Pemungutan Retribusi Daerah', 5, 2021, 0, 2, 2),
('5.1.1.03.02.0001', 'Belanja Insentif bagi ASN atas Pemungutan Retribusi Jasa Umum-Pelayanan Kesehatan', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0009', 'Belanja Bahan-Isi Tabung Pemadam Kebakaran', 6, 2021, 5070000, 2, 2),
('5.1.2.01.01.0037', 'Belanja Obat-Obatan-Obat', 6, 2021, 549000, 2, 2),
('5.1.2.01.01.0056', 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Kesehatan', 6, 2021, 3520000, 2, 2),
('5.1.2.02.01.0014', 'Belanja Jasa Tenaga Kesehatan', 6, 2021, 2500000, 2, 2),
('5.1.2.02.01.0034', 'Belanja Jasa Tenaga Juru Masak', 6, 2021, 655500000, 2, 2),
('5.1.2.02.01.0071', 'Belanja Lembur', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0073', 'Belanja Medical Check Up', 6, 2021, 50000000, 2, 2),
('5.1.2.02.02.0002', 'Belanja Kontribusi Jaminan Kesehatan bagi PBI', 6, 2021, 119288962800, 2, 2),
('5.1.2.02.02.0003', 'Belanja Iuran Jaminan Kesehatan bagi Peserta PBPU dan BP Kelas 3', 6, 2021, 0, 2, 2),
('5.1.2.02.03.0004', 'Belanja Sewa Tanah untuk Bangunan Tempat Kerja', 6, 2021, 25000000, 2, 2),
('5.1.2.02.04.0118', 'Belanja Sewa Mebel', 6, 2021, 8000000, 2, 2),
('5.1.2.02.09.0008', 'Belanja Jasa Konsultansi Berorientasi Bidang-Kesehatan', 6, 2021, 50000000, 2, 2),
('5.1.2.02.11', 'Belanja Beasiswa Pendidikan PNS', 5, 2021, 55000000, 2, 2),
('5.1.2.02.11.0002', 'Belanja Beasiswa Tugas Belajar S2', 6, 2021, 55000000, 2, 2),
('5.1.2.02.12.0001', 'Belanja Kursus Singkat/Pelatihan', 6, 2021, 0, 2, 2),
('5.1.2.03.02.0022', 'Belanja Pemeliharaan Alat Besar-Alat Bantu- Electric Generating Set', 6, 2021, 40500000, 2, 2),
('5.1.2.03.02.0138', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Komunikasi- Alat Komunikasi Telephone', 6, 2021, 0, 2, 2),
('5.1.2.03.02.0237', 'Belanja Pemeliharaan Alat Kedokteran dan Kesehatan-Alat Kesehatan Umum-Alat Kesehatan Umum Lainnya', 6, 2021, 50000000, 2, 2),
('5.1.2.03.02.0293', 'Belanja Pemeliharaan Alat Laboratorium-Unit Alat Laboratorium-Alat Laboratorium Lain', 6, 2021, 35920000, 2, 2),
('5.1.2.03.02.0405', 'Belanja Pemeliharaan Komputer-Komputer Unit-Personal Computer', 6, 2021, 4000000, 2, 2),
('5.1.2.03.03.0006', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Kesehatan', 6, 2021, 3684605000, 2, 2),
('5.1.2.03.04.0083', 'Belanja Pemeliharaan Instalasi-Instalasi Air Kotor-Instalasi Air Kotor Lainnya', 6, 2021, 183282000, 2, 2),
('5.1.2.03.04.0126', 'Belanja Pemeliharaan Jaringan-Jaringan Listrik-Jaringan Listrik Lainnya', 6, 2021, 16200000, 2, 2),
('5.1.5.05.03', 'Belanja Hibah kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 5, 2021, 389757033473, 2, 2),
('5.1.5.05.03.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 6, 2021, 389607033473, 2, 2),
('5.2.2.01.03.0005', 'Belanja Modal Pompa', 6, 2021, 103975000, 2, 2),
('5.2.2.03.03.0001', 'Belanja Modal Alat Ukur Universal', 6, 2021, 13500000, 2, 2),
('5.2.2.03.03.0021', 'Belanja Modal Alat Ukur Lainnya', 6, 2021, 2000000, 2, 2),
('5.2.2.06.01.0006', 'Belanja Modal Alat Studio Lainnya', 6, 2021, 12830000, 2, 2),
('5.2.2.07.01', 'Belanja Modal Alat Kedokteran', 5, 2021, 0, 2, 2),
('5.2.2.07.01.0007', 'Belanja Modal Alat Kedokteran Mata', 6, 2021, 967443000, 2, 2),
('5.2.2.07.01.0029', 'Belanja Modal Alat Kedokteran Lainnya', 6, 2021, 0, 2, 2),
('5.2.2.08.01.0013', 'Belanja Modal Alat Laboratorium Kimia', 6, 2021, 1780492000, 2, 2),
('5.2.2.10.02.0004', 'Belanja Modal Peralatan Jaringan', 6, 2021, 3000000, 2, 2),
('5.2.3.01.01.0036', 'Belanja Modal Taman', 6, 2021, 60000000, 2, 2),
('5.2.5.08', 'Belanja Modal Aset Tidak Berwujud', 4, 2021, 50000000, 2, 2),
('5.2.5.08.01', 'Belanja Modal Aset Tidak Berwujud', 5, 2021, 50000000, 2, 2),
('5.2.5.08.01.0005', 'Belanja Modal Software', 6, 2021, 50000000, 2, 2),
('5.1.1.03.08', 'Belanja Jasa Pengelolaan BMD', 5, 2021, 36600000, 2, 2),
('5.1.1.03.08.0002', 'Belanja Jasa Pengelolaan BMD yang Tidak Menghasilkan Pendapatan', 6, 2021, 36600000, 2, 2),
('5.1.2.01.01.0008', 'Belanja Bahan-Bahan/Bibit Tanaman', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0035', 'Belanja Jasa Tenaga Teknisi Mekanik dan Listrik', 6, 2021, 3000000, 2, 2),
('5.1.2.02.01.0048', 'Belanja Jasa Kontribusi Asosiasi', 6, 2021, 150000000, 2, 2),
('5.1.2.03.01', 'Belanja Pemeliharaan Tanah', 5, 2021, 198750000, 2, 2),
('5.1.2.03.01.0023', 'Belanja Pemeliharaan Tanah-Lapangan- Tanah untuk Jalan', 6, 2021, 198750000, 2, 2),
('5.1.2.03.03.0008', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Tempat Ibadah', 6, 2021, 22350000, 2, 2),
('5.1.2.03.03.0013', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung untuk Pos Jaga', 6, 2021, 207855000, 2, 2),
('5.1.2.03.03.0016', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Perpustakaan', 6, 2021, 196680000, 2, 2),
('5.2.2.03.01', 'Belanja Modal Alat Bengkel Bermesin', 5, 2021, 35000000, 2, 2),
('5.2.2.03.01.0008', 'Belanja Modal Peralatan Las', 6, 2021, 35000000, 2, 2),
('5.2.2.07.01.0001', 'Belanja Modal Alat Kedokteran Umum', 6, 2021, 0, 2, 2),
('5.2.2.07.01.0013', 'Belanja Modal Alat Kedokteran Neurologi (Saraf)', 6, 2021, 984856000, 2, 2),
('5.2.2.08.01.0064', 'Belanja Modal Unit Alat Laboratorium Lainnya', 6, 2021, 1700000, 2, 2),
('5.2.3.01.01.0004', 'Belanja Modal Bangunan Gedung Instalasi', 6, 2021, 848850000, 2, 2),
('5.2.3.01.01.0006', 'Belanja Modal Bangunan Kesehatan', 6, 2021, 17719000000, 2, 2),
('5.2.4', 'Belanja Modal Jalan, Jaringan, dan Irigasi', 3, 2021, 599655097, 2, 2),
('5.2.4.04', 'Belanja Modal Jaringan', 4, 2021, 182000000, 2, 2),
('5.2.4.04.02', 'Belanja Modal Jaringan Listrik', 5, 2021, 182000000, 2, 2),
('5.2.4.04.02.0003', 'Belanja Modal Jaringan Listrik Lainnya', 6, 2021, 182000000, 2, 2),
('5.1.1.99', 'Belanja Pegawai BLUD', 4, 2021, 37041212472, 2, 2),
('5.1.1.99.99', 'Belanja Pegawai BLUD', 5, 2021, 37041212472, 2, 2),
('5.1.1.99.99.9999', 'Belanja Pegawai BLUD', 6, 2021, 37041212472, 2, 2),
('5.1.2.02.01.0012', 'Honorarium Tim Anggaran Pemerintah Daerah', 6, 2021, 0, 2, 2),
('5.1.2.03.02.0301', 'Belanja Pemeliharaan Alat Laboratorium-Unit Alat Laboratorium-Unit Alat Laboratorium Lainnya', 6, 2021, 150000000, 2, 2),
('5.1.2.99', 'Belanja Barang dan Jasa BLUD', 4, 2021, 41208787528, 2, 2),
('5.1.2.99.99', 'Belanja Barang dan Jasa BLUD', 5, 2021, 41208787528, 2, 2),
('5.1.2.99.99.9999', 'Belanja Barang dan Jasa BLUD', 6, 2021, 41208787528, 2, 2),
('5.2.2.01.03.0008', 'Belanja Modal Alat Pengolahan Air Kotor', 6, 2021, 120000000, 2, 2),
('5.2.2.02', 'Belanja Modal Alat Angkutan', 4, 2021, 4942800000, 2, 2),
('5.2.2.02.01', 'Belanja Modal Alat Angkutan Darat Bermotor', 5, 2021, 4842800000, 2, 2),
('5.2.2.02.01.0006', 'Belanja Modal Kendaraan Bermotor Khusus', 6, 2021, 0, 2, 2),
('5.2.2.07.01.0004', 'Belanja Modal Alat Kedokteran Bedah', 6, 2021, 10726628000, 2, 2),
('5.2.3.04.01.0004', 'Belanja Modal Pagar', 6, 2021, 118852800, 2, 2),
('5.2.5.01.01.0003', 'Belanja Modal Buku Agama', 6, 2021, 5750000, 2, 2),
('4.1.2.02.01.0002', 'Retribusi Penyewaan Tanah', 6, 2021, 800000000, 1, 2),
('4.1.2.02.01.0007', 'Retribusi Pemakaian Alat', 6, 2021, 52404365, 1, 2),
('5.1.2.02.04.0148', 'Belanja Sewa Alat Komunikasi Lainnya', 6, 2021, 45712666, 2, 2),
('5.1.2.02.08.0009', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Pekerjaan Teknik Sipil Transportasi', 6, 2021, 0, 2, 2),
('5.1.2.02.08.0020', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Teknik Sipil Transportasi', 6, 2021, 395562200, 2, 2);
INSERT INTO `data_uraian_kegiatan_pemko` (`kode_rekening`, `uraian`, `level`, `tahun`, `anggaran`, `jenis`, `st_anggaran`) VALUES
('5.1.2.02.09.0012', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Studi Penelitian dan Bantuan Teknik', 6, 2021, 350000000, 2, 2),
('5.1.2.03.04.0002', 'Belanja Pemeliharaan Jalan dan Jembatan- Jalan-Jalan Provinsi', 6, 2021, 50197209184, 2, 2),
('5.1.2.03.04.0012', 'Belanja Pemeliharaan Jalan dan Jembatan- Jembatan-Jembatan pada Jalan Provinsi', 6, 2021, 4587828575, 2, 2),
('5.2.1.01.03', 'Belanja Modal Lapangan', 5, 2021, 3196000000, 2, 2),
('5.2.1.01.03.0007', 'Belanja Modal Tanah untuk Jalan', 6, 2021, 25000000000, 2, 2),
('5.2.2.01.01.0002', 'Belanja Modal Grader', 6, 2021, 6831000000, 2, 2),
('5.2.2.01.01.0007', 'Belanja Modal Compacting Equipment', 6, 2021, 6495775000, 2, 2),
('5.2.2.01.01.0008', 'Belanja Modal Aggregate and Concrete Equipment', 6, 2021, 0, 2, 2),
('5.2.2.01.01.0009', 'Belanja Modal Loader', 6, 2021, 5085300000, 2, 2),
('5.2.2.01.03.0003', 'Belanja Modal Compressor', 6, 2021, 0, 2, 2),
('5.2.2.02.01.0001', 'Belanja Modal Kendaraan Dinas Bermotor Perorangan', 6, 2021, 0, 2, 2),
('5.2.2.02.01.0003', 'Belanja Modal Kendaraan Bermotor Angkutan Barang', 6, 2021, 192800000, 2, 2),
('5.2.2.02.01.0004', 'Belanja Modal Kendaraan Bermotor Beroda Dua', 6, 2021, 104100000, 2, 2),
('5.2.2.05.01.0002', 'Belanja Modal Mesin Hitung/Mesin Jumlah', 6, 2021, 2250000, 2, 2),
('5.2.2.08.01.0006', 'Belanja Modal Alat Laboratorium Bahan Bangunan Konstruksi', 6, 2021, 2566846300, 2, 2),
('5.2.2.08.01.0007', 'Belanja Modal Alat Laboratorium Aspal, Cat, dan Kimia', 6, 2021, 117130000, 2, 2),
('5.2.2.08.01.0008', 'Belanja Modal Alat Laboratorium Mekanika Tanah dan Batuan', 6, 2021, 183137500, 2, 2),
('5.2.2.18', 'Belanja Modal Rambu-Rambu', 4, 2021, 125000000, 2, 2),
('5.2.2.18.01', 'Belanja Modal Rambu-Rambu Lalu Lintas Darat', 5, 2021, 125000000, 2, 2),
('5.2.2.18.01.0003', 'Belanja Modal Rambu-Rambu Lalu Lintas Darat Lainnya', 6, 2021, 125000000, 2, 2),
('5.2.4.01', 'Belanja Modal Jalan dan Jembatan', 4, 2021, 0, 2, 2),
('5.2.4.01.01.0002', 'Belanja Modal Jalan Provinsi', 6, 2021, 750000000, 2, 2),
('5.2.4.01.02', 'Belanja Modal Jembatan', 5, 2021, 0, 2, 2),
('5.2.4.01.02.0002', 'Belanja Modal Jembatan pada Jalan Provinsi', 6, 2021, 33017935110, 2, 2),
('5.1.2.01.01.0023', 'Belanja Suku Cadang-Suku Cadang Lainnya', 6, 2021, 32500000, 2, 2),
('5.1.2.02.01.0069', 'Belanja Pengolahan Air Limbah', 6, 2021, 1350000000, 2, 2),
('5.1.2.02.08.0021', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Teknik Sipil Air', 6, 2021, 2365000000, 2, 2),
('5.1.2.02.08.0027', 'Belanja Jasa Konsultansi Spesialis-Jasa Pembuatan Peta', 6, 2021, 100000000, 2, 2),
('5.1.2.02.09.0013', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Konsultansi Manajemen', 6, 2021, 100000000, 2, 2),
('5.1.2.02.12.0002', 'Belanja Sosialisasi', 6, 2021, 89520000, 2, 2),
('5.1.2.02.12.0004', 'Belanja Diklat Kepemimpinan', 6, 2021, 22125000, 2, 2),
('5.1.2.03.02.0120', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Pembersih', 6, 2021, 123925000, 2, 2),
('5.1.2.03.04.0025', 'Belanja Pemeliharaan Bangunan Air- Bangunan Air Irigasi-Bangunan Pengambilan Irigasi', 6, 2021, 12213560570, 2, 2),
('5.2.1.01.01.0007', 'Belanja Modal Tanah Persil Lainnya', 6, 2021, 1563010925, 2, 2),
('5.2.2.01.01.0006', 'Belanja Modal Asphalt Equipment', 6, 2021, 0, 2, 2),
('5.2.2.01.01.0012', 'Belanja Modal Alat Besar Darat Lainnya', 6, 2021, 0, 2, 2),
('5.2.2.04', 'Belanja Modal Alat Pertanian', 4, 2021, 0, 2, 2),
('5.2.2.04.01', 'Belanja Modal Alat Pengolahan', 5, 2021, 0, 2, 2),
('5.2.2.04.01.0001', 'Belanja Modal Alat Pengolahan Tanah dan Tanaman', 6, 2021, 0, 2, 2),
('5.2.3.01.01.0009', 'Belanja Modal Bangunan Gedung Tempat Pertemuan', 6, 2021, 310000000, 2, 2),
('5.2.3.01.02', 'Belanja Modal Bangunan Gedung Tempat Tinggal', 5, 2021, 161710287, 2, 2),
('5.2.3.01.02.0013', 'Belanja Modal Bangunan Gedung Tempat Tinggal Lainnya', 6, 2021, 161710287, 2, 2),
('5.2.4.01.01.0010', 'Belanja Modal Jalan Lainnya', 6, 2021, 537287500, 2, 2),
('5.2.4.02', 'Belanja Modal Bangunan Air', 4, 2021, 199655097, 2, 2),
('5.2.4.02.01', 'Belanja Modal Bangunan Air Irigasi', 5, 2021, 728000000, 2, 2),
('5.2.4.02.01.0002', 'Belanja Modal Bangunan Pengambilan Irigasi', 6, 2021, 8004021552, 2, 2),
('5.2.4.02.01.0003', 'Belanja Modal Bangunan Pembawa Irigasi', 6, 2021, 27438126888, 2, 2),
('5.2.4.02.01.0004', 'Belanja Modal Bangunan Pembuang Irigasi', 6, 2021, 1215111176, 2, 2),
('5.2.4.02.04', 'Belanja Modal Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam', 5, 2021, 199655097, 2, 2),
('5.2.4.02.04.0001', 'Belanja Modal Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam', 6, 2021, 199655097, 2, 2),
('5.2.4.02.04.0002', 'Belanja Modal Bangunan Pengambilan Pengaman Sungai/Pantai', 6, 2021, 48204998502, 2, 2),
('5.2.4.02.05', 'Belanja Modal Bangunan Pengembangan Sumber Air dan Air Tanah', 5, 2021, 1854267405, 2, 2),
('5.2.4.02.05.0001', 'Belanja Modal Bangunan Waduk Pengembangan Sumber Air', 6, 2021, 1854267405, 2, 2),
('5.2.4.02.06', 'Belanja Modal Bangunan Air Bersih/Air Baku', 5, 2021, 54163125065, 2, 2),
('5.2.4.02.06.0001', 'Belanja Modal Bangunan Waduk Air Bersih/Air Baku', 6, 2021, 1646424156, 2, 2),
('5.2.4.02.06.0002', 'Belanja Modal Bangunan Pengambilan Air Bersih/Air Baku', 6, 2021, 2629840909, 2, 2),
('5.2.4.02.06.0003', 'Belanja Modal Bangunan Pembawa Air Bersih/Air Baku', 6, 2021, 49886860000, 2, 2),
('5.2.4.03', 'Belanja Modal Instalasi', 4, 2021, 400000000, 2, 2),
('5.2.4.03.02', 'Belanja Modal Instalasi Air Kotor', 5, 2021, 300000000, 2, 2),
('5.2.4.03.02.0004', 'Belanja Modal Instalasi Air Kotor Lainnya', 6, 2021, 1410000000, 2, 2),
('5.1.2.01.01.0076', 'Belanja Pakaian Olahraga', 6, 2021, 6720000, 2, 2),
('5.1.5.02', 'Belanja Hibah kepada Pemerintah Daerah Lainnya', 4, 2021, 150600000, 2, 2),
('5.1.5.02.02', 'Belanja Hibah Barang kepada Pemerintah Daerah Lainnya', 5, 2021, 150600000, 2, 2),
('5.1.5.02.02.0001', 'Belanja Hibah Barang kepada Pemerintah Daerah Lainnya', 6, 2021, 150600000, 2, 2),
('5.1.6', 'Belanja Bantuan Sosial', 3, 2021, 405000000, 2, 2),
('5.1.6.03', 'Belanja Bantuan Sosial kepada Kelompok Masyarakat', 4, 2021, 0, 2, 2),
('5.1.6.03.01', 'Belanja Bantuan Sosial Uang yang direncanakan kepada Kelompok Masyarakat', 5, 2021, 1152000000, 2, 2),
('5.1.6.03.01.0001', 'Belanja Bantuan Sosial Uang yang Direncanakan kepada Kelompok Masyarakat', 6, 2021, 1152000000, 2, 2),
('5.1.2.01.01.0074', 'Belanja Pakaian Adat Daerah', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0046', 'Belanja Jasa Konversi Aplikasi/Sistem Informasi', 6, 2021, 200000000, 2, 2),
('5.1.2.02.04.0202', 'Belanja Sewa Peralatan Komunikasi untuk Dokumentasi', 6, 2021, 5000000, 2, 2),
('5.1.5.05.01', 'Belanja Hibah kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 5, 2021, 500000000, 2, 2),
('5.1.5.05.01.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 500000000, 2, 2),
('5.1.5.05.02.0001', 'Belanja Hibah Uang kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 6, 2021, 23550000000, 2, 2),
('5.1.5.05.03.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga Nirlaba, Sukarela Bersifat Sosial Kemasyarakatan', 6, 2021, 150000000, 2, 2),
('5.1.5.07', 'Belanja Hibah Bantuan Keuangan kepada Partai Politik', 4, 2021, 7591268400, 2, 2),
('5.1.5.07.01', 'Belanja Hibah Bantuan Keuangan kepada Partai Politik', 5, 2021, 7591268400, 2, 2),
('5.1.5.07.01.0001', 'Belanja Hibah berupa Bantuan Keuangan kepada Partai Politik', 6, 2021, 7591268400, 2, 2),
('5.2.2.05.02.0007', 'Belanja Modal Alat Pemadam Kebakaran', 6, 2021, 2510248000, 2, 2),
('5.2.2.05.03.0002', 'Belanja Modal Meja Rapat Pejabat', 6, 2021, 19500000, 2, 2),
('5.1.2.01.01.0064', 'Belanja Pakaian Dinas Lapangan (PDL)', 6, 2021, 38600000, 2, 2),
('5.1.2.02.01.0017', 'Belanja Jasa Tenaga Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat', 6, 2021, 11308462500, 2, 2),
('5.1.2.02.08.0016', 'Belanja Jasa Konsultansi Perencanaan Penataan Ruang-Jasa Perencanaan dan Perancangan Lingkungan Bangunan dan Landscape', 6, 2021, 50000000, 2, 2),
('5.1.2.02.08.0023', 'Belanja Jasa Konsultansi Pengawasan Penataan Ruang', 6, 2021, 190000000, 2, 2),
('5.1.5.01', 'Belanja Hibah kepada Pemerintah Pusat', 4, 2021, 5056600000, 2, 2),
('5.1.5.01.01', 'Belanja Hibah Uang kepada Pemerintah Pusat', 5, 2021, 3000000000, 2, 2),
('5.1.5.01.01.0001', 'Belanja Hibah Uang kepada Pemerintah Pusat', 6, 2021, 3000000000, 2, 2),
('5.2.2.06.02.0008', 'Belanja Modal Alat Komunikasi Khusus', 6, 2021, 208725000, 2, 2),
('5.1.2.01.01.0045', 'Belanja Natura dan Pakan-Natura dan Pakan Lainnya', 6, 2021, 158760000, 2, 2),
('5.1.2.02.01.0019', 'Belanja Jasa Tenaga Penanganan Bencana', 6, 2021, 5470000000, 2, 2),
('5.1.2.02.09.0014', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Khusus', 6, 2021, 96000000, 2, 2),
('5.1.2.03.03.0026', 'Belanja Belanja Pemeliharaan Bangunan Gedung-Bangunan Gedung Tempat Kerja-Bangunan Penampung Sekam', 6, 2021, 516285000, 2, 2),
('5.1.2.03.05', 'Belanja Pemeliharaan Aset Tetap Lainnya', 5, 2021, 0, 2, 2),
('5.1.2.03.05.0065', 'Belanja Pemeliharaan Aset Tetap dalam Renovasi-Aset Tetap dalam Renovasi-Aset Tetap dalam Renovasi', 6, 2021, 192550500, 2, 2),
('5.2.2.06.02.0011', 'Belanja Modal Alat Komunikasi Lainnya', 6, 2021, 450000000, 2, 2),
('5.2.2.15', 'Belanja Modal Alat Keselamatan Kerja', 4, 2021, 0, 2, 2),
('5.2.2.15.03', 'Belanja Modal Alat SAR', 5, 2021, 60356313, 2, 2),
('5.2.2.15.03.0004', 'Belanja Modal Alat SAR Lainnya', 6, 2021, 60356313, 2, 2),
('5.1.2.02.01.0065', 'Belanja Penambahan Daya', 6, 2021, 70349400, 2, 2),
('5.1.2.02.04.0052', 'Belanja Sewa Alat Angkutan Apung Bermotor Lainnya', 6, 2021, 6400000, 2, 2),
('5.1.2.02.04.0075', 'Belanja Sewa Perkakas Bengkel Kerja', 6, 2021, 2000000, 2, 2),
('5.1.2.02.04.0103', 'Belanja Sewa Alat Pengolahan Tanah dan Tanaman', 6, 2021, 4000000, 2, 2),
('5.1.2.02.05.0037', 'Belanja Sewa Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 20000000, 2, 2),
('5.1.6.01', 'Belanja Bantuan Sosial kepada Individu', 4, 2021, 405000000, 2, 2),
('5.1.6.01.01', 'Belanja Bantuan Sosial Uang yang direncanakan kepada Individu', 5, 2021, 405000000, 2, 2),
('5.1.6.01.01.0001', 'Belanja Bantuan Sosial Uang yang Direncanakan kepada Individu', 6, 2021, 405000000, 2, 2),
('5.1.6.01.02', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Individu', 5, 2021, 2469080288, 2, 2),
('5.1.6.01.02.0001', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Individu', 6, 2021, 2469080288, 2, 2),
('5.2.1.01.03.0008', 'Belanja Modal Tanah untuk Bangunan Air', 6, 2021, 497944158, 2, 2),
('5.2.2.02.02', 'Belanja Modal Alat Angkutan Darat Tak Bermotor', 5, 2021, 100000000, 2, 2),
('5.2.2.02.02.0001', 'Belanja Modal Kendaraan Tak Bermotor Angkutan Barang', 6, 2021, 0, 2, 2),
('5.2.2.05.02.0005', 'Belanja Modal Alat Dapur', 6, 2021, 100000000, 2, 2),
('4.1.2.03.06', 'Retribusi Perpanjangan Izin Mempekerjakan Tenaga Kerja Asing (IMTA)', 5, 2021, 1900000000, 1, 2),
('4.1.2.03.06.0001', 'Retribusi Pemberian Perpanjangan IMTA kepada Pemberi Kerja Tenaga Kerja Asing', 6, 2021, 1900000000, 1, 2),
('5.2.4.03.05', 'Belanja Modal Instalasi Pembangkit Listrik', 5, 2021, 100998750, 2, 2),
('5.2.4.03.05.0009', 'Belanja Modal Instalasi Pembangkit Listrik Tenaga Surya (PLTS)', 6, 2021, 100998750, 2, 2),
('5.1.2.02.05.0016', 'Belanja Sewa Bangunan Gedung Perpustakaan', 6, 2021, 0, 2, 2),
('5.1.2.04.02', 'Belanja Perjalanan Dinas Luar Negeri', 5, 2021, 0, 2, 2),
('5.1.2.04.02.0001', 'Belanja Perjalanan Dinas BiasaûLuar Negeri', 6, 2021, 0, 2, 2),
('5.1.2.05.02', 'Belanja Jasa yang Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 5, 2021, 0, 2, 2),
('5.1.2.05.02.0001', 'Belanja Jasa yang Diberikan kepada Pihak Ketiga/Pihak Lain', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0041', 'Belanja Persediaan untuk Tujuan Strategis/Berjaga-jaga-Persediaan untuk Tujuan Strategis/Berjaga-jaga', 6, 2021, 726000000, 2, 2),
('5.1.2.01.01.0044', 'Belanja Natura dan Pakan-Pakan', 6, 2021, 34392000, 2, 2),
('5.1.2.02.01.0023', 'Belanja Jasa Tenaga Teknis Pertanian dan Pangan', 6, 2021, 8269260000, 2, 2),
('5.1.2.02.01.0032', 'Belanja Jasa Tenaga Caraka', 6, 2021, 78000000, 2, 2),
('5.1.2.02.08.0006', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Nasihat dan Konsultansi Rekayasa Teknik', 6, 2021, 12000000, 2, 2),
('5.1.2.02.09.0001', 'Belanja Jasa Konsultansi Berorientasi Bidang-Pengembangan Pertanian dan Perdesaan', 6, 2021, 1193200000, 2, 2),
('5.1.2.03.02.0001', 'Belanja Pemeliharaan Alat Besar-Alat Besar Darat-Tractor', 6, 2021, 11000000, 2, 2),
('5.1.2.03.02.0039', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Bermotor Beroda Tiga', 6, 2021, 6000000, 2, 2),
('5.1.2.03.02.0107', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Laboratorium Pertanian', 6, 2021, 864000, 2, 2),
('5.1.2.03.02.0446', 'Belanja Pemeliharaan Alat Keselamatan Kerja-Alat Pelindung-Baju Pengaman', 6, 2021, 1600000, 2, 2),
('5.1.2.03.03.0002', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gudang', 6, 2021, 0, 2, 2),
('5.1.2.03.03.0029', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Peternakan/Perikanan', 6, 2021, 191092500, 2, 2),
('5.1.2.03.05.0046', 'Belanja Pemeliharaan Hewan-Hewan Lainnya-Hewan Lainnya', 6, 2021, 0, 2, 2),
('5.2.2.01.01.0001', 'Belanja Modal Tractor', 6, 2021, 35000000, 2, 2),
('5.2.2.02.01.0005', 'Belanja Modal Kendaraan Bermotor Beroda Tiga', 6, 2021, 150000000, 2, 2),
('5.2.2.03.03.0010', 'Belanja Modal Alat Timbangan/Biara', 6, 2021, 0, 2, 2),
('5.2.2.04.01.0009', 'Belanja Modal Alat-Alat Peternakan', 6, 2021, 76050000, 2, 2),
('5.2.3.01.01.0008', 'Belanja Modal Bangunan Gedung Tempat Ibadah', 6, 2021, 200000000, 2, 2),
('5.2.3.01.01.0013', 'Belanja Modal Bangunan Gedung untuk Pos Jaga', 6, 2021, 55875000, 2, 2),
('5.2.3.01.01.0029', 'Belanja Modal Bangunan Peternakan/Perikanan', 6, 2021, 973995000, 2, 2),
('5.2.4.02.01.0008', 'Belanja Modal Bangunan Air Irigasi Lainnya', 6, 2021, 728000000, 2, 2),
('5.2.4.03.02.0003', 'Belanja Modal Instalasi Air Buangan Pertanian', 6, 2021, 300000000, 2, 2),
('5.2.5.03', 'Belanja Modal Hewan', 4, 2021, 2877500000, 2, 2),
('5.2.5.03.02', 'Belanja Modal Ternak', 5, 2021, 2877500000, 2, 2),
('5.2.5.03.02.0004', 'Belanja Modal Ternak Lainnya', 6, 2021, 2877500000, 2, 2),
('5.1.2.01.01.0071', 'Belanja Pakaian Kerja Laboratorium', 6, 2021, 8000000, 2, 2),
('5.1.2.02.04.0050', 'Belanja Sewa Alat Angkutan Apung Bermotor Khusus', 6, 2021, 8000000, 2, 2),
('5.1.2.02.10', 'Belanja Jasa Ketersediaan Layanan (Availibility Payment)', 5, 2021, 21450000, 2, 2),
('5.1.2.02.10.0010', 'Belanja Jasa Ketersediaan Layanan (Availibility Payment) Infrastruktur Minyak dan Gas Bumi dan Energi Terbarukan', 6, 2021, 21450000, 2, 2),
('5.2.2.05.01.0001', 'Belanja Modal Mesin Ketik', 6, 2021, 2500000, 2, 2),
('5.2.5.01.03', 'Belanja Modal Kartografi, Naskah, dan Lukisan', 5, 2021, 0, 2, 2),
('5.2.5.01.03.0003', 'Belanja Modal Lukisan dan Ukiran', 6, 2021, 0, 2, 2),
('5.1.1.02.02', 'Tambahan Penghasilan berdasarkan Tempat Bertugas ASN', 5, 2021, 0, 2, 2),
('5.1.1.02.02.0001', 'Tambahan Penghasilan berdasarkan Tempat Bertugas PNS', 6, 2021, 0, 2, 2),
('5.1.2.02.04.0133', 'Belanja Sewa Peralatan Studio Video dan Film', 6, 2021, 112000000, 2, 2),
('5.1.5.05.02.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 6, 2021, 0, 2, 2),
('5.1.5.05.01.0002', 'Belanja Hibah Barang kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 460696000, 2, 2),
('4.1.2.03.03', 'Retribusi Izin Trayek untuk Menyediakan Pelayanan Angkutan Umum', 5, 2021, 300000000, 1, 2),
('4.1.2.03.03.0001', 'Retribusi Izin Trayek untuk Menyediakan Pelayanan Angkutan Umum', 6, 2021, 300000000, 1, 2),
('5.2.2.15.01', 'Belanja Modal Alat Deteksi', 5, 2021, 2372041630, 2, 2),
('5.2.2.15.01.0003', 'Belanja Modal Alat Deteksi Lainnya', 6, 2021, 2372041630, 2, 2),
('5.2.3.01.01.0018', 'Belanja Modal Bangunan Gedung Terminal/Pelabuhan/Bandara', 6, 2021, 0, 2, 2),
('5.2.4.01.02.0013', 'Belanja Modal Jembatan Lainnya', 6, 2021, 20942005, 2, 2),
('5.2.5.01.05', 'Belanja Modal Karya Grafika (Graphic Material)', 5, 2021, 5000000, 2, 2),
('5.2.5.01.05.0002', 'Belanja Modal Karya Grafika (Graphic Material) Lainnya', 6, 2021, 5000000, 2, 2),
('5.1.2.03.03.0033', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Parkir', 6, 2021, 161000000, 2, 2),
('5.1.5.01.02', 'Belanja Hibah Barang kepada Pemerintah Pusat', 5, 2021, 2056600000, 2, 2),
('5.1.5.01.02.0001', 'Belanja Hibah Barang kepada Pemerintah Pusat', 6, 2021, 2056600000, 2, 2),
('5.2.2.14', 'Belanja Modal Alat Bantu Eksplorasi', 4, 2021, 0, 2, 2),
('5.2.2.14.01', 'Belanja Modal Alat Bantu Eksplorasi', 5, 2021, 0, 2, 2),
('5.2.2.14.01.0002', 'Belanja Modal Elektrik', 6, 2021, 0, 2, 2),
('5.1.2.03.04.0010', 'Belanja Pemeliharaan Jalan dan Jembatan- Jalan-Jalan Lainnya', 6, 2021, 200000000, 2, 2),
('5.2.2.15.02', 'Belanja Modal Alat Pelindung', 5, 2021, 0, 2, 2),
('5.2.2.15.02.0002', 'Belanja Modal Masker', 6, 2021, 0, 2, 2),
('5.2.2.15.02.0006', 'Belanja Modal Alat Pelindung Lainnya', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0028', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Persediaan Dokumen/Administrasi Tender', 6, 2021, 0, 2, 2),
('5.1.2.02.04.0364', 'Belanja Sewa Photo and Film Equipment', 6, 2021, 0, 2, 2),
('5.2.2.11', 'Belanja Modal Alat Eksplorasi', 4, 2021, 1710000000, 2, 2),
('5.2.2.11.02', 'Belanja Modal Alat Eksplorasi Geofisika', 5, 2021, 1710000000, 2, 2),
('5.2.2.11.02.0002', 'Belanja Modal Elektronik/Electric', 6, 2021, 1710000000, 2, 2),
('5.2.5.08.01.0006', 'Belanja Modal Kajian', 6, 2021, 0, 2, 2),
('4.1.2.02.09', 'Retribusi Tempat Rekreasi dan Olahraga', 5, 2021, 120000000, 1, 2),
('4.1.2.02.09.0001', 'Retribusi Pelayanan Tempat Rekreasi dan Olahraga', 6, 2021, 120000000, 1, 2),
('5.1.2.02.01.0054', 'Belanja Jasa Jalan/Tol', 6, 2021, 28000000, 2, 2),
('5.1.2.02.04.0038', 'Belanja Sewa Kendaraan Bermotor Beroda Dua', 6, 2021, 60500000, 2, 2),
('5.1.2.02.04.0117', 'Belanja Sewa Alat Kantor Lainnya', 6, 2021, 46000000, 2, 2),
('5.1.2.03.03.0037', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 2031977749, 2, 2),
('5.1.2.05.01.0002', 'Belanja Penghargaan atas Suatu Prestasi', 6, 2021, 0, 2, 2),
('5.1.2.05.01.0004', 'Belanja Penanganan Dampak Sosial Kemasyarakatan', 6, 2021, 310000000, 2, 2),
('5.2.2.05.03.0006', 'Belanja Modal Kursi Tamu di Ruangan Pejabat', 6, 2021, 46550000, 2, 2),
('5.2.2.05.03.0007', 'Belanja Modal Lemari dan Arsip Pejabat', 6, 2021, 17000000, 2, 2),
('5.2.2.19', 'Belanja Modal Peralatan Olahraga', 4, 2021, 10000000, 2, 2),
('5.2.2.19.01', 'Belanja Modal Peralatan Olahraga', 5, 2021, 10000000, 2, 2),
('5.2.2.19.01.0006', 'Belanja Modal Peralatan Olahraga Lainnya', 6, 2021, 10000000, 2, 2),
('5.2.3.01.01.0011', 'Belanja Modal Bangunan Gedung Tempat Olahraga', 6, 2021, 15409810870, 2, 2),
('5.2.5.01.01.0007', 'Belanja Modal Buku Ilmu Pengetahuan Praktis', 6, 2021, 192000000, 2, 2),
('5.1.2.02.01.0006', 'Honorarium Penyuluhan atau Pendampingan', 6, 2021, 155600000, 2, 2),
('5.1.2.02.01.0036', 'Belanja Jasa Audit/Surveillance ISO', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0037', 'Belanja Jasa Juri Perlombaan/Pertandingan', 6, 2021, 2400000, 2, 2),
('5.1.2.02.04.0043', 'Belanja Sewa Alat Angkutan Darat Bermotor Lainnya', 6, 2021, 31500000, 2, 2),
('5.1.2.02.04.0049', 'Belanja Sewa Alat Angkutan Apung Bermotor untuk Penumpang', 6, 2021, 8000000, 2, 2),
('5.1.2.02.04.0122', 'Belanja Sewa Alat Dapur', 6, 2021, 6600000, 2, 2),
('5.1.2.02.09.0015', 'Belanja Jasa Konsultansi Bidang Kepariwisataan-Jasa Konsultansi Destinasi Pariwisata', 6, 2021, 94750000, 2, 2),
('5.2.1.01.03.0011', 'Belanja Modal Tanah untuk Bangunan Bersejarah', 6, 2021, 3196000000, 2, 2),
('5.1.2.02.01.0024', 'Belanja Jasa Tenaga Arsip dan Perpustakaan', 6, 2021, 31750000, 2, 2),
('5.1.2.02.01.0045', 'Belanja Jasa Pelayanan Kearsipan', 6, 2021, 3600000, 2, 2),
('5.2.5.01.01.0005', 'Belanja Modal Buku Ilmu Bahasa', 6, 2021, 198000000, 2, 2),
('4.1.2.02.11', 'Retribusi Penjualan Produksi Usaha Daerah', 5, 2021, 1000000000, 1, 2),
('4.1.2.02.11.0003', 'Retribusi Penjualan Produksi hasil Usaha Daerah berupa Bibit atau Benih Ikan', 6, 2021, 500000000, 1, 2),
('4.1.2.02.11.0004', 'Retribusi Penjualan Produksi hasil Usaha Daerah selain Bibit atau Benih Tanaman, Ternak, dan Ikan', 6, 2021, 1000000000, 1, 2),
('4.1.2.03.04', 'Retribusi Izin Usaha Perikanan', 5, 2021, 2550000000, 1, 2),
('4.1.2.03.04.0001', 'Retribusi Pemberian Izin Kegiatan Usaha Penangkapan Ikan', 6, 2021, 2550000000, 1, 2),
('5.1.2.01.01.0011', 'Belanja Bahan-Bahan/Bibit Ternak/Bibit Ikan', 6, 2021, 39200000, 2, 2),
('5.1.2.02.01.0042', 'Belanja Jasa Pelaksanaan Transaksi Keuangan', 6, 2021, 5460000000, 2, 2),
('5.1.2.02.01.0057', 'Belanja Jasa Operator Kapal', 6, 2021, 120000000, 2, 2),
('5.1.2.03.02.0052', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Apung Bermotor-Alat Angkutan Apung Bermotor Lainnya', 6, 2021, 15000000, 2, 2),
('5.1.5.05.04', 'Belanja Hibah kepada Koperasi', 5, 2021, 604773500, 2, 2),
('5.1.5.05.04.0002', 'Belanja Hibah Barang kepada Koperasi', 6, 2021, 604773500, 2, 2),
('5.2.2.01.02', 'Belanja Modal Alat Besar Apung', 5, 2021, 9267452500, 2, 2),
('5.2.2.01.02.0004', 'Belanja Modal Kapal Tarik', 6, 2021, 20000000, 2, 2),
('5.2.2.01.02.0006', 'Belanja Modal Alat Besar Apung Lainnya', 6, 2021, 9247452500, 2, 2),
('5.2.2.01.03.0013', 'Belanja Modal Peralatan Selam', 6, 2021, 536153751, 2, 2),
('5.2.2.01.03.0016', 'Belanja Modal Alat Bantu Lainnya', 6, 2021, 4800000, 2, 2),
('4.1.2.02.11.0001', 'Retribusi Penjualan Produksi Hasil Usaha Daerah berupa Bibit atau Benih Tanaman', 6, 2021, 1552500000, 1, 2),
('5.1.2.01.01.0007', 'Belanja Bahan-Barang dalam Proses', 6, 2021, 72800000, 2, 2),
('5.1.2.03.02.0112', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pengolahan Lainnya', 6, 2021, 15000000, 2, 2),
('5.1.5.05.01.0003', 'Belanja Hibah Jasa kepada Badan dan Lembaga yang Bersifat Nirlaba, Sukarela dan Sosial yang Dibentuk Berdasarkan Peraturan Perundang-Undangan', 6, 2021, 0, 2, 2),
('5.2.2.04.01.0005', 'Belanja Modal Alat Laboratorium Pertanian', 6, 2021, 600000000, 2, 2),
('5.2.2.17', 'Belanja Modal Peralatan Proses/Produksi', 4, 2021, 120000000, 2, 2),
('5.2.2.17.01', 'Belanja Modal Unit Peralatan Proses/Produksi', 5, 2021, 120000000, 2, 2),
('5.2.2.17.01.0026', 'Belanja Modal Unit Peralatan Proses/Produksi Lainnya', 6, 2021, 120000000, 2, 2),
('5.2.4.01.01.0005', 'Belanja Modal Jalan Desa', 6, 2021, 199999200, 2, 2),
('5.1.2.01.01.0070', 'Belanja Pakaian Pelatihan Kerja', 6, 2021, 56000000, 2, 2),
('4.1.2.02.01.0001', 'Retribusi Penyewaan Tanah dan Bangunan', 6, 2021, 0, 1, 2),
('5.1.2.02.05.0030', 'Belanja Sewa Bangunan Gedung Tempat Kerja Lainnya', 6, 2021, 150000000, 2, 2),
('5.1.2.02.08.0014', 'Belanja Jasa Konsultansi Perencanaan Penataan Ruang-Jasa Perencanaan dan Perancangan Perkotaan', 6, 2021, 90000000, 2, 2),
('5.1.6.03.02', 'Belanja Bantuan Sosial Barang yang Direncanakan kepada Kelompok Masyarakat', 5, 2021, 0, 2, 2),
('5.1.6.03.02.0001', 'Belanja Bantuan Sosial Barang yang direncanakan kepada Kelompok Masyarakat', 6, 2021, 0, 2, 2),
('5.2.3.01.01.0014', 'Belanja Modal Bangunan Gedung Garasi/Pool', 6, 2021, 141230000, 2, 2),
('4.1.2.01.08', 'Retribusi Penggantian Biaya Cetak Peta', 5, 2021, 102000000, 1, 2),
('4.1.2.01.08.0001', 'Retribusi Penyediaan Peta Dasar (Garis)', 6, 2021, 2000000, 1, 2),
('4.1.2.01.08.0004', 'Retribusi Penyediaan Peta Tematik', 6, 2021, 100000000, 1, 2),
('5.1.2.01.01.0018', 'Belanja Suku Cadang-Suku Cadang Alat Studio dan Komunikasi', 6, 2021, 8070000, 2, 2),
('5.1.2.02.01.0047', 'Belanja Jasa Penyelenggaraan Acara', 6, 2021, 180000000, 2, 2),
('5.1.2.02.08.0031', 'Belanja Jasa Konsultansi Spesialis-Jasa Inspeksi Teknikal', 6, 2021, 0, 2, 2),
('5.1.2.02.09.0006', 'Belanja Jasa Konsultansi Berorientasi Bidang-Keuangan', 6, 2021, 200000000, 2, 2),
('5.1.2.02.04.0405', 'Belanja Sewa Personal Computer', 6, 2021, 4800000, 2, 2),
('4.1.3', 'Hasil Pengelolaan Kekayaan Daerah yang Dipisahkan', 3, 2021, 356265026558, 1, 2),
('4.1.3.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 4, 2021, 526848905, 1, 2),
('4.1.3.01.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 5, 2021, 526848905, 1, 2),
('4.1.3.01.01.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMN', 6, 2021, 526848905, 1, 2),
('4.1.3.02', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD', 4, 2021, 355738177653, 1, 2),
('4.1.3.02.01', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Lembaga Keuangan)', 5, 2021, 329046680000, 1, 2),
('4.1.3.02.01.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Lembaga Keuangan)', 6, 2021, 329046680000, 1, 2),
('4.1.3.02.02', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Aneka Usaha)', 5, 2021, 1691497653, 1, 2),
('4.1.3.02.02.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Aneka Usaha)', 6, 2021, 1691497653, 1, 2),
('4.1.3.02.03', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada BUMD (Bidang Air Minum)', 5, 2021, 25000000000, 1, 2),
('4.1.3.02.03.0001', 'Bagian Laba yang Dibagikan kepada Pemerintah Daerah (Dividen) atas Penyertaan Modal pada Perusahaan Milik Daerah/BUMD (Bidang Air Minum)', 6, 2021, 25000000000, 1, 2),
('4.1.4.01', 'Hasil Penjualan BMD yang Tidak Dipisahkan', 4, 2021, 5250000000, 1, 2),
('4.1.4.01.03', 'Hasil Penjualan Gedung dan Bangunan', 5, 2021, 50000000, 1, 2),
('4.1.4.01.03.0001', 'Hasil Penjualan Bangunan Gedung', 6, 2021, 50000000, 1, 2),
('4.1.4.01.06', 'Hasil Penjualan Aset Lainnya', 5, 2021, 5200000000, 1, 2),
('4.1.4.01.06.0002', 'Hasil Penjualan Aset Lainnya-Aset Lain-Lain', 6, 2021, 5200000000, 1, 2),
('4.1.4.05', 'Jasa Giro', 4, 2021, 30900000000, 1, 2),
('4.1.4.05.01', 'Jasa Giro pada Kas Daerah', 5, 2021, 30900000000, 1, 2),
('4.1.4.05.01.0001', 'Jasa Giro pada Kas Daerah', 6, 2021, 30900000000, 1, 2),
('4.1.4.08', 'Penerimaan atas Tuntutan Ganti Kerugian Keuangan Daerah', 4, 2021, 40000000, 1, 2),
('4.1.4.08.01', 'Tuntutan Ganti Kerugian Daerah terhadap Bendahara', 5, 2021, 0, 1, 2),
('4.1.4.08.01.0001', 'Tuntutan Ganti Kerugian Daerah terhadap Bendahara', 6, 2021, 0, 1, 2),
('4.1.4.08.02', 'Tuntutan Ganti Kerugian Daerah terhadap Pegawai Negeri Bukan Bendahara atau Pejabat Lain', 5, 2021, 40000000, 1, 2),
('4.1.4.08.02.0001', 'Tuntutan Ganti Kerugian Daerah terhadap Pegawai Negeri Bukan Bendahara atau Pejabat Lain', 6, 2021, 40000000, 1, 2),
('4.1.4.11', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 4, 2021, 300000000, 1, 2),
('4.1.4.11.01', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 5, 2021, 300000000, 1, 2),
('4.1.4.11.01.0001', 'Pendapatan Denda atas Keterlambatan Pelaksanaan Pekerjaan', 6, 2021, 300000000, 1, 2),
('4.1.4.15', 'Pendapatan dari Pengembalian', 4, 2021, 2650600000, 1, 2),
('4.1.4.15.03', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Gaji dan Tunjangan', 5, 2021, 2250600000, 1, 2),
('4.1.4.15.03.0001', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Gaji dan Tunjangan', 6, 2021, 2250600000, 1, 2),
('4.1.4.15.04', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Perjalanan Dinas', 5, 2021, 400000000, 1, 2),
('4.1.4.15.04.0001', 'Pendapatan dari Pengembalian Kelebihan Pembayaran Perjalanan Dinas Dalam Negeri- Perjalanan Dinas Biasa', 6, 2021, 400000000, 1, 2),
('4.2', 'PENDAPATAN TRANSFER', 2, 2021, 7317180888300, 1, 2),
('4.2.1', 'Pendapatan Transfer Pemerintah Pusat', 3, 2021, 7317180888300, 1, 2),
('4.2.1.01', 'Dana Perimbangan', 4, 2021, 7317180888300, 1, 2),
('4.2.1.01.01', 'Dana Transfer Umum-Dana Bagi Hasil (DBH)', 5, 2021, 440441729300, 1, 2),
('4.2.1.01.01.0001', 'DBH Pajak Bumi dan Bangunan', 6, 2021, 91496739000, 1, 2),
('4.2.1.01.01.0002', 'DBH PPh Pasal 21', 6, 2021, 261353359000, 1, 2),
('4.2.1.01.01.0003', 'DBH PPh Pasal 25 dan Pasal 29/WPOPDN', 6, 2021, 28946479000, 1, 2),
('4.2.1.01.01.0004', 'DBH Cukai Hasil Tembakau (CHT)', 6, 2021, 3838485300, 1, 2),
('4.2.1.01.01.0005', 'DBH Sumber Daya Alam (SDA) Minyak Bumi', 6, 2021, 48793000, 1, 2),
('4.2.1.01.01.0006', 'DBH Sumber Daya Alam (SDA) Gas Bumi', 6, 2021, 293132000, 1, 2),
('4.2.1.01.01.0007', 'DBH Sumber Daya Alam (SDA) Pengusahaan Panas Bumi', 6, 2021, 3427158000, 1, 2),
('4.2.1.01.01.0008', 'DBH Sumber Daya Alam (SDA) Mineral dan Batubara-Landrent', 6, 2021, 1672772000, 1, 2),
('4.2.1.01.01.0009', 'Dana Bagi Hasil (DBH) Sumber Daya Alam (SDA) Mineral dan Batubara-Royalty', 6, 2021, 37240967000, 1, 2),
('4.2.1.01.01.0010', 'DBH Sumber Daya Alam (SDA) Kehutanan- Provisi Sumber Daya Hutan (PSDH)', 6, 2021, 3554255000, 1, 2),
('4.2.1.01.01.0012', 'DBH Sumber Daya Alam (SDA) Kehutanan- Dana Reboisasi (DR)', 6, 2021, 8569590000, 1, 2),
('4.2.1.01.02', 'Dana Transfer Umum-Dana Alokasi Umum (DAU)', 5, 2021, 2463686589000, 1, 2),
('4.2.1.01.02.0001', 'DAU', 6, 2021, 2463686589000, 1, 2),
('4.2.1.01.03', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Fisik', 5, 2021, 351822306000, 1, 2),
('4.2.1.01.03.0004', 'DAK Fisik-Bidang Pendidikan-Reguler-SMA', 6, 2021, 194930650000, 1, 2),
('4.2.1.01.03.0005', 'DAK Fisik-Bidang Pendidikan-Reguler-SLB', 6, 2021, 1895050000, 1, 2),
('4.2.1.01.03.0011', 'DAK Fisik-Bidang Pendidikan-Reguler- Perpustakaan Daerah', 6, 2021, 500000000, 1, 2),
('4.2.1.01.03.0013', 'DAK Fisik-Bidang Kesehatan dan KB- Reguler-Pelayanan Kesehatan Dasar', 6, 2021, 1780492000, 1, 2),
('4.2.1.01.03.0014', 'DAK Fisik-Bidang Kesehatan dan KB- Reguler-Pelayanan Kesehatan Rujukan', 6, 2021, 32485929000, 1, 2),
('4.2.1.01.03.0017', 'DAK Fisik-Bidang Kesehatan dan KB- Penugasan-Penguatan Intervensi Stunting', 6, 2021, 19256700000, 1, 2),
('4.2.1.01.03.0031', 'DAK Fisik-Bidang Pertanian-Penugasan-Pembangunan/Renovasi Sarana dan Prasarana Fisik Dasar Pembangunan Pertanian', 6, 2021, 4735000000, 1, 2),
('4.2.1.01.03.0032', 'DAK Fisik-Bidang Kelautan dan Perikanan- Penugasan', 6, 2021, 350000000, 1, 2),
('4.2.1.01.03.0034', 'DAK Fisik-Bidang Jalan-Reguler-Jalan', 6, 2021, 13133236000, 1, 2),
('4.2.1.01.03.0035', 'DAK Fisik-Bidang Jalan-Penugasan-Jalan', 6, 2021, 70294554000, 1, 2),
('4.2.1.01.03.0043', 'DAK Fisik-Bidang Irigasi-Penugasan', 6, 2021, 11999999000, 1, 2),
('4.2.1.01.03.0046', 'DAK Fisik-Bidang Lingkungan Hidup dan Kehutanan-Penugasan-Kehutanan', 6, 2021, 460696000, 1, 2),
('4.2.1.01.04', 'Dana Transfer Khusus-Dana Alokasi Khusus (DAK) Non Fisik', 5, 2021, 4061230264000, 1, 2),
('4.2.1.01.04.0001', 'DAK Non Fisik-BOS Reguler', 6, 2021, 3338953850000, 1, 2),
('4.2.1.01.04.0002', 'DAK Non Fisik-BOS Afirmasi', 6, 2021, 19620000000, 1, 2),
('4.2.1.01.04.0003', 'DAK Non Fisik-BOS Kinerja', 6, 2021, 35280000000, 1, 2),
('4.2.1.01.04.0004', 'DAK Non Fisik-TPG PNSD', 6, 2021, 627207840000, 1, 2),
('4.2.1.01.04.0005', 'DAK Non Fisik-Tamsil Guru PNSD', 6, 2021, 3090000000, 1, 2),
('4.2.1.01.04.0006', 'DAK Non Fisik-TKG PNSD', 6, 2021, 19940535000, 1, 2),
('4.2.1.01.04.0009', 'DAK Non Fisik-BOP Museum dan Taman Budaya-Museum', 6, 2021, 1593200000, 1, 2),
('4.2.1.01.04.0011', 'DAK Non Fisik-BOKKB-BOK', 6, 2021, 9492240000, 1, 2),
('4.2.1.01.04.0014', 'DAK Non Fisik-BOKKB-Jaminan Persalinan', 6, 2021, 558332000, 1, 2),
('4.2.1.01.04.0016', 'DAK Non Fisik-PK2UKM', 6, 2021, 2352080000, 1, 2),
('4.2.1.01.04.0017', 'DAK Non Fisik-Dana Pelayanan Administrasi Kependudukan', 6, 2021, 2279609000, 1, 2),
('4.2.1.01.04.0018', 'DAK Non Fisik-Dana Pelayanan Kepariwisataan', 6, 2021, 862578000, 1, 2),
('4.3', 'LAIN-LAIN PENDAPATAN DAERAH YANG SAH', 2, 2021, 90068000000, 1, 2),
('4.3.1', 'Pendapatan Hibah', 3, 2021, 59568000000, 1, 2),
('4.3.1.01', 'Pendapatan Hibah dari Pemerintah Pusat', 4, 2021, 53500000000, 1, 2),
('4.3.1.01.01', 'Pendapatan Hibah dari Pemerintah Pusat', 5, 2021, 53500000000, 1, 2),
('4.3.1.01.01.0001', 'Pendapatan Hibah dari Pemerintah Pusat', 6, 2021, 53500000000, 1, 2),
('4.3.1.03', 'Pendapatan Hibah dari Kelompok Masyarakat/Perorangan Dalam Negeri', 4, 2021, 500000000, 1, 2),
('4.3.1.03.01', 'Pendapatan Hibah dari Kelompok Masyarakat/Perorangan Dalam Negeri', 5, 2021, 500000000, 1, 2),
('4.3.1.03.01.0001', 'Pendapatan Hibah dari Kelompok Masyarakat Dalam Negeri/Perorangan dalam Negeri', 6, 2021, 500000000, 1, 2),
('4.3.1.04', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri/Luar Negeri', 4, 2021, 5568000000, 1, 2),
('4.3.1.04.01', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri', 5, 2021, 5568000000, 1, 2),
('4.3.1.04.01.0001', 'Pendapatan Hibah dari Badan/Lembaga/ Organisasi Dalam Negeri', 6, 2021, 5568000000, 1, 2),
('4.3.3', 'Lain-lain Pendapatan Sesuai dengan Ketentuan Peraturan Perundang-Undangan', 3, 2021, 30500000000, 1, 2),
('4.3.3.01', 'Lain-lain Pendapatan', 4, 2021, 30500000000, 1, 2),
('4.3.3.01.01', 'Pendapatan Hibah Dana BOS', 5, 2021, 20000000000, 1, 2),
('4.3.3.01.01.0001', 'Pendapatan Hibah Dana BOS', 6, 2021, 20000000000, 1, 2),
('4.3.3.01.02', 'Pendapatan atas Pengembalian Hibah', 5, 2021, 10500000000, 1, 2),
('4.3.3.01.02.0001', 'Pendapatan atas Pengembalian Hibah pada Pemerintah', 6, 2021, 0, 1, 2),
('4.3.3.01.02.0005', 'Pendapatan atas Pengembalian Hibah pada Badan, Lembaga, dan Organisasi Kemasyarakatan yang Berbadan hukum Indonesia', 6, 2021, 10500000000, 1, 2),
('5.1.2.02.04.0116', 'Belanja Sewa Alat Penyimpan Perlengkapan Kantor', 6, 2021, 30000000, 2, 2),
('5.1.2.02.09.0002', 'Belanja Jasa Konsultansi Berorientasi Bidang-Transportasi', 6, 2021, 350000000, 2, 2),
('5.3', 'BELANJA TIDAK TERDUGA', 2, 2021, 21058512853, 2, 2),
('5.3.1', 'Belanja Tidak Terduga', 3, 2021, 21058512853, 2, 2),
('5.3.1.01', 'Belanja Tidak Terduga', 4, 2021, 21058512853, 2, 2),
('5.3.1.01.01', 'Belanja Tidak Terduga', 5, 2021, 21058512853, 2, 2),
('5.3.1.01.01.0001', 'Belanja Tidak Terduga', 6, 2021, 21058512853, 2, 2),
('5.4', 'BELANJA TRANSFER', 2, 2021, 2576947489813, 2, 2),
('5.4.1', 'Belanja Bagi Hasil', 3, 2021, 2576947489813, 2, 2),
('5.4.1.01', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten/Kota dan Desa', 4, 2021, 2576947489813, 2, 2),
('5.4.1.01.01', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten', 5, 2021, 1434444567946, 2, 2),
('5.4.1.01.01.0001', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kabupaten', 6, 2021, 1434444567946, 2, 2),
('5.4.1.01.02', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kota', 5, 2021, 1142502921867, 2, 2),
('5.4.1.01.02.0001', 'Belanja Bagi Hasil Pajak Daerah Kepada Pemerintahan Kota', 6, 2021, 1142502921867, 2, 2),
('6.1', 'PENERIMAAN PEMBIAYAAN', 2, 2021, 476782631305, 3, 2),
('6.1.1', 'Sisa Lebih Perhitungan Anggaran Tahun Sebelumnya', 3, 2021, 476782631305, 3, 2),
('6.1.1.05', 'Penghematan Belanja', 4, 2021, 476782631305, 3, 2),
('6.1.1.05.03', 'Sisa Penggunaan Belanja Tidak Terduga', 5, 2021, 476782631305, 3, 2),
('6.1.1.05.03.0001', 'Sisa Penggunaan Belanja Tidak Terduga', 6, 2021, 476782631305, 3, 2),
('6.2', 'PENGELUARAN PEMBIAYAAN', 2, 2021, 210500000000, 4, 2),
('6.2.2', 'Penyertaan Modal Daerah', 3, 2021, 210500000000, 4, 2),
('6.2.2.02', 'Penyertaan Modal Daerah pada Badan Usaha Milik Daerah (BUMD)', 4, 2021, 210500000000, 4, 2),
('6.2.2.02.01', 'Penyertaan Modal Daerah pada BUMD', 5, 2021, 210500000000, 4, 2),
('6.2.2.02.01.0001', 'Penyertaan Modal Daerah pada BUMD', 6, 2021, 210500000000, 4, 2),
('5.1.2.02.01.0011', 'Honorarium Penyelenggaraan Kegiatan Pendidikan dan Pelatihan', 6, 2021, 528800000, 2, 2),
('5.1.2.02.08.0029', 'Belanja Jasa Konsultansi Spesialis-Jasa Pengujian dan Analisa Parameter Fisikal', 6, 2021, 1500000, 2, 2),
('5.1.1.04', 'Belanja Gaji dan Tunjangan DPRD', 4, 2021, 123747510610, 2, 2),
('5.1.1.04.01', 'Belanja Uang Representasi DPRD', 5, 2021, 3222939000, 2, 2),
('5.1.1.04.01.0001', 'Belanja Uang Representasi DPRD', 6, 2021, 3222939000, 2, 2),
('5.1.1.04.02', 'Belanja Tunjangan Keluarga DPRD', 5, 2021, 368501000, 2, 2),
('5.1.1.04.02.0001', 'Belanja Tunjangan Keluarga DPRD', 6, 2021, 368501000, 2, 2),
('5.1.1.04.03', 'Belanja Tunjangan Beras DPRD', 5, 2021, 281185000, 2, 2),
('5.1.1.04.03.0001', 'Belanja Tunjangan Beras DPRD', 6, 2021, 281185000, 2, 2),
('5.1.1.04.04', 'Belanja Uang Paket DPRD', 5, 2021, 396113000, 2, 2),
('5.1.1.04.04.0001', 'Belanja Uang Paket DPRD', 6, 2021, 396113000, 2, 2),
('5.1.1.04.05', 'Belanja Tunjangan Jabatan DPRD', 5, 2021, 4640215000, 2, 2),
('5.1.1.04.05.0001', 'Belanja Tunjangan Jabatan DPRD', 6, 2021, 4640215000, 2, 2),
('5.1.1.04.06', 'Belanja Tunjangan Alat Kelengkapan DPRD', 5, 2021, 671243000, 2, 2),
('5.1.1.04.06.0001', 'Belanja Tunjangan Alat Kelengkapan DPRD', 6, 2021, 671243000, 2, 2),
('5.1.1.04.07', 'Belanja Tunjangan Alat Kelengkapan Lainnya DPRD', 5, 2021, 95306000, 2, 2),
('5.1.1.04.07.0001', 'Belanja Tunjangan Alat Kelengkapan Lainnya DPRD', 6, 2021, 95306000, 2, 2),
('5.1.1.04.08', 'Belanja Tunjangan Komunikasi Intensif Pimpinan dan Anggota DPRD', 5, 2021, 25200000000, 2, 2),
('5.1.1.04.08.0001', 'Belanja Tunjangan Komunikasi Intensif Pimpinan dan Anggota DPRD', 6, 2021, 25200000000, 2, 2),
('5.1.1.04.09', 'Belanja Tunjangan Reses DPRD', 5, 2021, 6300000000, 2, 2),
('5.1.1.04.09.0001', 'Belanja Tunjangan Reses DPRD', 6, 2021, 6300000000, 2, 2),
('5.1.1.04.10', 'Belanja Pembebanan PPh kepada Pimpinan dan Anggota DPRD', 5, 2021, 158615000, 2, 2),
('5.1.1.04.10.0001', 'Belanja Pembebanan PPh kepada Pimpinan dan Anggota DPRD', 6, 2021, 158615000, 2, 2),
('5.1.1.04.12', 'Belanja Tunjangan Kesejahteraan Pimpinan dan Anggota DPRD', 5, 2021, 53929185000, 2, 2),
('5.1.1.04.12.0001', 'Belanja Iuran Jaminan Kesehatan bagi DPRD', 6, 2021, 3588853000, 2, 2),
('5.1.1.04.12.0002', 'Belanja Jaminan Kecelakaan Kerja DPRD', 6, 2021, 6583000, 2, 2),
('5.1.1.04.12.0003', 'Belanja Jaminan Kematian DPRD', 6, 2021, 19749000, 2, 2),
('5.1.1.04.12.0004', 'Belanja Tunjangan Perumahan DPRD', 6, 2021, 50314000000, 2, 2),
('5.1.1.04.13', 'Belanja Tunjangan Transportasi DPRD', 5, 2021, 28356083610, 2, 2),
('5.1.1.04.13.0001', 'Belanja Tunjangan Transportasi DPRD', 6, 2021, 28356083610, 2, 2),
('5.1.1.04.14', 'Belanja Uang Jasa Pengabdian DPRD', 5, 2021, 128125000, 2, 2),
('5.1.1.04.14.0001', 'Belanja Uang Jasa Pengabdian DPRD', 6, 2021, 128125000, 2, 2),
('5.1.1.06', 'Belanja Penerimaan Lainnya Pimpinan DPRD serta KDH/WKDH', 4, 2021, 9395520381, 2, 2),
('5.1.1.06.01', 'Belanja Dana Operasional Pimpinan DPRD', 5, 2021, 676800000, 2, 2),
('5.1.1.06.01.0001', 'Belanja Dana Operasional Pimpinan DPRD', 6, 2021, 676800000, 2, 2),
('5.1.2.02.01.0066', 'Belanja Registrasi/Keanggotaan', 6, 2021, 76000000, 2, 2),
('5.1.2.02.08.0007', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa untuk Konstruksi Pondasi serta Struktur Bangunan', 6, 2021, 36210000, 2, 2),
('5.1.2.02.08.0034', 'Belanja Jasa Konsultansi Lainnya-Jasa Manajemen Proyek Terkait Konstruksi Bangunan', 6, 2021, 15490000, 2, 2),
('5.2.4.01.02.0006', 'Belanja Modal Jembatan pada Jalan Tol', 6, 2021, 0, 2, 2),
('4.1.1', 'Pajak Daerah', 3, 2021, 5706083586846, 1, 2),
('4.1.1.01', 'Pajak Kendaraan Bermotor (PKB)', 4, 2021, 2293605395740, 1, 2),
('4.1.1.01.01', 'PKB-Mobil Penumpang-Sedan', 5, 2021, 34486371316, 1, 2),
('4.1.1.01.01.0001', 'PKB-Mobil Penumpang-Sedan-Pribadi', 6, 2021, 32938191268, 1, 2),
('4.1.1.01.01.0002', 'PKB-Mobil Penumpang-Sedan-Umum', 6, 2021, 52690074, 1, 2),
('4.1.1.01.01.0004', 'PKB-Mobil Penumpang-Sedan-Pemerintah Daerah', 6, 2021, 1495489974, 1, 2),
('4.1.1.01.02', 'PKB-Mobil Penumpang-Jeep', 5, 2021, 288950125987, 1, 2),
('4.1.1.01.02.0001', 'PKB-Mobil Penumpang-Jeep-Pribadi', 6, 2021, 284462179034, 1, 2),
('4.1.1.01.02.0004', 'PKB-Mobil Penumpang-Jeep-Pemerintah Daerah', 6, 2021, 4487946953, 1, 2),
('4.1.1.01.03', 'PKB-Mobil Penumpang-Minibus', 5, 2021, 1153718532079, 1, 2),
('4.1.1.01.03.0001', 'PKB-Mobil Penumpang-Minibus-Pribadi', 6, 2021, 1136537356089, 1, 2),
('4.1.1.01.03.0002', 'PKB-Mobil Penumpang-Minibus-Umum', 6, 2021, 2732571732, 1, 2),
('4.1.1.01.03.0004', 'PKB-Mobil Penumpang-Minibus-Pemerintah Daerah', 6, 2021, 14448604258, 1, 2),
('4.1.1.01.04', 'PKB-Mobil Bus-Microbus', 5, 2021, 7496239543, 1, 2),
('4.1.1.01.04.0001', 'PKB-Mobil Bus-Microbus-Pribadi', 6, 2021, 6298113815, 1, 2),
('4.1.1.01.04.0002', 'PKB-Mobil Bus-Microbus-Umum', 6, 2021, 288142357, 1, 2),
('4.1.1.01.04.0004', 'PKB-Mobil Bus-Microbus-Pemerintah Daerah', 6, 2021, 909983371, 1, 2),
('4.1.1.01.05', 'PKB-Mobil Bus-Bus', 5, 2021, 5756557775, 1, 2),
('4.1.1.01.05.0001', 'PKB-Mobil Bus-Bus-Pribadi', 6, 2021, 3218450071, 1, 2),
('4.1.1.01.05.0002', 'PKB-Mobil Bus-Bus-Umum', 6, 2021, 2219395524, 1, 2),
('4.1.1.01.05.0004', 'PKB-Mobil Bus-Bus-Pemerintah Daerah', 6, 2021, 318712180, 1, 2),
('4.1.1.01.06', 'PKB-Mobil Barang/Beban-Pick Up', 5, 2021, 166014095512, 1, 2),
('4.1.1.01.06.0001', 'PKB-Mobil Barang/Beban-Pick Up-Pribadi', 6, 2021, 161281690854, 1, 2),
('4.1.1.01.06.0002', 'PKB-Mobil Barang/Beban-Pick Up-Umum', 6, 2021, 162434393, 1, 2),
('4.1.1.01.06.0004', 'PKB-Mobil Barang/Beban-Pick Up-Pemerintah Daerah', 6, 2021, 4569970265, 1, 2),
('4.1.1.01.07', 'PKB-Mobil Barang/Beban-Light Truck', 5, 2021, 3447376850, 1, 2),
('4.1.1.01.07.0001', 'PKB-Mobil Barang/Beban-Light Truck-Pribadi', 6, 2021, 2944372688, 1, 2),
('4.1.1.01.07.0002', 'PKB-Mobil Barang/Beban-Light Truck-Umum', 6, 2021, 409420575, 1, 2),
('4.1.1.01.07.0004', 'PKB-Mobil Barang/Beban-Light Truck- Pemerintah Daerah', 6, 2021, 93583587, 1, 2),
('4.1.1.01.08', 'PKB-Mobil Barang/Beban-Truck', 5, 2021, 193333212472, 1, 2),
('4.1.1.01.08.0001', 'PKB-Mobil Barang/Beban-Truck-Pribadi', 6, 2021, 77124207818, 1, 2),
('4.1.1.01.08.0002', 'PKB-Mobil Barang/Beban-Truck-Umum', 6, 2021, 112369970324, 1, 2),
('4.1.1.01.08.0004', 'PKB-Mobil Barang/Beban-Truck-Pemerintah Daerah', 6, 2021, 3839034330, 1, 2),
('4.1.1.01.10', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua', 5, 2021, 439365025852, 1, 2),
('4.1.1.01.10.0001', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Pribadi', 6, 2021, 437341214161, 1, 2),
('4.1.1.01.10.0002', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Umum', 6, 2021, 3353538, 1, 2),
('4.1.1.01.10.0004', 'PKB-Sepeda Motor-Sepeda Motor Roda Dua- Pemerintah Daerah', 6, 2021, 2020458153, 1, 2),
('4.1.1.01.11', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga', 5, 2021, 743454416, 1, 2),
('4.1.1.01.11.0001', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Pribadi', 6, 2021, 458627030, 1, 2),
('4.1.1.01.11.0002', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Umum', 6, 2021, 103261025, 1, 2),
('4.1.1.01.11.0004', 'PKB-Sepeda Motor-Sepeda Motor Roda Tiga- Pemerintah Daerah', 6, 2021, 181566361, 1, 2),
('4.1.1.01.13', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar', 5, 2021, 294403938, 1, 2),
('4.1.1.01.13.0001', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar-Pribadi', 6, 2021, 259363459, 1, 2),
('4.1.1.01.13.0002', 'PKB-Kendaraan Khusus Alat Berat/Alat Besar-Umum', 6, 2021, 35040479, 1, 2),
('4.1.1.02', 'Bea Balik Nama Kendaraan Bermotor (BBNKB)', 4, 2021, 1238578316758, 1, 2),
('4.1.1.02.01', 'BBNKB-Mobil Penumpang-Sedan', 5, 2021, 16590388973, 1, 2),
('4.1.1.02.01.0001', 'BBNKB-Mobil Penumpang-Sedan', 6, 2021, 16590388973, 1, 2),
('4.1.1.02.02', 'BBNKB-Mobil Penumpang-Jeep', 5, 2021, 139005491166, 1, 2),
('4.1.1.02.02.0001', 'BBNKB-Mobil Penumpang-Jeep', 6, 2021, 139005491166, 1, 2),
('4.1.1.02.03', 'BBNKB-Mobil Penumpang-Minibus', 5, 2021, 555020388626, 1, 2),
('4.1.1.02.03.0001', 'BBNKB-Mobil Penumpang-Minibus', 6, 2021, 555020388626, 1, 2),
('4.1.1.02.04', 'BBNKB-Mobil Bus-Microbus', 5, 2021, 5138556711, 1, 2),
('4.1.1.02.04.0001', 'BBNKB-Mobil Bus-Microbus', 6, 2021, 5138556711, 1, 2),
('4.1.1.02.05', 'BBNKB-Mobil Bus-Bus', 5, 2021, 3946031663, 1, 2),
('4.1.1.02.05.0001', 'BBNKB-Mobil Bus-Bus', 6, 2021, 3946031663, 1, 2),
('4.1.1.02.06', 'BBNKB-Mobil Barang/Beban-Pick Up', 5, 2021, 64129908262, 1, 2),
('4.1.1.02.06.0001', 'BBNKB-Mobil Barang/Beban-Pick Up', 6, 2021, 64129908262, 1, 2),
('4.1.1.02.07', 'BBNKB-Mobil Barang/Beban-Light Truck', 5, 2021, 1331693917, 1, 2),
('4.1.1.02.07.0001', 'BBNKB-Mobil Barang/Beban-Light Truck', 6, 2021, 1331693917, 1, 2),
('4.1.1.02.08', 'BBNKB-Mobil Barang/Beban-Truck', 5, 2021, 74683063156, 1, 2),
('4.1.1.02.08.0001', 'BBNKB-Mobil Barang/Beban-Truck', 6, 2021, 74683063156, 1, 2),
('4.1.1.02.10', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Dua', 5, 2021, 378092168889, 1, 2),
('4.1.1.02.10.0001', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Dua', 6, 2021, 378092168889, 1, 2),
('4.1.1.02.11', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Tiga', 5, 2021, 639773937, 1, 2),
('4.1.1.02.11.0001', 'BBNKB-Sepeda Motor-Sepeda Motor Roda Tiga', 6, 2021, 639773937, 1, 2),
('4.1.1.02.13', 'BBNKB-Kendaraan Khusus Alat Berat', 5, 2021, 851458, 1, 2),
('4.1.1.02.13.0001', 'BBNKB-Kendaraan Khusus Alat Berat', 6, 2021, 851458, 1, 2),
('4.1.1.03', 'Pajak Bahan Bakar Kendaraan Bermotor (PBBKB)', 4, 2021, 1036674544484, 1, 2),
('4.1.1.03.01', 'PBBKB-Bahan Bakar Bensin', 5, 2021, 500361673351, 1, 2),
('4.1.1.03.01.0001', 'PBBKB Bahan Bakar Bensin', 6, 2021, 500361673351, 1, 2),
('4.1.1.03.02', 'PBBKB-Bahan Bakar Solar', 5, 2021, 309888447109, 1, 2),
('4.1.1.03.02.0001', 'PBBKB Bahan Bakar Solar', 6, 2021, 309888447109, 1, 2),
('4.1.1.03.04', 'PBBKB-Bahan Bakar Lainnya', 5, 2021, 226424424024, 1, 2),
('4.1.1.03.04.0001', 'PBBKB Bahan Bakar Lainnya', 6, 2021, 226424424024, 1, 2),
('4.1.1.04', 'Pajak Air Permukaan', 4, 2021, 76489854175, 1, 2),
('4.1.1.04.01', 'Pajak Air Permukaan', 5, 2021, 76489854175, 1, 2),
('4.1.1.04.01.0001', 'Pajak Air Permukaan', 6, 2021, 76489854175, 1, 2),
('4.1.1.05', 'Pajak Rokok', 4, 2021, 1060735475689, 1, 2),
('4.1.1.05.01', 'Pajak Rokok', 5, 2021, 1060735475689, 1, 2),
('4.1.1.05.01.0001', 'Pajak Rokok', 6, 2021, 1060735475689, 1, 2),
('4.1.4.12', 'Pendapatan Denda Pajak Daerah', 4, 2021, 69677525781, 1, 2),
('4.1.4.12.01', 'Pendapatan Denda Pajak Kendaraan Bermotor (PKB)', 5, 2021, 67197481083, 1, 2),
('4.1.4.12.01.0001', 'Pendapatan Denda PKB-Mobil Penumpang- Sedan', 6, 2021, 880457284, 1, 2),
('4.1.4.12.01.0002', 'Pendapatan Denda PKB-Mobil Penumpang- Jeep', 6, 2021, 8091766171, 1, 2),
('4.1.4.12.01.0003', 'Pendapatan Denda PKB-Mobil Penumpang- Minibus', 6, 2021, 30884510721, 1, 2),
('4.1.4.12.01.0004', 'Pendapatan Denda PKB-Mobil Bus-Microbus', 6, 2021, 250753677, 1, 2),
('4.1.4.12.01.0005', 'Pendapatan Denda PKB-Mobil Bus-Bus', 6, 2021, 192560285, 1, 2),
('4.1.4.12.01.0006', 'Pendapatan Denda PKB-Mobil Barang/Beban-Pick Up', 6, 2021, 5268587843, 1, 2),
('4.1.4.12.01.0007', 'Pendapatan Denda PKB-Mobil Barang/Beban-Light Truck', 6, 2021, 109405215, 1, 2),
('4.1.4.12.01.0008', 'Pendapatan Denda PKB-Mobil Barang/Beban-Truck', 6, 2021, 6850281498, 1, 2),
('4.1.4.12.01.0010', 'Pendapatan Denda PKB-Sepeda Motor- Sepeda Motor Roda Dua', 6, 2021, 14631206528, 1, 2),
('4.1.4.12.01.0011', 'Pendapatan Denda PKB-Sepeda Motor- Sepeda Motor Roda Tiga', 6, 2021, 22338923, 1, 2),
('4.1.4.12.01.0013', 'Pendapatan Denda PKB-Kendaraan Khusus Alat Berat/Alat Besar', 6, 2021, 15612938, 1, 2),
('4.1.4.12.02', 'Pendapatan Denda Bea Balik Nama Kendaraan Bermotor (BBNKB)', 5, 2021, 2470044698, 1, 2),
('4.1.4.12.02.0001', 'Pendapatan Denda BBNKB-Mobil Penumpang-Sedan', 6, 2021, 23291525, 1, 2),
('4.1.4.12.02.0002', 'Pendapatan Denda BBNKB-Mobil Penumpang-Jeep', 6, 2021, 195152134, 1, 2),
('4.1.4.12.02.0003', 'Pendapatan Denda BBNKB-Mobil Penumpang-Minibus', 6, 2021, 779202408, 1, 2),
('4.1.4.12.02.0004', 'Pendapatan Denda BBNKB-Mobil Bus- Microbus', 6, 2021, 12482730, 1, 2),
('4.1.4.12.02.0005', 'Pendapatan Denda BBNKB-Mobil Bus-Bus', 6, 2021, 9585814, 1, 2),
('4.1.4.12.02.0006', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Pick Up', 6, 2021, 206399547, 1, 2),
('4.1.4.12.02.0007', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Light Truck', 6, 2021, 4286004, 1, 2),
('4.1.4.12.02.0008', 'Pendapatan Denda BBNKB-Mobil Barang/Beban-Truck', 6, 2021, 240364455, 1, 2),
('4.1.4.12.02.0010', 'Pendapatan Denda BBNKB-Sepeda Motor- Sepeda Motor Roda Dua', 6, 2021, 997542037, 1, 2),
('4.1.4.12.02.0011', 'Pendapatan Denda BBNKB-Sepeda Motor- Sepeda Motor Roda Tiga', 6, 2021, 1687952, 1, 2),
('4.1.4.12.02.0013', 'Pendapatan Denda BBNKB-Kendaraan Khusus Alat Berat', 6, 2021, 50092, 1, 2),
('4.1.4.12.04', 'Pendapatan Denda Pajak Air Permukaan', 5, 2021, 10000000, 1, 2),
('4.1.4.12.04.0001', 'Pendapatan Denda Pajak Air Permukaan', 6, 2021, 10000000, 1, 2),
('5.1.1.03.01.0002', 'Belanja Insentif bagi ASN atas Pemungutan Bea Balik Nama Kendaraan Bermotor', 6, 2021, 35717946693, 2, 2),
('5.1.1.03.01.0003', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 20492840587, 2, 2),
('5.1.1.03.01.0004', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Air Permukaan', 6, 2021, 2426329265, 2, 2),
('5.1.1.05', 'Belanja Gaji dan Tunjangan KDH/WKDH', 4, 2021, 574231878, 2, 2),
('5.1.1.05.10', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Daerah', 5, 2021, 1922724380, 2, 2),
('5.1.1.05.10.0001', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Kendaraan Bermotor bagi KDH/WKDH', 6, 2021, 641280920, 2, 2),
('5.1.1.05.10.0002', 'Belanja Insentif Pemungutan bagi KDH/WKDH atas Bea Balik Nama Kendaraan Bermotor', 6, 2021, 849753950, 2, 2),
('5.1.1.05.10.0003', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 402026445, 2, 2),
('5.1.1.05.10.0004', 'Belanja Insentif bagi KDH/WKDH atas Pemungutan Pajak Air Permukaan', 6, 2021, 29663065, 2, 2),
('5.1.2.02.04.0410', 'Belanja Sewa Peralatan Jaringan', 6, 2021, 416058500, 2, 2),
('5.1.2.02.13', 'Belanja Jasa Insentif bagi Pegawai Non ASN atas Pemungutan Pajak Daerah', 5, 2021, 11625309174, 2, 2),
('5.1.2.02.13.0001', 'Belanja Jasa Insentif bagi Pegawai Non ASN atas Pemungutan Pajak Kendaraan Bermotor', 6, 2021, 4922100651, 2, 2),
('5.1.2.02.13.0002', 'Belanja Jasa Insentif Pegawai Non ASN atas Pemungutan Bea Balik Nama Kendaraan Bermotor', 6, 2021, 3593184860, 2, 2),
('5.1.2.02.13.0003', 'Belanja Jasa Insentif Pegawai Non ASN atas Pemungutan Pajak Bahan Bakar Kendaraan Bermotor', 6, 2021, 3110023663, 2, 2),
('5.1.2.02.01.0021', 'Belanja Jasa Tenaga Sumber Daya Air', 6, 2021, 17500000, 2, 2),
('5.1.2.02.07.0030', 'Belanja Sewa Alat Peraga Kesenian', 6, 2021, 3500000, 2, 2),
('5.2.3.04.01.0003', 'Belanja Modal Pilar/Tugu/Tanda Lainnya', 6, 2021, 180000000, 2, 2);
INSERT INTO `data_uraian_kegiatan_pemko` (`kode_rekening`, `uraian`, `level`, `tahun`, `anggaran`, `jenis`, `st_anggaran`) VALUES
('5.1.1.05.01', 'Belanja Gaji Pokok KDH/WKDH', 5, 2021, 77112000, 2, 2),
('5.1.1.05.01.0001', 'Belanja Gaji Pokok KDH/WKDH', 6, 2021, 77112000, 2, 2),
('5.1.1.05.02', 'Belanja Tunjangan Keluarga KDH/WKDH', 5, 2021, 9939000, 2, 2),
('5.1.1.05.02.0001', 'Belanja Tunjangan Keluarga KDH/WKDH', 6, 2021, 9939000, 2, 2),
('5.1.1.05.03', 'Belanja Tunjangan Jabatan KDH/WKDH', 5, 2021, 138802000, 2, 2),
('5.1.1.05.03.0001', 'Belanja Tunjangan Jabatan KDH/WKDH', 6, 2021, 138802000, 2, 2),
('5.1.1.05.04', 'Belanja Tunjangan Beras KDH/WKDH', 5, 2021, 6889780, 2, 2),
('5.1.1.05.04.0001', 'Belanja Tunjangan Beras KDH/WKDH', 6, 2021, 6889780, 2, 2),
('5.1.1.05.05', 'Belanja Tunjangan PPh/Tunjangan Khusus KDH/WKDH', 5, 2021, 10588000, 2, 2),
('5.1.1.05.05.0001', 'Belanja Tunjangan PPh/Tunjangan Khusus KDH/WKDH', 6, 2021, 10588000, 2, 2),
('5.1.1.05.06', 'Belanja Pembulatan Gaji KDH/WKDH', 5, 2021, 2098, 2, 2),
('5.1.1.05.06.0001', 'Belanja Pembulatan Gaji KDH/WKDH', 6, 2021, 2098, 2, 2),
('5.1.1.05.07', 'Belanja Iuran Jaminan Kesehatan bagi KDH/WKDH', 5, 2021, 330264000, 2, 2),
('5.1.1.05.07.0001', 'Belanja Iuran Jaminan Kesehatan bagi KDH/WKDH', 6, 2021, 330264000, 2, 2),
('5.1.1.05.08', 'Belanja Iuran Jaminan Kecelakaan Kerja KDH/WKDH', 5, 2021, 159000, 2, 2),
('5.1.1.05.08.0001', 'Belanja Iuran Jaminan Kecelakaan Kerja KDH/WKDH', 6, 2021, 159000, 2, 2),
('5.1.1.05.09', 'Belanja Iuran Jaminan Kematian KDH/WKDH', 5, 2021, 476000, 2, 2),
('5.1.1.05.09.0001', 'Belanja Iuran Jaminan Kematian KDH/WKDH', 6, 2021, 476000, 2, 2),
('5.1.1.06.02', 'Belanja Dana Operasional KDH/WKDH', 5, 2021, 9395520381, 2, 2),
('5.1.1.06.02.0001', 'Belanja Dana Operasional KDH/WKDH', 6, 2021, 9395520381, 2, 2),
('5.1.2.01.01.0059', 'Belanja Pakaian Dinas KDH dan WKDH', 6, 2021, 661000000, 2, 2),
('5.1.2.02.07.0013', 'Belanja Sewa Audio Visual', 6, 2021, 500000000, 2, 2),
('5.1.2.03.03.0038', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal-Rumah Negara Golongan I', 6, 2021, 0, 2, 2),
('5.1.2.03.04.0048', 'Belanja Pemeliharaan Bangunan Air- Bangunan Pengaman Sungai/Pantai dan Penanggulangan Bencana Alam-Bangunan Pengaman Sungai/Pantai dan Penanggulangan ', 6, 2021, 400000000, 2, 2),
('5.2.2.01.03.0001', 'Belanja Modal Alat Penarik', 6, 2021, 218000000, 2, 2),
('5.2.2.02.01.0002', 'Belanja Modal Kendaraan Bermotor Penumpang', 6, 2021, 4650000000, 2, 2),
('5.2.2.02.02.0002', 'Belanja Modal Kendaraan Tak Bermotor Penumpang', 6, 2021, 100000000, 2, 2),
('5.2.2.07.02.0004', 'Belanja Modal Alat Kesehatan Olahraga', 6, 2021, 265000000, 2, 2),
('5.2.4.03.10', 'Belanja Modal Instalasi Lain', 5, 2021, 400000000, 2, 2),
('5.2.4.03.10.0001', 'Belanja Modal Instalasi Lain', 6, 2021, 400000000, 2, 2),
('5.1.2.03.02.0133', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Studio- Peralatan Studio Video dan Film', 6, 2021, 200000000, 2, 2),
('4', 'PENDAPATAN DAERAH', 1, 2021, 2200000000, 1, 2),
('4.1', 'PENDAPATAN ASLI DAERAH (PAD)', 2, 2021, 2200000000, 1, 2),
('4.1.2', 'Retribusi Daerah', 3, 2021, 2200000000, 1, 2),
('4.1.2.02', 'Retribusi Jasa Usaha', 4, 2021, 2200000000, 1, 2),
('5', 'BELANJA DAERAH', 1, 2021, 3265837124, 2, 2),
('5.1', 'BELANJA OPERASI', 2, 2021, 3131387124, 2, 2),
('5.1.1', 'Belanja Pegawai', 3, 2021, 155880000, 2, 2),
('5.1.1.01', 'Belanja Gaji dan Tunjangan ASN', 4, 2021, 30860843291, 2, 2),
('5.1.1.01.01', 'Belanja Gaji Pokok ASN', 5, 2021, 22011777000, 2, 2),
('5.1.1.01.01.0001', 'Belanja Gaji Pokok PNS', 6, 2021, 22011777000, 2, 2),
('5.1.1.01.02', 'Belanja Tunjangan Keluarga ASN', 5, 2021, 2079514000, 2, 2),
('5.1.1.01.02.0001', 'Belanja Tunjangan Keluarga PNS', 6, 2021, 2079514000, 2, 2),
('5.1.1.01.03', 'Belanja Tunjangan Jabatan ASN', 5, 2021, 1460201000, 2, 2),
('5.1.1.01.03.0001', 'Belanja Tunjangan Jabatan PNS', 6, 2021, 1460201000, 2, 2),
('5.1.1.01.04', 'Belanja Tunjangan Fungsional ASN', 5, 2021, 185961000, 2, 2),
('5.1.1.01.04.0001', 'Belanja Tunjangan Fungsional PNS', 6, 2021, 185961000, 2, 2),
('5.1.1.01.05', 'Belanja Tunjangan Fungsional Umum ASN', 5, 2021, 856942996, 2, 2),
('5.1.1.01.05.0001', 'Belanja Tunjangan Fungsional Umum PNS', 6, 2021, 856942996, 2, 2),
('5.1.1.01.06', 'Belanja Tunjangan Beras ASN', 5, 2021, 1192959000, 2, 2),
('5.1.1.01.06.0001', 'Belanja Tunjangan Beras PNS', 6, 2021, 1192959000, 2, 2),
('5.1.1.01.07', 'Belanja Tunjangan PPh/Tunjangan Khusus ASN', 5, 2021, 184209000, 2, 2),
('5.1.1.01.07.0001', 'Belanja Tunjangan PPh/Tunjangan Khusus PNS', 6, 2021, 184209000, 2, 2),
('5.1.1.01.08', 'Belanja Pembulatan Gaji ASN', 5, 2021, 321295, 2, 2),
('5.1.1.01.08.0001', 'Belanja Pembulatan Gaji PNS', 6, 2021, 321295, 2, 2),
('5.1.1.01.09', 'Belanja Iuran Jaminan Kesehatan ASN', 5, 2021, 2705641000, 2, 2),
('5.1.1.01.09.0001', 'Belanja Iuran Jaminan Kesehatan PNS', 6, 2021, 2705641000, 2, 2),
('5.1.1.01.10', 'Belanja Iuran Jaminan Kecelakaan Kerja ASN', 5, 2021, 45829000, 2, 2),
('5.1.1.01.10.0001', 'Belanja Iuran Jaminan Kecelakaan Kerja PNS', 6, 2021, 45829000, 2, 2),
('5.1.1.01.11', 'Belanja Iuran Jaminan Kematian ASN', 5, 2021, 137488000, 2, 2),
('5.1.1.01.11.0001', 'Belanja Iuran Jaminan Kematian PNS', 6, 2021, 137488000, 2, 2),
('5.1.1.02', 'Belanja Tambahan Penghasilan ASN', 4, 2021, 38945631117, 2, 2),
('5.1.1.02.01', 'Tambahan Penghasilan berdasarkan Beban Kerja ASN', 5, 2021, 38945631117, 2, 2),
('5.1.1.02.01.0001', 'Tambahan Penghasilan berdasarkan Beban Kerja PNS', 6, 2021, 38945631117, 2, 2),
('5.1.1.03', 'Tambahan Penghasilan berdasarkan Pertimbangan Objektif Lainnya ASN', 4, 2021, 155880000, 2, 2),
('5.1.1.03.07', 'Belanja Honorarium', 5, 2021, 155880000, 2, 2),
('5.1.1.03.07.0001', 'Belanja Honorarium Penanggungjawaban Pengelola Keuangan', 6, 2021, 155880000, 2, 2),
('5.1.1.03.07.0002', 'Belanja Honorarium Pengadaan Barang/Jasa', 6, 2021, 0, 2, 2),
('5.1.2', 'Belanja Barang dan Jasa', 3, 2021, 2975507124, 2, 2),
('5.1.2.01', 'Belanja Barang', 4, 2021, 240762364, 2, 2),
('5.1.2.01.01', 'Belanja Barang Pakai Habis', 5, 2021, 240762364, 2, 2),
('5.1.2.01.01.0001', 'Belanja Bahan-Bahan Bangunan dan Konstruksi', 6, 2021, 2216958435, 2, 2),
('5.1.2.01.01.0004', 'Belanja Bahan-Bahan Bakar dan Pelumas', 6, 2021, 2524784500, 2, 2),
('5.1.2.01.01.0013', 'Belanja Suku Cadang-Suku Cadang Alat Angkutan', 6, 2021, 831526080, 2, 2),
('5.1.2.01.01.0015', 'Belanja Suku Cadang-Suku Cadang Alat Kedokteran', 6, 2021, 80850000, 2, 2),
('5.1.2.01.01.0024', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat Tulis Kantor', 6, 2021, 150584954, 2, 2),
('5.1.2.01.01.0025', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Kertas dan Cover', 6, 2021, 4400000, 2, 2),
('5.1.2.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak', 6, 2021, 23406410, 2, 2),
('5.1.2.01.01.0027', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Benda Pos', 6, 2021, 12382000, 2, 2),
('5.1.2.01.01.0029', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Komputer', 6, 2021, 10595000, 2, 2),
('5.1.2.01.01.0030', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perabot Kantor', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0031', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat Listrik', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0032', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perlengkapan Dinas', 6, 2021, 247500000, 2, 2),
('5.1.2.01.01.0035', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Suvenir/Cendera Mata', 6, 2021, 74970000, 2, 2),
('5.1.2.01.01.0038', 'Belanja Obat-Obatan-Obat-Obatan Lainnya', 6, 2021, 350000000, 2, 2),
('5.1.2.01.01.0052', 'Belanja Makanan dan Minuman Rapat', 6, 2021, 46876000, 2, 2),
('5.1.2.01.01.0053', 'Belanja Makanan dan Minuman Jamuan Tamu', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0058', 'Belanja Makanan dan Minuman Aktivitas Lapangan', 6, 2021, 0, 2, 2),
('5.1.2.02', 'Belanja Jasa', 4, 2021, 1167591760, 2, 2),
('5.1.2.02.01', 'Belanja Jasa Kantor', 5, 2021, 992091760, 2, 2),
('5.1.2.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia', 6, 2021, 39150000, 2, 2),
('5.1.2.02.01.0004', 'Honorarium Tim Pelaksana Kegiatan dan Sekretariat Tim Pelaksana Kegiatan', 6, 2021, 376050000, 2, 2),
('5.1.2.02.01.0028', 'Belanja Jasa Tenaga Pelayanan Umum', 6, 2021, 266291760, 2, 2),
('5.1.2.02.01.0029', 'Belanja Jasa Tenaga Ahli', 6, 2021, 310000000, 2, 2),
('5.1.2.02.01.0030', 'Belanja Jasa Tenaga Kebersihan', 6, 2021, 150000000, 2, 2),
('5.1.2.02.01.0031', 'Belanja Jasa Tenaga Keamanan', 6, 2021, 321696000, 2, 2),
('5.1.2.02.01.0038', 'Belanja Jasa Tata Rias', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0060', 'Belanja Tagihan Air', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0061', 'Belanja Tagihan Listrik', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0062', 'Belanja Langganan Jurnal/Surat Kabar/Majalah', 6, 2021, 54416000, 2, 2),
('5.1.2.02.01.0063', 'Belanja Kawat/Faksimili/Internet/TV Berlangganan', 6, 2021, 600000, 2, 2),
('5.1.2.02.01.0064', 'Belanja Paket/Pengiriman', 6, 2021, 4615000, 2, 2),
('5.1.2.02.04', 'Belanja Sewa Peralatan dan Mesin', 5, 2021, 0, 2, 2),
('5.1.2.02.04.0123', 'Belanja Sewa Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 3801220000, 2, 2),
('5.1.2.02.04.0132', 'Belanja Sewa Peralatan Studio Audio', 6, 2021, 0, 2, 2),
('5.1.2.02.04.0406', 'Belanja Sewa Komputer Unit Lainnya', 6, 2021, 7525000, 2, 2),
('5.1.2.02.05', 'Belanja Sewa Gedung dan Bangunan', 5, 2021, 175500000, 2, 2),
('5.1.2.02.05.0001', 'Belanja Sewa Bangunan Gedung Kantor', 6, 2021, 550000000, 2, 2),
('5.1.2.02.05.0009', 'Belanja Sewa Bangunan Gedung Tempat Pertemuan', 6, 2021, 175500000, 2, 2),
('5.1.2.02.05.0032', 'Belanja Sewa Bangunan Fasilitas Umum', 6, 2021, 450000000, 2, 2),
('5.1.2.02.08', 'Belanja Jasa Konsultansi Konstruksi', 5, 2021, 8415635520, 2, 2),
('5.1.2.02.08.0005', 'Belanja Jasa Konsultansi Perencanaan Arsitektur-Jasa Arsitektur Lainnya', 6, 2021, 70000000, 2, 2),
('5.1.2.02.08.0012', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Nasihat dan Konsultansi Jasa Rekayasa Konstruksi', 6, 2021, 3877210620, 2, 2),
('5.1.2.02.08.0019', 'Belanja Jasa Konsultansi Pengawasan Rekayasa-Jasa Pengawas Pekerjaan Konstruksi Bangunan Gedung', 6, 2021, 4538424900, 2, 2),
('5.1.2.02.12', 'Belanja Kursus/Pelatihan, Sosialisasi, Bimbingan Teknis serta Pendidikan dan Pelatihan', 5, 2021, 0, 2, 2),
('5.1.2.02.12.0003', 'Belanja Bimbingan Teknis', 6, 2021, 749599000, 2, 2),
('5.1.2.03', 'Belanja Pemeliharaan', 4, 2021, 25000000, 2, 2),
('5.1.2.03.02', 'Belanja Pemeliharaan Peralatan dan Mesin', 5, 2021, 25000000, 2, 2),
('5.1.2.03.02.0035', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Dinas Bermotor Perorangan', 6, 2021, 3453088970, 2, 2),
('5.1.2.03.02.0117', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Kantor-Alat Kantor Lainnya', 6, 2021, 21800000, 2, 2),
('5.1.2.03.02.0121', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Pendingin', 6, 2021, 18750000, 2, 2),
('5.1.2.03.02.0123', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 754996000, 2, 2),
('5.1.2.03.02.0132', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Alat Studio- Peralatan Studio Audio', 6, 2021, 0, 2, 2),
('5.1.2.03.02.0181', 'Belanja Pemeliharaan Alat Studio, Komunikasi, dan Pemancar-Peralatan Pemancar-Peralatan Pemancar dan Penerima VHF', 6, 2021, 0, 2, 2),
('5.1.2.03.02.0404', 'Belanja Pemeliharaan Komputer-Komputer Unit-Komputer Jaringan', 6, 2021, 18000000, 2, 2),
('5.1.2.03.02.0406', 'Belanja Pemeliharaan Komputer-Komputer Unit-Komputer Unit Lainnya', 6, 2021, 17500000, 2, 2),
('5.1.2.04', 'Belana Perjalanan Dinas', 4, 2021, 1542153000, 2, 2),
('5.1.2.04.01', 'Belanja Perjalanan Dinas Dalam Negeri', 5, 2021, 1542153000, 2, 2),
('5.1.2.04.01.0001', 'Belanja Perjalanan Dinas Biasa', 6, 2021, 63600000, 2, 2),
('5.1.2.04.01.0003', 'Belanja Perjalanan Dinas Dalam Kota', 6, 2021, 1012921000, 2, 2),
('5.1.2.04.01.0005', 'Belanja Perjalanan Dinas Paket Meeting Luar Kota', 6, 2021, 465632000, 2, 2),
('5.1.2.05', 'Belanja Uang dan/atau Jasa untuk Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 4, 2021, 0, 2, 2),
('5.1.2.05.01', 'Belanja Uang yang Diberikan kepada Pihak Ketiga/Pihak Lain/Masyarakat', 5, 2021, 0, 2, 2),
('5.1.2.05.01.0001', 'Belanja Hadiah yang Bersifat Perlombaan', 6, 2021, 0, 2, 2),
('5.1.5', 'Belanja Hibah', 3, 2021, 5556600000, 2, 2),
('5.2', 'BELANJA MODAL', 2, 2021, 134450000, 2, 2),
('5.2.1.01.01', 'Belanja Modal Tanah Persil', 5, 2021, 1563010925, 2, 2),
('5.2.2', 'Belanja Modal Peralatan dan Mesin', 3, 2021, 134450000, 2, 2),
('5.2.2.05', 'Belanja Modal Alat Kantor dan Rumah Tangga', 4, 2021, 13350000, 2, 2),
('5.2.2.05.01', 'Belanja Modal Alat Kantor', 5, 2021, 13350000, 2, 2),
('5.2.2.05.01.0004', 'Belanja Modal Alat Penyimpan Perlengkapan Kantor', 6, 2021, 171600000, 2, 2),
('5.2.2.05.01.0005', 'Belanja Modal Alat Kantor Lainnya', 6, 2021, 13350000, 2, 2),
('5.2.2.05.02', 'Belanja Modal Alat Rumah Tangga', 5, 2021, 370515286, 2, 2),
('5.2.2.05.02.0001', 'Belanja Modal Mebel', 6, 2021, 232561110, 2, 2),
('5.2.2.05.02.0004', 'Belanja Modal Alat Pendingin', 6, 2021, 92048142, 2, 2),
('5.2.2.05.02.0006', 'Belanja Modal Alat Rumah Tangga Lainnya (Home Use)', 6, 2021, 45906034, 2, 2),
('5.2.2.05.03', 'Belanja Modal Meja dan Kursi Kerja/Rapat Pejabat', 5, 2021, 1203043650, 2, 2),
('5.2.2.05.03.0001', 'Belanja Modal Meja Kerja Pejabat', 6, 2021, 709618650, 2, 2),
('5.2.2.05.03.0003', 'Belanja Modal Kursi Kerja Pejabat', 6, 2021, 493425000, 2, 2),
('5.2.2.06', 'Belanja Modal Alat Studio, Komunikasi, dan Pemancar', 4, 2021, 51800000, 2, 2),
('5.2.2.06.01', 'Belanja Modal Alat Studio', 5, 2021, 51800000, 2, 2),
('5.2.2.06.01.0002', 'Belanja Modal Peralatan Studio Video dan Film', 6, 2021, 51800000, 2, 2),
('5.2.2.07', 'Belanja Modal Alat Kedokteran dan Kesehatan', 4, 2021, 271465000, 2, 2),
('5.2.2.07.02', 'Belanja Modal Alat Kesehatan Umum', 5, 2021, 271465000, 2, 2),
('5.2.2.07.02.0005', 'Belanja Modal Alat Kesehatan Umum Lainnya', 6, 2021, 6465000, 2, 2),
('5.2.2.08.01.0056', 'Belanja Modal Alat Laboratorium Lain', 6, 2021, 6000000, 2, 2),
('5.2.2.10', 'Belanja Modal Komputer', 4, 2021, 69300000, 2, 2),
('5.2.2.10.01', 'Belanja Modal Komputer Unit', 5, 2021, 299795545, 2, 2),
('5.2.2.10.01.0001', 'Belanja Modal Komputer Jaringan', 6, 2021, 217910000, 2, 2),
('5.2.2.10.01.0003', 'Belanja Modal Komputer Unit Lainnya', 6, 2021, 13700000, 2, 2),
('5.2.2.10.02', 'Belanja Modal Peralatan Komputer', 5, 2021, 69300000, 2, 2),
('5.2.2.10.02.0005', 'Belanja Modal Peralatan Komputer Lainnya', 6, 2021, 66300000, 2, 2),
('5.2.3', 'Belanja Modal Gedung dan Bangunan', 3, 2021, 835000000, 2, 2),
('5.2.3.01', 'Belanja Modal Bangunan Gedung', 4, 2021, 835000000, 2, 2),
('5.2.3.01.01', 'Belanja Modal Bangunan Gedung Tempat Kerja', 5, 2021, 835000000, 2, 2),
('5.2.3.01.01.0001', 'Belanja Modal Bangunan Gedung Kantor', 6, 2021, 325000000, 2, 2),
('5.2.5.01', 'Belanja Modal Bahan Perpustakaan', 4, 2021, 4428468000, 2, 2),
('5.2.5.01.01', 'Belanja Modal Bahan Perpustakaan Tercetak', 5, 2021, 12000000, 2, 2),
('5.2.5.01.01.0001', 'Belanja Modal Buku Umum', 6, 2021, 12000000, 2, 2),
('5.2.5.05.01.0001', 'Belanja Modal Tanaman', 6, 2021, 200000000, 2, 2),
('4.1.2.02.06', 'Retribusi Tempat Penginapan/ Pesanggrahan/Vila', 5, 2021, 2200000000, 1, 2),
('4.1.4', 'Lain-lain PAD yang Sah', 3, 2021, 69677525781, 1, 2),
('5.1.1.02.04', 'Tambahan Penghasilan berdasarkan Kelangkaan Profesi ASN', 5, 2021, 18121320000, 2, 2),
('5.1.1.02.04.0001', 'Tambahan Penghasilan berdasarkan Kelangkaan Profesi PNS', 6, 2021, 18121320000, 2, 2),
('5.1.2.01.01.0002', 'Belanja Bahan-Bahan Kimia', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0010', 'Belanja Bahan-Isi Tabung Gas', 6, 2021, 54325000, 2, 2),
('5.1.2.01.01.0012', 'Belanja Bahan-Bahan Lainnya', 6, 2021, 4900000, 2, 2),
('5.1.2.01.01.0016', 'Belanja Suku Cadang-Suku Cadang Alat Laboratorium', 6, 2021, 175210000, 2, 2),
('5.1.2.01.01.0034', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Perlengkapan Pendukung Olahraga', 6, 2021, 138564000, 2, 2),
('5.1.2.01.01.0039', 'Belanja Barang untuk Dijual/Diserahkan kepada Masyarakat', 6, 2021, 118200000, 2, 2),
('5.1.2.01.01.0043', 'Belanja Natura dan Pakan-Natura', 6, 2021, 2450239630, 2, 2),
('5.1.2.01.01.0054', 'Belanja Penambah Daya Tahan Tubuh', 6, 2021, 16500000, 2, 2),
('5.1.2.02.01.0007', 'Honorarium Rohaniwan', 6, 2021, 8400000, 2, 2),
('5.1.2.02.01.0008', 'Honorarium Tim Penyusunan Jurnal, Buletin, Majalah, Pengelola Teknologi Informasi dan Pengelola Website', 6, 2021, 38000000, 2, 2),
('5.1.2.02.01.0015', 'Belanja Jasa Tenaga Laboratorium', 6, 2021, 4000000, 2, 2),
('5.1.2.02.01.0026', 'Belanja Jasa Tenaga Administrasi', 6, 2021, 252000000, 2, 2),
('5.1.2.02.01.0027', 'Belanja Jasa Tenaga Operator Komputer', 6, 2021, 195000000, 2, 2),
('5.1.2.02.01.0033', 'Belanja Jasa Tenaga Supir', 6, 2021, 43008762, 2, 2),
('5.1.2.02.01.0049', 'Belanja Jasa Pencucian Pakaian, Alat Kesenian dan Kebudayaan, serta Alat Rumah Tangga', 6, 2021, 25000000, 2, 2),
('5.1.2.02.01.0050', 'Belanja Jasa Kalibrasi', 6, 2021, 60000000, 2, 2),
('5.1.2.02.01.0051', 'Belanja Jasa Pengolahan Sampah', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0055', 'Belanja Jasa Iklan/Reklame, Film, dan Pemotretan', 6, 2021, 4500000, 2, 2),
('5.1.2.02.01.0059', 'Belanja Tagihan Telepon', 6, 2021, 0, 2, 2),
('5.1.2.02.01.0067', 'Belanja Pembayaran Pajak, Bea, dan Perizinan', 6, 2021, 702000000, 2, 2),
('5.1.2.02.02', 'Belanja Iuran Jaminan/Asuransi', 5, 2021, 203800000, 2, 2),
('5.1.2.02.03', 'Belanja Sewa Tanah', 5, 2021, 25000000, 2, 2),
('5.1.2.02.04.0034', 'Belanja Sewa Alat Bantu Lainnya', 6, 2021, 30000000, 2, 2),
('5.1.2.02.04.0035', 'Belanja Sewa Kendaraan Dinas Bermotor Perorangan', 6, 2021, 373000000, 2, 2),
('5.1.2.02.04.0201', 'Belanja Sewa Alat Pengatur Telekomunikasi', 6, 2021, 400000000, 2, 2),
('5.1.2.02.04.0355', 'Belanja Sewa Peralatan Umum', 6, 2021, 40000000, 2, 2),
('5.1.2.02.04.0416', 'Belanja Sewa Elektronik/Electric', 6, 2021, 0, 2, 2),
('5.1.2.02.05.0036', 'Belanja Sewa Taman', 6, 2021, 0, 2, 2),
('5.1.2.02.05.0043', 'Belanja Sewa Hotel', 6, 2021, 150000000, 2, 2),
('5.1.2.02.08.0028', 'Belanja Jasa Konsultansi Spesialis-Jasa Pengujian dan Analisa Komposisi dan Tingkat Kemurnian', 6, 2021, 50000000, 2, 2),
('5.1.2.02.09', 'Belanja Jasa Konsultansi Non Konstruksi', 5, 2021, 350000000, 2, 2),
('5.1.2.03.02.0038', 'Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat Bermotor-Kendaraan Bermotor Beroda Dua', 6, 2021, 37000000, 2, 2),
('5.1.2.03.02.0118', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Rumah Tangga-Mebel', 6, 2021, 6000000, 2, 2),
('5.1.2.03.02.0410', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Jaringan', 6, 2021, 47000000, 2, 2),
('5.1.2.03.03', 'Belanja Pemeliharaan Gedung dan Bangunan', 5, 2021, 126098221625, 2, 2),
('5.1.2.03.03.0001', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Bangunan Gedung Kantor', 6, 2021, 110015621625, 2, 2),
('5.1.2.03.03.0036', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Kerja-Taman', 6, 2021, 200000000, 2, 2),
('5.1.2.03.04', 'Belanja Pemeliharaan Jalan, Jaringan, dan Irigasi', 5, 2021, 400000000, 2, 2),
('5.1.2.04.01.0004', 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota', 6, 2021, 22000000, 2, 2),
('5.1.5.05', 'Belanja Hibah kepada Badan, Lembaga, Organisasi Kemasyarakatan yang Berbadan Hukum Indonesia', 4, 2021, 500000000, 2, 2),
('5.2.2.01', 'Belanja Modal Alat Besar', 4, 2021, 521975000, 2, 2),
('5.2.2.01.01', 'Belanja Modal Alat Besar Darat', 5, 2021, 47300000, 2, 2),
('5.2.2.01.01.0011', 'Belanja Modal Mesin Proses', 6, 2021, 47300000, 2, 2),
('5.2.2.01.03', 'Belanja Modal Alat Bantu', 5, 2021, 521975000, 2, 2),
('5.2.2.01.03.0004', 'Belanja Modal Electric Generating Set', 6, 2021, 200000000, 2, 2),
('5.2.2.03', 'Belanja Modal Alat Bengkel dan Alat Ukur', 4, 2021, 13500000, 2, 2),
('5.2.2.03.03', 'Belanja Modal Alat Ukur', 5, 2021, 13500000, 2, 2),
('5.2.2.06.02', 'Belanja Modal Alat Komunikasi', 5, 2021, 289674218, 2, 2),
('5.2.2.06.02.0001', 'Belanja Modal Alat Komunikasi Telephone', 6, 2021, 289674218, 2, 2),
('5.2.2.10.01.0002', 'Belanja Modal Personal Computer', 6, 2021, 286095545, 2, 2),
('5.1.2.01.01.0036', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Alat/Bahan untuk Kegiatan Kantor Lainnya', 6, 2021, 1699770000, 2, 2),
('5.1.2.02.02.0006', 'Belanja Iuran Jaminan Kecelakaan Kerja bagi Non ASN', 6, 2021, 256785883, 2, 2),
('5.1.2.03.02.0103', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pengolahan Tanah dan Tanaman', 6, 2021, 4000000, 2, 2),
('5.1.2.03.02.0409', 'Belanja Pemeliharaan Komputer-Peralatan Komputer-Peralatan Personal Computer', 6, 2021, 7500000, 2, 2),
('5.2.2.06.01.0004', 'Belanja Modal Peralatan Cetak', 6, 2021, 86438900, 2, 2),
('5.1.1.03.07.0003', 'Belanja Honorarium Perangkat Unit Kerja Pengadaan Barang dan Jasa (UKPBJ)', 6, 2021, 0, 2, 2),
('5.1.2.03.03.0046', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal-Rumah Negara dalam Proses Penggolongan', 6, 2021, 15382600000, 2, 2),
('5.2.3.04', 'Belanja Modal Tugu Titik Kontrol/Pasti', 4, 2021, 180000000, 2, 2),
('5.2.3.04.01', 'Belanja Modal Tugu/Tanda Batas', 5, 2021, 180000000, 2, 2),
('4.1.2.02.01.0004', 'Retribusi Pemakaian Laboratorium', 6, 2021, 650000000, 1, 2),
('5.1.2.03.02.0012', 'Belanja Pemeliharaan Alat Besar-Alat Besar Darat-Alat Besar Darat Lainnya', 6, 2021, 24700000, 2, 2),
('5.2.4.01.01', 'Belanja Modal Jalan', 5, 2021, 199999200, 2, 2),
('5.1.2.02.04.0135', 'Belanja Sewa Peralatan Cetak', 6, 2021, 79200000, 2, 2),
('5.1.2.02.01.0025', 'Belanja Jasa Tenaga Kesenian dan Kebudayaan', 6, 2021, 184000000, 2, 2),
('5.1.2.02.02.0008', 'Belanja Asuransi Barang Milik Daerah', 6, 2021, 203800000, 2, 2),
('5.1.2.02.07', 'Belanja Sewa Aset Tetap Lainnya', 5, 2021, 0, 2, 2),
('5.1.2.02.07.0028', 'Belanja Sewa Alat Musik', 6, 2021, 12000000, 2, 2),
('5.1.5.05.02', 'Belanja Hibah kepada Badan dan Lembaga Nirlaba, Sukarela dan Sosial yang Telah Memiliki Surat Keterangan Terdaftar', 5, 2021, 23550000000, 2, 2),
('5.1.2.02.01.0041', 'Belanja Jasa Pemasangan Instalasi Telepon, Air, dan Listrik', 6, 2021, 80000000, 2, 2),
('5.1.2.02.04.0121', 'Belanja Sewa Alat Pendingin', 6, 2021, 0, 2, 2),
('5.2.2.06.04', 'Belanja Modal Peralatan Komunikasi Navigasi', 5, 2021, 4000000, 2, 2),
('5.2.2.06.04.0006', 'Belanja Modal Peralatan Komunikasi untuk Dokumentasi', 6, 2021, 4000000, 2, 2),
('5.1.2.02.04.0012', 'Belanja Sewa Alat Besar Darat Lainnya', 6, 2021, 650000000, 2, 2),
('5.1.2.02.04.0037', 'Belanja Sewa Kendaraan Bermotor Angkutan Barang', 6, 2021, 150000000, 2, 2),
('4.1.2.03', 'Retribusi Perizinan Tertentu', 4, 2021, 2550000000, 1, 2),
('5.1.2.01.01.0005', 'Belanja Bahan-Bahan Baku', 6, 2021, 20211050, 2, 2),
('5.1.2.02.04.0003', 'Belanja Sewa Excavator', 6, 2021, 48328291, 2, 2),
('5.1.2.02.04.0018', 'Belanja Sewa Alat Besar Apung Lainnya', 6, 2021, 20000000, 2, 2),
('5.1.2.02.09.0011', 'Belanja Jasa Konsultansi Berorientasi Layanan-Jasa Survei', 6, 2021, 1490000000, 2, 2),
('5.1.2.03.02.0113', 'Belanja Pemeliharaan Alat Kantor dan Rumah Tangga-Alat Kantor-Mesin Ketik', 6, 2021, 15000000, 2, 2),
('5.2.3.01.01.0032', 'Belanja Modal Bangunan Fasilitas Umum', 6, 2021, 1456231000, 2, 2),
('5.2.5.01.01.0004', 'Belanja Modal Buku Ilmu Sosial', 6, 2021, 48000000, 2, 2),
('5.1.2.01.01.0040', 'Belanja Barang untuk Dijual/Diserahkan kepada Pihak Ketiga/Pihak Lain', 6, 2021, 980681250, 2, 2),
('5.1.2.02.07.0057', 'Belanja Sewa Tanaman', 6, 2021, 0, 2, 2),
('5.1.2.01.01.0046', 'Belanja Persediaan Penelitian-Persediaan Penelitian Biologi', 6, 2021, 132600000, 2, 2),
('5.1.2.02.05.0002', 'Belanja Sewa Bangunan Gudang', 6, 2021, 200000000, 2, 2),
('5.1.2.02.08.0013', 'Belanja Jasa Konsultansi Perencanaan Rekayasa-Jasa Desain Rekayasa Lainnya', 6, 2021, 9550000, 2, 2),
('5.1.2.03.02.0104', 'Belanja Pemeliharaan Alat Pertanian-Alat Pengolahan-Alat Pemeliharaan Tanaman/ Ikan/Ternak', 6, 2021, 30000000, 2, 2),
('5.1.2.03.03.0041', 'Belanja Pemeliharaan Bangunan Gedung- Bangunan Gedung Tempat Tinggal- Mess/Wisma/Bungalow/Tempat Peristirahatan', 6, 2021, 500000000, 2, 2),
('5.2.2.06.01.0001', 'Belanja Modal Peralatan Studio Audio', 6, 2021, 194810000, 2, 2),
('5.1.1.03.01', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Daerah', 5, 2021, 99756073360, 2, 2),
('5.1.1.03.01.0001', 'Belanja Insentif bagi ASN atas Pemungutan Pajak Kendaraan Bermotor', 6, 2021, 41118956815, 2, 2),
('5.1.2.02.04.0404', 'Belanja Sewa Komputer Jaringan', 6, 2021, 70000000, 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_uraian_kegiatan_skpd`
--

CREATE TABLE `data_uraian_kegiatan_skpd` (
  `id_uraian` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `kode_rekening` varchar(16) NOT NULL,
  `level` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `anggaran` double NOT NULL,
  `jenis` int(11) NOT NULL COMMENT '1:Pendapatan, 2:Belanja, 3:Penerimaan, 4:Pengeluaran',
  `st_anggaran` int(11) NOT NULL COMMENT '1:APBD, 2:PAPBD',
  `is_transfer` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pelaksanaan`
--

CREATE TABLE `jenis_pelaksanaan` (
  `id_jenis_pelaksanaan` int(11) NOT NULL,
  `nama_jenis_pelaksanaan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jenis_pelaksanaan`
--

INSERT INTO `jenis_pelaksanaan` (`id_jenis_pelaksanaan`, `nama_jenis_pelaksanaan`) VALUES
(1, 'Pengadaan Langsung'),
(2, 'e-Purchasing'),
(3, 'Tender'),
(4, 'Penunjukan Langsung'),
(5, 'Tender Cepat'),
(6, 'Seleksi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Pekerjaan Konstruksi'),
(2, 'Pengadaan Barang'),
(3, 'Jasa Lainnya'),
(4, 'Jasa Konsultansi Badan Usaha');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_dokumentasi`
--

CREATE TABLE `kegiatan_dokumentasi` (
  `id_dokumentasi` int(11) NOT NULL,
  `id_kegiatan_detail` int(11) NOT NULL,
  `file_dokumentasi` varchar(100) NOT NULL,
  `user_input` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kegiatan_dokumentasi`
--

INSERT INTO `kegiatan_dokumentasi` (`id_dokumentasi`, `id_kegiatan_detail`, `file_dokumentasi`, `user_input`, `tgl_input`) VALUES
(19, 23, '23-20211115-024023.png', 34, '2021-11-15 02:40:23'),
(20, 23, '23-20211115-024038.png', 34, '2021-11-15 02:40:38'),
(21, 32, '32-20230614-093916.jpg', 103, '2023-06-14 09:39:16'),
(22, 33, '33-20230614-095801.jpg', 103, '2023-06-14 09:58:01'),
(23, 33, '33-20230621-115647.png', 103, '2023-06-21 11:56:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_upload`
--

CREATE TABLE `log_upload` (
  `id_log` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `st_data` int(11) NOT NULL DEFAULT '1' COMMENT '1:pemko, 2:SKPD',
  `tgl_data` date NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_input` int(11) NOT NULL,
  `namafile` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_upload_realisasi`
--

CREATE TABLE `log_upload_realisasi` (
  `id_log` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `tgl_data` date NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_input` int(11) NOT NULL,
  `jenis` int(11) NOT NULL DEFAULT '1' COMMENT '1: dak, 2:dk, 3:tp'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_user`
--

CREATE TABLE `log_user` (
  `log_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `aktivitas` varchar(100) NOT NULL,
  `aktivitas_detail` text NOT NULL,
  `browser` varchar(200) NOT NULL,
  `waktu_akses` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `log_user`
--

INSERT INTO `log_user` (`log_id`, `username`, `aktivitas`, `aktivitas_detail`, `browser`, `waktu_akses`) VALUES
(2147483647, 'admin', 'login', '-', '{\"browser\":\"Opera\",\"version\":\"113.0.0.0\",\"os\":\"Windows 10\",\"ip\":\"::1\"}', '2024-10-02 10:23:25'),
(2147483647, 'admin', 'new tbl_mandatory', '{\"tbl_mandatory\":{\"id_kabupaten\":\"34\",\"tahun\":\"2024\",\"pendidikan\":\"1\",\"kesehatan\":\"1\",\"infrastruktur\":\"1\",\"pengawasan\":\"1\",\"persen_pendidikan\":\"1\",\"persen_kesehatan\":\"1\",\"persen_infrestruktur\":\"1\",\"persen_pengawasan\":\"1\",\"st_apbd\":\"1\"}}', '{\"browser\":\"Opera\",\"version\":\"113.0.0.0\",\"os\":\"Windows 10\",\"ip\":\"::1\"}', '2024-10-02 10:36:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penanda_tangan`
--

CREATE TABLE `penanda_tangan` (
  `id_ttd` int(11) NOT NULL,
  `nip_ttd` varchar(20) NOT NULL,
  `nama_ttd` varchar(100) NOT NULL,
  `ttd` varchar(200) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna_anggaran`
--

CREATE TABLE `pengguna_anggaran` (
  `id_pa` int(11) NOT NULL,
  `nip_pa` varchar(20) NOT NULL,
  `nama_pa` varchar(100) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `is_active` enum('Y','N') NOT NULL,
  `tgl_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_anggaran`
--

CREATE TABLE `setting_anggaran` (
  `id_setting` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `pendapatan` double NOT NULL,
  `belanja` double NOT NULL,
  `papbd` date NOT NULL,
  `pendapatan_p` double NOT NULL,
  `belanja_p` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `setting_anggaran`
--

INSERT INTO `setting_anggaran` (`id_setting`, `tahun`, `pendapatan`, `belanja`, `papbd`, `pendapatan_p`, `belanja_p`) VALUES
(1, 2021, 13687123662525, 13953318293830, '2021-10-28', 0, 0),
(2, 2022, 1010829400050, 1039563285496, '2021-12-30', 0, 0),
(3, 2022, 942541818298, 1066322774767, '2022-10-28', 0, 0),
(8, 2023, 2378168923917, 2464572666859, '2023-10-23', 2378168923917, 2464572666859),
(9, 2024, 2781171448903, 2803671448903, '2024-10-31', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta_kabupaten`
--

CREATE TABLE `ta_kabupaten` (
  `id_kabupaten` int(11) NOT NULL,
  `nama_kabupaten` varchar(30) NOT NULL,
  `kabupaten_danadesa` varchar(50) NOT NULL,
  `kode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ta_kabupaten`
--

INSERT INTO `ta_kabupaten` (`id_kabupaten`, `nama_kabupaten`, `kabupaten_danadesa`, `kode`) VALUES
(34, 'KABUPATEN MANDAILING NATAL', 'Panyabungan', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta_kontrak`
--

CREATE TABLE `ta_kontrak` (
  `id_kontrak` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `pagu` double NOT NULL,
  `no_kontrak` varchar(50) DEFAULT '',
  `kd_urusan` int(11) NOT NULL,
  `kd_bidang` int(11) NOT NULL,
  `kd_unit` int(11) NOT NULL,
  `kd_sub` int(11) NOT NULL,
  `kd_prog` int(11) NOT NULL,
  `id_prog` int(11) NOT NULL,
  `kd_keg` int(11) NOT NULL,
  `tgl_kontrak` date DEFAULT '0000-00-00',
  `keperluan` varchar(255) NOT NULL,
  `waktu` varchar(150) DEFAULT '',
  `nilai` double NOT NULL,
  `nm_perusahaan` varchar(100) DEFAULT '',
  `bentuk` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nm_pemilik` varchar(100) NOT NULL,
  `npwp` varchar(20) NOT NULL,
  `nm_bank` varchar(50) NOT NULL,
  `nm_rekening` varchar(100) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `real_kontrak` double NOT NULL,
  `realisasi` double NOT NULL,
  `persen_realisasi` double NOT NULL,
  `tgl_input` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_prioritas` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `status_1` enum('Y','N') NOT NULL DEFAULT 'N',
  `status_2` enum('Y','N') NOT NULL DEFAULT 'N',
  `status_3` enum('Y','N') NOT NULL,
  `koordinat` varchar(75) DEFAULT '',
  `lokasi_pekerjaan` varchar(150) DEFAULT '',
  `adendum` double NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `st_adendum` int(11) NOT NULL,
  `realisasi_fisik` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta_kontrak_pa`
--

CREATE TABLE `ta_kontrak_pa` (
  `id_kontrak` int(11) NOT NULL,
  `nama_pa` varchar(100) NOT NULL,
  `nip_pa` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta_spp_kontrak`
--

CREATE TABLE `ta_spp_kontrak` (
  `tahun` int(11) NOT NULL,
  `no_spp` varchar(50) NOT NULL,
  `no_kontrak` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `bentuk` varchar(25) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nm_pimpinan` varchar(50) NOT NULL,
  `nm_bank` varchar(50) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `keperluan` varchar(255) NOT NULL,
  `tgl_kontrak` datetime NOT NULL,
  `waktu` varchar(150) NOT NULL,
  `npwp` varchar(20) NOT NULL,
  `nilai` double NOT NULL,
  `no_addendum` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ta_spp_rinc`
--

CREATE TABLE `ta_spp_rinc` (
  `tahun` int(11) NOT NULL,
  `no_spp` varchar(50) NOT NULL,
  `no_id` int(11) NOT NULL,
  `kd_urusan` int(11) NOT NULL,
  `kd_bidang` int(11) NOT NULL,
  `kd_unit` int(11) NOT NULL,
  `kd_sub` int(11) NOT NULL,
  `kd_prog` int(11) NOT NULL,
  `id_prog` int(11) NOT NULL,
  `kd_keg` int(11) NOT NULL,
  `kd_rek_1` int(11) NOT NULL,
  `kd_rek_2` int(11) NOT NULL,
  `kd_rek_3` int(11) NOT NULL,
  `kd_rek_4` int(11) NOT NULL,
  `kd_rek_5` int(11) NOT NULL,
  `usulan` double NOT NULL,
  `nilai` double NOT NULL,
  `kd_sumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_dana_dekon`
--

CREATE TABLE `tbl_dana_dekon` (
  `id_dana` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `jenis` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `keterangan` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pagu` double NOT NULL,
  `tahun` int(11) NOT NULL,
  `kd_satker` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_dana_desa`
--

CREATE TABLE `tbl_dana_desa` (
  `id_kabupaten` int(11) NOT NULL,
  `tahun` int(11) NOT NULL DEFAULT '2021',
  `bulan` int(11) NOT NULL DEFAULT '12',
  `alokasi` double NOT NULL,
  `tahap1` double NOT NULL,
  `tahap2` double NOT NULL,
  `tahap3` double NOT NULL,
  `total_realisasi` double NOT NULL,
  `persen` double NOT NULL,
  `desa` int(11) NOT NULL,
  `desa1` int(11) NOT NULL,
  `desa2` int(11) NOT NULL,
  `desa3` int(11) NOT NULL,
  `belum1` int(11) NOT NULL,
  `belum2` int(11) NOT NULL,
  `belum3` int(11) NOT NULL,
  `blt` int(11) NOT NULL,
  `tw1` double NOT NULL,
  `tw2` double NOT NULL,
  `tw3` double NOT NULL,
  `tw4` double NOT NULL,
  `total_blt` double NOT NULL,
  `blt_cair1` int(11) NOT NULL,
  `blt_cair2` int(11) NOT NULL,
  `blt_cair3` int(11) NOT NULL,
  `blt_cair4` int(11) NOT NULL,
  `blt_bcair1` int(11) NOT NULL,
  `blt_bcair2` int(11) NOT NULL,
  `blt_bcair3` int(11) NOT NULL,
  `blt_bcair4` int(11) NOT NULL,
  `relokasi_jumlah` double NOT NULL,
  `relokasi_desa` int(11) NOT NULL,
  `relokasi_belum_salur` int(11) NOT NULL,
  `total_salur` double NOT NULL,
  `persen_salur` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_dana_desa_log`
--

CREATE TABLE `tbl_dana_desa_log` (
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL DEFAULT '12',
  `periode` date NOT NULL,
  `tgl_input` datetime NOT NULL,
  `user_input` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_data_dak`
--

CREATE TABLE `tbl_data_dak` (
  `id_dak` int(11) NOT NULL,
  `subbidang` varchar(150) DEFAULT NULL,
  `id_satker` int(11) DEFAULT NULL,
  `total` double DEFAULT NULL,
  `tahun` int(11) NOT NULL DEFAULT '2021',
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mandatory`
--

CREATE TABLE `tbl_mandatory` (
  `id_mandatory` int(11) NOT NULL,
  `tahun` int(11) NOT NULL DEFAULT '2021',
  `id_kabupaten` int(11) NOT NULL,
  `pendidikan` double NOT NULL,
  `persen_pendidikan` double NOT NULL,
  `kesehatan` double NOT NULL,
  `persen_kesehatan` double NOT NULL,
  `infrastruktur` double NOT NULL,
  `persen_infrestruktur` double NOT NULL,
  `fkub` double NOT NULL,
  `persen_fkub` double NOT NULL,
  `kompetisi` double NOT NULL,
  `persen_kompetisi` double NOT NULL,
  `pengawasan` double NOT NULL,
  `persen_pengawasan` double NOT NULL,
  `st_apbd` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_mandatory`
--

INSERT INTO `tbl_mandatory` (`id_mandatory`, `tahun`, `id_kabupaten`, `pendidikan`, `persen_pendidikan`, `kesehatan`, `persen_kesehatan`, `infrastruktur`, `persen_infrestruktur`, `fkub`, `persen_fkub`, `kompetisi`, `persen_kompetisi`, `pengawasan`, `persen_pengawasan`, `st_apbd`) VALUES
(42, 2024, 34, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_realisasi_dak`
--

CREATE TABLE `tbl_realisasi_dak` (
  `id_realisasi` int(11) NOT NULL,
  `id_dak` int(11) NOT NULL,
  `id_satker` int(11) DEFAULT NULL,
  `sp2d_tahap1` double DEFAULT NULL,
  `sp2d_tahap2` double DEFAULT NULL,
  `sp2d_tahap3` double DEFAULT NULL,
  `sp2d_total` double DEFAULT NULL,
  `tahun` int(11) NOT NULL DEFAULT '2021',
  `bulan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_realisasi_dekon`
--

CREATE TABLE `tbl_realisasi_dekon` (
  `id_dana` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `jenis` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `realisasi` double NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `id_realisasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_realisasi_skpd`
--

CREATE TABLE `tbl_realisasi_skpd` (
  `id_realisasi` int(11) NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `kode_rekening` int(11) NOT NULL,
  `realisasi` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_realisasi_skpd`
--

INSERT INTO `tbl_realisasi_skpd` (`id_realisasi`, `id_skpd`, `tahun`, `bulan`, `kode_rekening`, `realisasi`) VALUES
(1, 38, 2024, 2, 4, 0),
(2, 38, 2024, 2, 5, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tampilan`
--

CREATE TABLE `tbl_tampilan` (
  `id_data` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `judul1` varchar(50) NOT NULL,
  `judul2` varchar(50) NOT NULL,
  `sub1` varchar(50) NOT NULL,
  `sub2` varchar(50) NOT NULL,
  `bagian1` varchar(50) NOT NULL,
  `bagian2` varchar(50) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `banner` varchar(50) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `koordinat` varchar(75) NOT NULL,
  `zoom` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_tampilan`
--

INSERT INTO `tbl_tampilan` (`id_data`, `title`, `copyright`, `judul1`, `judul2`, `sub1`, `sub2`, `bagian1`, `bagian2`, `logo`, `banner`, `foto`, `link`, `koordinat`, `zoom`) VALUES
(1, 'Progress Report Pengendalian Pembangunan Kabupaten Mandailing Natal', 'Bagian Administrasi Pembangunan Setda Kab. Mandailing Natal', 'Aplikasi', 'PRP2Madina', 'Progress Report Pengendalian Pembangunan', 'Kabupaten  Mandailing Natal', 'Bagian Administrasi Pembangunan', 'Setda Kab. Mandailing Natal', 'logo-20241001-080510.png', 'banner-20241001-080601.png', 'foto-20241001-080619.png', 'https://madina.go.id', '2.936025308775414, 99.00081646225541', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `nama_user` varchar(35) NOT NULL,
  `nip_user` varchar(25) NOT NULL,
  `password` varchar(150) NOT NULL DEFAULT '$2y$10$S9h2GPMZbUjy1sZANFqbAuRSNqvaUEy2K6/7yQr66cJ9nCjdKxNva',
  `foto_profile` varchar(100) NOT NULL,
  `role_admin` enum('master','fisik','keuangan','provinsi','kabupaten','desa','biro','pakar') NOT NULL,
  `id_skpd` int(11) NOT NULL,
  `id_pegawai` char(7) NOT NULL,
  `is_active` enum('Y','N') NOT NULL,
  `level` int(11) NOT NULL,
  `skpd` enum('Y','N') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `nama_user`, `nip_user`, `password`, `foto_profile`, `role_admin`, `id_skpd`, `id_pegawai`, `is_active`, `level`, `skpd`) VALUES
(1, 'admin', 'BERNANDO D. SILAEN, ST., M.Si.', '197708202006041014', '$2y$10$yP2linCxMV2HM9XGe4YWE.Qk9SIm728yIA97PDWUW4M.d5hZr/ugi', 'admin-20240202-101928.png', 'master', 38, '', 'Y', 1, 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_access`
--

CREATE TABLE `users_access` (
  `access_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `akses` enum('Y','N') NOT NULL,
  `tambah` enum('Y','N') NOT NULL,
  `ubah` enum('Y','N') NOT NULL,
  `hapus` enum('Y','N') NOT NULL,
  `ubah_1` enum('Y','N') NOT NULL,
  `hapus_1` enum('Y','N') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users_access`
--

INSERT INTO `users_access` (`access_id`, `role_id`, `menu_id`, `akses`, `tambah`, `ubah`, `hapus`, `ubah_1`, `hapus_1`) VALUES
(1, 1, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(2, 1, 2, 'Y', 'N', 'N', 'N', 'N', 'N'),
(3, 1, 3, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(4, 1, 4, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(5, 1, 5, 'Y', 'N', 'N', 'N', 'N', 'N'),
(6, 1, 6, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(7, 1, 7, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(8, 1, 8, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(9, 1, 9, 'Y', 'N', 'N', 'N', 'N', 'N'),
(10, 1, 10, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(11, 1, 11, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(12, 1, 12, 'Y', 'N', 'N', 'N', 'N', 'N'),
(13, 1, 13, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(14, 1, 14, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(15, 1, 15, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(16, 1, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(17, 3, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(18, 3, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(19, 3, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(20, 3, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(21, 3, 5, 'Y', 'N', 'N', 'N', 'N', 'N'),
(22, 3, 6, 'N', 'N', 'N', 'N', 'N', 'N'),
(23, 3, 7, 'N', 'N', 'N', 'N', 'N', 'N'),
(24, 3, 8, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(25, 3, 9, 'N', 'N', 'N', 'N', 'N', 'N'),
(26, 3, 10, 'N', 'N', 'N', 'N', 'N', 'N'),
(27, 3, 11, 'N', 'N', 'N', 'N', 'N', 'N'),
(28, 3, 12, 'N', 'N', 'N', 'N', 'N', 'N'),
(29, 3, 13, 'N', 'N', 'N', 'N', 'N', 'N'),
(30, 3, 14, 'N', 'N', 'N', 'N', 'N', 'N'),
(31, 3, 15, 'N', 'N', 'N', 'N', 'N', 'N'),
(32, 3, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(33, 1, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(34, 3, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(35, 1, 18, 'Y', 'N', 'N', 'N', 'N', 'N'),
(36, 1, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(37, 1, 20, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(38, 1, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(45, 1, 28, 'Y', 'N', 'N', 'N', 'N', 'N'),
(46, 1, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(47, 1, 30, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(48, 1, 31, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(85, 2, 2, 'Y', 'N', 'N', 'N', 'N', 'N'),
(51, 1, 34, 'Y', 'N', 'N', 'N', 'N', 'N'),
(52, 1, 35, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(53, 1, 36, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(54, 1, 37, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(55, 1, 38, 'Y', 'N', 'N', 'N', 'N', 'N'),
(56, 1, 39, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(57, 1, 40, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(58, 1, 41, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(59, 3, 18, 'Y', 'N', 'N', 'N', 'N', 'N'),
(60, 3, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(61, 3, 20, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(62, 3, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(69, 3, 28, 'Y', 'N', 'N', 'N', 'N', 'N'),
(70, 3, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(71, 3, 30, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(72, 3, 31, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(83, 1, 42, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(84, 2, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(75, 3, 34, 'Y', 'N', 'N', 'N', 'N', 'N'),
(76, 3, 35, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(77, 3, 36, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(78, 3, 37, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(79, 3, 38, 'Y', 'N', 'N', 'N', 'N', 'N'),
(80, 3, 39, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(81, 3, 40, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(82, 3, 41, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(86, 2, 3, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(87, 2, 4, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(88, 2, 5, 'Y', 'N', 'N', 'N', 'N', 'N'),
(89, 2, 6, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(90, 2, 7, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(91, 2, 8, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(92, 2, 9, 'Y', 'N', 'N', 'N', 'N', 'N'),
(93, 2, 10, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(94, 2, 11, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(95, 2, 12, 'Y', 'N', 'N', 'N', 'N', 'N'),
(96, 2, 13, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(97, 2, 14, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(98, 2, 15, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(99, 2, 16, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(100, 2, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(101, 2, 18, 'Y', 'N', 'N', 'N', 'N', 'N'),
(102, 2, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(103, 2, 20, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(104, 2, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(105, 2, 28, 'Y', 'N', 'N', 'N', 'N', 'N'),
(106, 2, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(107, 2, 30, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(108, 2, 31, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(109, 2, 42, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(110, 2, 34, 'Y', 'N', 'N', 'N', 'N', 'N'),
(111, 2, 35, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(112, 2, 36, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(113, 2, 37, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(114, 2, 38, 'Y', 'N', 'N', 'N', 'N', 'N'),
(115, 2, 39, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(116, 2, 40, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(117, 2, 41, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(118, 3, 42, 'N', 'N', 'N', 'N', 'N', 'N'),
(119, 4, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(120, 4, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(121, 4, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(122, 4, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(123, 4, 5, 'Y', 'N', 'N', 'N', 'N', 'N'),
(124, 4, 6, 'N', 'N', 'N', 'N', 'N', 'N'),
(125, 4, 7, 'N', 'N', 'N', 'N', 'N', 'N'),
(126, 4, 8, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(127, 4, 9, 'N', 'N', 'N', 'N', 'N', 'N'),
(128, 4, 10, 'N', 'N', 'N', 'N', 'N', 'N'),
(129, 4, 11, 'N', 'N', 'N', 'N', 'N', 'N'),
(130, 4, 12, 'Y', 'N', 'N', 'N', 'N', 'N'),
(131, 4, 13, 'Y', 'N', 'N', 'N', 'N', 'N'),
(132, 4, 14, 'Y', 'N', 'N', 'N', 'N', 'N'),
(133, 4, 15, 'Y', 'N', 'N', 'N', 'N', 'N'),
(134, 4, 16, 'Y', 'N', 'N', 'N', 'N', 'N'),
(135, 4, 17, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(136, 4, 18, 'Y', 'N', 'N', 'N', 'N', 'N'),
(137, 4, 19, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(138, 4, 20, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(139, 4, 21, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(140, 4, 28, 'Y', 'N', 'N', 'N', 'N', 'N'),
(141, 4, 29, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(142, 4, 30, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(143, 4, 31, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(144, 4, 42, 'N', 'N', 'N', 'N', 'N', 'N'),
(145, 4, 34, 'Y', 'N', 'N', 'N', 'N', 'N'),
(146, 4, 35, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(147, 4, 36, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(148, 4, 37, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(149, 4, 38, 'Y', 'N', 'N', 'N', 'N', 'N'),
(150, 4, 39, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(151, 4, 40, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(152, 4, 41, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(153, 5, 1, 'Y', 'N', 'N', 'N', 'N', 'N'),
(154, 5, 2, 'N', 'N', 'N', 'N', 'N', 'N'),
(155, 5, 3, 'N', 'N', 'N', 'N', 'N', 'N'),
(156, 5, 4, 'N', 'N', 'N', 'N', 'N', 'N'),
(157, 5, 5, 'Y', 'N', 'N', 'N', 'N', 'N'),
(158, 5, 6, 'N', 'N', 'N', 'N', 'N', 'N'),
(159, 5, 7, 'N', 'N', 'N', 'N', 'N', 'N'),
(160, 5, 8, 'N', 'N', 'N', 'N', 'N', 'N'),
(161, 5, 9, 'N', 'N', 'N', 'N', 'N', 'N'),
(162, 5, 10, 'N', 'N', 'N', 'N', 'N', 'N'),
(163, 5, 11, 'N', 'N', 'N', 'N', 'N', 'N'),
(164, 5, 12, 'Y', 'N', 'N', 'N', 'N', 'N'),
(165, 5, 13, 'N', 'N', 'N', 'N', 'N', 'N'),
(166, 5, 14, 'N', 'N', 'N', 'N', 'N', 'N'),
(167, 5, 15, 'N', 'N', 'N', 'N', 'N', 'N'),
(168, 5, 16, 'Y', 'N', 'N', 'N', 'N', 'N'),
(169, 5, 17, 'Y', 'N', 'N', 'N', 'N', 'N'),
(170, 5, 18, 'Y', 'N', 'N', 'N', 'N', 'N'),
(171, 5, 19, 'Y', 'N', 'N', 'N', 'N', 'N'),
(172, 5, 20, 'Y', 'N', 'N', 'N', 'N', 'N'),
(173, 5, 21, 'Y', 'N', 'N', 'N', 'N', 'N'),
(174, 5, 28, 'N', 'N', 'N', 'N', 'N', 'N'),
(175, 5, 29, 'N', 'N', 'N', 'N', 'N', 'N'),
(176, 5, 30, 'N', 'N', 'N', 'N', 'N', 'N'),
(177, 5, 31, 'N', 'N', 'N', 'N', 'N', 'N'),
(178, 5, 42, 'N', 'N', 'N', 'N', 'N', 'N'),
(179, 5, 34, 'Y', 'N', 'N', 'N', 'N', 'N'),
(180, 5, 35, 'Y', 'N', 'N', 'N', 'N', 'N'),
(181, 5, 36, 'Y', 'N', 'N', 'N', 'N', 'N'),
(182, 5, 37, 'Y', 'N', 'N', 'N', 'N', 'N'),
(183, 5, 38, 'Y', 'N', 'N', 'N', 'N', 'N'),
(184, 5, 39, 'Y', 'N', 'N', 'N', 'N', 'N'),
(185, 5, 40, 'Y', 'N', 'N', 'N', 'N', 'N'),
(186, 5, 41, 'Y', 'N', 'N', 'N', 'N', 'N'),
(187, 1, 43, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(188, 1, 44, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(189, 1, 33, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(190, 2, 33, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(191, 2, 43, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(192, 2, 44, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(193, 3, 33, 'N', 'N', 'N', 'N', 'N', 'N'),
(194, 3, 43, 'N', 'N', 'N', 'N', 'N', 'N'),
(195, 3, 44, 'N', 'N', 'N', 'N', 'N', 'N'),
(196, 5, 33, 'N', 'N', 'N', 'N', 'N', 'N'),
(197, 5, 43, 'Y', 'N', 'N', 'N', 'N', 'N'),
(198, 5, 44, 'N', 'N', 'N', 'N', 'N', 'N'),
(199, 4, 33, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(200, 4, 43, 'Y', 'N', 'N', 'N', 'N', 'N'),
(201, 4, 44, 'N', 'N', 'N', 'N', 'N', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_level`
--

CREATE TABLE `users_level` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users_level`
--

INSERT INTO `users_level` (`role_id`, `role_name`) VALUES
(1, 'Administrator'),
(2, 'Admin adpemb'),
(3, 'Admin Fisik dan keuangan OPD'),
(4, 'Admin Keuangan OPD'),
(5, 'Pimpinan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_menu`
--

CREATE TABLE `users_menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(50) NOT NULL,
  `menu_link` varchar(100) NOT NULL,
  `menu_icon` varchar(50) NOT NULL,
  `is_main_menu` int(11) NOT NULL,
  `fitur_add` enum('Y','N') NOT NULL,
  `fitur_update` enum('Y','N') NOT NULL,
  `fitur_delete` enum('Y','N') NOT NULL,
  `fitur_update_1` enum('Y','N') NOT NULL,
  `fitur_delete_1` enum('Y','N') NOT NULL,
  `urutan` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users_menu`
--

INSERT INTO `users_menu` (`menu_id`, `menu_name`, `menu_link`, `menu_icon`, `is_main_menu`, `fitur_add`, `fitur_update`, `fitur_delete`, `fitur_update_1`, `fitur_delete_1`, `urutan`) VALUES
(1, 'Dashboard', 'dashboard', 'fa fa-tachometer-alt', 0, 'N', 'N', 'N', 'N', 'N', 1),
(2, 'Konfigurasi', '#konfigurasi', 'fa fa-user-lock', 0, 'N', 'N', 'N', 'N', 'N', 11),
(3, 'List Menu', 'list-menu', '-', 2, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(4, 'Akses Menu', 'akses-menu', '-', 2, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(5, 'Master Data', '#master_data', 'fa fa-database', 0, 'N', 'N', 'N', 'N', 'N', 2),
(6, 'Data SKPD', 'data-skpd', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(7, 'Jenis Pelaksanaan', 'jenis-pelaksanaan', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 5),
(8, 'Kepala SKPD', 'kepala-skpd', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 6),
(9, 'Data User', '#users', 'fa fa-users', 0, 'N', 'N', 'N', 'N', 'N', 10),
(10, 'Data User', 'data-user', '-', 9, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(11, 'Profile User', 'profil-user', '-', 9, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(12, 'Postur APBD', '#postur_apbd', 'fa  fa-signal', 0, 'N', 'N', 'N', 'N', 'N', 3),
(13, 'Mandatory Spending', 'mandatory', '-', 12, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(14, 'Postur Anggaran', 'postur-anggaran', '-', 12, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(15, 'Dana Transfer &amp;  PAD', 'dana-transfer', '-', 12, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(16, 'Data APBD', 'data-apbd', 'fa fa-list', 0, 'Y', 'Y', 'Y', 'Y', 'Y', 4),
(17, 'Kegiatan Fisik', 'kegiatan', 'fa fa-building', 0, 'Y', 'Y', 'Y', 'Y', 'Y', 5),
(18, 'Data APBN', '#data_apbn', 'fa fa-signal', 0, 'N', 'N', 'N', 'N', 'N', 6),
(19, 'Dana DAK', 'dana-dak', '-', 18, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(20, 'Dana Dekonsentrasi', 'dana-dekonsentrasi', '-', 18, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(21, 'Dana Tugas Pembantuan', 'dana-tp', '-', 18, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(28, 'Upload Data', '#upload_data', 'fa fa-upload', 0, 'N', 'N', 'N', 'N', 'N', 7),
(29, 'Data Anggaran OPD', 'upload-anggaran-opd', '-', 28, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(30, 'Data LRA OPD', 'upload-lra-opd', '-', 28, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(31, 'Anggaran Kas', 'upload-anggaran-kas', '-', 28, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(42, 'Nama Daerah', 'nama-daerah', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(33, 'Realisasi Dana Desa', 'upload-dana-desa', '-', 28, 'Y', 'Y', 'Y', 'Y', 'Y', 5),
(34, 'Realisasi APBN', '#realisasi_apbn', 'fa fa-list', 0, 'N', 'N', 'N', 'N', 'N', 8),
(35, 'Realisasi Dana DAK', 'realisasi-dana-dak', '-', 34, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(36, 'Realisasi Dana Dekonsentrasi', 'realisasi-dana-dekon', '-', 34, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(37, 'Realisasi Dana Tugas Pembantuan', 'realisasi-dana-tp', '-', 34, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(38, 'Laporan', '#laporan', 'fa fa-industry', 0, 'N', 'N', 'N', 'N', 'N', 9),
(39, 'Data Realisasi Keuangan', 'realisasi-keuangan', '-', 38, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(40, 'Laporan Realisasi Keuangan', 'laporan-realisasi-keuangan', '-', 38, 'Y', 'Y', 'Y', 'Y', 'Y', 2),
(41, 'Laporan Realisasi Fisik', 'laporan-realisasi-fisik', '-', 38, 'Y', 'Y', 'Y', 'Y', 'Y', 3),
(43, 'Tampilan Aplikasi', 'tampilan-aplikasi', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 1),
(44, 'Data SKPD Per Tahun', 'skpd-tahun', '-', 5, 'Y', 'Y', 'Y', 'Y', 'Y', 4);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bulan`
--
ALTER TABLE `bulan`
  ADD PRIMARY KEY (`id_bulan`);

--
-- Indeks untuk tabel `data_kegiatan`
--
ALTER TABLE `data_kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`);

--
-- Indeks untuk tabel `data_kegiatan_detail`
--
ALTER TABLE `data_kegiatan_detail`
  ADD PRIMARY KEY (`id_kegiatan_detail`);

--
-- Indeks untuk tabel `data_kegiatan_fisik`
--
ALTER TABLE `data_kegiatan_fisik`
  ADD PRIMARY KEY (`id_kegiatan_fisik`);

--
-- Indeks untuk tabel `data_kode_rekening`
--
ALTER TABLE `data_kode_rekening`
  ADD PRIMARY KEY (`id_uraian`);

--
-- Indeks untuk tabel `data_kontrak_real`
--
ALTER TABLE `data_kontrak_real`
  ADD PRIMARY KEY (`id_real`);

--
-- Indeks untuk tabel `data_realisasi_detail_skpd`
--
ALTER TABLE `data_realisasi_detail_skpd`
  ADD PRIMARY KEY (`id_realisasi`);

--
-- Indeks untuk tabel `data_serapan_skpd`
--
ALTER TABLE `data_serapan_skpd`
  ADD PRIMARY KEY (`id_serapan`);

--
-- Indeks untuk tabel `data_skpd`
--
ALTER TABLE `data_skpd`
  ADD PRIMARY KEY (`id_skpd`);

--
-- Indeks untuk tabel `data_skpd_tahun`
--
ALTER TABLE `data_skpd_tahun`
  ADD PRIMARY KEY (`id_data`);

--
-- Indeks untuk tabel `data_uraian_kegiatan_skpd`
--
ALTER TABLE `data_uraian_kegiatan_skpd`
  ADD PRIMARY KEY (`id_uraian`);

--
-- Indeks untuk tabel `jenis_pelaksanaan`
--
ALTER TABLE `jenis_pelaksanaan`
  ADD PRIMARY KEY (`id_jenis_pelaksanaan`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kegiatan_dokumentasi`
--
ALTER TABLE `kegiatan_dokumentasi`
  ADD PRIMARY KEY (`id_dokumentasi`);

--
-- Indeks untuk tabel `log_upload`
--
ALTER TABLE `log_upload`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `log_upload_realisasi`
--
ALTER TABLE `log_upload_realisasi`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `penanda_tangan`
--
ALTER TABLE `penanda_tangan`
  ADD PRIMARY KEY (`id_ttd`);

--
-- Indeks untuk tabel `pengguna_anggaran`
--
ALTER TABLE `pengguna_anggaran`
  ADD PRIMARY KEY (`id_pa`);

--
-- Indeks untuk tabel `setting_anggaran`
--
ALTER TABLE `setting_anggaran`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `ta_kabupaten`
--
ALTER TABLE `ta_kabupaten`
  ADD PRIMARY KEY (`id_kabupaten`);

--
-- Indeks untuk tabel `ta_kontrak`
--
ALTER TABLE `ta_kontrak`
  ADD PRIMARY KEY (`id_kontrak`),
  ADD KEY `tahun` (`tahun`),
  ADD KEY `no_kontrak` (`no_kontrak`);

--
-- Indeks untuk tabel `ta_spp_kontrak`
--
ALTER TABLE `ta_spp_kontrak`
  ADD KEY `tahun` (`tahun`),
  ADD KEY `no_spp` (`no_spp`),
  ADD KEY `no_kontrak` (`no_kontrak`);

--
-- Indeks untuk tabel `ta_spp_rinc`
--
ALTER TABLE `ta_spp_rinc`
  ADD KEY `tahun` (`tahun`),
  ADD KEY `no_spp` (`no_spp`),
  ADD KEY `no_id` (`no_id`);

--
-- Indeks untuk tabel `tbl_dana_dekon`
--
ALTER TABLE `tbl_dana_dekon`
  ADD PRIMARY KEY (`id_dana`);

--
-- Indeks untuk tabel `tbl_data_dak`
--
ALTER TABLE `tbl_data_dak`
  ADD PRIMARY KEY (`id_dak`);

--
-- Indeks untuk tabel `tbl_mandatory`
--
ALTER TABLE `tbl_mandatory`
  ADD PRIMARY KEY (`id_mandatory`);

--
-- Indeks untuk tabel `tbl_realisasi_dak`
--
ALTER TABLE `tbl_realisasi_dak`
  ADD PRIMARY KEY (`id_realisasi`);

--
-- Indeks untuk tabel `tbl_realisasi_dekon`
--
ALTER TABLE `tbl_realisasi_dekon`
  ADD PRIMARY KEY (`id_realisasi`);

--
-- Indeks untuk tabel `tbl_realisasi_skpd`
--
ALTER TABLE `tbl_realisasi_skpd`
  ADD PRIMARY KEY (`id_realisasi`);

--
-- Indeks untuk tabel `tbl_tampilan`
--
ALTER TABLE `tbl_tampilan`
  ADD PRIMARY KEY (`id_data`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `users_access`
--
ALTER TABLE `users_access`
  ADD PRIMARY KEY (`access_id`);

--
-- Indeks untuk tabel `users_level`
--
ALTER TABLE `users_level`
  ADD PRIMARY KEY (`role_id`);

--
-- Indeks untuk tabel `users_menu`
--
ALTER TABLE `users_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_kegiatan`
--
ALTER TABLE `data_kegiatan`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_kegiatan_detail`
--
ALTER TABLE `data_kegiatan_detail`
  MODIFY `id_kegiatan_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_kegiatan_fisik`
--
ALTER TABLE `data_kegiatan_fisik`
  MODIFY `id_kegiatan_fisik` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_kode_rekening`
--
ALTER TABLE `data_kode_rekening`
  MODIFY `id_uraian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `data_kontrak_real`
--
ALTER TABLE `data_kontrak_real`
  MODIFY `id_real` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_realisasi_detail_skpd`
--
ALTER TABLE `data_realisasi_detail_skpd`
  MODIFY `id_realisasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_serapan_skpd`
--
ALTER TABLE `data_serapan_skpd`
  MODIFY `id_serapan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=469;

--
-- AUTO_INCREMENT untuk tabel `data_skpd`
--
ALTER TABLE `data_skpd`
  MODIFY `id_skpd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT untuk tabel `data_skpd_tahun`
--
ALTER TABLE `data_skpd_tahun`
  MODIFY `id_data` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_uraian_kegiatan_skpd`
--
ALTER TABLE `data_uraian_kegiatan_skpd`
  MODIFY `id_uraian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kegiatan_dokumentasi`
--
ALTER TABLE `kegiatan_dokumentasi`
  MODIFY `id_dokumentasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `log_upload`
--
ALTER TABLE `log_upload`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_upload_realisasi`
--
ALTER TABLE `log_upload_realisasi`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penanda_tangan`
--
ALTER TABLE `penanda_tangan`
  MODIFY `id_ttd` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengguna_anggaran`
--
ALTER TABLE `pengguna_anggaran`
  MODIFY `id_pa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `setting_anggaran`
--
ALTER TABLE `setting_anggaran`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `ta_kabupaten`
--
ALTER TABLE `ta_kabupaten`
  MODIFY `id_kabupaten` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `ta_kontrak`
--
ALTER TABLE `ta_kontrak`
  MODIFY `id_kontrak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_dana_dekon`
--
ALTER TABLE `tbl_dana_dekon`
  MODIFY `id_dana` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_data_dak`
--
ALTER TABLE `tbl_data_dak`
  MODIFY `id_dak` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_mandatory`
--
ALTER TABLE `tbl_mandatory`
  MODIFY `id_mandatory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `tbl_realisasi_dak`
--
ALTER TABLE `tbl_realisasi_dak`
  MODIFY `id_realisasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_realisasi_dekon`
--
ALTER TABLE `tbl_realisasi_dekon`
  MODIFY `id_realisasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_realisasi_skpd`
--
ALTER TABLE `tbl_realisasi_skpd`
  MODIFY `id_realisasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_tampilan`
--
ALTER TABLE `tbl_tampilan`
  MODIFY `id_data` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT untuk tabel `users_access`
--
ALTER TABLE `users_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT untuk tabel `users_level`
--
ALTER TABLE `users_level`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users_menu`
--
ALTER TABLE `users_menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
