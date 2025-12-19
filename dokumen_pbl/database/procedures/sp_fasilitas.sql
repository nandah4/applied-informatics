
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
    -- Validasi: Nama fasilitas harus unik (case-insensitive)
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
    -- Validasi 1: Cek apakah ID fasilitas ada
    IF NOT EXISTS (SELECT 1 FROM mst_fasilitas WHERE id = p_id) THEN
        RAISE EXCEPTION 'Fasilitas dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Validasi 2: Nama tidak boleh duplicate dengan fasilitas lain
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

END;
$$;


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
