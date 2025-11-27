-- ============================================================================
-- STORED PROCEDURES FOR RECRUITMENT MANAGEMENT (FIXED VERSION)
-- ============================================================================

-- PROCEDURE: Insert Recruitment
CREATE OR REPLACE PROCEDURE sp_insert_recruitment (
    p_judul VARCHAR,
    p_deskripsi TEXT,
    p_status rekrutmen_status_enum,
    p_tanggal_buka DATE,
    p_tanggal_tutup DATE,
    p_lokasi VARCHAR
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_auto_status rekrutmen_status_enum;
BEGIN
    -- Validasi tanggal tutup tidak boleh lebih awal dari tanggal buka
    IF p_tanggal_tutup < p_tanggal_buka THEN 
        RAISE EXCEPTION 'Tanggal tutup tidak boleh lebih awal dari tanggal buka';
    END IF;

    -- AUTO-DETERMINE STATUS BERDASARKAN TANGGAL:
    -- 1. Jika tanggal tutup sudah terlewat -> TUTUP
    -- 2. Jika tanggal tutup >= hari ini -> BUKA
    IF p_tanggal_tutup < CURRENT_DATE THEN
        v_auto_status := 'tutup';
    ELSE
        v_auto_status := 'buka';
    END IF;

    -- Insert dengan status yang sudah ditentukan otomatis
    INSERT INTO trx_rekrutmen (judul, deskripsi, status, tanggal_buka, tanggal_tutup, lokasi)
    VALUES (p_judul, p_deskripsi, v_auto_status, p_tanggal_buka, p_tanggal_tutup, p_lokasi);

END;
$$;


-- PROCEDURE: Update Recruitment
CREATE OR REPLACE PROCEDURE sp_update_recruitment (
    p_id BIGINT,
    p_judul VARCHAR,
    p_deskripsi TEXT,
    p_status rekrutmen_status_enum,  -- Parameter ini akan di-ignore, status ditentukan otomatis
    p_tanggal_buka DATE,
    p_tanggal_tutup DATE,
    p_lokasi VARCHAR
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_auto_status rekrutmen_status_enum;
BEGIN
    -- Validasi tanggal tutup tidak boleh lebih awal dari tanggal buka
    IF p_tanggal_tutup < p_tanggal_buka THEN 
        RAISE EXCEPTION 'Tanggal tutup tidak boleh lebih awal dari tanggal buka';
    END IF;

    -- Cek apakah recruitment exists
    IF NOT EXISTS (SELECT 1 FROM trx_rekrutmen WHERE id = p_id) THEN
        RAISE EXCEPTION 'Recruitment dengan ID % tidak ditemukan', p_id;
    END IF;

    -- AUTO-UPDATE STATUS BERDASARKAN TANGGAL:
    -- 1. Jika tanggal tutup sudah terlewat (< hari ini) -> TUTUP
    -- 2. Jika tanggal tutup >= hari ini -> BUKA (auto-reopen jika diperpanjang)
    IF p_tanggal_tutup < CURRENT_DATE THEN
        v_auto_status := 'tutup';  -- Sudah expired atau diperpendek ke masa lalu
    ELSE
        v_auto_status := 'buka';   -- Masih aktif atau diperpanjang ke masa depan
    END IF;

    -- Update dengan status yang ditentukan otomatis
    UPDATE trx_rekrutmen 
    SET 
        judul = p_judul,
        deskripsi = p_deskripsi,
        status = v_auto_status,  -- Status ditentukan oleh logika di atas, bukan dari parameter
        tanggal_buka = p_tanggal_buka,
        tanggal_tutup = p_tanggal_tutup,
        lokasi = p_lokasi,
        updated_at = NOW()
    WHERE 
        id = p_id; 

END;
$$;


-- PROCEDURE: Delete Recruitment
CREATE OR REPLACE PROCEDURE sp_delete_recruitment (
    p_id BIGINT
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Cek apakah recruitment exists
    IF NOT EXISTS (SELECT 1 FROM trx_rekrutmen WHERE id = p_id) THEN
        RAISE EXCEPTION 'Recruitment dengan ID % tidak ditemukan', p_id;
    END IF;

    -- Delete recruitment
    DELETE FROM trx_rekrutmen WHERE id = p_id;

END;
$$;


-- ============================================================================
-- FUNCTION: Auto-close expired recruitment
-- ============================================================================
-- Function ini dipanggil setiap kali ada operasi read untuk memastikan
-- status recruitment selalu up-to-date
CREATE OR REPLACE FUNCTION fn_auto_close_expired_recruitment()
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_updated_count INTEGER;
BEGIN
    -- Update status menjadi 'tutup' untuk recruitment yang tanggal tutupnya sudah terlewat
    UPDATE trx_rekrutmen
    SET status = 'tutup',
        updated_at = NOW()
    WHERE tanggal_tutup < CURRENT_DATE 
      AND status = 'buka';
    
    -- Return jumlah record yang diupdate
    GET DIAGNOSTICS v_updated_count = ROW_COUNT;
    RETURN v_updated_count;
END;
$$;


-- ============================================================================
-- OPTIONAL: Trigger untuk auto-update status saat insert/update
-- ============================================================================
-- Trigger ini akan otomatis set status berdasarkan tanggal
CREATE OR REPLACE FUNCTION trg_auto_set_recruitment_status()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    -- Set status berdasarkan tanggal tutup
    IF NEW.tanggal_tutup < CURRENT_DATE THEN
        NEW.status := 'tutup';
    ELSE
        NEW.status := 'buka';
    END IF;
    
    RETURN NEW;
END;
$$;

-- Drop trigger jika sudah ada
DROP TRIGGER IF EXISTS trg_before_insert_recruitment ON trx_rekrutmen;
DROP TRIGGER IF EXISTS trg_before_update_recruitment ON trx_rekrutmen;

-- Create trigger untuk INSERT
CREATE TRIGGER trg_before_insert_recruitment
    BEFORE INSERT ON trx_rekrutmen
    FOR EACH ROW
    EXECUTE FUNCTION trg_auto_set_recruitment_status();

-- Create trigger untuk UPDATE
CREATE TRIGGER trg_before_update_recruitment
    BEFORE UPDATE ON trx_rekrutmen
    FOR EACH ROW
    EXECUTE FUNCTION trg_auto_set_recruitment_status();


-- ============================================================================
-- TEST CASES (Uncomment untuk testing)
-- ============================================================================

/*
-- Test 1: Insert recruitment dengan tanggal di masa depan (harus BUKA)
CALL sp_insert_recruitment(
    'Test Recruitment 1',
    'Deskripsi test',
    'tutup',  -- Akan di-override menjadi 'buka'
    CURRENT_DATE,
    CURRENT_DATE + INTERVAL '30 days',
    'Lab AI'
);

-- Test 2: Insert recruitment dengan tanggal sudah terlewat (harus TUTUP)
CALL sp_insert_recruitment(
    'Test Recruitment 2',
    'Deskripsi test',
    'buka',  -- Akan di-override menjadi 'tutup'
    CURRENT_DATE - INTERVAL '60 days',
    CURRENT_DATE - INTERVAL '30 days',
    'Lab AI'
);

-- Test 3: Update - perpanjang tanggal tutup (harus auto BUKA)
CALL sp_update_recruitment(
    1,  -- ID recruitment
    'Updated Recruitment',
    'Deskripsi updated',
    'tutup',  -- Akan di-override menjadi 'buka' karena tanggal diperpanjang
    CURRENT_DATE,
    CURRENT_DATE + INTERVAL '60 days',
    'Lab AI Updated'
);

-- Test 4: Update - perpendek tanggal tutup ke masa lalu (harus auto TUTUP)
CALL sp_update_recruitment(
    1,
    'Updated Recruitment',
    'Deskripsi updated',
    'buka',  -- Akan di-override menjadi 'tutup' karena tanggal sudah lewat
    CURRENT_DATE - INTERVAL '10 days',
    CURRENT_DATE - INTERVAL '5 days',
    'Lab AI Updated'
);

-- Test 5: Check auto-close function
SELECT fn_auto_close_expired_recruitment();

-- Lihat hasil
SELECT id, judul, status, tanggal_buka, tanggal_tutup, updated_at 
FROM trx_rekrutmen 
ORDER BY id;
*/