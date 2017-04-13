CREATE OR REPLACE FUNCTION rec.f_archivar (
)
RETURNS void AS
$body$
DECLARE

	v_id_funcionario	integer;
    v_record			record;
    v_id_usuario		integer;
    v_acceso_directo	varchar;
    v_clase 			varchar;
    v_parametros_ad 	varchar;
    v_tipo_noti			varchar;
    v_titulo			varchar;
    v_id_estado_actual	integer;

    v_fecha				date;

    v_codigo_estado integer;

BEGIN
	 --Procesamos todos los reclamos
     v_acceso_directo = '';
     v_clase = '';
     v_parametros_ad = '';
     v_tipo_noti = 'notificacion';
     v_titulo  = 'Archivo Concluido';
     --INSERT INTO rec.ttipo_incidente(nombre_incidente,fk_tipo_incidente,tiempo_respuesta, nivel) VALUES ('pokemon',1,'5',1);
     IF ((SELECT count(*) FROM rec.treclamo r WHERE  r.estado = 'respuesta_registrado_ripat')>0)THEN

     	FOR	v_record IN
        SELECT r.*
        FROM rec.treclamo r
        WHERE  r.estado = 'respuesta_registrado_ripat' LOOP

            SELECT tr.fecha_mod INTO v_fecha
            FROM rec.trespuesta tr
            WHERE tr.tipo_respuesta='respuesta_final' AND tr.id_reclamo=v_record.id_reclamo;

        	IF (rec.f_dias_respuesta(now()::date, v_fecha,'CONT_DIAS')=15) THEN

            	SELECT 	te.id_funcionario
                INTO v_id_funcionario
                FROM wf.testado_wf te
                WHERE te.id_estado_wf=v_record.id_estado_wf;

                --id tipo_estado
                SELECT te.id_tipo_estado
                INTO v_codigo_estado
                FROM wf.ttipo_estado te
                WHERE te.codigo = 'archivado_concluido';

                SELECT tu.id_usuario INTO v_id_usuario
                FROM wf.testado_wf te
                inner join orga.tfuncionario tf on tf.id_funcionario = te.id_funcionario
                inner join segu.tpersona tp on tp.id_persona = tf.id_persona
                inner join segu.tusuario tu on tu.id_persona = tp.id_persona
                WHERE te.id_estado_wf=v_record.id_estado_wf;
                v_id_estado_actual =  wf.f_registra_estado_wf(
                						v_codigo_estado,
                                        v_id_funcionario,
                                        v_record.id_estado_wf,
                                        v_record.id_proceso_wf,
                                        v_id_usuario,
                                        v_record.id_usuario_ai,
                                        v_record.usuario_ai,
                                        NULL,
                                        COALESCE(v_record.nro_tramite,'--')||' Obs:'||'ARCHIVADO DESPUES DE 15 DIAS',
                                        v_acceso_directo,
                                        v_clase,
                                        v_parametros_ad,
                                        v_tipo_noti,
                                        v_titulo);

                update rec.treclamo r set
                revisado = 'concluido',
       			id_estado_wf =  v_id_estado_actual,
      			estado = 'archivado_concluido',
       			id_usuario_mod=v_id_usuario,
       			id_usuario_ai = v_record.id_usuario_ai,
		       	usuario_ai = v_record.usuario_ai,
       			fecha_mod=now()
    			where id_proceso_wf = v_record.id_proceso_wf;

            END IF;

     	END LOOP;
	END IF;

    --INSERT INTO rec.ttipo_incidente(nombre_incidente,fk_tipo_incidente,tiempo_respuesta, nivel) VALUES (p_valor,1,'5',1);

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;