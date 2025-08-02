-- Database: Sistem Koperasi Arkebono
-- Created: 2025-01-27
-- Description: Database untuk sistem koperasi dengan master data dan transaksi

-- Drop database if exists (uncomment if needed)
-- DROP DATABASE IF EXISTS ar kebono_db;

-- Create database
CREATE DATABASE IF NOT EXISTS ar kebono_db;
USE ar kebono_db;

-- Create users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create cache table
CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
);

-- Create cache_locks table
CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
);

-- Create jobs table
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX queue_index (queue)
);

-- Create failed_jobs table
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create master_karyawan table
CREATE TABLE master_karyawan (
    npk VARCHAR(10) NOT NULL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create master_item table
CREATE TABLE master_item (
    kode VARCHAR(10) NOT NULL PRIMARY KEY,
    nama_item VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create transaksi_koperasi table
CREATE TABLE transaksi_koperasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tanggal_transaksi DATE NOT NULL,
    npk VARCHAR(10) NOT NULL,
    kode_item VARCHAR(10) NOT NULL,
    jumlah INTEGER NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (npk) REFERENCES master_karyawan(npk) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kode_item) REFERENCES master_item(kode) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert sample data for master_karyawan
INSERT INTO master_karyawan (npk, nama, alamat) VALUES
('K001', 'Ahmad Supriadi', 'Jl. Merdeka No. 123, Jakarta'),
('K002', 'Siti Nurhaliza', 'Jl. Sudirman No. 45, Bandung'),
('K003', 'Budi Santoso', 'Jl. Gatot Subroto No. 67, Surabaya'),
('K004', 'Dewi Sartika', 'Jl. Thamrin No. 89, Medan'),
('K005', 'Rudi Hermawan', 'Jl. Asia Afrika No. 12, Semarang');

-- Insert sample data for master_item
INSERT INTO master_item (kode, nama_item, harga) VALUES
('I001', 'Beras Premium 5kg', 75000.00),
('I002', 'Minyak Goreng 2L', 25000.00),
('I003', 'Gula Pasir 1kg', 15000.00),
('I004', 'Tepung Terigu 1kg', 12000.00),
('I005', 'Telur Ayam 1kg', 28000.00),
('I006', 'Susu Kental Manis 1L', 18000.00),
('I007', 'Kecap Manis 600ml', 22000.00),
('I008', 'Sarden Kaleng 425gr', 8500.00),
('I009', 'Mie Instan 1 Dus', 45000.00),
('I010', 'Kopi Bubuk 200gr', 35000.00);

-- Insert sample data for transaksi_koperasi
INSERT INTO transaksi_koperasi (tanggal_transaksi, npk, kode_item, jumlah, harga_satuan, total_harga) VALUES
('2025-01-15', 'K001', 'I001', 2, 75000.00, 150000.00),
('2025-01-15', 'K001', 'I002', 1, 25000.00, 25000.00),
('2025-01-16', 'K002', 'I003', 3, 15000.00, 45000.00),
('2025-01-16', 'K002', 'I004', 2, 12000.00, 24000.00),
('2025-01-17', 'K003', 'I005', 1, 28000.00, 28000.00),
('2025-01-17', 'K003', 'I006', 2, 18000.00, 36000.00),
('2025-01-18', 'K004', 'I007', 1, 22000.00, 22000.00),
('2025-01-18', 'K004', 'I008', 5, 8500.00, 42500.00),
('2025-01-19', 'K005', 'I009', 1, 45000.00, 45000.00),
('2025-01-19', 'K005', 'I010', 2, 35000.00, 70000.00);

-- Create indexes for better performance
CREATE INDEX idx_transaksi_tanggal ON transaksi_koperasi(tanggal_transaksi);
CREATE INDEX idx_transaksi_npk ON transaksi_koperasi(npk);
CREATE INDEX idx_transaksi_kode_item ON transaksi_koperasi(kode_item);
CREATE INDEX idx_master_karyawan_nama ON master_karyawan(nama);
CREATE INDEX idx_master_item_nama ON master_item(nama_item);

-- Create view for transaksi with detail
CREATE VIEW v_transaksi_detail AS
SELECT 
    t.id,
    t.tanggal_transaksi,
    t.npk,
    k.nama AS nama_karyawan,
    t.kode_item,
    i.nama_item,
    i.harga AS harga_satuan,
    t.jumlah,
    t.total_harga,
    t.created_at,
    t.updated_at
FROM transaksi_koperasi t
JOIN master_karyawan k ON t.npk = k.npk
JOIN master_item i ON t.kode_item = i.kode;

-- Create view for laporan transaksi harian
CREATE VIEW v_laporan_harian AS
SELECT 
    tanggal_transaksi,
    COUNT(*) AS jumlah_transaksi,
    SUM(total_harga) AS total_penjualan,
    COUNT(DISTINCT npk) AS jumlah_karyawan
FROM transaksi_koperasi
GROUP BY tanggal_transaksi
ORDER BY tanggal_transaksi DESC;

-- Create view for laporan item terlaris
CREATE VIEW v_item_terlaris AS
SELECT 
    t.kode_item,
    i.nama_item,
    SUM(t.jumlah) AS total_terjual,
    SUM(t.total_harga) AS total_pendapatan,
    COUNT(*) AS jumlah_transaksi
FROM transaksi_koperasi t
JOIN master_item i ON t.kode_item = i.kode
GROUP BY t.kode_item, i.nama_item
ORDER BY total_terjual DESC;

-- Create view for laporan karyawan
CREATE VIEW v_laporan_karyawan AS
SELECT 
    t.npk,
    k.nama AS nama_karyawan,
    COUNT(*) AS jumlah_transaksi,
    SUM(t.total_harga) AS total_pembelian,
    AVG(t.total_harga) AS rata_rata_transaksi
FROM transaksi_koperasi t
JOIN master_karyawan k ON t.npk = k.npk
GROUP BY t.npk, k.nama
ORDER BY total_pembelian DESC;

-- Grant permissions (adjust as needed)
-- GRANT ALL PRIVILEGES ON ar kebono_db.* TO 'your_username'@'localhost';
-- FLUSH PRIVILEGES;

-- Show tables
SHOW TABLES;

-- Show sample data
SELECT 'Master Karyawan' AS table_name, COUNT(*) AS record_count FROM master_karyawan
UNION ALL
SELECT 'Master Item' AS table_name, COUNT(*) AS record_count FROM master_item
UNION ALL
SELECT 'Transaksi Koperasi' AS table_name, COUNT(*) AS record_count FROM transaksi_koperasi; 