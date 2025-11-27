-- =================================================================
-- STORED PROCEDURES FOR MST_FASILITAS
-- =================================================================
-- Description: Procedures untuk CRUD operations pada tabel fasilitas
-- Table: mst_fasilitas
-- Author: Applied Informatics Lab
-- Version: 3.0 (Verified with schema)
-- 
-- FITUR:
-- - Validasi nama duplicate (case-insensitive)
-- - Auto-timestamp untuk created_at dan updated_at
-- - Error handling dengan RAISE EXCEPTION
-- - Logging dengan RAISE NOTICE (optional)
-- =================================================================

-- =================================================================
-- PROCEDURE: Insert data fasilitas baru
-- =================================================================
-- Parameter:
--   - p_nama: Nama fasilitas (VARCHAR 150, required)
--   - p_deskripsi: Deskripsi fasilitas (VARCHAR 255, optional)
--   - p_foto: Path/URL foto (TEXT, optional)
-- 
-- Validasi:
--   - Nama tidak boleh duplicate (case-insensitive)
--   - Nama di-TRIM untuk menghindari spasi
-- 
-- Return: None (RAISE EXCEPTION jika error)
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_insert_fasilitas(
    p_nama VARCHAR(150),
    p_deskripsi VARCHAR(255),
    p_foto TEXT DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi: Nama fasilitas harus unik (case-insensitive)
    -- Menggunakan LOWER dan TRIM untuk perbandingan yang lebih robust
    IF EXISTS (
        SELECT 1 FROM mst_fasilitas 
        WHERE LOWER(TRIM(nama)) = LOWER(TRIM(p_nama))
    ) THEN
        RAISE EXCEPTION 'Nama fasilitas sudah terdaftar';
    END IF;

    -- Insert data fasilitas
    -- created_at dan updated_at akan terisi otomatis dari DEFAULT NOW()
    INSERT INTO mst_fasilitas (nama, deskripsi, foto)
    VALUES (TRIM(p_nama), p_deskripsi, p_foto);

    -- Log untuk debugging (opsional, bisa di-comment di production)
    RAISE NOTICE 'Fasilitas "%" berhasil ditambahkan', p_nama;

END;
$$;

COMMENT ON PROCEDURE sp_insert_fasilitas IS 
'Insert fasilitas baru. Validasi nama unik (case-insensitive). Auto-timestamp created_at dan updated_at.';


-- =================================================================
-- PROCEDURE: Update data fasilitas
-- =================================================================
-- Parameter:
--   - p_id: ID fasilitas yang akan diupdate (BIGINT, required)
--   - p_nama: Nama fasilitas baru (VARCHAR 150, required)
--   - p_deskripsi: Deskripsi baru (VARCHAR 255, required)
--   - p_foto: Path/URL foto baru (TEXT, required)
-- 
-- Validasi:
--   - ID harus ada di database
--   - Nama tidak boleh duplicate dengan fasilitas lain (exclude ID sendiri)
-- 
-- Return: None (RAISE EXCEPTION jika error)
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
    -- Validasi 1: Cek apakah ID fasilitas ada
    IF NOT EXISTS (SELECT 1 FROM mst_fasilitas WHERE id = p_id) THEN
        RAISE EXCEPTION 'Fasilitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Validasi 2: Nama tidak boleh duplicate dengan fasilitas lain
    -- Exclude ID sendiri dari pengecekan (AND id <> p_id)
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
        updated_at = NOW() -- Selalu update timestamp
    WHERE id = p_id;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Fasilitas ID % berhasil diupdate', p_id;

END;
$$;

COMMENT ON PROCEDURE sp_update_fasilitas IS 
'Update fasilitas. Validasi ID dan nama unik (case-insensitive, exclude ID sendiri). Auto-update timestamp.';


-- =================================================================
-- PROCEDURE: Delete data fasilitas
-- =================================================================
-- Parameter:
--   - p_id: ID fasilitas yang akan dihapus (BIGINT, required)
-- 
-- Validasi:
--   - ID harus ada di database (cek dengan ROW_COUNT)
-- 
-- CATATAN PENTING:
--   - Procedure ini TIDAK mengembalikan nama file foto
--   - Model PHP HARUS SELECT nama foto terlebih dahulu
--   - Foto file harus dihapus manual di PHP setelah delete data
-- 
-- Return: None (RAISE EXCEPTION jika error)
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_delete_fasilitas(
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_row_count INT;
BEGIN
    -- Delete data berdasarkan ID
    DELETE FROM mst_fasilitas
    WHERE id = p_id;

    -- Cek apakah ada baris yang terhapus
    GET DIAGNOSTICS v_row_count = ROW_COUNT;

    -- Jika tidak ada baris yang terhapus, berarti ID tidak ditemukan
    IF v_row_count = 0 THEN
        RAISE EXCEPTION 'Fasilitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Fasilitas ID % berhasil dihapus', p_id;

    -- CATATAN PENTING:
    -- Prosedur ini TIDAK mengembalikan nama file foto yang dihapus.
    -- Model PHP HARUS:
    -- 1. SELECT foto FROM mst_fasilitas WHERE id = p_id (sebelum delete)
    -- 2. CALL sp_delete_fasilitas(p_id)
    -- 3. Hapus file foto dari server menggunakan info dari step 1

END;
$$;

COMMENT ON PROCEDURE sp_delete_fasilitas IS 
'Delete fasilitas berdasarkan ID. Validasi ID existence. File foto harus dihapus manual di PHP.';


-- =================================================================
-- TEST PROCEDURES (Uncomment untuk testing)
-- =================================================================

/*
-- ===== TEST CASE 1: Insert fasilitas baru =====
CALL sp_insert_fasilitas(
    'Laboratorium Komputer', 
    'Lab dengan 40 unit komputer high-end', 
    'lab_komputer.jpg'
);
-- Expected: Success, data inserted

-- ===== TEST CASE 2: Insert duplicate (harus error) =====
CALL sp_insert_fasilitas(
    'LABORATORIUM KOMPUTER',  -- Case berbeda tapi sama
    'Test duplicate', 
    'test.jpg'
);
-- Expected: ERROR: Nama fasilitas sudah terdaftar

-- ===== TEST CASE 3: Insert dengan nama yang ada spasi =====
CALL sp_insert_fasilitas(
    '  Lab AI  ',  -- Ada spasi di depan dan belakang
    'Test trim', 
    'test.jpg'
);
-- Expected: Success, nama akan di-TRIM menjadi 'Lab AI'

-- ===== TEST CASE 4: Update fasilitas =====
CALL sp_update_fasilitas(
    1, 
    'Lab Komputer Upgraded', 
    'Lab dengan 50 komputer high-end', 
    'lab_new.jpg'
);
-- Expected: Success, data updated

-- ===== TEST CASE 5: Update dengan ID tidak ada =====
CALL sp_update_fasilitas(
    999, 
    'Test', 
    'Test', 
    'test.jpg'
);
-- Expected: ERROR: Fasilitas dengan ID 999 tidak ditemukan

-- ===== TEST CASE 6: Update dengan nama duplicate =====
-- Asumsi: ID 1 = 'Lab Komputer Upgraded', ID 2 = 'Lab AI'
CALL sp_update_fasilitas(
    2, 
    'Lab Komputer Upgraded',  -- Nama sudah dipakai ID 1
    'Test', 
    'test.jpg'
);
-- Expected: ERROR: Nama fasilitas sudah terdaftar

-- ===== TEST CASE 7: Update dengan nama sendiri (harus success) =====
CALL sp_update_fasilitas(
    1, 
    'Lab Komputer Upgraded',  -- Nama sendiri (ID 1)
    'Deskripsi diupdate', 
    'lab_new.jpg'
);
-- Expected: Success, karena exclude ID sendiri dari validasi

-- ===== TEST CASE 8: Delete fasilitas =====
-- Step 1: Get foto terlebih dahulu (untuk dihapus di server)
SELECT fn_get_fasilitas_foto(1);  -- Returns: 'lab_new.jpg'

-- Step 2: Delete data
CALL sp_delete_fasilitas(1);
-- Expected: Success, data deleted

-- ===== TEST CASE 9: Delete dengan ID tidak ada =====
CALL sp_delete_fasilitas(999);
-- Expected: ERROR: Fasilitas dengan ID 999 tidak ditemukan

-- ===== TEST CASE 10: Lihat semua data =====
SELECT id, nama, deskripsi, foto, created_at, updated_at 
FROM mst_fasilitas 
ORDER BY id;
*/


-- =================================================================
-- ROLLBACK/CLEANUP (Jika perlu menghapus semua procedures)
-- =================================================================
/*
DROP PROCEDURE IF EXISTS sp_insert_fasilitas;
DROP PROCEDURE IF EXISTS sp_update_fasilitas;
DROP PROCEDURE IF EXISTS sp_delete_fasilitas;
DROP FUNCTION IF EXISTS fn_get_fasilitas_foto;
*/


-- =================================================================
-- VERIFICATION CHECKLIST
-- =================================================================
-- ✅ Schema: mst_fasilitas exists dengan kolom yang sesuai
-- ✅ Stored procedures sesuai dengan signature di schema
-- ✅ Validasi nama duplicate dengan case-insensitive
-- ✅ Auto-timestamp untuk created_at dan updated_at
-- ✅ Error handling dengan RAISE EXCEPTION
-- ✅ Logging dengan RAISE NOTICE (optional)
-- ✅ Model PHP tidak menggunakan view (query langsung ke table)
-- ✅ Model PHP handle foto deletion sebelum call sp_delete
-- =================================================================