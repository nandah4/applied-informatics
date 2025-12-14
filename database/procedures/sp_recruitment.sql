-- ============================================================================
-- STORED PROCEDURES FOR RECRUITMENT MANAGEMENT (FIXED VERSION WITH MANUAL CLOSE)
-- ============================================================================

-- PROCEDURE: Insert Recruitment
CREATE OR REPLACE PROCEDURE sp_insert_recruitment (
    p_judul VARCHAR,
    p_deskripsi TEXT,
    p_status rekrutmen_status_enum,
    p_tanggal_buka DATE,
    p_tanggal_tutup DATE,
    p_kategori kategori_rekrutmen_enum,
    p_periode VARCHAR,
    p_banner_image TEXT
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
    -- 2. Jika tanggal tutup >= hari ini -> Gunakan status dari parameter (bisa buka/tutup)
    IF p_tanggal_tutup < CURRENT_DATE THEN
        v_auto_status := 'tutup';
    ELSE
        v_auto_status := p_status; -- Gunakan status dari parameter
    END IF;

    -- Insert dengan status yang sudah ditentukan
    INSERT INTO trx_rekrutmen (judul, deskripsi, status, tanggal_buka, tanggal_tutup, kategori, periode, banner_image)
    VALUES (p_judul, p_deskripsi, v_auto_status, p_tanggal_buka, p_tanggal_tutup, p_kategori, p_periode, p_banner_image);

END;
$$;


-- PROCEDURE: Update Recruitment (FIXED - Allow manual close)
CREATE OR REPLACE PROCEDURE sp_update_recruitment (
    p_id BIGINT,
    p_judul VARCHAR,
    p_deskripsi TEXT,
    p_status rekrutmen_status_enum,
    p_tanggal_buka DATE,
    p_tanggal_tutup DATE,
    p_kategori kategori_rekrutmen_enum,
    p_periode VARCHAR,
    p_banner_image TEXT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_final_status rekrutmen_status_enum;
    v_old_tanggal_tutup DATE;
BEGIN
    -- Validasi tanggal tutup tidak boleh lebih awal dari tanggal buka
    IF p_tanggal_tutup < p_tanggal_buka THEN 
        RAISE EXCEPTION 'Tanggal tutup tidak boleh lebih awal dari tanggal buka';
    END IF;

    -- Cek apakah recruitment exists dan ambil tanggal tutup lama
    SELECT tanggal_tutup INTO v_old_tanggal_tutup 
    FROM trx_rekrutmen 
    WHERE id = p_id;
    
    IF v_old_tanggal_tutup IS NULL THEN
        RAISE EXCEPTION 'Recruitment dengan ID % tidak ditemukan', p_id;
    END IF;

    -- LOGIKA STATUS YANG LEBIH FLEKSIBEL:
    -- 1. Jika tanggal tutup sudah terlewat -> SELALU tutup (tidak bisa dibuka)
    -- 2. Jika tanggal tutup masih di masa depan:
    --    a. Jika tanggal diperpanjang dari masa lalu ke masa depan -> Auto BUKA
    --    b. Jika tanggal tidak berubah atau berubah tapi masih di masa depan -> Gunakan status dari parameter
    
    IF p_tanggal_tutup < CURRENT_DATE THEN
        -- Sudah expired, tidak bisa dibuka
        v_final_status := 'tutup';
    ELSIF v_old_tanggal_tutup < CURRENT_DATE AND p_tanggal_tutup >= CURRENT_DATE THEN
        -- Diperpanjang dari expired ke aktif -> Auto buka
        v_final_status := 'buka';
    ELSE
        -- Tanggal tutup masih valid -> Gunakan status dari parameter (allow manual close/open)
        v_final_status := p_status;
    END IF;

    -- Update dengan status yang ditentukan
    UPDATE trx_rekrutmen 
    SET 
        judul = p_judul,
        deskripsi = p_deskripsi,
        status = v_final_status,
        tanggal_buka = p_tanggal_buka,
        tanggal_tutup = p_tanggal_tutup,
        kategori = p_kategori,
        periode = p_periode,
        banner_image = p_banner_image,
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
-- FUNCTION: Auto-close expired recruitment (Hanya close yang expired)
-- ============================================================================
CREATE OR REPLACE FUNCTION fn_auto_close_expired_recruitment()
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_updated_count INTEGER;
BEGIN
    -- HANYA update status menjadi 'tutup' untuk recruitment yang:
    -- 1. Tanggal tutupnya sudah terlewat
    -- 2. Statusnya masih 'buka'
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
-- TRIGGER: Auto-set status pada INSERT/UPDATE (FIXED)
-- ============================================================================
CREATE OR REPLACE FUNCTION trg_auto_set_recruitment_status()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    -- Hanya auto-close jika tanggal sudah expired dan masih buka
    -- Jangan auto-open jika user sengaja menutup secara manual
    IF NEW.tanggal_tutup < CURRENT_DATE AND NEW.status = 'buka' THEN
        NEW.status := 'tutup';
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
-- STORED PROCEDURES LAINNYA UNTUK RECRUITMENT
-- ============================================================================

-- Prosedur pendaftaran mahasiswa
CREATE OR REPLACE PROCEDURE sp_daftar_rekrutmen (
    p_rekrutmen_id BIGINT,
    p_nim VARCHAR,
    p_nama VARCHAR,
    p_email VARCHAR,
    p_no_hp VARCHAR,
    p_semester INT,
    p_ipk DECIMAL,
    p_link_portfolio VARCHAR,
    p_link_github VARCHAR,
    p_file_cv VARCHAR,
    p_file_khs VARCHAR
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- 1. Validasi: Apakah rekrutmen ada dan statusnya 'buka'?
    IF NOT EXISTS (SELECT 1 FROM trx_rekrutmen WHERE id = p_rekrutmen_id AND status = 'buka') THEN
        RAISE EXCEPTION 'Lowongan rekrutmen tidak ditemukan atau sudah ditutup.';
    END IF;

    -- 2. Validasi: Apakah NIM ini SUDAH mendaftar di rekrutmen INI?
    IF EXISTS (SELECT 1 FROM trx_pendaftar WHERE rekrutmen_id = p_rekrutmen_id AND nim = p_nim) THEN
        RAISE EXCEPTION 'Anda sudah mendaftar pada lowongan ini sebelumnya.';
    END IF;

    -- 3. Insert Data
    INSERT INTO trx_pendaftar (
        rekrutmen_id, nim, nama, email, no_hp, semester, ipk,
        link_portfolio, link_github, file_cv, file_khs, status_seleksi
    ) VALUES (
        p_rekrutmen_id, TRIM(p_nim), TRIM(p_nama), TRIM(p_email), p_no_hp, p_semester, p_ipk,
        p_link_portfolio, p_link_github, p_file_cv, p_file_khs, 'Pending'
    );
END;
$$;


-- Update status seleksi pendaftar
CREATE OR REPLACE PROCEDURE sp_update_status_seleksi (
    p_pendaftar_id BIGINT,
    p_status_baru seleksi_status_enum,
    p_deskripsi TEXT DEFAULT NULL
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM trx_pendaftar WHERE id = p_pendaftar_id) THEN
        RAISE EXCEPTION 'Data pendaftar tidak ditemukan.';
    END IF;

    UPDATE trx_pendaftar 
    SET status_seleksi = p_status_baru,
        deskripsi = CASE 
            WHEN p_status_baru = 'Ditolak' THEN p_deskripsi 
            ELSE NULL 
        END,
        updated_at = NOW()
    WHERE id = p_pendaftar_id;
END;
$$;


-- Terima anggota (promosi)
CREATE OR REPLACE PROCEDURE sp_terima_anggota (
    p_pendaftar_id BIGINT
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_rec RECORD;
BEGIN
    SELECT p.*, r.kategori AS tipe_anggota
    INTO v_rec 
    FROM trx_pendaftar p
    JOIN trx_rekrutmen r ON p.rekrutmen_id = r.id
    WHERE p.id = p_pendaftar_id;

    IF v_rec.id IS NULL THEN
        RAISE EXCEPTION 'Data pendaftar tidak ditemukan.';
    END IF;

    IF EXISTS (SELECT 1 FROM mst_mahasiswa WHERE nim = v_rec.nim) THEN
        RAISE EXCEPTION 'Mahasiswa dengan NIM % sudah menjadi anggota lab.', v_rec.nim;
    END IF;

    UPDATE trx_pendaftar 
    SET status_seleksi = 'Diterima', updated_at = NOW() 
    WHERE id = p_pendaftar_id;

    INSERT INTO mst_mahasiswa (
        nim, nama, email, no_hp, 
        tipe_anggota, semester, link_github, status_aktif, tanggal_gabung, asal_pendaftar_id
    ) VALUES (
        v_rec.nim, 
        v_rec.nama, 
        v_rec.email, 
        v_rec.no_hp,
        v_rec.tipe_anggota,
        v_rec.semester,
        v_rec.link_github,
        TRUE, 
        CURRENT_DATE, 
        p_pendaftar_id
    );

END;
$$;