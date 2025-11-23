-- =================================================================
-- VIEW: Menampilkan data aktivitas
-- =================================================================
CREATE OR REPLACE VIEW vw_show_aktivitas_lab AS
SELECT
    id,
    judul_aktivitas,
    deskripsi,
    foto_aktivitas,
    tanggal_kegiatan,
    created_at,
    updated_at
FROM trx_aktivitas_lab
ORDER BY tanggal_kegiatan DESC, created_at DESC;

COMMENT ON VIEW vw_show_aktivitas IS
'View untuk menampilkan data aktivitas laboratorium';