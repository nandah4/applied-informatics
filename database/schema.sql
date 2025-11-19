-- CREATE DATABASE
-- CREATE DATABASE db_lab_ai;

CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- ==========================================
-- 1. SYSTEM & AUTHENTICATION (sys_)
-- ==========================================

CREATE TYPE user_role_enum AS ENUM ('guest', 'admin');

-- Mengganti tbl_users menjadi sys_users
CREATE TABLE sys_users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role          user_role_enum NOT NULL DEFAULT 'guest',
    created_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Seed Admin
INSERT INTO sys_users (email, password, role)
VALUES ('admin@gmail.com', crypt('12345678', gen_salt('bf', 12)), 'admin');


-- ==========================================
-- 2. REFERENCE DATA (ref_)
-- Tabel kecil untuk lookup/pilihan
-- ==========================================

-- Mengganti tbl_jabatan menjadi ref_jabatan
CREATE TABLE ref_jabatan(
    id BIGSERIAL PRIMARY KEY,
    nama_jabatan VARCHAR(255) UNIQUE NOT NULL
);

-- Mengganti tbl_keahlian menjadi ref_keahlian
CREATE TABLE ref_keahlian(
    id BIGSERIAL PRIMARY KEY,
    nama_keahlian VARCHAR(255) UNIQUE NOT NULL
);


-- ==========================================
-- 3. MASTER DATA (mst_)
-- Entitas utama dalam sistem
-- ==========================================

-- Mengganti tbl_dosen menjadi mst_dosen
CREATE TABLE mst_dosen (
    id BIGSERIAL PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    nidn VARCHAR(50) UNIQUE,
    foto_profil VARCHAR(255),
    deskripsi text default NULL,
    jabatan_id bigint, 

    CONSTRAINT fk_ref_jabatan
        FOREIGN KEY(jabatan_id) 
        REFERENCES ref_jabatan(id)
        ON DELETE SET NULL
);

-- Mengganti tbl_fasilitas menjadi mst_fasilitas
CREATE TABLE mst_fasilitas (
    id            BIGSERIAL PRIMARY KEY, -- ganti fasilitas_id jadi id biar konsisten
    nama          VARCHAR(150) NOT NULL,
    deskripsi     TEXT,
    foto          VARCHAR(255),
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Mengganti tbl_mitra menjadi mst_mitra
CREATE TYPE mitra_status_enum AS ENUM ('aktif', 'non-aktif');
CREATE TYPE mitra_kategori_enum AS ENUM ('industri', 'internasional', 'institusi pemerintah', 'institusi pendidikan', 'komunitas');

CREATE TABLE mst_mitra (
    id              BIGSERIAL PRIMARY KEY,
    nama            VARCHAR(150) NOT NULL,
    status          mitra_status_enum NOT NULL DEFAULT 'non-aktif',
    deskripsi       TEXT,
    logo_mitra      VARCHAR(255),
    kategori        mitra_kategori_enum NOT NULL DEFAULT 'industri',
    tanggal_mulai   DATE,
    tanggal_akhir   DATE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW()
);

-- mst_produk
CREATE TABLE mst_produk (
    id            BIGSERIAL PRIMARY KEY,
    nama_produk   VARCHAR(255) NOT NULL,
    deskripsi     TEXT,
    foto_produk   VARCHAR(255),
    link_produk   VARCHAR(255),
    tim_mahasiswa VARCHAR(255), 
    
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Mapping Table: Produk <-> Dosen
CREATE TABLE map_produk_dosen (
    produk_id BIGINT NOT NULL,
    dosen_id  BIGINT NOT NULL,
    
    CONSTRAINT fk_map_produk
        FOREIGN KEY(produk_id) 
        REFERENCES mst_produk(id)
        ON DELETE CASCADE,
        
    CONSTRAINT fk_map_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE,
        
    -- Composite Primary Key: Mencegah duplikasi data dosen yang sama di produk yang sama
    PRIMARY KEY (produk_id, dosen_id)
);

-- ==========================================
-- 4. SUB-MASTER / DETAILS (dtl_)
-- Detail tambahan yang terikat pada Master
-- ==========================================

CREATE TYPE profil_tipe_enum AS ENUM (
    'SINTA', 'SCOPUS', 'GOOGLE_SCHOLAR', 'ORCID', 'RESEARCHGATE'
);

-- Mengganti tbl_profil_publikasi menjadi dtl_dosen_link
-- Ini bukan data publikasi, tapi link profil eksternal dosen
CREATE TABLE dtl_dosen_link (
    id            BIGSERIAL PRIMARY KEY,
    dosen_id      BIGINT NOT NULL, 
    tipe          profil_tipe_enum NOT NULL, 
    url_ke_profil VARCHAR(255) NOT NULL,

    CONSTRAINT fk_mst_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE
);


-- ==========================================
-- 5. TRANSACTION / ACTIVITY DATA (trx_)
-- Data yang bersifat kejadian, event, atau log
-- ==========================================

-- Mengganti tbl_aktivitas_lab menjadi trx_aktivitas
CREATE TABLE trx_aktivitas (
    id              BIGSERIAL PRIMARY KEY,
    judul_aktivitas VARCHAR(255) NOT NULL,
    deskripsi       TEXT,
    foto_aktivitas  VARCHAR(255), 
    tanggal_kegiatan DATE, 
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TYPE rekrutmen_status_enum AS ENUM ('buka', 'tutup');

-- Mengganti tbl_rekrutmen menjadi trx_rekrutmen
-- Masuk transaksi karena ada periode buka/tutup
CREATE TABLE trx_rekrutmen (
    id            BIGSERIAL PRIMARY KEY,
    judul         VARCHAR(255) NOT NULL,
    deskripsi     TEXT NOT NULL,
    status        rekrutmen_status_enum NOT NULL DEFAULT 'tutup', 
    tanggal_buka  DATE,
    tanggal_tutup DATE,
    lokasi        VARCHAR(255),
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TYPE publikasi_tipe_enum AS ENUM ('Riset', 'Kekayaan Intelektual', 'PPM');

-- Mengganti tbl_publikasi menjadi trx_publikasi
-- Bisa jadi Master atau Transaksi. Masuk Transaksi karena merupakan output kegiatan tahunan.
CREATE TABLE trx_publikasi (
    id            BIGSERIAL PRIMARY KEY,
    dosen_id      BIGINT NOT NULL, 
    judul         TEXT NOT NULL,
    url_publikasi VARCHAR(255),
    tahun_publikasi INT,
    tipe_publikasi publikasi_tipe_enum NOT NULL, 
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_mst_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE
);


-- ==========================================
-- 6. MAPPING / PIVOT TABLES (map_)
-- Relasi Many-to-Many
-- ==========================================

-- Mengganti tbl_dosen_keahlian menjadi map_dosen_keahlian
CREATE TABLE map_dosen_keahlian(
    dosen_id bigint,
    keahlian_id bigint,
    
    CONSTRAINT fk_mst_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE,
    
    CONSTRAINT fk_ref_keahlian
        FOREIGN KEY(keahlian_id) 
        REFERENCES ref_keahlian(id)
        ON DELETE CASCADE,
    
    PRIMARY KEY(dosen_id, keahlian_id)
);