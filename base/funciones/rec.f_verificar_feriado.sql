CREATE OR REPLACE FUNCTION rec.f_verificar_feriado (
  p_feriado date
)
RETURNS boolean AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Reclamos
 FUNCION: 		rec.f_verificar_feriado
 DESCRIPCION:   Funcion que verifica si un dia es feriado, en caso de ser feriado aumenta un dia a la fecha de respuesta
 AUTOR: 		 (fea)
 FECHA:	        14-06-2017 15:15:26
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_record				record;
    v_fecha_limite			date;

BEGIN

    v_nombre_funcion = 'rec.f_verificar_feriado';
    IF(p_feriado = '06/08/2018'::date)THEN
      FOR v_record IN (SELECT tr.id_reclamo, tr.fecha_limite_respuesta, tr.nro_tramite
                       FROM rec. treclamo tr
                       WHERE (tr.fecha_hora_recepcion::date BETWEEN '16/7/2018'::date AND '06/08/2018'::date) AND tr.id_gestion = 16)LOOP
          IF(v_record.fecha_limite_respuesta>=p_feriado)THEN
          	IF(date_part('dow',v_record.fecha_limite_respuesta) IN (1, 2, 3, 4))THEN
              v_fecha_limite = v_record.fecha_limite_respuesta + ('1 day')::interval;
            ELSIF(date_part('dow',v_record.fecha_limite_respuesta) IN (5))THEN
              v_fecha_limite = v_record.fecha_limite_respuesta + ('3 day')::interval;
          	END IF;
          END IF;

          UPDATE rec.treclamo SET
          	fecha_limite_respuesta = v_fecha_limite
          WHERE id_reclamo = v_record.id_reclamo;
          raise notice 'v_record %',v_record;
      END LOOP;
    END IF;

	return true;

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
SECURITY INVOKER
COST 100;