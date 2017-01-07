CREATE OR REPLACE FUNCTION rec.ft_reclamo_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Reclamos
 FUNCION: 		rec.ft_reclamo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.treclamo'
 AUTOR: 		 (admin)
 FECHA:	        10-08-2016 18:32:59
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
	v_id_reclamo			integer;
    v_nro_tramite			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado 		varchar;

    v_codigo_tipo_proceso 	varchar;
    v_id_proceso_macro    	integer;
	v_num_reclamo			varchar;

    v_reclamo				record;
    v_id_tipo_estado		integer;
    v_pedir_obs		    	varchar;
    v_codigo_estado_siguiente varchar;
    v_acceso_directo  	varchar;
    v_clase   			varchar;
    v_parametros_ad   	varchar;
    v_tipo_noti  		varchar;
    v_titulo   			varchar;
    v_id_estado_actual	integer;
    v_id_depto				integer;
    v_obs					text;
    v_registros_proc	record;
    v_codigo_tipo_pro	varchar;
    v_codigo_llave		varchar;
    v_funcionario   	integer;
    v_gestion 			integer;
    v_registros_rec		record;


    v_id_funcionario    integer;
    v_id_usuario_reg	integer;
    v_id_estado_wf_ant  integer;
    v_operacion			varchar;

    v_record			record;
    v_nombre			varchar;
    v_revisado			varchar;

    v_fecha_limite			date;
    v_dias				integer;

    v_fecha_mod 		timestamp;
    v_anulado			integer=null;
    v_motivo_anulado	varchar;
    v_id_medio_reclamo	integer;
    v_cont_resp			integer;

    v_estado_wf			varchar;

    v_record_gestion	record;
    v_siguiente_estado 	varchar;

    --STADISTICA BEGIN
    	v_stadistica	record;
    	v_hombres			integer=0;
        v_mujeres			integer=0;
        v_genero			varchar;
    --END

