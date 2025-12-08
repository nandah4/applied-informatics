
CREATE OR REPLACE VIEW vw_dashboard_count AS
SELECT
    (SELECT COUNT(*) FROM mst_dosen WHERE status_aktif = TRUE) AS total_dosen,
    (SELECT COUNT(*) FROM trx_publikasi) AS total_publikasi,
    (SELECT COUNT(*) FROM mst_mitra) AS total_mitra,
    (SELECT COUNT(*) FROM mst_produk) AS total_produk,
    (SELECT COUNT(*) FROM mst_fasilitas) AS total_fasilitas,
    (SELECT COUNT(*) FROM ref_keahlian) AS total_keahlian,
    (SELECT COUNT(*) FROM ref_jabatan) AS total_jabatan,
    (SELECT COUNT(*) FROM trx_aktivitas_lab) AS total_aktivitas_lab;
