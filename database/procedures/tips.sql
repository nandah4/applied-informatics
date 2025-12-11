-- Verify the updated stored procedure definition
SELECT proname, proargtypes
FROM pg_proc
WHERE proname = 'sp_insert_aktivitas_lab';

-- How to drop procedure 1 by 1
DROP PROCEDURE sp_insert_aktivitas_lab(varchar, text, varchar, bigint, date);

-- How to drop procedure all at once
DO $$
DECLARE 
    p record;
BEGIN
    FOR p IN 
        SELECT proname, oid::regprocedure AS signature
        FROM pg_proc 
        WHERE proname = 'sp_insert_aktivitas_lab'
    LOOP
        EXECUTE 'DROP PROCEDURE ' || p.signature;
    END LOOP;
END $$;