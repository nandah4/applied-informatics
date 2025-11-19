-- =================================================================
-- STORED PROCEDURES FOR TBL_PRODUK
-- =================================================================
-- Description: Procedures untuk CRUD operations pada tabel produk
-- Author: Applied Informatics Lab
-- Version: 1.0
-- =================================================================

-- =================================================================
-- PROCEDURE: Insert data produk baru
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_insert_produk(
    p_nama_produk VARCHAR(255),
    p_deskripsi TEXT,
    p_foto_produk VARCHAR(255),
    p_link_produk VARCHAR(255) DEFAULT NULL,
    p_author_dosen_id BIGINT DEFAULT NULL,
    p_author_mahasiswa_nama VARCHAR(255) DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi nama produk duplicate (CASE-INSENSITIVE)
    IF EXISTS (
        SELECT 1 FROM tbl_produk 
        WHERE LOWER(TRIM(nama_produk)) = LOWER(TRIM(p_nama_produk))
    ) THEN
        RAISE EXCEPTION 'Nama produk sudah terdaftar';
    END IF;

    -- Validasi: Minimal salah satu author harus diisi
    IF p_author_dosen_id IS NULL AND p_author_mahasiswa_nama IS NULL THEN
        RAISE EXCEPTION 'Minimal salah satu author (dosen atau mahasiswa) harus diisi';
    END IF;

    -- Validasi: Jika author_dosen_id diisi, pastikan dosen ada
    IF p_author_dosen_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM tbl_dosen WHERE id = p_author_dosen_id) THEN
            RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan', p_author_dosen_id;
        END IF;
    END IF;

    -- Insert data produk
    INSERT INTO tbl_produk (
        nama_produk, 
        deskripsi, 
        foto_produk, 
        link_produk,
        author_dosen_id, 
        author_mahasiswa_nama
    )
    VALUES (
        TRIM(p_nama_produk), 
        p_deskripsi, 
        p_foto_produk, 
        p_link_produk,
        p_author_dosen_id, 
        TRIM(p_author_mahasiswa_nama)
    );

    -- Log untuk debugging (opsional, bisa dihapus di production)
    RAISE NOTICE 'Produk "%" berhasil ditambahkan', p_nama_produk;

END;
$$;

COMMENT ON PROCEDURE sp_insert_produk IS 
'Procedure untuk insert produk baru. Validasi nama unik (case-insensitive) dan minimal salah satu author terisi.';

-- =================================================================
-- PROCEDURE: Update data produk
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_update_produk(
    p_id BIGINT,
    p_nama_produk VARCHAR(255),
    p_deskripsi TEXT,
    p_foto_produk VARCHAR(255),
    p_link_produk VARCHAR(255),
    p_author_dosen_id BIGINT,
    p_author_mahasiswa_nama VARCHAR(255)
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Validasi apakah ID produk ada
    IF NOT EXISTS (SELECT 1 FROM tbl_produk WHERE id = p_id) THEN
        RAISE EXCEPTION 'Produk dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Validasi nama duplicate (CASE-INSENSITIVE, exclude ID sendiri)
    IF EXISTS (
        SELECT 1 FROM tbl_produk
        WHERE LOWER(TRIM(nama_produk)) = LOWER(TRIM(p_nama_produk))
          AND id <> p_id
    ) THEN
        RAISE EXCEPTION 'Nama produk sudah terdaftar';
    END IF;

    -- Validasi: Minimal salah satu author harus diisi
    IF p_author_dosen_id IS NULL AND p_author_mahasiswa_nama IS NULL THEN
        RAISE EXCEPTION 'Minimal salah satu author (dosen atau mahasiswa) harus diisi';
    END IF;

    -- Validasi: Jika author_dosen_id diisi, pastikan dosen ada
    IF p_author_dosen_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM tbl_dosen WHERE id = p_author_dosen_id) THEN
            RAISE EXCEPTION 'Dosen dengan ID % tidak ditemukan', p_author_dosen_id;
        END IF;
    END IF;

    -- Update data
    UPDATE tbl_produk
    SET
        nama_produk = TRIM(p_nama_produk),
        deskripsi = p_deskripsi,
        foto_produk = p_foto_produk,
        link_produk = p_link_produk,
        author_dosen_id = p_author_dosen_id,
        author_mahasiswa_nama = TRIM(p_author_mahasiswa_nama),
        updated_at = NOW() -- Selalu perbarui timestamp updated_at
    WHERE id = p_id;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Produk ID % berhasil diupdate', p_id;

