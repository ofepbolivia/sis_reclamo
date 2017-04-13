CREATE OR REPLACE FUNCTION rec.ft_respuesta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_respuesta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.trespuesta'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 16:01:08
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
	v_id_respuesta			integer;
    v_cite			        varchar;
    v_fecha_respuesta 		date;

    v_nro_respuesta			varchar;

    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado 		varchar;
    v_codigo_tipo_proceso	varchar;
    v_id_proceso_macro		integer;
	v_id_gestion	  		record;

    v_respuesta 			record;

    v_operacion 			varchar;
    v_registros_res			record;
    v_id_tipo_estado		integer;
    v_id_funcionario		integer;
    v_id_usuario_reg		integer;
    v_id_depto				integer;

    v_id_estado_wf_ant		integer;
    v_acceso_directo		varchar;
    v_clase					varchar;
    v_parametros_ad			varchar;
    v_tipo_noti				varchar;
    v_titulo				varchar;

    v_id_estado_actual		integer;
    v_pedir_obs				varchar;
    v_codigo_estado_siguiente	varchar;
    v_obs					varchar;
    v_registros_proc		record;
    v_codigo_tipo_pro		varchar;
    v_codigo_llave			varchar;

    v_id_reclamo			integer;
    v_record				record;

    v_res 					varchar;
    v_valid					varchar;
