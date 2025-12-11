-- =================================================================
-- PROCEDURE: Insert data aktivitas baru
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_insert_aktivitas_lab(
    p_judul_aktivitas VARCHAR(255),
    p_deskripsi TEXT,
    p_foto_aktivitas VARCHAR(255),
    p_tanggal_kegiatan DATE,
    p_penulis_id BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_penulis_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = p_penulis_id) THEN
            RAISE EXCEPTION 'Penulis dengan ID % tidak ditemukan', p_penulis_id;
        END IF;
    END IF;

    -- Validasi judul tidak boleh kosong
    IF p_judul_aktivitas IS NULL OR TRIM(p_judul_aktivitas) = '' THEN
        RAISE EXCEPTION 'Judul aktivitas tidak boleh kosong';
    END IF;

    -- Insert data aktivitas
    INSERT INTO trx_aktivitas_lab (judul_aktivitas, deskripsi, foto_aktivitas, tanggal_kegiatan, penulis_id)
    VALUES (TRIM(p_judul_aktivitas), p_deskripsi, p_foto_aktivitas, p_tanggal_kegiatan, p_penulis_id);

    RAISE NOTICE 'Aktivitas "%" berhasil ditambahkan', p_judul_aktivitas;
END;
$$;





-- =================================================================
-- PROCEDURE: Update data aktivitas
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_update_aktivitas_lab(
    p_id BIGINT,
    p_judul_aktivitas VARCHAR(255),
    p_deskripsi TEXT,
    p_foto_aktivitas VARCHAR(255),
    p_tanggal_kegiatan DATE,
    p_penulis_id BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_penulis_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = p_penulis_id) THEN
            RAISE EXCEPTION 'Penulis dengan ID % tidak ditemukan', p_penulis_id;
        END IF;
    END IF;


    -- Validasi apakah ID aktivitas ada
    IF NOT EXISTS (SELECT 1 FROM trx_aktivitas_lab WHERE id = p_id) THEN
        RAISE EXCEPTION 'Aktivitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Validasi judul tidak boleh kosong
    IF p_judul_aktivitas IS NULL OR TRIM(p_judul_aktivitas) = '' THEN
        RAISE EXCEPTION 'Judul aktivitas tidak boleh kosong';
    END IF;

    -- Update data
    UPDATE trx_aktivitas_lab
    SET
        judul_aktivitas = TRIM(p_judul_aktivitas),
        deskripsi = p_deskripsi,
        foto_aktivitas = p_foto_aktivitas,
        tanggal_kegiatan = p_tanggal_kegiatan,
        penulis_id = p_penulis_id,
        updated_at = NOW()
    WHERE id = p_id;

    RAISE NOTICE 'Aktivitas ID % berhasil diupdate', p_id;
END;
$$;

-- =================================================================
-- ROLLBACK (Jika perlu menghapus)
-- =================================================================
-- DROP VIEW IF EXISTS vw_show_aktivitas;
-- DROP PROCEDURE IF EXISTS sp_insert_aktivitas;
-- DROP PROCEDURE IF EXISTS sp_update_aktivitas;