BEGIN

    v_nombre_funcion = 'rec.ft_reclamo_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_REC_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	if(p_transaccion='REC_REC_INS')then
        begin

           -- obtener el codigo del tipo_proceso
           select   tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm, wf.ttipo_proceso tp
           where pm.codigo='REC' and tp.tabla='rec.vreclamo' and tp.estado_reg = 'activo' and tp.inicio = 'si';

           --Obtenemos la gestion

           select g.id_gestion
           into v_gestion
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);

           -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 v_parametros.id_funcionario_recepcion,
                 null,
                 'RECLAMO',
                 'SAC-REC'
            );

            --Control de fecha_limite de una respuesta, mas uno porque se cuenta a partir del dia siguiente habil.

            	IF 	(select v_parametros.id_tipo_incidente IN (4,6,37,38,48,50))THEN
                	v_dias = 10;
                ELSIF v_parametros.id_tipo_incidente=36 THEN
                	v_dias = 7;
                END IF;

                v_fecha_mod = v_parametros.fecha_hora_recepcion;

                IF(select date_part('dow',v_fecha_mod) IN (1, 2, 3, 4, 5) AND v_dias=10)THEN
                	v_dias = v_dias + 4;
                  	v_fecha_limite = v_fecha_mod + (v_dias||' day')::interval;

                ELSIF(select date_part('dow',v_fecha_mod) IN (1, 2, 3) AND v_dias=7)THEN
                	v_dias = v_dias + 2;
                    v_fecha_limite = v_fecha_mod + (v_dias||' day')::interval;

                ELSIF(select date_part('dow',v_fecha_mod) IN (4, 5) AND v_dias=7)THEN
                	v_dias = v_dias + 4;
                    v_fecha_limite = v_fecha_mod + (v_dias||' day')::interval;

                END IF;

            SELECT tmr.id_medio_reclamo into v_id_medio_reclamo
            FROM rec.tmedio_reclamo tmr
            WHERE tmr.codigo='FRD';

        	--Sentencia de la insercion
        	insert into rec.treclamo(
			id_tipo_incidente,
			id_subtipo_incidente,
			id_medio_reclamo,
			id_funcionario_recepcion,
			id_funcionario_denunciado,
			id_oficina_incidente,
			id_oficina_registro_incidente,
			id_proceso_wf,
			id_estado_wf,
			id_cliente,
			estado,
			fecha_hora_incidente,
			nro_ripat_att,
			nro_hoja_ruta,
			fecha_hora_recepcion,
			estado_reg,
			fecha_hora_vuelo,
			origen,
			nro_frd,
            correlativo_preimpreso_frd,
            transito,
            fecha_limite_respuesta,
			observaciones_incidente,
			destino,
			nro_pir,
			nro_frsa,
			nro_att_canalizado,
			nro_tramite,
			detalle_incidente,
			pnr,
			nro_vuelo,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod,
            id_gestion,
            id_motivo_anulado
          	) values(
			v_parametros.id_tipo_incidente,
			v_parametros.id_subtipo_incidente,
			v_id_medio_reclamo,
			v_parametros.id_funcionario_recepcion,
			v_parametros.id_funcionario_denunciado,
			v_parametros.id_oficina_registro_incidente,
			v_parametros.id_oficina_registro_incidente,
			v_id_proceso_wf,
			v_id_estado_wf,
			v_parametros.id_cliente,
			v_codigo_estado,
			v_parametros.fecha_hora_incidente,
			v_parametros.nro_ripat_att,
			v_parametros.nro_hoja_ruta,
			v_parametros.fecha_hora_recepcion,
			'activo',
			v_parametros.fecha_hora_vuelo,
			upper(v_parametros.origen),
			v_parametros.nro_frd,
            v_parametros.correlativo_preimpreso_frd,
            upper(v_parametros.transito),
            v_fecha_limite,
			v_parametros.observaciones_incidente,
			upper(v_parametros.destino),
			v_parametros.nro_pir,
			v_parametros.nro_frsa,
			v_parametros.nro_att_canalizado,
			v_nro_tramite,
			v_parametros.detalle_incidente,
			v_parametros.pnr,
			v_parametros.nro_vuelo,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			v_parametros._id_usuario_ai,
			null,
			null,
            v_gestion,
            v_anulado

			)RETURNING id_reclamo into v_id_reclamo;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos almacenado(a) con exito (id_reclamo'||v_id_reclamo||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_id_reclamo::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_estado_wf',v_id_estado_wf::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_proceso_wf',v_id_proceso_wf::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_momento', 'new');

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_REC_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	elsif(p_transaccion='REC_REC_MOD')then

		begin
			--Sentencia de la modificacion


            IF (v_parametros.id_medio_reclamo is null)THEN
            	SELECT tmr.id_medio_reclamo into v_id_medio_reclamo
            	FROM rec.tmedio_reclamo tmr
            	WHERE tmr.codigo='FRD';
            ELSE
            	v_id_medio_reclamo = v_parametros.id_medio_reclamo;
            END IF;

            --Control de fecha_limite de una respuesta, mas uno porque se cuenta a partir del dia siguiente habil.
            --begin

            	v_fecha_mod = v_parametros.fecha_hora_recepcion;
                --raise exception 'entra: %',v_fecha_mod;
            	IF 	(select v_parametros.id_tipo_incidente IN (4,6,37,38,48,50))THEN
                	v_dias = 10;
                ELSIF v_parametros.id_tipo_incidente=36 THEN
                	v_dias = 7;
                END IF;

                IF (select date_part('dow',v_fecha_mod) IN (1, 2, 3, 4, 5)  AND v_dias=10) THEN

                    v_dias = v_dias + 4;
                  	v_fecha_limite =  v_fecha_mod + (v_dias||' day')::interval;
                ELSIF(select date_part('dow',v_fecha_mod) IN (1, 2, 3) AND v_dias=7) THEN

                	v_dias = v_dias + 2;
                    v_fecha_limite = v_fecha_mod + (v_dias||' day')::interval;
                ELSIF(select date_part('dow',v_fecha_mod) IN (4, 5) AND v_dias=7) THEN

                	v_dias = v_dias + 4;
                    v_fecha_limite = v_fecha_mod + (v_dias||' day')::interval;
                END IF;

            --end
            -- Para cuando id_motivo_anulado es NULL
            --begin

            IF (v_parametros.id_motivo_anulado IS NULL) THEN
            	v_anulado = NULL;
            ELSE
            	v_anulado =  v_parametros.id_motivo_anulado;
            END IF;
            --end

			update rec.treclamo set
			id_tipo_incidente = v_parametros.id_tipo_incidente,
			id_subtipo_incidente = v_parametros.id_subtipo_incidente,
			id_medio_reclamo = v_id_medio_reclamo,
			id_funcionario_recepcion = v_parametros.id_funcionario_recepcion,
			id_funcionario_denunciado = v_parametros.id_funcionario_denunciado,
			id_oficina_incidente = v_parametros.id_oficina_incidente,
			id_oficina_registro_incidente = v_parametros.id_oficina_registro_incidente,
			--id_proceso_wf = v_parametros.id_proceso_wf,
			--id_estado_wf = v_parametros.id_estado_wf,
			id_cliente = v_parametros.id_cliente,
			--estado = v_parametros.estado,
			fecha_hora_incidente = v_parametros.fecha_hora_incidente,
			nro_ripat_att = v_parametros.nro_ripat_att,
			nro_hoja_ruta = v_parametros.nro_hoja_ruta,
			fecha_hora_recepcion = v_parametros.fecha_hora_recepcion,
			fecha_hora_vuelo = v_parametros.fecha_hora_vuelo,
			origen = upper(v_parametros.origen),
			nro_frd = v_parametros.nro_frd,
            correlativo_preimpreso_frd = v_parametros.correlativo_preimpreso_frd,
            fecha_limite_respuesta = v_fecha_limite,
			observaciones_incidente = v_parametros.observaciones_incidente,
			destino = upper(v_parametros.destino),
			nro_pir = v_parametros.nro_pir,
			nro_frsa = v_parametros.nro_frsa,
			nro_att_canalizado = v_parametros.nro_att_canalizado,
			--nro_tramite = v_parametros.nro_tramite,
            transito = upper(v_parametros.transito),
			detalle_incidente = v_parametros.detalle_incidente,
			pnr = v_parametros.pnr,
			nro_vuelo = upper(v_parametros.nro_vuelo),
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            id_motivo_anulado =  v_anulado

            where id_reclamo=v_parametros.id_reclamo;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_parametros.id_reclamo::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_momento', 'edit');

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_REC_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	elsif(p_transaccion='REC_REC_ELI')then

		begin
        select
            	tr.id_estado_wf,
            	tr.id_proceso_wf,
            	tr.estado,
                tr.id_reclamo,
                tr.nro_tramite,
                tr.id_funcionario_recepcion,
                tr.nro_tramite
        into
            	v_id_estado_wf,
                v_id_proceso_wf,
                v_codigo_estado,
                v_id_reclamo,
                v_nro_tramite,
                v_funcionario,
                v_nro_tramite
        from rec.treclamo tr
        where tr.id_reclamo = v_parametros.id_reclamo;

        if v_codigo_estado != 'borrador' then
        	raise exception 'Solo pueden anularce reclamos del estado borrador';
        end if;

        -- obtenemos el tipo del estado anulado

        select
        	te.id_tipo_estado
        into
        	v_id_tipo_estado
        from wf.tproceso_wf pw
        inner join wf.ttipo_proceso tp on pw.id_tipo_proceso = tp.id_tipo_proceso
        inner join wf.ttipo_estado te on te.id_tipo_proceso = tp.id_tipo_proceso and te.codigo = 'anulado'
        where pw.id_proceso_wf = v_id_proceso_wf;


        if v_id_tipo_estado is null  then
        	raise exception 'No se parametrizo el estado "anulado" para reclamos';
        end if;


        -- pasamos el reclamo  al siguiente anulado
        --raise exception 'revisado: %', v_id_tipo_estado;
        v_id_estado_actual =  wf.f_registra_estado_wf(
        	v_id_tipo_estado,
        	v_funcionario,
            v_id_estado_wf,
            v_id_proceso_wf,
            p_id_usuario,
            v_parametros._id_usuario_ai,
            v_parametros._nombre_usuario_ai,
            v_id_depto,
            'Eliminacion de Reclamo'|| COALESCE(v_nro_tramite,'--')
        );

        -- actualiza estado en la solicitud

        update rec.treclamo  set
        	id_estado_wf =  v_id_estado_actual,
        	estado = 'anulado',
        	id_usuario_mod=p_id_usuario,
        	fecha_mod=now()
        where id_reclamo  = v_parametros.id_reclamo;

            --Sentencia de la eliminacion
			/*delete from rec.treclamo
            where id_reclamo=v_parametros.id_reclamo;*/

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_parametros.id_reclamo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'REC_ANTEREC_IME'
 	#DESCRIPCION:	Anterior estado de un Reclamo
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/
    elseif(p_transaccion='REC_ANTEREC_IME') then
    	begin

        	v_operacion = 'anterior';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

            --obtenermos datos basicos de reclamo
            select
                rec.id_reclamo,
                rec.id_proceso_wf,
                rec.estado,
                pwf.id_tipo_proceso
            into v_registros_rec
            from rec.treclamo  rec
            inner  join wf.tproceso_wf pwf  on  pwf.id_proceso_wf = rec.id_proceso_wf
            where rec.id_proceso_wf  = v_parametros.id_proceso_wf;

            v_id_proceso_wf = v_registros_rec.id_proceso_wf;

            select r.nro_tramite, r.id_motivo_anulado, r.id_estado_wf
            into v_reclamo
            from rec.treclamo r
            where	r.id_reclamo = v_registros_rec.id_reclamo;

        SELECT count(*)
        INTO v_cont_resp
        FROM rec.trespuesta
        WHERE id_reclamo = v_registros_rec.id_reclamo;
		IF(v_cont_resp = 0)	THEN
            IF  v_operacion = 'anterior' THEN
                --------------------------------------------------
                --Retrocede al estado inmediatamente anterior
                -------------------------------------------------
               	--recuperaq estado anterior segun Log del WF
                --raise exception 'v_parametros.id_estado_wf: %',v_parametros.id_estado_wf;
                  SELECT

                     ps_id_tipo_estado,
                     ps_id_funcionario,
                     ps_id_usuario_reg,
                     ps_id_depto,
                     ps_codigo_estado,
                     ps_id_estado_wf_ant
                  into
                     v_id_tipo_estado,
                     v_id_funcionario,
                     v_id_usuario_reg,
                     v_id_depto,
                     v_codigo_estado,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);

                  --Verificar si el estado actual es anulado y el anterior estado registro ripat
            	  --para poner el id_motivo_anulado en NULL
                  --begin


                     SELECT tte.codigo INTO v_motivo_anulado
					 FROM wf.testado_wf tew
					 INNER JOIN wf.ttipo_estado tte ON tte.id_tipo_estado = tew.id_tipo_estado
					 WHERE tew.id_estado_wf = v_reclamo.id_estado_wf;

                     IF(v_motivo_anulado = 'anulado')THEN
                     	UPDATE rec.treclamo  SET id_motivo_anulado = NULL
                        WHERE id_reclamo = v_registros_rec.id_reclamo;
                     END IF;


            	  --end;

                  --raise exception 'v_id_tipo_estado: % + v_id_funcionario: % + v_id_usuario_reg: % + v_id_depto: % +v_codigo_estado: % +v_id_estado_wf_ant: % ', v_id_tipo_estado, v_id_funcionario,v_id_usuario_reg,v_id_depto, v_codigo_estado, v_id_estado_wf_ant;

                  select
                    ew.id_proceso_wf
                  into
                    v_id_proceso_wf
                  from wf.testado_wf ew
                  where ew.id_estado_wf= v_id_estado_wf_ant;
            END IF;



             --configurar acceso directo para la alarma
                 v_acceso_directo = '';
                 v_clase = '';
                 v_parametros_ad = '';
                 v_tipo_noti = 'notificacion';
                 v_titulo  = 'Visto Bueno';


               IF   v_codigo_estado_siguiente not in('borrador','pendiente_revision','pendiente_respuesta','derivado','anulado')   THEN
                      v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/RegistroReclamos.php';
                     v_clase = 'RegistroReclamos';
                     v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||v_id_proceso_wf::varchar||'"}}';
                     v_tipo_noti = 'notificacion';
                     v_titulo  = 'Visto Bueno';

               END IF;


              -- registra nuevo estado

              v_id_estado_actual = wf.f_registra_estado_wf(
                  v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                  v_id_funcionario,                --  funcionario del estado anterior
                  v_parametros.id_estado_wf,       --  estado actual ...
                  v_id_proceso_wf,                 --  id del proceso actual
                  p_id_usuario,                    -- usuario que registra
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_depto,                       --depto del estado anterior
                  '[RETROCESO] '|| v_parametros.obs,
                  v_acceso_directo,
                  v_clase,
                  v_parametros_ad,
                  v_tipo_noti,
                  v_titulo);

                IF  not rec.f_ant_estado_reclamo_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                   raise exception 'Error al retroceder estado';

                END IF;


             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respu	esta
                return v_resp;
    	ELSIF (v_registros_rec.estado IN
        ('en_avenimiento', 'formulacion_cargos', 'resolucion_administrativa',
        'recurso_revocatorio','recurso_jerarquico', 'contencioso_administrativo')) THEN
        	IF  v_operacion = 'anterior' THEN
                --------------------------------------------------
                --Retrocede al estado inmediatamente anterior
                -------------------------------------------------
               	--recuperaq estado anterior segun Log del WF
                --raise exception 'v_parametros.id_estado_wf: %',v_parametros.id_estado_wf;
                  SELECT

                     ps_id_tipo_estado,
                     ps_id_funcionario,
                     ps_id_usuario_reg,
                     ps_id_depto,
                     ps_codigo_estado,
                     ps_id_estado_wf_ant
                  into
                     v_id_tipo_estado,
                     v_id_funcionario,
                     v_id_usuario_reg,
                     v_id_depto,
                     v_codigo_estado,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);

                  --Verificar si el estado actual es anulado y el anterior estado registro ripat
            	  --para poner el id_motivo_anulado en NULL
                  --begin


                     SELECT tte.codigo INTO v_motivo_anulado
					 FROM wf.testado_wf tew
					 INNER JOIN wf.ttipo_estado tte ON tte.id_tipo_estado = tew.id_tipo_estado
					 WHERE tew.id_estado_wf = v_reclamo.id_estado_wf;

                     IF(v_motivo_anulado = 'anulado')THEN
                     	UPDATE rec.treclamo  SET id_motivo_anulado = NULL
                        WHERE id_reclamo = v_registros_rec.id_reclamo;
                     END IF;


            	  --end;

                  --raise exception 'v_id_tipo_estado: % + v_id_funcionario: % + v_id_usuario_reg: % + v_id_depto: % +v_codigo_estado: % +v_id_estado_wf_ant: % ', v_id_tipo_estado, v_id_funcionario,v_id_usuario_reg,v_id_depto, v_codigo_estado, v_id_estado_wf_ant;

                  select
                    ew.id_proceso_wf
                  into
                    v_id_proceso_wf
                  from wf.testado_wf ew
                  where ew.id_estado_wf= v_id_estado_wf_ant;
            END IF;



             --configurar acceso directo para la alarma
                 v_acceso_directo = '';
                 v_clase = '';
                 v_parametros_ad = '';
                 v_tipo_noti = 'notificacion';
                 v_titulo  = 'Visto Bueno';


               IF   v_codigo_estado_siguiente not in('borrador','pendiente_revision','pendiente_respuesta','derivado','anulado')   THEN
                      v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/RegistroReclamos.php';
                     v_clase = 'RegistroReclamos';
                     v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||v_id_proceso_wf::varchar||'"}}';
                     v_tipo_noti = 'notificacion';
                     v_titulo  = 'Visto Bueno';

               END IF;


              -- registra nuevo estado

              v_id_estado_actual = wf.f_registra_estado_wf(
                  v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                  v_id_funcionario,                --  funcionario del estado anterior
                  v_parametros.id_estado_wf,       --  estado actual ...
                  v_id_proceso_wf,                 --  id del proceso actual
                  p_id_usuario,                    -- usuario que registra
                  v_parametros._id_usuario_ai,
                  v_parametros._nombre_usuario_ai,
                  v_id_depto,                       --depto del estado anterior
                  '[RETROCESO] '|| v_parametros.obs,
                  v_acceso_directo,
                  v_clase,
                  v_parametros_ad,
                  v_tipo_noti,
                  v_titulo);

                IF  not rec.f_ant_estado_reclamo_wf(p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                   raise exception 'Error al retroceder estado';

                END IF;


             -- si hay mas de un estado disponible  preguntamos al usuario
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respu	esta
                return v_resp;
        ELSE
        	RAISE EXCEPTION 'Tiene respuestas pendientes en proceso para, %', v_reclamo.nro_tramite::varchar;
		END IF;
        end;

    /*********************************
 	#TRANSACCION:  'REC_SIGEREC_IME'
 	#DESCRIPCION:	Siguiente estado de un Reclamo
 	#AUTOR:		admin
 	#FECHA:		21-09-2016 11:32:59
	***********************************/
    elseif(p_transaccion='REC_SIGEREC_IME') then
    	begin

    	/*   PARAMETROS
        $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
        $this->setParametro('id_depto_wf','id_depto_wf','int4');
        $this->setParametro('obs','obs','text');
        $this->setParametro('json_procesos','json_procesos','text');
        */

          --recupera toda la tabla reclamos
          select recl.*
          into v_reclamo
          from rec.treclamo recl
          where id_proceso_wf = v_parametros.id_proceso_wf_act;

          select
            ew.id_tipo_estado ,
            te.pedir_obs,
            ew.id_estado_wf
           into
            v_id_tipo_estado,
            v_pedir_obs,
            v_id_estado_wf

          from wf.testado_wf ew
          inner join wf.ttipo_estado te on te.id_tipo_estado = ew.id_tipo_estado
          where ew.id_estado_wf =  v_parametros.id_estado_wf_act;


           -- obtener datos tipo estado siguiente //codigo=borrador
           select te.codigo into
             v_codigo_estado_siguiente
           from wf.ttipo_estado te
           where te.id_tipo_estado = v_parametros.id_tipo_estado;


           IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN
           	 v_id_depto = v_parametros.id_depto_wf;
           END IF;

           IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
           	 v_obs = v_parametros.obs;
           ELSE
           	 v_obs='---';
           END IF;

             --configurar acceso directo para la alarma
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Bueno';


             IF   v_codigo_estado_siguiente not in('borrador','pendiente_revision','registrado_ripat','pendiente_informacion','anulado')   THEN

                  v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/Reclamo.php';
                  v_clase = 'Reclamo';
                  v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||
                  v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;
             --RAISE EXCEPTION 'v_id_depto: %, %',v_id_depto, v_parametros.id_funcionario_wf;
             -- hay que recuperar el supervidor que seria el estado inmediato...
            	v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                             v_parametros.id_funcionario_wf,
                                                             v_parametros.id_estado_wf_act,
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_reclamo.nro_tramite,'--')||' Obs:'||v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);


         		IF rec.f_procesar_estados_reclamo(p_id_usuario,
           									v_parametros._id_usuario_ai,
                                            v_parametros._nombre_usuario_ai,
                                            v_id_estado_actual,
                                            v_parametros.id_proceso_wf_act,
                                            v_codigo_estado_siguiente) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';

          		END IF;


          --------------------------------------
          -- registra los procesos disparados
          --------------------------------------

          FOR v_registros_proc in ( select * from json_populate_recordset(null::wf.proceso_disparado_wf, v_parametros.json_procesos::json)) LOOP

               --get cdigo tipo proceso
               select
                  tp.codigo,
                  tp.codigo_llave
               into
                  v_codigo_tipo_pro,
                  v_codigo_llave
               from wf.ttipo_proceso tp
                where  tp.id_tipo_proceso =  v_registros_proc.id_tipo_proceso_pro;


               -- disparar creacion de procesos seleccionados

              SELECT
                       ps_id_proceso_wf,
                       ps_id_estado_wf,
                       ps_codigo_estado
                 into
                       v_id_proceso_wf,
                       v_id_estado_wf,
                       v_codigo_estado
              FROM wf.f_registra_proceso_disparado_wf(
                       p_id_usuario,
                       v_parametros._id_usuario_ai,
                       v_parametros._nombre_usuario_ai,
                       v_id_estado_actual,
                       v_registros_proc.id_funcionario_wf_pro,
                       v_registros_proc.id_depto_wf_pro,
                       v_registros_proc.obs_pro,
                       v_codigo_tipo_pro,
                       v_codigo_tipo_pro);

           END LOOP;

          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado del Reclamo)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);

          -- Devuelve la respuesta
          return v_resp;
        end;

     /*********************************
    #TRANSACCION:  'REC_STADISTICA_GET'
 	#DESCRIPCION:	Obtiene el numero de dias de respuesta
 	#AUTOR:		Franklin Espinoza Alvarez
 	#FECHA:		20-10-2016 10:01:08
	***********************************/
    elsif(p_transaccion='REC_STADISTICA_GET')then

		begin

			FOR v_stadistica IN (SELECT * FROM rec.treclamo  WHERE id_gestion=13) LOOP

                SELECT c.genero INTO v_genero
                FROM rec.tcliente c
                WHERE c.id_cliente=v_stadistica.id_cliente;

                IF v_genero = 'VARON' THEN
                	v_hombres=v_hombres+1;
                ELSE
                	v_mujeres=v_mujeres+1;
                END IF;
            END LOOP;
            --v_resp = '';
            --RAISE EXCEPTION 'DATOS: %,%',v_hombres,v_mujeres;
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Transaccion Exitosa');
            v_resp = pxp.f_agrega_clave(v_resp,'v_hombres',v_hombres::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_mujeres',v_mujeres::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    elseif (p_transaccion='REC_FUNNOM_GET')then
   		begin

			select tf.desc_funcionario1 into v_nombre
            from orga.vfuncionario tf
            where tf.id_funcionario = v_parametros.id_funcionario;
            --raise exception 'fun:%',v_nombre;
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Nombre Funcionario');
            v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_nombre);


            --Devuelve la respuesta
            return v_resp;

		end;
    elseif (p_transaccion='REC_DATOFI_GET')then
   		begin

			SELECT vfcl.id_oficina, vfcl.oficina_nombre, tf.id_funcionario, vfcl.desc_funcionario1, vfcl.nombre_cargo
            INTO v_record
			FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario;

            SELECT tr.estado INTO v_estado_wf
            FROM rec.treclamo tr
            WHERE tr.id_estado_wf = v_parametros.id_usuario;

           	SELECT g.id_gestion, g.gestion
           	INTO v_record_gestion
           	FROM param.tgestion g
           	WHERE g.gestion = EXTRACT(YEAR FROM current_date);



            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Datos de Oficina y Funcionario que registra Reclamos');
            v_resp = pxp.f_agrega_clave(v_resp,'id_oficina',v_record.id_oficina::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'oficina_nombre',v_record.oficina_nombre);
            v_resp = pxp.f_agrega_clave(v_resp,'id_funcionario',v_record.id_funcionario::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'desc_funcionario1',v_record.desc_funcionario1);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_cargo',v_record.nombre_cargo);
            v_resp = pxp.f_agrega_clave(v_resp,'estado',v_estado_wf);
            v_resp = pxp.f_agrega_clave(v_resp,'id_gestion',v_record_gestion.id_gestion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'gestion',v_record_gestion.gestion::varchar);
            --Devuelve la respuesta de petición
            return v_resp;

		end;
    elsif (p_transaccion='REC_REV_IME')then

		begin

            select
              tr.revisado,
              tr.id_proceso_wf
            into
              v_registros_rec
            from rec.treclamo tr
            where tr.id_reclamo = v_parametros.id_reclamo;

            IF (v_registros_rec.revisado = 'si') THEN
               v_revisado = 'no';
            ELSE
               v_revisado = 'si';
            END IF;

            update rec.treclamo  tr set
               revisado = v_revisado,
               id_usuario_mod=p_id_usuario,
               fecha_mod=now(),
               id_usuario_ai = v_parametros._id_usuario_ai,
               usuario_ai = v_parametros._nombre_usuario_ai
             where tr.id_reclamo  = v_parametros.id_reclamo;

            --modifica el proeso wf para actulizar el mismo campo
             update wf.tproceso_wf  set
               revisado_asistente = v_revisado
             where id_proceso_wf  = v_registros_rec.id_proceso_wf;


            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','el reclamo fue marcado como revisado');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_parametros.id_reclamo::varchar);

            --Devuelve la respuesta
            return v_resp;
        end;
	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

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