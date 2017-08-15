CREATE OR REPLACE FUNCTION rec.f_verificar_alerta_falla (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 FUNCION: 		rec.f_verificar_alerta_falla
 DESCRIPCION: 	verifica las alarmas que fallaron al enviar por algun motivo.
 AUTOR: 		FEA
 FECHA:			08/08/2017
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:

***************************************************************************/
DECLARE
	v_nombre_funcion 	varchar;
	v_resp				varchar;

	v_cont				integer;
	v_ids_alarma		INTEGER[];
    v_index				integer = 1;
    v_nro_tramites		varchar[];
    v_titulo_correo		varchar[];
    v_correo			varchar[];
    v_fecha_reg			TIMESTAMP[];
  	v_record			record;
	v_cadena			varchar;

    v_id_rec			integer;
    v_id_resp			integer;
    v_record_rec		record;
    v_record_resp		record;
    v_record_ids 		record;

    v_id_tipo_estado	integer;
    v_id_funcionario	integer;
    v_id_usuario_reg	integer;
    v_id_depto 			integer;
    v_codigo_estado		varchar;
    v_id_estado_wf_ant	integer;

    v_id_estado_actual  integer;
    v_desc_fallas 		varchar[];
    v_cont_estados		integer;
BEGIN
    v_nombre_funcion = 'rec.f_verificar_alerta_falla';


    /*********************************
    #TRANSACCION:  'FAILED_MAILS'
    #DESCRIPCION:	Permite cambiar al estado pendiente_respuesta a un reclamo, y de la respuesta al estado respuesta_aprobada
    #				y envia un correo a sac@boa.bo para informarles que hubo error de envio como constancia.
    #AUTOR:		Franklin Espinoza
    #FECHA:		08-08-2017 18:32:59
      ***********************************/

    IF (p_transaccion = 'FAILED_MAILS')THEN


        --Volviendo al estado anterior
        FOR v_record_ids IN (SELECT count(ta.id_alarma) AS contador, tr.id_respuesta, tr.id_reclamo, ta.desc_falla
                                  FROM rec.trespuesta tr
                                  INNER JOIN param.talarma ta ON ta.id_proceso_wf = tr.id_proceso_wf
                                  WHERE (ta.estado_envio = 'falla' OR ta.pendiente <> 'no' ) AND
                                  (ta.fecha_reg::date BETWEEN now()::date-1 AND now()::date+1)
                                  GROUP BY tr.id_respuesta, ta.desc_falla)LOOP

            IF(v_record_ids.desc_falla <> 'SMTP connect() failed.')THEN
              --reclamo
              /*===================================================================================================*/
              UPDATE rec.treclamo SET
                revisado = 'falla_envio'
              WHERE id_reclamo = v_record_ids.id_reclamo;

              SELECT rec.nro_tramite, rec.id_proceso_wf, rec.id_estado_wf, rec.estado
              INTO v_record_rec
              FROM rec.treclamo rec
              WHERE rec.id_reclamo = v_record_ids.id_reclamo;

              IF(v_record_rec.estado = 'respuesta_registrado_ripat')THEN
              	v_cont_estados = 2;
              ELSIF(v_record_rec.estado = 'archivo_con_respuesta')THEN
              	v_cont_estados = 1;
              END IF;

			  FOR v_cont IN 1..v_cont_estados LOOP

                SELECT rec.nro_tramite, rec.id_proceso_wf, rec.id_estado_wf, rec.estado
              	INTO v_record_rec
              	FROM rec.treclamo rec
              	WHERE rec.id_reclamo = v_record_ids.id_reclamo;

                SELECT
                   ps_id_tipo_estado,
                   ps_id_funcionario,
                   ps_id_usuario_reg,
                   ps_id_depto,
                   ps_codigo_estado,
                   ps_id_estado_wf_ant
                INTO
                   v_id_tipo_estado,
                   v_id_funcionario,
                   v_id_usuario_reg,
                   v_id_depto,
                   v_codigo_estado,
                   v_id_estado_wf_ant
                FROM wf.f_obtener_estado_ant_log_wf(v_record_rec.id_estado_wf);

                -- registramos estado pendiente_respuesta
                v_id_estado_actual = wf.f_registra_estado_wf(
                        v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                        v_id_funcionario,                --  funcionario del estado anterior
                        v_record_rec.id_estado_wf,       --  estado actual ...
                        v_record_rec.id_proceso_wf,      --  id del proceso actual
                        p_id_usuario,                    -- usuario que registra
                        NULL,
                        NULL,
                        v_id_depto,                       --depto del estado anterior
                        v_record_rec.nro_tramite||'->[RETROCESO] - '||'[ERROR AL ENVIAR RESPUESTA]',
                        '',
                        '',
                        '',
                        'cambio de estado forzoso',
                        'Cambio de Estado');
                IF NOT rec.f_ant_estado_reclamo_wf(p_id_usuario, NULL, NULL, v_id_estado_actual,
                                                v_record_rec.id_proceso_wf, v_codigo_estado) THEN
                         raise exception 'Error al retroceder estado';
                END IF;
              END LOOP;
              --respuesta
              /*===================================================================================================*/
              SELECT res.nro_respuesta, res.id_proceso_wf, res.id_estado_wf
              INTO v_record_resp
              FROM rec.trespuesta res
              WHERE res.id_respuesta = v_record_ids.id_respuesta;

              SELECT
                 ps_id_tipo_estado,
                 ps_id_funcionario,
                 ps_id_usuario_reg,
                 ps_id_depto,
                 ps_codigo_estado,
                 ps_id_estado_wf_ant
              INTO
                 v_id_tipo_estado,
                 v_id_funcionario,
                 v_id_usuario_reg,
                 v_id_depto,
                 v_codigo_estado,
                 v_id_estado_wf_ant
              FROM wf.f_obtener_estado_ant_log_wf(v_record_resp.id_estado_wf);
              -- registramos estado pendiente_respuesta
              v_id_estado_actual = wf.f_registra_estado_wf(
                  v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                  v_id_funcionario,                --  funcionario del estado anterior
                  v_record_resp.id_estado_wf,       --  estado actual ...
                  v_record_resp.id_proceso_wf,      --  id del proceso actual
                  p_id_usuario,                    -- usuario que registra
                  NULL,
                  NULL,
                  v_id_depto,                       --depto del estado anterior
                  v_record_resp.nro_respuesta||'->[RETROCESO] - '|| '[ERROR AL ENVIAR RESPUESTA]',
                  '',
                  '',
                  '',
                  'cambio de estado forzoso',
                  'Cambio de Estado');
              IF  NOT rec.f_ant_estado_respuesta_wf(p_id_usuario, NULL, NULL, v_id_estado_actual,
                                                  v_record_resp.id_proceso_wf, v_codigo_estado) THEN
                       raise exception 'Error al retroceder estado';
              END IF;
            END IF;
        END LOOP;

        --Modificamos la alarma para informar a responsables de sac lo del incidente.
        IF(v_record_ids.contador > 0)THEN
          FOR v_record IN  (SELECT ta.id_alarma, ta.titulo_correo, ta.fecha_reg, ta.correos, tr.nro_respuesta, ta.desc_falla
                                FROM rec.trespuesta tr
                                INNER JOIN param.talarma ta ON ta.id_proceso_wf = tr.id_proceso_wf
                                WHERE (ta.estado_envio = 'falla' OR ta.pendiente <> 'no' ) AND
                                (ta.fecha_reg::date BETWEEN now()::date-1 AND now()::date+1)) LOOP

              v_ids_alarma[v_index] = v_record.id_alarma;
              v_nro_tramites[v_index] = v_record.nro_respuesta;
              v_titulo_correo[v_index] = v_record.titulo_correo;
              v_correo[v_index] = v_record.correos;
              v_fecha_reg[v_index] = v_record.fecha_reg;
			  v_desc_fallas[v_index] = v_record.desc_falla;

              v_index = v_index + 1;
          END LOOP;

          FOR v_index IN 1..array_length(v_ids_alarma,1) LOOP
              IF (v_desc_fallas[v_index] = 'SMTP connect() failed.') THEN
              	UPDATE param.talarma  SET
                  estado_envio = 'exito',
                  desc_falla = null,
                  pendiente = 'no'
                WHERE id_alarma = v_ids_alarma[v_index]::integer;
              ELSE
                v_cadena = substr(v_correo[v_index], 1, position(',' IN v_correo[v_index])-1);
                UPDATE param.talarma  SET
                  descripcion ='<div  style="font-size: 12px; color: #000080; font-family: Verdana, Arial;">
                                    <p>
                                        <span>De: <b>Sistema ERP BOA</b></span><br>
                                        <span>Fecha: '||v_fecha_reg[v_index]||'</span><br>
                                        <span>Asunto: '||v_titulo_correo[v_index]||'</span><br>
                                        <span>Para: "'||v_cadena||'" </span><br>
                                        <span>Cc: "sac@boa.bo" </span>
                                    </p>
                                </div><br><br>
                                <div style="font-size: 12px; color: #000080; font-family: Verdana, Arial;">
                                    <span><b>Estimados Señores:</b></span><br><br>
                                    <p><img src="../../../sis_reclamo/reportes/sac.png"></p><br><br>
                                    <span>Se presento un error al enviar el correo.</span><br><br>
                                    <p><img src="../../../sis_reclamo/media/error_mail.png"></p><br><br><br>
                                    <span>La falla se debe a un error de nombre de correo, pongase en contacto </span><br>
                                    <span>con el cliente para confirmar la veracidad del correo al que se envio la respuesta.</span><br><br>
                                    <span><b>Nro. Tramite:</b> </span>'||v_nro_tramites[v_index]||'
                                    <span><b>Tipo Error:</b> </span>'||v_desc_fallas[v_index]||'
                                </div>',
                  titulo_correo = regexp_replace(titulo_correo,'Respuesta al Reclamo','Error al enviar correo,'),
                  estado_envio = 'exito',
                  desc_falla = '',
                  pendiente = 'no',
                  correos = 'sac@boa.bo,(gvelasquez@boa.bo;franklin.e.a777@boa.bo)'
                WHERE id_alarma = v_ids_alarma[v_index]::integer;
              END IF;
          END LOOP;
        END IF;
    END IF;
	v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reenvio realizado con Exito');
    RETURN v_resp;
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