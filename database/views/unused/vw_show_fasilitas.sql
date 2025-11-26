-- ============================================================================
-- VIEW: vw_show_fasilitas
-- ============================================================================
-- Description: View untuk menampilkan data fasilitas
-- Author: Applied Informatics Lab
-- Version: 1.0
-- ============================================================================

-- DROP VIEW IF EXISTS vw_show_fasilitas;

CREATE OR REPLACE VIEW vw_show_fasilitas AS
    SELECT
        f.id,
        f.nama,
        f.deskripsi,
        f.foto,
        f.created_at,
        f.updated_at
    FROM mst_fasilitas f
    ORDER BY f.created_at DESC;

-- ============================================================================
-- KOMENTAR
-- ============================================================================
COMMENT ON VIEW vw_show_fasilitas IS 
'View untuk menampilkan semua data fasilitas dengan sorting by created_at DESC';

-- ============================================================================
-- CARA PENGGUNAAN
-- ============================================================================
-- SELECT * FROM vw_show_fasilitas;
-- SELECT * FROM vw_show_fasilitas WHERE id = 1;
-- SELECT * FROM vw_show_fasilitas ORDER BY nama ASC;