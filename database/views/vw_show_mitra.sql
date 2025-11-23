-- VIEW : getAll data mitra dari table mst_mitra

CREATE OR REPLACE VIEW vw_show_mitra AS
    SELECT
        m.id,
        m.nama,          
        m.status,        
        m.deskripsi,
        m.logo_mitra,
        m.kategori,
        m.tanggal_mulai,
        m.tanggal_akhir,
        m.created_at,
        m.updated_at
    FROM mst_mitra m;

        