CREATE OR REPLACE FUNCTION "rec"."ft_motivo_anulado_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Gestión de Reclamos
 FUNCION: 		rec.ft_motivo_anulado_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tmotivo_anulado'
 AUTOR: 		 (admin)
 FECHA:	        12-10-2016 19:36:54
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
	v_id_motivo_anulado	integer;
			    
BEGIN

    v_nombre_funcion = 'rec.ft_motivo_anulado_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_RMA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:36:54
	***********************************/

	if(p_transaccion='REC_RMA_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.tmotivo_anulado(
			descripcion_motivo,
			estado_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.descripcion_motivo,
			'activo',
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_motivo_anulado into v_id_motivo_anulado;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','MotivoAnulado almacenado(a) con exito (id_motivo_anulado'||v_id_motivo_anulado||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_motivo_anulado',v_id_motivo_anulado::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'REC_RMA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:36:54
	***********************************/

	elsif(p_transaccion='REC_RMA_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tmotivo_anulado set
			descripcion_motivo = v_parametros.descripcion_motivo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_motivo_anulado=v_parametros.id_motivo_anulado;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','MotivoAnulado modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_motivo_anulado',v_parametros.id_motivo_anulado::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'REC_RMA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:36:54
	***********************************/

	elsif(p_transaccion='REC_RMA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tmotivo_anulado
            where id_motivo_anulado=v_parametros.id_motivo_anulado;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','MotivoAnulado eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_motivo_anulado',v_parametros.id_motivo_anulado::varchar);
              
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
ALTER FUNCTION "rec"."ft_motivo_anulado_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
