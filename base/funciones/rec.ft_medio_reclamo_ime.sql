CREATE OR REPLACE FUNCTION "rec"."ft_medio_reclamo_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		medio reclamo
 FUNCION: 		rec.ft_medio_reclamo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tmedio_reclamo'
 AUTOR: 		 (admin)
 FECHA:	        10-08-2016 20:59:01
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
	v_id_medio_reclamo	integer;
			    
BEGIN

    v_nombre_funcion = 'rec.ft_medio_reclamo_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'rc_rec_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 20:59:01
	***********************************/

	if(p_transaccion='rc_rec_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.tmedio_reclamo(
			llave,
			nombre_medio,
			obs,
			id_forenkey,
			codigo,
			tabla
          	) values(
			v_parametros.llave,
			v_parametros.nombre_medio,
			v_parametros.obs,
			v_parametros.id_forenkey,
			v_parametros.codigo,
			v_parametros.tabla
							
			
			
			)RETURNING id_medio_reclamo into v_id_medio_reclamo;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reclamo almacenado(a) con exito (id_medio_reclamo'||v_id_medio_reclamo||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_id_medio_reclamo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'rc_rec_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 20:59:01
	***********************************/

	elsif(p_transaccion='rc_rec_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tmedio_reclamo set
			llave = v_parametros.llave,
			nombre_medio = v_parametros.nombre_medio,
			obs = v_parametros.obs,
			id_forenkey = v_parametros.id_forenkey,
			codigo = v_parametros.codigo,
			tabla = v_parametros.tabla,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_medio_reclamo=v_parametros.id_medio_reclamo;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reclamo modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_parametros.id_medio_reclamo::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'rc_rec_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 20:59:01
	***********************************/

	elsif(p_transaccion='rc_rec_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tmedio_reclamo
            where id_medio_reclamo=v_parametros.id_medio_reclamo;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reclamo eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_parametros.id_medio_reclamo::varchar);
              
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
ALTER FUNCTION "rec"."ft_medio_reclamo_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
