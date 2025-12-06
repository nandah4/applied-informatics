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
-- Tabel kecil untuk lookup/pilihan/detail
-- ==========================================

-- table jabatan
CREATE TABLE ref_jabatan(
    id BIGSERIAL PRIMARY KEY,
    nama_jabatan VARCHAR(255) UNIQUE NOT NULL
);

-- table keahlian
CREATE TABLE ref_keahlian(
    id BIGSERIAL PRIMARY KEY,
    nama_keahlian VARCHAR(255) UNIQUE NOT NULL
);

CREATE TYPE profil_tipe_enum AS ENUM (
    'SINTA', 'SCOPUS', 'GOOGLE_SCHOLAR', 'ORCID', 'RESEARCHGATE'
);

-- table profil_publikasi_dosen
CREATE TABLE ref_profil_publikasi (
    id            BIGSERIAL PRIMARY KEY,
    dosen_id      BIGINT NOT NULL, 
    tipe          profil_tipe_enum NOT NULL, 
    url_profil TEXT NOT NULL,

    CONSTRAINT fk_mst_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE
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
    status_aktif    BOOLEAN DEFAULT TRUE,
    jabatan_id bigint,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW(), 

    CONSTRAINT fk_ref_jabatan
        FOREIGN KEY(jabatan_id) 
        REFERENCES ref_jabatan(id)
        ON DELETE SET NULL
);

-- Mengganti tbl_fasilitas menjadi mst_fasilitas
CREATE TABLE mst_fasilitas (
    id            BIGSERIAL PRIMARY KEY, 
    nama          VARCHAR(150) NOT NULL,
    deskripsi     VARCHAR(255),
    foto          TEXT,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Mengganti tbl_mitra menjadi mst_mitra
CREATE TYPE mitra_status_enum AS ENUM ('aktif', 'non-aktif');
CREATE TYPE mitra_kategori_enum AS ENUM ('industri', 'internasional', 'institusi pemerintah', 'institusi pendidikan', 'komunitas');

CREATE TABLE mst_mitra (
    id              BIGSERIAL PRIMARY KEY,
    nama            VARCHAR(200) NOT NULL,
    status          mitra_status_enum NOT NULL DEFAULT 'non-aktif',
    logo_mitra      TEXT,
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
    deskripsi     VARCHAR(255),
    foto_produk   TEXT,
    link_produk   VARCHAR(255),
    tim_mahasiswa VARCHAR(255), 
    
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);


-- TABLE: mst_mahasiswa
CREATE TABLE mst_mahasiswa (
    id              BIGSERIAL PRIMARY KEY,
    
    nim             VARCHAR(20) UNIQUE NOT NULL,
    email           VARCHAR(150) UNIQUE NOT NULL,
    nama            VARCHAR(150) NOT NULL,
    no_hp           VARCHAR(20), 
    jabatan_lab     VARCHAR(100) DEFAULT 'Asisten Lab',
    semester        INT NOT NULL,
    
    link_github     VARCHAR(255),
    
    status_aktif    BOOLEAN DEFAULT TRUE,
    tanggal_gabung  DATE DEFAULT CURRENT_DATE,
    
    asal_pendaftar_id BIGINT,

    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    
    -- Constraint ke trx_pendaftar
    CONSTRAINT fk_asal_pendaftar
        FOREIGN KEY(asal_pendaftar_id)
        REFERENCES trx_pendaftar(id)
        ON DELETE SET NULL
);

-- Trigger
CREATE TRIGGER trg_set_timestamp_mahasiswa
BEFORE UPDATE ON mst_mahasiswa
FOR EACH ROW EXECUTE FUNCTION fn_trigger_set_timestamp();



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
-- 5. TRANSACTION / ACTIVITY DATA (trx_)
-- Data yang bersifat kejadian, event, atau log
-- ==========================================

-- Mengganti tbl_aktivitas_lab menjadi trx_aktivitas
CREATE TABLE trx_aktivitas_lab(
    id              BIGSERIAL PRIMARY KEY,
    judul_aktivitas VARCHAR(255) NOT NULL,
    deskripsi       TEXT,
    foto_aktivitas  TEXT, 
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

CREATE TYPE publikasi_tipe_enum AS ENUM ('Publikasi', 'Riset', 'Kekayaan Intelektual', 'PPM');

-- TABLE : trx_publikasi
CREATE TABLE trx_publikasi (
    id            BIGSERIAL PRIMARY KEY,
    dosen_id      BIGINT NOT NULL, 
    judul         TEXT NOT NULL,
    url_publikasi TEXT,
    tahun_publikasi INT,
    tipe_publikasi publikasi_tipe_enum NOT NULL, 
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_mst_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES mst_dosen(id)
        ON DELETE CASCADE
);


-- TABLE : trx_pendaftar

CREATE TYPE seleksi_status_enum AS ENUM ('Pending', 'Diterima', 'Ditolak');

CREATE TABLE trx_pendaftar (
    id              BIGSERIAL PRIMARY KEY,
    
    -- Relasi ke Transaksi Rekrutmen
    rekrutmen_id    BIGINT NOT NULL, 
    
    nim             VARCHAR(20) NOT NULL,
    nama            VARCHAR(150) NOT NULL,
    email           VARCHAR(150) NOT NULL,
    no_hp           VARCHAR(20),
    semester        INT NOT NULL,
    ipk             DECIMAL(3,2),
    
    link_portfolio  VARCHAR(255),
    link_github     VARCHAR(255),
    file_cv         VARCHAR(255) NOT NULL,
    file_khs        VARCHAR(255),
    
    status_seleksi  seleksi_status_enum NOT NULL DEFAULT 'Pending',
    deskripsi       TEXT DEFAULT NULL,  -- Feedback for rejected applicants
    
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_trx_rekrutmen
        FOREIGN KEY(rekrutmen_id) 
        REFERENCES trx_rekrutmen(id)
        ON DELETE CASCADE
);

-- Trigger
CREATE TRIGGER trg_set_timestamp_pendaftar
BEFORE UPDATE ON trx_pendaftar
FOR EACH ROW EXECUTE FUNCTION fn_trigger_set_timestamp();





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