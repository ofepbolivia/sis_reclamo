CREATE OR REPLACE FUNCTION rec.f_procesar_estados_respuesta (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
DECLARE
    v_nombre_funcion	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
    v_respuesta			record;

    v_record			record;
    v_id_estado_actual	integer;

    v_acceso_directo 	varchar;
    v_clase  			varchar;
    v_parametros_ad  	varchar;
    v_tipo_noti 		varchar;
    v_titulo         	varchar;

    v_id_funcionario 	integer;
    v_id_tipo_estado	integer;
    v_registros_rec		record;
    v_revisado			varchar;


    va_id_tipo_estado_res 	integer[];
    va_codigo_estado_res 	varchar[];
    va_disparador_res		varchar[];
    va_regla_res			varchar[];
    va_prioridad_res		integer[];

    v_id_reclamo	integer;
	v_id_tipo_estado_sig	integer;
    v_tipo_respuesta		varchar;
    v_codigo_estado_sig		varchar;

BEGIN

	v_nombre_funcion = 'rec.f_procesar_estados_respuesta';

    select * into v_respuesta
    from rec.trespuesta	res
    where res.id_proceso_wf = p_id_proceso_wf;

        if(p_codigo_estado in ('elaboracion_respuesta')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;

                SELECT r.id_reclamo
                INTO v_id_reclamo
                FROM rec.trespuesta r
                WHERE id_proceso_wf = p_id_proceso_wf;

                UPDATE rec.treclamo SET
                	revisado = 'proceso'
                WHERE id_reclamo = v_id_reclamo;
            end;

        elsif(p_codigo_estado in ('revision_legal')) then
            begin
            --RAISE EXCEPTION 'LLEGA';
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('vobo_respuesta')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('respuesta_aprobada')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('respuesta_enviada')) then
            begin

                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now(),
                    fecha_notificacion=now()::date
                where id_proceso_wf = p_id_proceso_wf;

                SELECT tr.id_estado_wf, tr.id_proceso_wf, tr.estado, tr.nro_tramite
                INTO v_record
                FROM rec.treclamo tr
                WHERE tr.id_reclamo = v_respuesta.id_reclamo;


                v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/Reclamo.php';
                v_clase = 'Reclamo';
                v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||
                v_record.id_proceso_wf::varchar||'"}}';
                v_tipo_noti = 'notificacion';
                v_titulo  = 'Notificacion';



                SELECT tf.id_funcionario
                INTO v_id_funcionario
				FROM segu.tusuario tu
				INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
				WHERE tu.id_usuario = (SELECT tr.id_usuario_mod
                						FROM rec.trespuesta tr
						                WHERE tr.id_estado_wf = p_id_estado_wf);

                SELECT
                           ps_id_tipo_estado,
                           ps_codigo_estado,
                           ps_disparador,
                           ps_regla,
                           ps_prioridad
            	INTO
                          va_id_tipo_estado_res,
                          va_codigo_estado_res,
                          va_disparador_res,
                          va_regla_res,
                          va_prioridad_res
            	FROM wf.f_obtener_estado_wf(v_record.id_proceso_wf, v_record.id_estado_wf,NULL,'siguiente');

                --Definimos si la Respuesta se va al estado respuesta_parcial o al estado archivo_con_respuesta
                SELECT tr.tipo_respuesta
                INTO v_tipo_respuesta
                FROM rec.trespuesta tr
                WHERE tr.id_proceso_wf = p_id_proceso_wf;



				IF(v_tipo_respuesta = 'respuesta_parcial')THEN
                	v_id_tipo_estado_sig = va_id_tipo_estado_res[3];
                    v_codigo_estado_sig = va_codigo_estado_res[3];
                ELSIF(v_tipo_respuesta = 'respuesta_final')THEN
                	v_id_tipo_estado_sig = va_id_tipo_estado_res[1];
                    v_codigo_estado_sig = va_codigo_estado_res[1];
                END IF;

                v_id_estado_actual =  wf.f_registra_estado_wf(v_id_tipo_estado_sig,
                                                             v_id_funcionario,
                                                             v_record.id_estado_wf,
                                                             v_record.id_proceso_wf,
                                                             p_id_usuario,
                                                             p_id_usuario_ai,
                                                             p_usuario_ai,
                                                             NULL,
                                                             COALESCE(v_record.nro_tramite,'--')||' Obs: Respues Enviada',
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);

         		IF rec.f_procesar_estados_reclamo(p_id_usuario,
           									p_id_usuario_ai,
                                            p_usuario_ai,
                                            v_id_estado_actual,
                                            v_record.id_proceso_wf,
                                            v_codigo_estado_sig,
                                            1) THEN
                	RAISE NOTICE 'PASANDO ESTADO CON EXITO';

          		END IF;

            end;
        end if;

    /*ELSE

    		RAISE EXCEPTION 'La respuesta es improcedente, Verifique información.';

	END IF;*/
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