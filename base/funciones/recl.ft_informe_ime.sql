CREATE OR REPLACE FUNCTION "recl"."ft_informe_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		recl.ft_informe_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'recl.tinforme'
 AUTOR: 		 (admin)
 FECHA:	        10-08-2016 16:42:40
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
	v_id_informe	integer;
			    
BEGIN

    v_nombre_funcion = 'recl.ft_informe_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'RECL_INF_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 16:42:40
	***********************************/

	if(p_transaccion='RECL_INF_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into recl.tinforme(
			id_funcionario,
			id_compensacion,
			sugerncia_respuesta,
			antecedentes_informe,
			id_reclamo,
			conclusion_recomendacion,
			fecha_informe,
			nro_informe,
			analisis_tecnico,
			estado_reg,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_funcionario,
			v_parametros.id_compensacion,
			v_parametros.sugerncia_respuesta,
			v_parametros.antecedentes_informe,
			v_parametros.id_reclamo,
			v_parametros.conclusion_recomendacion,
			v_parametros.fecha_informe,
			v_parametros.nro_informe,
			v_parametros.analisis_tecnico,
			'activo',
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_informe into v_id_informe;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Informe almacenado(a) con exito (id_informe'||v_id_informe||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_id_informe::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'RECL_INF_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 16:42:40
	***********************************/

	elsif(p_transaccion='RECL_INF_MOD')then

		begin
			--Sentencia de la modificacion
			update recl.tinforme set
			id_funcionario = v_parametros.id_funcionario,
			id_compensacion = v_parametros.id_compensacion,
			sugerncia_respuesta = v_parametros.sugerncia_respuesta,
			antecedentes_informe = v_parametros.antecedentes_informe,
			id_reclamo = v_parametros.id_reclamo,
			conclusion_recomendacion = v_parametros.conclusion_recomendacion,
			fecha_informe = v_parametros.fecha_informe,
			nro_informe = v_parametros.nro_informe,
			analisis_tecnico = v_parametros.analisis_tecnico,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_informe=v_parametros.id_informe;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Informe modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_parametros.id_informe::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'RECL_INF_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 16:42:40
	***********************************/

	elsif(p_transaccion='RECL_INF_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from recl.tinforme
            where id_informe=v_parametros.id_informe;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Informe eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_parametros.id_informe::varchar);
              
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
ALTER FUNCTION "recl"."ft_informe_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
