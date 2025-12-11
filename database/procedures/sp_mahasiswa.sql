-- ============================================================================
-- File: database/procedures/sp_mahasiswa.sql
-- Deskripsi: Stored procedures, functions, dan triggers untuk mst_mahasiswa
-- ============================================================================

-- ============================================================================
-- FUNCTION: Auto-deactivate expired mahasiswa
-- Set status_aktif = FALSE jika tanggal_selesai sudah terlewat
-- ============================================================================
CREATE OR REPLACE FUNCTION fn_auto_deactivate_expired_mahasiswa()
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_updated_count INTEGER;
BEGIN
    -- Update status_aktif menjadi FALSE untuk mahasiswa yang:
    -- 1. tanggal_selesai sudah terlewat (< CURRENT_DATE)
    -- 2. status_aktif masih TRUE
    UPDATE mst_mahasiswa
    SET status_aktif = FALSE,
        updated_at = NOW()
    WHERE tanggal_selesai IS NOT NULL
      AND tanggal_selesai < CURRENT_DATE 
      AND status_aktif = TRUE;
    
    -- Return jumlah record yang diupdate
    GET DIAGNOSTICS v_updated_count = ROW_COUNT;
    RETURN v_updated_count;
END;
$$;


-- ============================================================================
-- TRIGGER FUNCTION: Auto-set status pada INSERT/UPDATE
-- ============================================================================
CREATE OR REPLACE FUNCTION trg_auto_set_mahasiswa_status()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    -- Jika tanggal_selesai diisi dan sudah lewat, otomatis set status_aktif = FALSE
    IF NEW.tanggal_selesai IS NOT NULL 
       AND NEW.tanggal_selesai < CURRENT_DATE 
       AND NEW.status_aktif = TRUE THEN
        NEW.status_aktif := FALSE;
    END IF;
    
    RETURN NEW;
END;
$$;


-- ============================================================================
-- DROP & CREATE TRIGGERS
-- ============================================================================

-- Drop trigger jika sudah ada
DROP TRIGGER IF EXISTS trg_before_insert_mahasiswa ON mst_mahasiswa;
DROP TRIGGER IF EXISTS trg_before_update_mahasiswa ON mst_mahasiswa;

-- Create trigger untuk INSERT
CREATE TRIGGER trg_before_insert_mahasiswa
    BEFORE INSERT ON mst_mahasiswa
    FOR EACH ROW
    EXECUTE FUNCTION trg_auto_set_mahasiswa_status();

-- Create trigger untuk UPDATE
CREATE TRIGGER trg_before_update_mahasiswa
    BEFORE UPDATE ON mst_mahasiswa
    FOR EACH ROW
    EXECUTE FUNCTION trg_auto_set_mahasiswa_status();


-- ============================================================================
-- STORED PROCEDURE: Update Mahasiswa
-- Dengan validasi dan auto-status check
-- ============================================================================
CREATE OR REPLACE PROCEDURE sp_update_mahasiswa (
    p_mahasiswa_id BIGINT,
    p_nim VARCHAR,
    p_nama VARCHAR,
    p_email VARCHAR,
    p_no_hp VARCHAR,
    p_semester INT,
    p_link_github VARCHAR,
    p_tipe_anggota kategori_rekrutmen_enum,
    p_periode_aktif VARCHAR,
    p_status_aktif BOOLEAN,
    p_tanggal_selesai DATE DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- 1. Validasi Keberadaan Data
    IF NOT EXISTS (SELECT 1 FROM mst_mahasiswa WHERE id = p_mahasiswa_id) THEN
        RAISE EXCEPTION 'Data mahasiswa tidak ditemukan.';
    END IF;

    -- 2. Validasi Email Unik (KECUALI milik diri sendiri)
    -- "Cek apakah ada orang LAIN (id != current_id) yang pakai email ini"
    IF EXISTS (SELECT 1 FROM mst_mahasiswa WHERE email = p_email AND id != p_mahasiswa_id) THEN
        RAISE EXCEPTION 'Email % sudah digunakan oleh anggota lain.', p_email;
    END IF;

    -- 3. Validasi NIM Unik (KECUALI milik diri sendiri)
    IF EXISTS (SELECT 1 FROM mst_mahasiswa WHERE nim = p_nim AND id != p_mahasiswa_id) THEN
        RAISE EXCEPTION 'NIM % sudah digunakan oleh anggota lain.', p_nim;
    END IF;

    -- 4. Eksekusi Update
    UPDATE mst_mahasiswa SET 
        nim = p_nim,
        nama = p_nama,
        email = p_email,
        no_hp = p_no_hp,
        semester = p_semester,
        link_github = p_link_github,
        tipe_anggota = p_tipe_anggota,
        periode_aktif = p_periode_aktif,
        tanggal_selesai = p_tanggal_selesai,
        status_aktif = p_status_aktif,
        updated_at = NOW()
    WHERE id = p_mahasiswa_id; 
    
    RAISE NOTICE 'Data mahasiswa % berhasil diperbarui.', p_nama;
END;
$$;
