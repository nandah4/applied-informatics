-- ============================================================
-- FILE: sp_publikasi_akademik.sql
-- DESKRIPSI: Stored Procedures untuk operasi CRUD publikasi akademik
--
-- PROCEDURES:
-- - sp_insert_publikasi_akademik: Insert data publikasi baru
-- - sp_update_publikasi_akademik: Update data publikasi
--
-- TABEL TERKAIT:
-- - trx_publikasi: Tabel utama publikasi akademik
-- - mst_dosen: Tabel referensi dosen
--
-- ENUM YANG DIGUNAKAN:
-- - publikasi_tipe_enum: 'Riset', 'Kekayaan Intelektual', 'PPM'
-- ============================================================


-- ============================================================
-- PROCEDURE: sp_insert_publikasi_akademik
-- DESKRIPSI: Insert data publikasi akademik baru ke table trx_publikasi
--
-- PARAMETER:
--   p_dosen_id (BIGINT): ID dosen pemilik publikasi
--   p_judul (TEXT): Judul publikasi (wajib, tidak boleh kosong)
--   p_url_publikasi (TEXT): URL publikasi online (opsional)
--   p_tahun_publikasi (INT): Tahun publikasi (1900 - tahun sekarang + 1)
--   p_tipe_publikasi (publikasi_tipe_enum): Tipe publikasi
--
-- VALIDASI:
--   1. Dosen dengan ID tersebut harus ada di database
--   2. Judul tidak boleh kosong
--   3. Tahun publikasi harus valid (1900 - tahun depan)
--
-- CONTOH PENGGUNAAN:
--   CALL sp_insert_publikasi_akademik(1, 'Judul Penelitian', 'https://...', 2024, 'Riset');
-- ============================================================

CREATE OR REPLACE PROCEDURE sp_insert_publikasi_akademik (
    p_dosen_id BIGINT,
    p_judul TEXT,
    p_url_publikasi TEXT,
    p_tahun_publikasi INT,
    p_tipe_publikasi publikasi_tipe_enum
)
LANGUAGE plpgsql
AS $$
BEGIN

    -- VALIDASI: check apakah dosen exist
    IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = p_dosen_id) THEN
        RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan.', p_dosen_id;
    END IF;

    -- VALIDASI: judul tidak boleh kosong
    IF p_judul IS NULL OR TRIM(p_judul) = '' THEN
        RAISE EXCEPTION 'Judul publikasi tidak boleh kosong.';
    END IF;

    -- VALIDASI: Tahun harus masuk akal (misal > 1900)
    IF p_tahun_publikasi < 1900 OR p_tahun_publikasi > (EXTRACT(YEAR FROM NOW()) + 1) THEN
        RAISE EXCEPTION 'Tahun publikasi tidak valid.';
    END IF;

    -- Insert data publikasi
    INSERT INTO trx_publikasi (
        dosen_id,
        judul,
        url_publikasi,
        tahun_publikasi,
        tipe_publikasi
    ) VALUES (
        p_dosen_id,
        TRIM(p_judul),
        TRIM(p_url_publikasi),
        p_tahun_publikasi,
        p_tipe_publikasi
    );

END;
$$;



-- ============================================================
-- PROCEDURE: sp_update_publikasi_akademik
-- DESKRIPSI: Update data publikasi akademik di table trx_publikasi
--
-- PARAMETER:
--   p_id (BIGINT): ID publikasi yang akan diupdate
--   p_dosen_id (BIGINT): ID dosen pemilik publikasi
--   p_judul (TEXT): Judul publikasi (wajib, tidak boleh kosong)
--   p_url_publikasi (TEXT): URL publikasi online (opsional)
--   p_tahun_publikasi (INT): Tahun publikasi (1900 - tahun sekarang + 1)
--   p_tipe_publikasi (publikasi_tipe_enum): Tipe publikasi
--
-- VALIDASI:
--   1. Publikasi dengan ID tersebut harus ada di database
--   2. Dosen dengan ID tersebut harus ada di database
--   3. Judul tidak boleh kosong
--   4. Tahun publikasi harus valid (1900 - tahun depan)
--
-- CATATAN:
--   - Field updated_at akan diupdate otomatis ke waktu sekarang
--
-- CONTOH PENGGUNAAN:
--   CALL sp_update_publikasi_akademik(1, 1, 'Judul Baru', 'https://...', 2024, 'Riset');
-- ============================================================

CREATE OR REPLACE PROCEDURE sp_update_publikasi_akademik (
    p_id BIGINT,
    p_dosen_id BIGINT,
    p_judul TEXT,
    p_url_publikasi TEXT,
    p_tahun_publikasi INT,
    p_tipe_publikasi publikasi_tipe_enum
)
LANGUAGE plpgsql
AS $$
BEGIN

    -- VALIDASI: check apakah publikasi exist
    IF NOT EXISTS (SELECT 1 FROM trx_publikasi WHERE id = p_id) THEN
        RAISE EXCEPTION 'Publikasi dengan ID % tidak ditemukan.', p_id;
    END IF;

    -- VALIDASI: check apakah dosen exist
    IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = p_dosen_id) THEN
        RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan.', p_dosen_id;
    END IF;

    -- VALIDASI: judul tidak boleh kosong
    IF p_judul IS NULL OR TRIM(p_judul) = '' THEN
        RAISE EXCEPTION 'Judul publikasi tidak boleh kosong.';
    END IF;

    -- VALIDASI: Tahun harus masuk akal (misal > 1900)
    IF p_tahun_publikasi < 1900 OR p_tahun_publikasi > (EXTRACT(YEAR FROM NOW()) + 1) THEN
        RAISE EXCEPTION 'Tahun publikasi tidak valid.';
    END IF;

    -- Update data publikasi
    UPDATE trx_publikasi SET
        dosen_id = p_dosen_id,
        judul = TRIM(p_judul),
        url_publikasi = TRIM(p_url_publikasi),
        tahun_publikasi = p_tahun_publikasi,
        tipe_publikasi = p_tipe_publikasi,
        updated_at = NOW()
    WHERE id = p_id;

END;
$$;