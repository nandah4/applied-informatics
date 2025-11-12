-- PROCEDURE : insert data dosen ke table dosen dan keahlian
-- Validasi dilakukan dengan RAISE EXCEPTION
CREATE OR REPLACE PROCEDURE sp_insert_dosen_with_keahlian(
    p_full_name VARCHAR,
    p_email VARCHAR,
    p_nidn VARCHAR,
    p_jabatan_id BIGINT,
    p_keahlian_ids BIGINT[],
    p_foto_profil VARCHAR DEFAULT NULL,
    p_deskripsi TEXT DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_dosen_id BIGINT;
BEGIN
    -- Validasi email duplicate
    IF EXISTS (SELECT 1 FROM tbl_dosen WHERE email = p_email) THEN
        RAISE EXCEPTION 'Email sudah terdaftar';
    END IF;

    -- Validasi nidn duplicate (hanya jika NIDN tidak NULL)
    IF p_nidn IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM tbl_dosen WHERE nidn = p_nidn) THEN
            RAISE EXCEPTION 'NIDN sudah terdaftar';
        END IF;
    END IF;

    -- Insert data dosen
    INSERT INTO tbl_dosen (full_name, email, nidn, jabatan_id, foto_profil, deskripsi)
    VALUES (p_full_name, p_email, p_nidn, p_jabatan_id, p_foto_profil, p_deskripsi)
    RETURNING id INTO v_dosen_id;

    -- Insert keahlian menggunakan unnest ke many-to-many
    IF p_keahlian_ids IS NOT NULL AND array_length(p_keahlian_ids, 1) > 0 THEN
        INSERT INTO tbl_dosen_keahlian (dosen_id, keahlian_id)
        SELECT v_dosen_id, unnest(p_keahlian_ids);
    END IF;


END;
$$;

-- Komentar untuk dokumentasi
COMMENT ON PROCEDURE sp_insert_dosen_with_keahlian IS 'Procedure untuk insert dosen beserta keahlian. Gunakan RAISE EXCEPTION untuk error handling.';