-- VIEW : getAll data dosen dari table mst_dosen dan join ke ref table

CREATE OR REPLACE VIEW vw_show_dosen AS
    SELECT
        d.id,
        d.full_name,
        d.email,
        d.nidn,
        d.foto_profil,
        d.deskripsi,
        d.jabatan_id,
        j.nama_jabatan as jabatan_name,
        STRING_AGG(k.nama_keahlian, ', ') as keahlian_list,
        d.updated_at,
        d.created_at
    FROM mst_dosen d
    LEFT JOIN ref_jabatan j ON d.jabatan_id = j.id
    LEFT JOIN map_dosen_keahlian dk ON d.id = dk.dosen_id
    LEFT JOIN ref_keahlian k ON dk.keahlian_id = k.id
    GROUP BY d.id, d.full_name, d.email, d.nidn, d.foto_profil, d.deskripsi, d.jabatan_id, j.nama_jabatan, d.created_at;



-- VIEW : getAll data publikasi dosen dari table ref_profil_publikasi

CREATE OR REPLACE VIEW vw_profil_publikasi AS
    SELECT
        p.dosen_id,
        p.tipe,
        p.url_profil
    FROM ref_profil_publikasi p
    JOIN mst_dosen d ON d.id = p.dosen_id