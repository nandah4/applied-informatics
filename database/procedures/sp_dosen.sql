-- PROCEDURE : insert data dosen ke table dosen dan keahlian
-- Validasi dilakukan dengan RAISE EXCEPTION
CREATE OR REPLACE PROCEDURE sp_insert_dosen(
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
    IF EXISTS (SELECT 1 FROM mst_dosen WHERE email = p_email) THEN
        RAISE EXCEPTION 'Email sudah terdaftar';
    END IF;

    -- Validasi nidn duplicate (hanya jika NIDN tidak NULL)
    IF p_nidn IS NOT NULL THEN
        IF EXISTS (SELECT 1 FROM mst_dosen WHERE nidn = p_nidn) THEN
            RAISE EXCEPTION 'NIDN sudah terdaftar';
        END IF;
    END IF;

    -- Insert data dosen
    INSERT INTO mst_dosen (full_name, email, nidn, jabatan_id, foto_profil, deskripsi)
    VALUES (p_full_name, p_email, p_nidn, p_jabatan_id, p_foto_profil, p_deskripsi)
    RETURNING id INTO v_dosen_id;

    -- Insert keahlian menggunakan unnest ke many-to-many
    IF p_keahlian_ids IS NOT NULL AND array_length(p_keahlian_ids, 1) > 0 THEN
        INSERT INTO map_dosen_keahlian (dosen_id, keahlian_id)
        SELECT v_dosen_id, unnest(p_keahlian_ids);
    END IF;

END;
$$;

-- Komentar untuk dokumentasi
COMMENT ON PROCEDURE sp_insert_dosen_with_keahlian IS 'Procedure untuk insert dosen beserta keahlian. Gunakan RAISE EXCEPTION untuk error handling.';


--


-- PROCEDURE : update data dosen ke table dosen dan keahlian
-- Validasi dilakukan dengan RAISE EXCEPTION
CREATE OR REPLACE PROCEDURE sp_update_dosen(
    p_id BIGINT,
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
    -- VALIDATION EMAIL (excluding current id)
    IF EXISTS (
        SELECT 1 FROM mst_dosen 
        WHERE email = p_email AND id != p_id
    ) THEN
        RAISE EXCEPTION 'Email sudah terdaftar';
    END IF;

    -- VALIDATION NIDN (if not null)
    IF p_nidn IS NOT NULL THEN
        IF EXISTS (
            SELECT 1 FROM mst_dosen 
            WHERE nidn = p_nidn AND id != p_id
        ) THEN
            RAISE EXCEPTION 'NIDN sudah terdaftar';
        END IF;
    END IF;

    -- UPDATE MAIN TABLE
    UPDATE mst_dosen SET
        full_name   = p_full_name,
        email       = p_email,
        nidn        = p_nidn,
        jabatan_id  = p_jabatan_id,
        foto_profil = p_foto_profil,
        deskripsi   = p_deskripsi
    WHERE id = p_id
    RETURNING id INTO v_dosen_id;

    IF v_dosen_id IS NULL THEN
        RAISE EXCEPTION 'Data dosen tidak ditemukan';
    END IF;

    -- UPDATE KEAHLIAN (many-to-many)
    -- 1. Delete old relations
    DELETE FROM map_dosen_keahlian
    WHERE dosen_id = v_dosen_id;

    -- 2. Insert new keahlian
    IF p_keahlian_ids IS NOT NULL 
       AND array_length(p_keahlian_ids, 1) > 0 THEN

        INSERT INTO map_dosen_keahlian (dosen_id, keahlian_id)
        SELECT v_dosen_id, unnest(p_keahlian_ids);

    END IF;

END;
$$;



-- DIVIDER



-- ==========================================
-- PROFIL PUBLIKASI DOSEN
-- ==========================================

-- PROCEDURE : insert profil publikasi dosen
CREATE OR REPLACE PROCEDURE sp_insert_profil_publikasi (
    p_dosen_id BIGINT,
    p_tipe profil_tipe_enum,
    p_url_profil TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi dosen_id exists
    IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = p_dosen_id) THEN
        RAISE EXCEPTION 'Dosen tidak ditemukan';
    END IF;

    -- Validasi duplikat (satu dosen hanya boleh punya satu profil per tipe)
    IF EXISTS (
        SELECT 1 FROM ref_profil_publikasi
        WHERE dosen_id = p_dosen_id AND tipe = p_tipe
    ) THEN
        RAISE EXCEPTION 'Profil % sudah ada untuk dosen ini', p_tipe;
    END IF;

    -- Insert data
    INSERT INTO ref_profil_publikasi (dosen_id, tipe, url_profil)
    VALUES (p_dosen_id, p_tipe, p_url_profil);
END;
$$;


-- PROCEDURE : update profil publikasi dosen
CREATE OR REPLACE PROCEDURE sp_update_profil_publikasi (
    p_id BIGINT,
    p_url_profil TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi id exists
    IF NOT EXISTS (SELECT 1 FROM ref_profil_publikasi WHERE id = p_id) THEN
        RAISE EXCEPTION 'Profil publikasi tidak ditemukan';
    END IF;

    -- Update data
    UPDATE ref_profil_publikasi
    SET url_profil = p_url_profil
    WHERE id = p_id;
END;
$$;


-- PROCEDURE : delete profil publikasi dosen
CREATE OR REPLACE PROCEDURE sp_delete_profil_publikasi (
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi id exists
    IF NOT EXISTS (SELECT 1 FROM ref_profil_publikasi WHERE id = p_id) THEN
        RAISE EXCEPTION 'Profil publikasi tidak ditemukan';
    END IF;

    -- Delete data
    DELETE FROM ref_profil_publikasi WHERE id = p_id;
END;
$$;
