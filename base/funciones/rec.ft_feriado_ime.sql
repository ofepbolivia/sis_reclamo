CREATE OR REPLACE FUNCTION "rec"."ft_feriado_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Gestión de Reclamos
 FUNCION: 		rec.ft_feriado_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tferiado'
 AUTOR: 		 (admin)
 FECHA:	        21-04-2017 20:09:06
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
	v_id_feriado	integer;
			    
BEGIN

    v_nombre_funcion = 'rec.ft_feriado_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_DAYF_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2017 20:09:06
	***********************************/

	if(p_transaccion='REC_DAYF_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.tferiado(
			dia,
			mes,
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.dia,
			v_parametros.mes,
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_feriado into v_id_feriado;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dias Feriados almacenado(a) con exito (id_feriado'||v_id_feriado||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_feriado',v_id_feriado::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'REC_DAYF_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2017 20:09:06
	***********************************/

	elsif(p_transaccion='REC_DAYF_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tferiado set
			dia = v_parametros.dia,
			mes = v_parametros.mes,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_feriado=v_parametros.id_feriado;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dias Feriados modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_feriado',v_parametros.id_feriado::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'REC_DAYF_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		21-04-2017 20:09:06
	***********************************/

	elsif(p_transaccion='REC_DAYF_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tferiado
            where id_feriado=v_parametros.id_feriado;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Dias Feriados eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_feriado',v_parametros.id_feriado::varchar);
              
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
ALTER FUNCTION "rec"."ft_feriado_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