END;
$$;

COMMENT ON PROCEDURE sp_update_produk IS 
'Procedure untuk update produk. Validasi nama unik (case-insensitive), ID, dan minimal salah satu author terisi.';

-- =================================================================
-- PROCEDURE: Delete data produk
-- =================================================================
CREATE OR REPLACE PROCEDURE sp_delete_produk(
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_row_count INT;
BEGIN
    -- Hapus data
    DELETE FROM tbl_produk
    WHERE id = p_id;

    -- Cek apakah ada baris yang terhapus
    GET DIAGNOSTICS v_row_count = ROW_COUNT;

    IF v_row_count = 0 THEN
        RAISE EXCEPTION 'Produk dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Log untuk debugging (opsional)
    RAISE NOTICE 'Produk ID % berhasil dihapus', p_id;

    -- CATATAN PENTING:
    -- Prosedur ini TIDAK mengembalikan nama file foto yang dihapus.
    -- Model PHP HARUS SELECT nama foto terlebih dahulu
    -- sebelum memanggil prosedur ini untuk menghapus file dari server.

END;
$$;

COMMENT ON PROCEDURE sp_delete_produk IS 
'Procedure untuk hapus produk berdasarkan ID. File foto harus dihapus manual di PHP.';

-- =================================================================
-- TEST PROCEDURES (Run untuk testing)
-- =================================================================

-- Test 1: Insert produk baru (Author: Dosen)
-- CALL sp_insert_produk('Aplikasi E-Learning', 'Platform pembelajaran online', 'elearning.jpg', 'https://elearning.com', 1, NULL);

-- Test 2: Insert produk baru (Author: Mahasiswa)
-- CALL sp_insert_produk('IoT Monitoring', 'Sistem monitoring IoT', 'iot.jpg', 'https://iot.com', NULL, 'Ahmad Hidayat');

-- Test 3: Insert produk baru (Author: Kolaborasi)
-- CALL sp_insert_produk('Smart Home', 'Aplikasi smart home', 'smarthome.jpg', NULL, 1, 'Siti Nurhaliza');

-- Test 4: Insert duplicate (harus error)
-- CALL sp_insert_produk('APLIKASI E-LEARNING', 'Test duplicate', 'test.jpg', NULL, 1, NULL);
-- Expected: ERROR: Nama produk sudah terdaftar

-- Test 5: Insert tanpa author (harus error)
-- CALL sp_insert_produk('Test Produk', 'Test', 'test.jpg', NULL, NULL, NULL);
-- Expected: ERROR: Minimal salah satu author harus diisi

-- Test 6: Update produk
-- CALL sp_update_produk(1, 'Aplikasi E-Learning Pro', 'Platform upgraded', 'elearning_new.jpg', 'https://elearning.com', 1, NULL);

-- Test 7: Update dengan nama duplicate (harus error)
-- CALL sp_update_produk(2, 'Aplikasi E-Learning Pro', 'Test', 'test.jpg', NULL, NULL, 'Ahmad');
-- Expected: ERROR: Nama produk sudah terdaftar

-- Test 8: Delete produk
-- CALL sp_delete_produk(1);

-- Test 9: Delete produk yang tidak ada (harus error)
-- CALL sp_delete_produk(999);
-- Expected: ERROR: Produk dengan ID 999 tidak ditemukan

-- =================================================================
-- ROLLBACK PROCEDURES (Jika perlu menghapus)
-- =================================================================
-- DROP PROCEDURE IF EXISTS sp_insert_produk;
-- DROP PROCEDURE IF EXISTS sp_update_produk;
-- DROP PROCEDURE IF EXISTS sp_delete_produk;