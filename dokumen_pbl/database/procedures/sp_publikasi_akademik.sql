-- ============================================================
-- PROCEDURE: sp_insert_publikasi_akademik
-- DESKRIPSI: Insert data publikasi akademik baru ke table trx_publikasi
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