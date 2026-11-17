-- Buat Database
CREATE DATABASE IF NOT EXISTS db_perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_perpustakaan;

-- =========================================================================
-- 1. TABEL USERS (Anggota Perpustakaan & Petugas/Admin)
-- Selaras dengan app/models/User.php & app/views/users/index.php
-- =========================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_anggota VARCHAR(30) UNIQUE NOT NULL,   -- Misal: PTG-001 atau AGT-2026001
    nama_lengkap VARCHAR(150) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,             -- Simpan hash password_hash()
    role ENUM('admin', 'petugas', 'anggota') NOT NULL DEFAULT 'anggota',
    no_telepon VARCHAR(20) NULL,
    alamat TEXT NULL,
    status_akun ENUM('aktif', 'nonaktif', 'dibekukan') DEFAULT 'aktif',
    last_login_at DATETIME NULL,
    foto_profil VARCHAR(255) NULL,
    deleted_at DATETIME NULL,                   -- Mendukung SoftDelete.php
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 2. TABEL CATEGORIES (Kategori / Rak Buku)
-- Selaras dengan app/models/Category.php & app/views/categories/
-- =========================================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(20) UNIQUE NOT NULL,  -- Misal: RAK-A1, FIKSI, TEK
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================================================
-- 3. TABEL ITEMS (Koleksi Buku)
-- Selaras dengan app/models/Item.php & app/views/items/ (termasuk trash.php)
-- =========================================================================
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    kode_buku VARCHAR(30) UNIQUE NOT NULL,      -- Barcode / Nomor Induk Buku
    isbn VARCHAR(20) NULL,
    judul VARCHAR(200) NOT NULL,
    penulis VARCHAR(150) NOT NULL,
    penerbit VARCHAR(100) NULL,
    tahun_terbit YEAR NULL,
    lokasi_rak VARCHAR(50) NULL,
    stok_total INT NOT NULL DEFAULT 0,          -- Jumlah eksemplar milik perpustakaan
    stok_tersedia INT NOT NULL DEFAULT 0,       -- Jumlah buku yang siap dipinjam
    cover VARCHAR(255) NULL,                    -- Path gambar di folder images/
    deskripsi TEXT NULL,
    deleted_at DATETIME NULL,                   -- Untuk fitur Soft Delete & Trash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_items_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================================================================
-- 4. TABEL TRANSACTIONS (Sirkulasi Peminjaman, Pengembalian & Denda)
-- Selaras dengan app/models/Transaction.php & app/views/transactions/
-- =========================================================================
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(30) UNIQUE NOT NULL, -- Misal: TRX-20261117-001
    user_id INT NOT NULL,                       -- Anggota yang meminjam
    item_id INT NOT NULL,                       -- Buku yang dipinjam
    petugas_id INT NOT NULL,                    -- Petugas/Admin yang memproses
    jumlah INT NOT NULL DEFAULT 1,
    tanggal_pinjam DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    tanggal_kembali DATE NULL,                  -- Diisi saat buku dikembalikan
    status ENUM('dipinjam', 'dikembalikan', 'terlambat', 'hilang') NOT NULL DEFAULT 'dipinjam',
    hari_terlambat INT NOT NULL DEFAULT 0,
    tarif_denda_per_hari DECIMAL(10, 2) NOT NULL DEFAULT 2000.00,
    jumlah_denda DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    status_denda ENUM('tidak_ada', 'belum_lunas', 'lunas') NOT NULL DEFAULT 'tidak_ada',
    tanggal_bayar_denda DATETIME NULL,
    catatan TEXT NULL,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_trx_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_trx_item FOREIGN KEY (item_id) REFERENCES items(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_trx_petugas FOREIGN KEY (petugas_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================================================================
-- 5. TABEL ACTIVITY_LOGS (Catatan Aktivitas Sistem)
-- Selaras dengan app/models/ActivityLog.php, core/Logger.php & views/logs/
-- =========================================================================
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,                           -- Bisa NULL jika aktivitas sistem / gagal login
    aksi VARCHAR(100) NOT NULL,                 -- Contoh: 'PINJAM_BUKU', 'KEMBALI_BUKU', 'BAYAR_DENDA'
    tabel_terkait VARCHAR(50) NULL,             -- Contoh: 'transactions', 'items', 'users'
    record_id INT NULL,                         -- ID baris data yang diubah
    keterangan TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================================
-- 6. TABEL SETTINGS (Pengaturan Sirkulasi & Denda Dinamis)
-- =========================================================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255) NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (kode_anggota, nama_lengkap, username, password, role, no_telepon, status_akun) VALUES
('ADM-001', 'Administrator Perpus', 'admin@', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567890', 'aktif');

INSERT INTO categories (kode_kategori, nama_kategori, deskripsi) VALUES
('KAT-TEK', 'Teknologi & Pemrograman', 'Buku ilmu komputer, rekayasa perangkat lunak, dan jaringan'),
('KAT-FIK', 'Fiksi & Sastra', 'Novel, kumpulan cerpen, dan karya sastra'),
('KAT-REF', 'Referensi & Jurnal', 'Kamus, ensiklopedia, dan karya ilmiah');

INSERT INTO items (category_id, kode_buku, isbn, judul, penulis, penerbit, tahun_terbit, lokasi_rak, stok_total, stok_tersedia) VALUES
(1, 'BK-001', '978-602-04-8821-9', 'Clean Code: Panduan Praktis Kode Bersih', 'Robert C. Martin', 'Informatika', 2021, 'Rak A-01', 5, 4),
(1, 'BK-002', '978-623-00-1234-5', 'Arsitektur PHP MVC Modern', 'Andi Wijaya', 'Elex Media', 2023, 'Rak A-02', 3, 3),
(2, 'BK-003', '978-979-22-4861-6', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2008, 'Rak B-01', 4, 3);

INSERT INTO settings (setting_key, setting_value, keterangan) VALUES
('max_hari_pinjam', '7', 'Maksimal durasi peminjaman buku dalam hari'),
('denda_per_hari', '2000', 'Tarif denda keterlambatan per hari (Rupiah)'),
('max_buku_pinjam', '3', 'Batas maksimal buku yang dapat dipinjam per anggota');


INSERT INTO transactions (kode_transaksi, user_id, item_id, petugas_id, jumlah, tanggal_pinjam, tanggal_jatuh_tempo, status, tarif_denda_per_hari) VALUES
('TRX-20261115-001', 3, 1, 2, 1, '2026-11-15', '2026-11-22', 'dipinjam', 2000.00),
('TRX-20261101-002', 4, 3, 2, 1, '2026-11-01', '2026-11-08', 'dipinjam', 2000.00);