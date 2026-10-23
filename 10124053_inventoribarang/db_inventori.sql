-- Database setup for Sistem Informasi Manajemen Data Sederhana: Inventaris Barang
-- Import this file from phpMyAdmin after selecting/creating db_inventori.
-- No DROP TABLE statements are used, so existing tables are not deleted.

CREATE DATABASE IF NOT EXISTS db_inventori
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_inventori;

CREATE TABLE IF NOT EXISTS barang (
  kode_barang VARCHAR(20) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  total_stok INT UNSIGNED NOT NULL DEFAULT 0,
  rak_penyimpanan VARCHAR(50) NOT NULL,
  PRIMARY KEY (kode_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contoh data barang: tidak menimpa barang yang sudah memiliki kode sama.
INSERT IGNORE INTO barang
  (kode_barang, nama_barang, kategori, total_stok, rak_penyimpanan)
VALUES
  ('BRG004', 'Speaker', 'Elektronik', 12, 'RAK 1');

-- Akun demo. Password awal: admin123
-- Hash dibuat dengan PHP password_hash('admin123', PASSWORD_DEFAULT).
-- Jika username admin sudah ada, hash passwordnya diperbarui ke admin123.
INSERT INTO users (username, password, nama)
VALUES (
  'admin',
  '$2y$10$SpMOCFBYaFM2ER4XKkfoKuJgKNuqbKbOrB4PQOh2tVPn5fCWzhMIS',
  'Administrator'
)
ON DUPLICATE KEY UPDATE
  password = VALUES(password),
  nama = VALUES(nama);
