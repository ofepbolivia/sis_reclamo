CREATE OR REPLACE FUNCTION rec.f_lista_funcionario_registro_reclamo (
  p_id_usuario integer,
  p_id_tipo_estado integer,
  p_fecha date = now(),
  p_id_estado_wf integer = NULL::integer,
  p_count boolean = false,
  p_limit integer = 1,
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying
)
RETURNS SETOF record AS
$body$
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE RECLAMOS
***************************************************************************
 SCRIPT: 		rec.f_lista_funcionario_registro_reclamo
 DESCRIPCIÓN: 	Lista el funcionario quien cambia de estado
 AUTOR: 		Grover Velasquez Colque
 FECHA:			08/01/2016
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCIÓN: Consulta que recupera el id del anterior Usuario
 AUTOR: Franklin Espinoza
 FECHA:

***************************************************************************/

-------------------------
-- CUERPO DE LA FUNCIÓN --
--------------------------

-- PARÁMETROS FIJOS
/*
  p_id_usuario integer,                                identificador del actual usuario de sistema
  p_id_tipo_estado integer,                            idnetificador del tipo estado del que se quiere obtener el listado de funcionario  (se correponde con tipo_estado que le sigue a id_estado_wf proporcionado)
  p_fecha date = now(),                                fecha  --para verificar asginacion de cargo con organigrama
  p_id_estado_wf integer = NULL::integer,              identificaro de estado_wf actual en el proceso_wf
  p_count boolean = false,                             si queremos obtener numero de funcionario = true por defecto false
  p_limit integer = 1,                                 los siguiente son parametros para filtrar en la consulta
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying
*/

DECLARE
	g_registros  		record;
    v_depto_asignacion    varchar;

    v_consulta varchar;
    v_nombre_funcion varchar;
    v_resp varchar;

    v_id_usuario_reg  integer;
    v_id_funcionario integer;

BEGIN

    v_nombre_funcion ='rec.f_lista_funcionario_registro_reclamo';

    --recuperamos la la opbligacion de pago a partir del is_estado_wf del la obligacion

                    /*SELECT tew.id_funcionario
                    INTO v_id_funcionario
                    FROM wf.testado_wf tew
                    LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = tew.id_estado_wf
                    LEFT JOIN rec.trespuesta  tr ON tr.id_estado_wf = te.id_estado_wf
                    WHERE tr.id_estado_wf =  p_id_estado_wf;*/

                    SELECT tew.id_funcionario
                    INTO v_id_funcionario
					FROM wf.testado_wf  tew
					LEFT JOIN wf.ttipo_estado tte ON tte.id_tipo_estado = tew.id_tipo_estado
					WHERE tte.codigo =  'borrador' AND tew.id_proceso_wf = (SELECT tew.id_proceso_wf
																						 FROM wf.testado_wf tew
																						 WHERE tew.id_estado_wf = p_id_estado_wf);
    IF not p_count then
             v_consulta:='SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''Gerente''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario = '||COALESCE(v_id_funcionario,0)::varchar||'
                         and '||p_filtro||'
                         limit '|| p_limit::varchar||' offset '||p_start::varchar;


                   FOR g_registros in execute (v_consulta)LOOP
                     RETURN NEXT g_registros;
                   END LOOP;

      ELSE
                  v_consulta='select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun WHERE fun.id_funcionario = '||COALESCE(v_id_funcionario,0)::varchar||'
                                 and '||p_filtro;

                   FOR g_registros in execute (v_consulta)LOOP
                     RETURN NEXT g_registros;
                   END LOOP;


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
SECURITY INVOKER
COST 100 ROWS 1000;