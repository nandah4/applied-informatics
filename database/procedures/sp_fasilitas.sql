-- =================================================================
-- STORED PROCEDURES FOR MST_FASILITAS
-- =================================================================
-- Description: Procedures untuk CRUD operations pada tabel fasilitas
-- Author: Applied Informatics Lab
-- Version: 3.0 (Updated for new schema)
-- =================================================================

-- =================================================================
-- PROCEDURE: Insert data fasilitas baru
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_insert_fasilitas(
    p_nama VARCHAR(150),
    p_deskripsi VARCHAR(255),
    p_foto TEXT DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi nama duplicate (CASE-INSENSITIVE)
    IF EXISTS (
        SELECT 1 FROM mst_fasilitas 
        WHERE LOWER(TRIM(nama)) = LOWER(TRIM(p_nama))
    ) THEN
        RAISE EXCEPTION 'Nama fasilitas sudah terdaftar';
    END IF;

    -- Insert data fasilitas
    -- created_at dan updated_at akan terisi otomatis (DEFAULT NOW())
    INSERT INTO mst_fasilitas (nama, deskripsi, foto)
    VALUES (TRIM(p_nama), p_deskripsi, p_foto);

    -- Log untuk debugging (opsional, bisa dihapus di production)
    RAISE NOTICE 'Fasilitas "%" berhasil ditambahkan', p_nama;

END;
$$;

COMMENT ON PROCEDURE sp_insert_fasilitas IS 
'Procedure untuk insert fasilitas baru. Validasi nama unik (case-insensitive).';

-- =================================================================
-- PROCEDURE: Update data fasilitas
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_update_fasilitas(
    p_id BIGINT,
    p_nama VARCHAR(150),
    p_deskripsi VARCHAR(255),
    p_foto TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi apakah ID fasilitas ada
    IF NOT EXISTS (SELECT 1 FROM mst_fasilitas WHERE id = p_id) THEN
        RAISE EXCEPTION 'Fasilitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Validasi nama duplicate (CASE-INSENSITIVE, exclude ID sendiri)
    IF EXISTS (
        SELECT 1 FROM mst_fasilitas
        WHERE LOWER(TRIM(nama)) = LOWER(TRIM(p_nama))
          AND id <> p_id
    ) THEN
        RAISE EXCEPTION 'Nama fasilitas sudah terdaftar';
    END IF;

    -- Update data
    UPDATE mst_fasilitas
    SET
        nama = TRIM(p_nama),
        deskripsi = p_deskripsi,
        foto = p_foto,
        updated_at = NOW() -- Selalu perbarui timestamp updated_at
    WHERE id = p_id;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Fasilitas ID % berhasil diupdate', p_id;

END;
$$;

COMMENT ON PROCEDURE sp_update_fasilitas IS 
'Procedure untuk update fasilitas. Validasi nama unik (case-insensitive) dan ID.';

-- =================================================================
-- PROCEDURE: Delete data fasilitas
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_delete_fasilitas(
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_row_count INT;
BEGIN
    -- Hapus data
    DELETE FROM mst_fasilitas
    WHERE id = p_id;

    -- Cek apakah ada baris yang terhapus
    GET DIAGNOSTICS v_row_count = ROW_COUNT;

    IF v_row_count = 0 THEN
        RAISE EXCEPTION 'Fasilitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Fasilitas ID % berhasil dihapus', p_id;

    -- CATATAN PENTING:
    -- Prosedur ini TIDAK mengembalikan nama file foto yang dihapus.
    -- Model PHP HARUS SELECT nama foto terlebih dahulu
    -- sebelum memanggil prosedur ini untuk menghapus file dari server.

END;
$$;

COMMENT ON PROCEDURE sp_delete_fasilitas IS 
'Procedure untuk hapus fasilitas berdasarkan ID. File foto harus dihapus manual di PHP.';

-- =================================================================
-- TEST PROCEDURES (Run untuk testing)
-- =================================================================

-- Test 1: Insert fasilitas baru
-- CALL sp_insert_fasilitas('Laboratorium Komputer', 'Lab dengan 40 komputer', 'lab_komputer.jpg');

-- Test 2: Insert duplicate (harus error)
-- CALL sp_insert_fasilitas('LABORATORIUM KOMPUTER', 'Test duplicate', 'test.jpg');
-- Expected: ERROR: Nama fasilitas sudah terdaftar

-- Test 3: Update fasilitas
-- CALL sp_update_fasilitas(1, 'Lab Komputer Upgraded', 'Lab dengan 50 komputer', 'lab_new.jpg');

-- Test 4: Update dengan nama duplicate (harus error)
-- CALL sp_update_fasilitas(2, 'Lab Komputer Upgraded', 'Test', 'test.jpg');
-- Expected: ERROR: Nama fasilitas sudah terdaftar

-- Test 5: Delete fasilitas
-- CALL sp_delete_fasilitas(1);

-- Test 6: Delete fasilitas yang tidak ada (harus error)
-- CALL sp_delete_fasilitas(999);
-- Expected: ERROR: Fasilitas dengan ID 999 tidak ditemukan

-- =================================================================
-- ROLLBACK PROCEDURES (Jika perlu menghapus)
-- =================================================================
-- DROP PROCEDURE IF EXISTS sp_insert_fasilitas;
-- DROP PROCEDURE IF EXISTS sp_update_fasilitas;
-- DROP PROCEDURE IF EXISTS sp_delete_fasilitas;