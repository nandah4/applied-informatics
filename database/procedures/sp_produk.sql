-- =================================================================
-- STORED PROCEDURES FOR MST_PRODUK (Sesuai Schema.sql)
-- =================================================================
-- Description: Procedures untuk CRUD operations pada tabel mst_produk
-- Dependencies: mst_produk, mst_dosen, map_produk_dosen
-- =================================================================

-- =================================================================
-- 1. PROCEDURE: Insert Produk
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_insert_produk(
    p_nama_produk   VARCHAR(255),
    p_deskripsi     VARCHAR(255), -- Sesuai schema VARCHAR(255)
    p_foto_produk   TEXT,
    p_link_produk   VARCHAR(255) DEFAULT NULL,
    p_tim_mahasiswa VARCHAR(255) DEFAULT NULL, -- Sesuai nama kolom di schema
    p_dosen_ids     BIGINT[] DEFAULT NULL      -- Array ID Dosen untuk tabel map
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_produk_id BIGINT;
    v_dosen_id  BIGINT;
BEGIN
    -- 1. Validasi nama produk duplicate (CASE-INSENSITIVE)
    -- Meskipun schema tidak ada UNIQUE constraint, validasi logic tetap disarankan
    IF EXISTS (
        SELECT 1 FROM mst_produk 
        WHERE LOWER(TRIM(nama_produk)) = LOWER(TRIM(p_nama_produk))
    ) THEN
        RAISE EXCEPTION 'Nama produk "%" sudah terdaftar dalam sistem.', p_nama_produk;
    END IF;

    -- 2. Validasi Keberadaan Dosen (Jika ada input dosen)
    IF p_dosen_ids IS NOT NULL AND array_length(p_dosen_ids, 1) > 0 THEN
        FOREACH v_dosen_id IN ARRAY p_dosen_ids
        LOOP
            IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = v_dosen_id) THEN
                RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan.', v_dosen_id;
            END IF;
        END LOOP;
    END IF;

    -- 3. Insert ke tabel mst_produk
    INSERT INTO mst_produk (
        nama_produk, 
        deskripsi, 
        foto_produk, 
        link_produk,
        tim_mahasiswa,
        created_at,
        updated_at
    )
    VALUES (
        TRIM(p_nama_produk), 
        p_deskripsi, 
        p_foto_produk, 
        p_link_produk,
        TRIM(p_tim_mahasiswa),
        NOW(),
        NOW()
    )
    RETURNING id INTO v_produk_id;

    -- 4. Insert ke tabel map_produk_dosen (Many-to-Many)
    IF p_dosen_ids IS NOT NULL AND array_length(p_dosen_ids, 1) > 0 THEN
        FOREACH v_dosen_id IN ARRAY p_dosen_ids
        LOOP
            INSERT INTO map_produk_dosen (produk_id, dosen_id)
            VALUES (v_produk_id, v_dosen_id)
            ON CONFLICT (produk_id, dosen_id) DO NOTHING; -- Mencegah duplikasi pair
        END LOOP;
    END IF;

    RAISE NOTICE 'Produk berhasil ditambahkan dengan ID %', v_produk_id;
END;
$$;


-- =================================================================
-- 2. PROCEDURE: Update Produk
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_update_produk(
    p_id            BIGINT,
    p_nama_produk   VARCHAR(255),
    p_deskripsi     VARCHAR(255),
    p_foto_produk   TEXT,
    p_link_produk   VARCHAR(255),
    p_tim_mahasiswa VARCHAR(255),
    p_dosen_ids     BIGINT[] DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_dosen_id BIGINT;
BEGIN
    -- 1. Validasi ID Produk
    IF NOT EXISTS (SELECT 1 FROM mst_produk WHERE id = p_id) THEN
        RAISE EXCEPTION 'Produk dengan ID % tidak ditemukan.', p_id;
    END IF;

    -- 2. Validasi Nama Duplicate (Exclude ID sendiri)
    IF EXISTS (
        SELECT 1 FROM mst_produk
        WHERE LOWER(TRIM(nama_produk)) = LOWER(TRIM(p_nama_produk))
          AND id <> p_id
    ) THEN
        RAISE EXCEPTION 'Nama produk "%" sudah digunakan oleh data lain.', p_nama_produk;
    END IF;

    -- 3. Validasi Keberadaan Dosen
    IF p_dosen_ids IS NOT NULL AND array_length(p_dosen_ids, 1) > 0 THEN
        FOREACH v_dosen_id IN ARRAY p_dosen_ids
        LOOP
            IF NOT EXISTS (SELECT 1 FROM mst_dosen WHERE id = v_dosen_id) THEN
                RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan.', v_dosen_id;
            END IF;
        END LOOP;
    END IF;

    -- 4. Update tabel mst_produk
    UPDATE mst_produk
    SET
        nama_produk   = TRIM(p_nama_produk),
        deskripsi     = p_deskripsi,
        foto_produk   = p_foto_produk,
        link_produk   = p_link_produk,
        tim_mahasiswa = TRIM(p_tim_mahasiswa),
        updated_at    = NOW()
    WHERE id = p_id;

    -- 5. Reset Mapping Dosen
    -- Hapus mapping lama
    DELETE FROM map_produk_dosen WHERE produk_id = p_id;

    -- Insert mapping baru
    IF p_dosen_ids IS NOT NULL AND array_length(p_dosen_ids, 1) > 0 THEN
        FOREACH v_dosen_id IN ARRAY p_dosen_ids
        LOOP
            INSERT INTO map_produk_dosen (produk_id, dosen_id)
            VALUES (p_id, v_dosen_id)
            ON CONFLICT (produk_id, dosen_id) DO NOTHING;
        END LOOP;
    END IF;

    RAISE NOTICE 'Produk ID % berhasil diperbarui.', p_id;
END;
$$;


-- =================================================================
-- 3. PROCEDURE: Delete Produk
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_delete_produk(
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_row_count INT;
BEGIN
    -- 1. Hapus data produk
    -- Catatan: Tidak perlu hapus map_produk_dosen secara manual
    -- karena di schema.sql sudah didefinisikan ON DELETE CASCADE
    
    DELETE FROM mst_produk WHERE id = p_id;

    -- 2. Cek apakah ada data yang terhapus
    GET DIAGNOSTICS v_row_count = ROW_COUNT;

    IF v_row_count = 0 THEN
        RAISE EXCEPTION 'Produk dengan ID % tidak ditemukan atau sudah dihapus.', p_id;
    END IF;

    RAISE NOTICE 'Produk ID % berhasil dihapus beserta data mappingnya.', p_id;
END;
$$;