BEGIN

    v_nombre_funcion = 'rec.ft_respuesta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_RES_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/
	--raise exception 'tabla: %, transaccion: %, p_administrador: %, p_id_usuario: %',p_tabla,p_transaccion, p_administrador,p_id_usuario;
	if(p_transaccion='REC_RES_INS')then
        begin

        	v_resp = rec.f_inserta_respuesta(p_administrador, p_id_usuario,hstore(v_parametros));
            return v_resp;

        	/*--Inicio del workflow
            select * into v_reclamo
            from rec.treclamo r
            where r.id_reclamo = v_parametros.id_reclamo;

            --select r.id_gestion,r.nro_tramite into v_id_gestion from rec.treclamo r where r.id_reclamo = v_parametros.id_reclamo;

            select   tp.codigo, pm.id_proceso_macro
           	into v_codigo_tipo_proceso, v_id_proceso_macro
           	from  wf.tproceso_macro pm, wf.ttipo_proceso tp
           	where pm.id_proceso_macro=38 and tp.tabla='rec.trespuesta' and tp.estado_reg = 'activo' and tp.inicio = 'no';



            /*v_num_respuesta =   param.f_obtener_correlativo(
	                  v_reclamo.nro_tramite,
	                  v_reclamo.id_gestion,-- par_id,
	                  null, --id_uo
	                  null,    -- id_depto
	                  p_id_usuario,
	                  'RESP',
	                  null );*/
            --raise exception 'v_codigo_tipo_proceso: % ::: v_id_proceso_macro: %',v_codigo_tipo_proceso, v_id_proceso_macro;
            --raise exception 'gestion: %',v_codigo_tipo_proceso;
            SELECT
                 ps_num_tramite,
                 ps_id_proceso_wf,
                 ps_id_estado_wf,
                 ps_codigo_estado
              into
                 v_nro_respuesta,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 14,
                 v_codigo_tipo_proceso,
                 v_reclamo.id_funcionario_recepcion,
                 null,
                 'RESPUESTA',
                 'SAC-RES'
            );

            --raise exception 'v_nro_respuesta: % ::: v_codigo_estado: % ::: v_id_proceso_wf: %',v_nro_respuesta, v_codigo_estado, v_id_proceso_wf;
        	--Sentencia de la insercion
        	insert into rec.trespuesta(
			id_reclamo,
			recomendaciones,
			nro_cite,
			respuesta,
			fecha_respuesta,
			estado_reg,
			procedente,
			fecha_notificacion,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			fecha_mod,
			id_usuario_mod,
            asunto,
            tipo_respuesta,
            id_proceso_wf,
            id_estado_wf,
            estado,
            nro_respuesta
          	) values(
			v_parametros.id_reclamo,
			v_parametros.recomendaciones,
			upper(v_parametros.nro_cite),
			v_parametros.respuesta,
			v_parametros.fecha_respuesta,
			'activo',
			v_parametros.procedente,
			v_parametros.fecha_notificacion,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null,
            v_parametros.asunto,
            v_parametros.tipo_respuesta,
            v_id_proceso_wf,
            v_id_estado_wf,
            v_codigo_estado,
            v_nro_respuesta
			)RETURNING id_respuesta into v_id_respuesta;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta almacenado(a) con exito (id_respuesta'||v_id_respuesta||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_id_respuesta::varchar);

            --Devuelve la respuesta
            return v_resp;*/

		end;

	/*********************************
 	#TRANSACCION:  'REC_RES_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/

	elsif(p_transaccion='REC_RES_MOD')then

		begin

        	v_res = replace(v_parametros.respuesta,' align="right" ',' ');
            v_res = replace(v_res,' align="center" ',' ');
            v_res = replace(v_res,' align="left" ',' ');
            v_res = replace(v_res,'text-align: center','text-align: justify;');
            v_res = replace(v_res,'text-align: left;','text-align: justify;');
            v_res = replace(v_res,'text-align: right;','text-align: justify;');
            v_res = replace(v_res,'text-align:center','text-align: justify;');
            v_res = replace(v_res,'text-align:left;','text-align: justify;');
            v_res = replace(v_res,'text-align:right;','text-align: justify;');
            --IF position('text-align:justify;' in v_parametros.respuesta)<=0 THEN
                v_res = replace(v_res,'<p class="MsoNormal" style="','<p class="MsoNormal" style="text-align:justify; ');
            --END IF;
			--Sentencia de la modificacion
			update rec.trespuesta set
			id_reclamo = v_parametros.id_reclamo,
			recomendaciones = v_parametros.recomendaciones,
			nro_cite = upper(v_parametros.nro_cite),
			--respuesta = v_parametros.respuesta,
            respuesta = v_res,
			fecha_respuesta = v_parametros.fecha_respuesta,
			procedente = v_parametros.procedente,
			--fecha_notificacion = v_parametros.fecha_notificacion,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            asunto = v_parametros.asunto,
            tipo_respuesta = v_parametros.tipo_respuesta
			where id_respuesta=v_parametros.id_respuesta;

			/*UPDATE rec.treclamo SET
            	revisado = 'proceso'
            WHERE id_reclamo=v_parametros.id_reclamo;*/

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_parametros.id_respuesta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_RES_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/

	elsif(p_transaccion='REC_RES_ELI')then

		begin
			--Sentencia de la eliminacion

            /*select r.id_reclamo into v_id_reclamo
            from rec.trespuesta r
            where id_respuesta=v_parametros.id_respuesta;

            update rec.treclamo
            set cont_respuesta = cont_respuesta - 1
            where id_reclamo = v_id_reclamo;*/

			delete from rec.trespuesta
            where id_respuesta=v_parametros.id_respuesta;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_parametros.id_respuesta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'REC_ANTERES_IME'
 	#DESCRIPCION:	Siguiente estado de un Reclamo
 	#AUTOR:		admin
 	#FECHA:		21-10-2016 11:32:59
	***********************************/
    elseif(p_transaccion='REC_ANTERES_IME') then


    	begin
        v_operacion = 'anterior';

        IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
           v_operacion = v_parametros.estado_destino;
        END IF;

        --recueprar datos del reclamo
        --raise exception 'id_funcionario_usu: %', v_parametros.id_funcionario_usu;
        --obtenermos datos basicos
        select
            res.id_respuesta,
            res.id_proceso_wf,
            res.estado,
            pwf.id_tipo_proceso
        into
            v_registros_res

        from rec.trespuesta  res
        inner  join wf.tproceso_wf pwf  on  pwf.id_proceso_wf = res.id_proceso_wf
        where res.id_proceso_wf  = v_parametros.id_proceso_wf;

        v_id_proceso_wf = v_registros_res.id_proceso_wf;

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

            IF  not rec.f_ant_estado_respuesta_wf(p_id_usuario,
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


          --Devuelve la respuesta
            return v_resp;

        end;

    /*********************************
 	#TRANSACCION:  'REC_SIGERES_IME'
 	#DESCRIPCION:	Siguiente estado de una Respuesta
 	#AUTOR:		admin
 	#FECHA:		21-10-2016 11:32:59
	***********************************/
    elseif(p_transaccion='REC_SIGERES_IME') then
    	begin


        	/*   PARAMETROS

        $this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
        $this->setParametro('id_tipo_estado','id_tipo_estado','int4');
        $this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
        $this->setParametro('id_depto_wf','id_depto_wf','int4');
        $this->setParametro('obs','obs','text');
        $this->setParametro('json_procesos','json_procesos','text');
        */
          --recupera todos los registros de respuesta
          select res.*
          into v_respuesta
          from rec.trespuesta res
          where id_proceso_wf = v_parametros.id_proceso_wf_act;
          --raise exception 'proceso actua: %',v_parametros.id_proceso_wf_act;
          select
            ew.id_tipo_estado,
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
             --RAISE EXCEPTION 'v_codigo_estado_siguiente: %,v_id_tipo_estado: %, v_id_estado_wf: %, v_parametros.id_proceso_wf_act: %, v_parametros.id_estado_wf_act: %',v_codigo_estado_siguiente,v_id_tipo_estado,v_id_estado_wf, v_parametros.id_proceso_wf_act, v_parametros.id_estado_wf_act;

             --Sirve para configurar el acceso o enlace dentro del correo electronico
             IF   v_codigo_estado_siguiente not in('elaboracion_respuesta','respuesta_aprobada','respuesta_enviada','vobo_respuesta')   THEN

                  v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/Reclamo.php';
                  v_clase = 'Reclamo';
                  v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||
                  v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;

			--RAISE EXCEPTION 'LLEGA: %,%,%,%, % , %, %', v_parametros.id_funcionario_wf, v_parametros.id_tipo_estado,v_parametros.id_estado_wf_act,v_parametros.id_proceso_wf_act,v_id_depto,v_parametros._id_usuario_ai,v_parametros._nombre_usuario_ai;
             -- hay que recuperar el supervidor que seria el estado inmediato...

            v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                             v_parametros.id_funcionario_wf,
                                                             v_parametros.id_estado_wf_act,
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
            												 v_parametros._id_usuario_ai,
                                                             COALESCE(v_parametros._nombre_usuario_ai,''),
                                                             NULL,
                                                             COALESCE(v_respuesta.nro_respuesta,'--')||' Obs:'||v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);




             	--archivado_concluido
         --RAISE EXCEPTION 'p_id_usuario:%,id_usuario:%,NOMBRE:%,ACTUAL:%,PROCESO:%,ESTADO:%',p_id_usuario,v_parametros._id_usuario_ai,v_parametros._nombre_usuario_ai,v_id_estado_actual,v_parametros.id_proceso_wf_act,v_codigo_estado_siguiente;
         IF rec.f_procesar_estados_respuesta(p_id_usuario,
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
			/*
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
*/

          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de la planilla)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);


          -- Devuelve la respuesta
          return v_resp;
        end;

    /*********************************
    #TRANSACCION:  'REC_CITE_GET'
 	#DESCRIPCION:	Genera el numero de CITE para Respuesta
 	#AUTOR:		Franklin Espinoza Alvarez
 	#FECHA:		20-10-2016 10:01:08
	***********************************/
    elsif(p_transaccion='REC_CITE_GET')then

		begin

			SELECT tr.nro_cite,tr.fecha_respuesta INTO v_record
            FROM rec.trespuesta tr
            ORDER BY tr.id_respuesta DESC LIMIT 1;
            --~ ^[0-9]+[(\/)(\-)(\.)][0-9a-zA-Z]+$
            IF(EXTRACT(YEAR FROM v_record.fecha_respuesta)=EXTRACT(YEAR FROM CURRENT_DATE))THEN
                IF(v_record.nro_cite LIKE '%/%')THEN
                    v_cite = SUBSTRING(v_record.nro_cite FROM 1 FOR POSITION('/' IN v_record.nro_cite)-1)::integer + 1;
                ELSIF(v_record.nro_cite LIKE '%-%')THEN
                    v_cite = SUBSTRING(v_record.nro_cite FROM 1 FOR POSITION('-' IN v_record.nro_cite)-1)::integer + 1;
                ELSIF(v_record.nro_cite LIKE '%.%')THEN
                    v_cite = SUBSTRING(v_record.nro_cite FROM 1 FOR POSITION('.' IN v_record.nro_cite)-1)::integer + 1;
                ELSE
                    v_cite = v_record.nro_cite::integer + 1;
                END IF;
            ELSE
            	v_cite=0;
            END IF;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Numero Cite');
            v_resp = pxp.f_agrega_clave(v_resp,'v_cite',v_cite);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
    #TRANSACCION:  'RES_VALIDAR_CITE'
 	#DESCRIPCION:	Validar el numero de CITE para Respuesta en caso de duplicados.
 	#AUTOR:		Franklin Espinoza Alvarez
 	#FECHA:		21-02-2017 10:01:08
	***********************************/
    elsif(p_transaccion='RES_VALIDAR_CITE')then

		begin

			SELECT count(tr.id_respuesta) AS contador, tr.nro_respuesta INTO v_record
            FROM rec.trespuesta tr
            WHERE tr.nro_cite = v_parametros.nro_cite
            GROUP BY tr.nro_respuesta;

            IF(v_record.contador >= 1)THEN
            	v_valid = 'true';
            ELSE
            	v_valid = 'false';
            END IF;


            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Numero de Cite Valido');
            v_resp = pxp.f_agrega_clave(v_resp,'v_valid',v_valid);
            v_resp = pxp.f_agrega_clave(v_resp,'v_nro_respuesta',v_record.nro_respuesta);

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