-- VIEW untuk menampilkan recruitment 
CREATE OR REPLACE VIEW vw_show_recruitment AS
SELECT 
    id,
    judul,
    deskripsi,
    status,
    tanggal_buka,
    tanggal_tutup,
    lokasi,
    created_at,
    updated_at
FROM trx_rekrutmen
ORDER BY created_at DESC;