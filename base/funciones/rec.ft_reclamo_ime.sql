CREATE OR REPLACE FUNCTION "rec"."ft_reclamo_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

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
	v_id_reclamo	integer;
			    
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
			hora_vuelo,
			origen,
			nro_frd,
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
			v_parametros.id_proceso_wf,
			v_parametros.id_estado_wf,
			v_parametros.id_cliente,
			v_parametros.estado,
			v_parametros.fecha_hora_incidente,
			v_parametros.nro_ripat_att,
			v_parametros.nro_hoja_ruta,
			v_parametros.fecha_hora_recepcion,
			'activo',
			v_parametros.hora_vuelo,
			v_parametros.origen,
			v_parametros.nro_frd,
			v_parametros.observaciones_incidente,
			v_parametros.destino,
			v_parametros.nro_pir,
			v_parametros.nro_frsa,
			v_parametros.nro_att_canalizado,
			v_parametros.nro_tramite,
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
			update rec.treclamo set
			id_tipo_incidente = v_parametros.id_tipo_incidente,
			id_subtipo_incidente = v_parametros.id_subtipo_incidente,
			id_medio_reclamo = v_parametros.id_medio_reclamo,
			id_funcionario_recepcion = v_parametros.id_funcionario_recepcion,
			id_funcionario_denunciado = v_parametros.id_funcionario_denunciado,
			id_oficina_incidente = v_parametros.id_oficina_incidente,
			id_oficina_registro_incidente = v_parametros.id_oficina_registro_incidente,
			id_proceso_wf = v_parametros.id_proceso_wf,
			id_estado_wf = v_parametros.id_estado_wf,
			id_cliente = v_parametros.id_cliente,
			estado = v_parametros.estado,
			fecha_hora_incidente = v_parametros.fecha_hora_incidente,
			nro_ripat_att = v_parametros.nro_ripat_att,
			nro_hoja_ruta = v_parametros.nro_hoja_ruta,
			fecha_hora_recepcion = v_parametros.fecha_hora_recepcion,
			hora_vuelo = v_parametros.hora_vuelo,
			origen = v_parametros.origen,
			nro_frd = v_parametros.nro_frd,
			observaciones_incidente = v_parametros.observaciones_incidente,
			destino = v_parametros.destino,
			nro_pir = v_parametros.nro_pir,
			nro_frsa = v_parametros.nro_frsa,
			nro_att_canalizado = v_parametros.nro_att_canalizado,
			nro_tramite = v_parametros.nro_tramite,
			detalle_incidente = v_parametros.detalle_incidente,
			pnr = v_parametros.pnr,
			nro_vuelo = v_parametros.nro_vuelo,
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
			--Sentencia de la eliminacion
			delete from rec.treclamo
            where id_reclamo=v_parametros.id_reclamo;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Reclamos eliminado(a)'); 
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "rec"."ft_reclamo_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
