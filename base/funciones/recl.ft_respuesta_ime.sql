CREATE OR REPLACE FUNCTION "recl"."ft_respuesta_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		recl.ft_respuesta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'recl.trespuesta'
 AUTOR: 		 (admin)
 FECHA:	        09-08-2016 15:08:00
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
	v_id_respuesta	integer;
			    
BEGIN

    v_nombre_funcion = 'recl.ft_respuesta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'RECL_RES_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 15:08:00
	***********************************/

	if(p_transaccion='RECL_RES_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into recl.trespuesta(
			id_reclamo,
			recomendaciones,
			estado_reg,
			procedimiento,
			fecha_notificacion,
			nro_cite,
			respuesta,
			fecha_respuesta,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_reclamo,
			v_parametros.recomendaciones,
			'activo',
			v_parametros.procedimiento,
			v_parametros.fecha_notificacion,
			v_parametros.nro_cite,
			v_parametros.respuesta,
			v_parametros.fecha_respuesta,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_respuesta into v_id_respuesta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta almacenado(a) con exito (id_respuesta'||v_id_respuesta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_id_respuesta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'RECL_RES_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 15:08:00
	***********************************/

	elsif(p_transaccion='RECL_RES_MOD')then

		begin
			--Sentencia de la modificacion
			update recl.trespuesta set
			id_reclamo = v_parametros.id_reclamo,
			recomendaciones = v_parametros.recomendaciones,
			procedimiento = v_parametros.procedimiento,
			fecha_notificacion = v_parametros.fecha_notificacion,
			nro_cite = v_parametros.nro_cite,
			respuesta = v_parametros.respuesta,
			fecha_respuesta = v_parametros.fecha_respuesta,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_respuesta=v_parametros.id_respuesta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_parametros.id_respuesta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'RECL_RES_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 15:08:00
	***********************************/

	elsif(p_transaccion='RECL_RES_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from recl.trespuesta
            where id_respuesta=v_parametros.id_respuesta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_parametros.id_respuesta::varchar);
              
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
ALTER FUNCTION "recl"."ft_respuesta_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
