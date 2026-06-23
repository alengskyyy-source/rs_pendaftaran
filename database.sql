CREATE DATABASE IF NOT EXISTS rs_pendaftaran
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE rs_pendaftaran;

DROP TABLE IF EXISTS pendaftaran;
DROP TABLE IF EXISTS pasien;
DROP TABLE IF EXISTS dokter;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pasien') NOT NULL DEFAULT 'pasien',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pasien (
    id_pasien INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL UNIQUE,
    nama_pasien VARCHAR(100) NOT NULL,
    tgl_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    no_telp VARCHAR(20) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pasien_user
        FOREIGN KEY (id_user) REFERENCES users(id_user)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE dokter (
    id_dokter INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokter VARCHAR(100) NOT NULL,
    spesialis VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pendaftaran (
    id_daftar INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT NOT NULL,
    id_dokter INT NOT NULL,
    keluhan TEXT NOT NULL,
    tgl_kunjungan DATE NOT NULL,
    jam_kunjungan TIME NOT NULL,
    status ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    CONSTRAINT fk_pendaftaran_pasien
        FOREIGN KEY (id_pasien) REFERENCES pasien(id_pasien)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_pendaftaran_dokter
        FOREIGN KEY (id_dokter) REFERENCES dokter(id_dokter)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Login admin awal:
-- username: admin
-- password: admin123
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$12$3ig.Up3kxy5hRQRaccVFf.ncl6AA2.O2WO09hM34xQqaYsFmm1T.q', 'admin');

INSERT INTO dokter (nama_dokter, spesialis) VALUES
('dr. Andi Pratama, Sp.PD', 'Penyakit Dalam'),
('dr. Siti Rahma, Sp.A', 'Anak'),
('dr. Budi Santoso, Sp.B', 'Bedah'),
('dr. Rina Marlina, Sp.OG', 'Kandungan'),
('dr. Ahmad Fauzi, Sp.JP', 'Jantung'),
('dr. Dewi Lestari, Sp.M', 'Mata'),
('dr. Reza Kurniawan, Sp.THT', 'THT');
