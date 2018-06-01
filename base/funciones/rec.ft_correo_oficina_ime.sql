CREATE OR REPLACE FUNCTION "rec"."ft_correo_oficina_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_correo_oficina_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tcorreo_oficina'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        11-05-2018 22:27:57
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				11-05-2018 22:27:57								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tcorreo_oficina'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_correo_att	integer;
			    
BEGIN

    v_nombre_funcion = 'rec.ft_correo_oficina_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_cof_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		11-05-2018 22:27:57
	***********************************/

	if(p_transaccion='REC_cof_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.tcorreo_oficina(
			correo,
			id_oficina,
			estado_reg,
			id_funcionario,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.correo,
			v_parametros.id_oficina,
			'activo',
			v_parametros.id_funcionario,
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_correo_att into v_id_correo_att;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CorreoOficina almacenado(a) con exito (id_correo_att'||v_id_correo_att||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_att',v_id_correo_att::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'REC_cof_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		11-05-2018 22:27:57
	***********************************/

	elsif(p_transaccion='REC_cof_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tcorreo_oficina set
			correo = v_parametros.correo,
			id_oficina = v_parametros.id_oficina,
			id_funcionario = v_parametros.id_funcionario,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_correo_att=v_parametros.id_correo_att;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CorreoOficina modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_att',v_parametros.id_correo_att::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'REC_cof_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		11-05-2018 22:27:57
	***********************************/

	elsif(p_transaccion='REC_cof_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tcorreo_oficina
            where id_correo_att=v_parametros.id_correo_att;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CorreoOficina eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_att',v_parametros.id_correo_att::varchar);
              
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
ALTER FUNCTION "rec"."ft_correo_oficina_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
