-- VIEW : getAll data produk dengan join ke dosen
CREATE OR REPLACE VIEW vw_show_produk AS
    SELECT
        p.id,
        p.nama_produk,
        p.deskripsi,
        p.foto_produk,
        p.link_produk,
        p.tim_mahasiswa,
        p.created_at,
        p.updated_at,
        -- Aggregate dosen names (comma-separated)
        STRING_AGG(d.full_name, ', ' ORDER BY d.full_name) as dosen_names,
        -- Count dosen
        COUNT(DISTINCT mpd.dosen_id) as dosen_count
    FROM mst_produk p
    LEFT JOIN map_produk_dosen mpd ON p.id = mpd.produk_id
    LEFT JOIN mst_dosen d ON mpd.dosen_id = d.id
    GROUP BY p.id, p.nama_produk, p.deskripsi, p.foto_produk, 
             p.link_produk, p.tim_mahasiswa, p.created_at, p.updated_at
    ORDER BY p.created_at DESC;