-- CREATE DATABASE
CREATE DATABASE db_lab_ai;

-- CREATE TABLE users
CREATE TYPE user_role_enum AS ENUM ('guest', 'admin');

CREATE TABLE tbl_users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role          user_role_enum NOT NULL DEFAULT 'guest',
    created_at    TIMESTAMP NOT NULL DEFAULT NOW()
);
    -- INSERT New Admin

    CREATE EXTENSION IF NOT EXISTS pgcrypto;

    INSERT INTO tbl_users (email, password, role)
    VALUES ('admin@gmail.com', crypt('12345678', gen_salt('bf', 12)), 'admin');

-- CREATE TABLE jabatan
CREATE TABLE tbl_jabatan(
    id BIGSERIAL PRIMARY KEY,
    jabatan VARCHAR(255) UNIQUE NOT NULL
);

-- CREATE TABLE keahlian
CREATE TABLE tbl_keahlian(
    id BIGSERIAL PRIMARY KEY,
    keahlian VARCHAR(255) UNIQUE NOT NULL
);


-- CREATE TABLE dosen
CREATE TABLE tbl_dosen (
    id BIGSERIAL PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    nidn VARCHAR(50) UNIQUE,
    foto_profil VARCHAR(255),
    deskripsi text default NULL,
    jabatan_id bigint, 

    CONSTRAINT fk_jabatan
    FOREIGN KEY(jabatan_id) 
    REFERENCES tbl_jabatan(id)
    ON DELETE SET NULL
);

-- CREATE TABLE dosen - keahlian
CREATE TABLE tbl_dosen_keahlian(
    dosen_id bigint,
    keahlian_id bigint,
    CONSTRAINT fk_dosen
        FOREIGN KEY(dosen_id) 
        REFERENCES tbl_dosen(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_keahlian
    FOREIGN KEY(keahlian_id) 
    REFERENCES tbl_keahlian(id)
    ON DELETE CASCADE,
    PRIMARY KEY(dosen_id, keahlian_id)
)

-- CREATE PROCEDURE sp_dosen di dir procedures/sp_dosen.sql

-- CREATE TABLE fasilitas
CREATE TABLE tbl_fasilitas (
    fasilitas_id  BIGSERIAL PRIMARY KEY,
    nama          VARCHAR(150) NOT NULL,
    deskripsi     TEXT,
    foto          VARCHAR(255),
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

-- CREATE TABLE mitra 
CREATE TYPE status_enum AS ENUM ('aktif', 'non-aktif');

CREATE TABLE tbl_mitra (
    id              BIGSERIAL PRIMARY KEY,
    nama            VARCHAR(150) NOT NULL,
    status          status_enum NOT NULL DEFAULT 'non-aktif',
    deskripsi	    TEXT,
    logo_mitra      VARCHAR(255),
    tanggal_mulai   DATE,
    tanggal_akhir   DATE,
    created_at      TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP NOT NULL DEFAULT NOW()
);
