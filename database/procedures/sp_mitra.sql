-- PROCEDURE Create/Update Mitra

CREATE OR REPLACE PROCEDURE sp_insert_mitra (
    p_nama VARCHAR,
    p_status mitra_status_enum,
    p_kategori mitra_kategori_enum,
    p_logo_mitra TEXT,
    p_tanggal_mulai TIMESTAMP,
    p_tanggal_akhir TIMESTAMP
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_tanggal_akhir IS NOT NULL AND p_tanggal_mulai > p_tanggal_akhir THEN 
        RAISE EXCEPTION 'Tanggal mulai tidak boleh lebih dari tanggal akhir';
    END IF;

    INSERT INTO mst_mitra (nama, status, kategori, logo_mitra, tanggal_mulai, tanggal_akhir)
    VALUES (p_nama, p_status, p_kategori, p_logo_mitra, p_tanggal_mulai, p_tanggal_akhir);

END;
$$;




CREATE OR REPLACE PROCEDURE sp_update_mitra (
    p_id BIGINT,
    p_nama VARCHAR,
    p_status mitra_status_enum,
    p_kategori mitra_kategori_enum,
    p_logo_mitra TEXT,
    p_tanggal_mulai TIMESTAMP,
    p_tanggal_akhir TIMESTAMP
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_tanggal_akhir IS NOT NULL AND p_tanggal_mulai > p_tanggal_akhir THEN 
        RAISE EXCEPTION 'Tanggal mulai tidak boleh lebih dari tanggal akhir';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM mst_mitra WHERE id = p_id) THEN
        RAISE EXCEPTION 'Mitra dengan ID % tidak ditemukan', p_id;
    END IF;

    UPDATE mst_mitra 
    SET 
        nama = p_nama,
        status = p_status,
        kategori = p_kategori,
        logo_mitra = p_logo_mitra,
        tanggal_mulai = p_tanggal_mulai,
        tanggal_akhir = p_tanggal_akhir,
        updated_at = NOW()
    WHERE 
        id = p_id; 

END;
$$;
