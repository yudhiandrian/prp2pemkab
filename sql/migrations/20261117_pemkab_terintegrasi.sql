-- Tabel baru saja; tabel operasional lama tidak diubah/dihapus.
CREATE TABLE IF NOT EXISTS pk_installation (
 id TINYINT UNSIGNED PRIMARY KEY, installation_id CHAR(36) NOT NULL UNIQUE,
 id_kabupaten INT UNSIGNED NOT NULL, nama_daerah VARCHAR(180) NOT NULL,
 approval_mode VARCHAR(12) NOT NULL DEFAULT 'manual', created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_user_access (
 id_user INT NOT NULL PRIMARY KEY, role VARCHAR(12) NOT NULL, id_opd_pemkab INT UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pemkab_opd (
 id_opd_pemkab INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 id_kabupaten INT UNSIGNED NOT NULL, nama_opd VARCHAR(180) NOT NULL,
 status_aktif CHAR(1) NOT NULL DEFAULT 'Y', created_at DATETIME NOT NULL,
 UNIQUE KEY uk_opd (id_kabupaten,nama_opd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pemkab_opd_tahun (
 id_opd_tahun INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, id_opd_pemkab INT UNSIGNED NOT NULL,
 tahun SMALLINT UNSIGNED NOT NULL, nama_opd VARCHAR(180) NOT NULL, status_aktif CHAR(1) NOT NULL DEFAULT 'Y',
 UNIQUE KEY uk_opd_tahun (id_opd_pemkab,tahun),
 FOREIGN KEY (id_opd_pemkab) REFERENCES pemkab_opd(id_opd_pemkab)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_references (
 jenis VARCHAR(10) NOT NULL, kode VARCHAR(50) NOT NULL, nama VARCHAR(180) NOT NULL,
 version VARCHAR(80) NOT NULL, aktif CHAR(1) NOT NULL DEFAULT 'Y', PRIMARY KEY(jenis,kode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS infrastruktur_wilayah (
 id_kegiatan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 id_kabupaten INT UNSIGNED NOT NULL, tahun SMALLINT UNSIGNED NOT NULL,
 keperluan VARCHAR(255) NOT NULL, pagu DECIMAL(18,2) NOT NULL, nilai DECIMAL(18,2) NOT NULL DEFAULT 0,
 tgl_kontrak DATE NULL, waktu VARCHAR(150) NOT NULL DEFAULT '', st_kontrak CHAR(1) NOT NULL DEFAULT 'B',
 lokasi_pekerjaan VARCHAR(150) NOT NULL DEFAULT '', koordinat VARCHAR(75) NOT NULL DEFAULT '',
 status_1 CHAR(1) NOT NULL DEFAULT 'N', status_3 CHAR(1) NOT NULL DEFAULT 'Y', tkd CHAR(1) NOT NULL DEFAULT 'N',
 psd VARCHAR(50) NULL, phtc VARCHAR(50) NULL, deleted_at DATETIME NULL, created_at DATETIME NOT NULL,
 KEY idx_kegiatan_periode (id_kabupaten,tahun,deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pemkab_kegiatan_opd (
 id_pemetaan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, id_kegiatan INT UNSIGNED NOT NULL UNIQUE,
 id_opd_pemkab INT UNSIGNED NOT NULL, KEY idx_opd (id_opd_pemkab),
 FOREIGN KEY(id_kegiatan) REFERENCES infrastruktur_wilayah(id_kegiatan),
 FOREIGN KEY(id_opd_pemkab) REFERENCES pemkab_opd(id_opd_pemkab)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS infrastruktur_detail (
 id_detail INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 id_kegiatan INT UNSIGNED NOT NULL, tanggal DATE NOT NULL, realisasi DECIMAL(5,2) NOT NULL,
 keterangan VARCHAR(500) NOT NULL DEFAULT '', deleted_at DATETIME NULL,
 KEY idx_fisik (id_kegiatan,tanggal,id_detail), FOREIGN KEY(id_kegiatan) REFERENCES infrastruktur_wilayah(id_kegiatan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS infrastruktur_keuangan (
 id_keuangan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 id_kegiatan INT UNSIGNED NOT NULL, tahun SMALLINT UNSIGNED NOT NULL, tgl_realisasi DATE NOT NULL,
 nilai DECIMAL(18,2) NOT NULL, keterangan VARCHAR(500) NOT NULL DEFAULT '', deleted_at DATETIME NULL,
 KEY idx_keuangan (id_kegiatan,tgl_realisasi), FOREIGN KEY(id_kegiatan) REFERENCES infrastruktur_wilayah(id_kegiatan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS infrastruktur_dokumentasi (
 id_dokumentasi INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 id_detail INT UNSIGNED NOT NULL, file_dokumentasi VARCHAR(80) NOT NULL UNIQUE,
 sha256 CHAR(64) NOT NULL, mime VARCHAR(32) NOT NULL, ukuran INT UNSIGNED NOT NULL,
 tanggal DATE NULL, keterangan VARCHAR(500) NOT NULL DEFAULT '', deleted_at DATETIME NULL,
 FOREIGN KEY(id_detail) REFERENCES infrastruktur_detail(id_detail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_finance (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, source_uuid CHAR(36) NOT NULL UNIQUE,
 jenis VARCHAR(20) NOT NULL, tahun SMALLINT UNSIGNED NOT NULL, bulan TINYINT UNSIGNED NOT NULL DEFAULT 0,
 status_apbd VARCHAR(12) NOT NULL DEFAULT 'murni', kode VARCHAR(50) NOT NULL,
 nama VARCHAR(180) NOT NULL, nilai DECIMAL(18,2) NOT NULL, deleted_at DATETIME NULL,
 UNIQUE KEY uk_finance (jenis,tahun,bulan,status_apbd,kode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_sync_entities (
 source_uuid CHAR(36) PRIMARY KEY, entity_type VARCHAR(20) NOT NULL,
 source_version INT UNSIGNED NOT NULL DEFAULT 0, remote_id VARCHAR(100) NULL,
 remote_version INT UNSIGNED NOT NULL DEFAULT 0, ack_source_version INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_outbox (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, event_id CHAR(36) NOT NULL UNIQUE,
 source_uuid CHAR(36) NOT NULL, source_version INT UNSIGNED NOT NULL,
 entity_type VARCHAR(20) NOT NULL, payload LONGTEXT NOT NULL, checksum CHAR(64) NOT NULL,
 status VARCHAR(16) NOT NULL, attempts INT UNSIGNED NOT NULL DEFAULT 0,
 next_attempt DATETIME NOT NULL, lease_until DATETIME NULL, lease_token CHAR(36) NULL,
 request_body LONGTEXT NULL, approved_by INT NULL, approved_at DATETIME NULL,
 last_error VARCHAR(500) NULL, receipt LONGTEXT NULL, created_at DATETIME NOT NULL,
 UNIQUE KEY uk_event_version (source_uuid,source_version), KEY idx_worker (status,next_attempt),
 FOREIGN KEY(source_uuid) REFERENCES pk_sync_entities(source_uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_sync_attempts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, event_id CHAR(36) NOT NULL,
 attempted_at DATETIME NOT NULL, http_status INT NOT NULL, outcome VARCHAR(16) NOT NULL,
 message VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_audit (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, id_user INT NOT NULL, action VARCHAR(60) NOT NULL,
 source_uuid CHAR(36) NULL, detail LONGTEXT NOT NULL, created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_legacy_map (
 source_table VARCHAR(40) NOT NULL, source_id INT NOT NULL, target_uuid CHAR(36) NOT NULL,
 source_checksum CHAR(64) NOT NULL, PRIMARY KEY(source_table,source_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pk_migration_runs (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tahun SMALLINT UNSIGNED NOT NULL,
 status VARCHAR(16) NOT NULL, report LONGTEXT NOT NULL, created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
