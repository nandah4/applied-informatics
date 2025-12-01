-- VIEW untuk menampilkan recruitment - unused
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


-- VIEW untuk show pendaftar

CREATE OR REPLACE VIEW vw_show_pendaftar AS                                                                                                                                                          
SELECT                                                                                                                                                                                               
    p.id,                                                                                                                                                                                            
     p.rekrutmen_id,                                                                                                                                                                                  
     r.judul AS judul_rekrutmen,                                                                                                                                                                      
     p.nim,                                                                                                                                                                                           
     p.nama,                                                                                                                                                                                          
     p3.email,                                                                                                                                                                                         
     p.no_hp,                                                                                                                                                                                         
     p.semester,                                                                                                                                                                                      
     p.ipk,                                                                                                                                                                                           
     p.link_portfolio,                                                                                                                                                                                
     p.link_github,                                                                                                                                                                                   
     p.file_cv,                                                                                                                                                                                       
     p.file_khs,                                                                                                                                                                                      
     p.status_seleksi,                                                                                                                                                                                
     p.created_at,                                                                                                                                                                                    
     p.updated_at                                                                                                                                                                                     
FROM trx_pendaftar p                                                                                                                                                                                 
INNER JOIN trx_rekrutmen r ON p.rekrutmen_id = r.id                                                                                                                                                  
ORDER BY p.created_at DESC;