CREATE OR REPLACE FUNCTION rec.f_procesar_frds (
  p_id_oficina integer,
  p_nro_frd varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 FUNCION: 		rec.f_procesar_frds
 DESCRIPCION: 	procesa los frds de una oficina
 AUTOR: 		FEA
 FECHA:			16/06/2017
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:

***************************************************************************/
DECLARE
  v_num_siguiente 	INTEGER;
  v_gestion 		varchar;
  v_id_gestion 		integer;
  v_cont_gestion 	integer;
  v_nombre_funcion 	VARCHAR;
  v_resp 			varchar;
  v_index			integer;
  v_cont			integer;
  v_frds			INTEGER[];
  v_frd_faltantes	varchar[];
  v_band_frds		varchar;
  v_max				integer;
BEGIN
    v_nombre_funcion = 'rec.f_procesar_frds';

    select g.id_gestion
    into v_id_gestion
    from param.tgestion g
    where g.gestion = EXTRACT(YEAR FROM current_date);
    --encontramos la lista de frd faltantes
    --begin
    IF(p_transaccion = 'FRD_FALTANTES')THEN
    	v_cont = 1;
        v_frds = string_to_array(rec.f_procesar_frds(p_id_oficina,'15','FRD_REGISTRADOS'),',');
        IF (v_frds IS NOT NULL)THEN
          FOR v_index IN 1..rec.f_procesar_frds(p_id_oficina,'15','FRD_MAX') LOOP
            IF v_index = ANY (v_frds) THEN

            ELSE
               --v_frds_aux[v_cont] = v_index;
               IF(v_cont::integer % 10 = 0)THEN
                  v_frd_faltantes[v_cont] = v_index::varchar||'<br>';
               ELSE
                  v_frd_faltantes[v_cont] = v_index;
               END IF;
                v_cont = v_cont + 1;
            END IF;
          END LOOP;
        return array_to_string(v_frd_faltantes,',');
    ELSIF(p_transaccion = 'FRD_REGISTRADOS')THEN
    	v_cont = 1;
        FOR v_index IN (SELECT to_number(tr.nro_frd,'9999999') AS nro_frd
        				FROM rec.treclamo tr
                        INNER JOIN rec.toficina tof ON tof.id_oficina = tr.id_oficina_registro_incidente
                        WHERE tr.id_oficina_registro_incidente = p_id_oficina and tr.id_gestion = v_id_gestion ORDER BY nro_frd ASC)LOOP
          v_frds[v_cont] = v_index;
          v_cont = v_cont + 1;
          RAISE NOTICE 'v_index %, v_cont %',v_index,v_cont;
        END LOOP;
        return array_to_string(v_frds,',');
    ELSIF(p_transaccion = 'FRD_MAX')THEN
    	SELECT max(to_number(tr.nro_frd,'9999999'))
        INTO v_max
        FROM rec.treclamo tr
        INNER JOIN rec.toficina tof ON tof.id_oficina = tr.id_oficina_registro_incidente
        WHERE tr.id_oficina_registro_incidente = p_id_oficina::integer and tr.id_gestion = v_id_gestion ;
        return v_max::varchar;
    END IF;

EXCEPTION
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY DEFINER
COST 100;