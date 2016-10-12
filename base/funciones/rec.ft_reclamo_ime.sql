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
    v_gestion 			varchar;

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
        	--Obtenemos la gestion
            --SELECT EXTRACT(YEAR FROM v_parametros.fecha_hora_recepcion)
            --into v_gestion ;

           -- obtener el codigo del tipo_proceso
           select   tp.codigo, pm.id_proceso_macro
           into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm, wf.ttipo_proceso tp
           where pm.codigo='REC' and tp.tabla='rec.vreclamo' and tp.estado_reg = 'activo' and tp.inicio = 'si';

           --raise exception 'Nro. %', v_codigo_tipo_proceso;
           --raise exception 'CODIGO: %',v_num_reclamo;
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
                 14,
                 v_codigo_tipo_proceso,
                 v_parametros.id_funcionario_recepcion,
                 null,
                 'Reclamaciones',
                 'Rec-9-2016'
            );

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
			id_usuario_mod
          	) values(
			v_parametros.id_tipo_incidente,
			v_parametros.id_subtipo_incidente,
			v_parametros.id_medio_reclamo,
			v_parametros.id_funcionario_recepcion,
			v_parametros.id_funcionario_denunciado,
			v_parametros.id_oficina_incidente,
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
			v_parametros.origen,
			v_parametros.nro_frd,
            v_parametros.correlativo_preimpreso_frd,
            v_parametros.fecha_limite_respuesta,
			v_parametros.observaciones_incidente,
			v_parametros.destino,
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
			null



			)RETURNING id_reclamo into v_id_reclamo;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos almacenado(a) con exito (id_reclamo'||v_id_reclamo||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_id_reclamo::varchar);

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
            --RAISE EXCEPTION 'ENTRA';
			update rec.treclamo set
			id_tipo_incidente = v_parametros.id_tipo_incidente,
			id_subtipo_incidente = v_parametros.id_subtipo_incidente,
			id_medio_reclamo = v_parametros.id_medio_reclamo,
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
            fecha_limite_respuesta = v_parametros.fecha_limite_respuesta,
			observaciones_incidente = v_parametros.observaciones_incidente,
			destino = upper(v_parametros.destino),
			nro_pir = v_parametros.nro_pir,
			nro_frsa = v_parametros.nro_frsa,
			nro_att_canalizado = v_parametros.nro_att_canalizado,
			--nro_tramite = v_parametros.nro_tramite,
			detalle_incidente = v_parametros.detalle_incidente,
			pnr = v_parametros.pnr,
			nro_vuelo = upper(v_parametros.nro_vuelo),
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai

            where id_reclamo=v_parametros.id_reclamo;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_reclamo',v_parametros.id_reclamo::varchar);

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

      	  --recupera id_tipo_estado=775,pedir_obs=no,id_estado_wf=377307 actuales del reclamo seleccionado
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


         --RAISE EXCEPTION '%', v_parametros.id_estado_wf_act;
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


             --'elaboracion_respuesta','revision_respuesta','respuesta_revisada','respuesta_enviada'
             IF   v_codigo_estado_siguiente not in('borrador','registrado_ripat','pendiente_inf','anulado')   THEN

                  v_acceso_directo = '../../../sis_reclamo/vista/Reclamo/Reclamo.php';
                  v_clase = 'Reclamo';
                  v_parametros_ad = '{filtro_directo:{campo:"rec.id_proceso_wf",valor:"'||
                  v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;


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



         IF rec.f_procesar_estados(p_id_usuario,
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
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado de la planilla)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');


          -- Devuelve la respuesta
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