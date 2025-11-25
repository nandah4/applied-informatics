-- ============================================================
-- FILE: vw_show_publikasi_akademik.sql
-- DESKRIPSI: View untuk menampilkan data publikasi akademik dengan informasi dosen
--
-- TABEL SUMBER:
-- - trx_publikasi: Data publikasi akademik
-- - mst_dosen: Data dosen (untuk nama penulis)
--
-- KOLOM OUTPUT:
-- - id: ID publikasi
-- - dosen_id: ID dosen pemilik publikasi
-- - dosen_name: Nama lengkap dosen
-- - judul: Judul publikasi
-- - tipe_publikasi: Tipe (Riset/Kekayaan Intelektual/PPM)
-- - tahun_publikasi: Tahun terbit
-- - url_publikasi: Link ke publikasi online
-- - created_at: Tanggal dibuat
-- - updated_at: Tanggal terakhir diupdate
--
-- PENGGUNAAN:
--   SELECT * FROM vw_show_publikasi WHERE dosen_id = 1;
--   SELECT * FROM vw_show_publikasi ORDER BY tahun_publikasi DESC;
-- ============================================================

CREATE OR REPLACE VIEW vw_show_publikasi AS
  SELECT
      p.id,
      p.dosen_id,
      d.full_name AS dosen_name,
      p.judul,
      p.tipe_publikasi,
      p.tahun_publikasi,
      p.url_publikasi,
      p.created_at,
      p.updated_at
  FROM trx_publikasi p
  LEFT JOIN mst_dosen d ON p.dosen_id = d.id
  ORDER BY p.created_at DESC;