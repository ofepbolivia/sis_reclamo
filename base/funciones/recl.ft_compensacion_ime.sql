CREATE OR REPLACE FUNCTION "recl"."ft_compensacion_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		recl.ft_compensacion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'recl.tcompensacion'
 AUTOR: 		 (admin)
 FECHA:	        09-08-2016 13:50:44
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
	v_id_compensacion	integer;
			    
BEGIN

    v_nombre_funcion = 'recl.ft_compensacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'RECL_COM_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 13:50:44
	***********************************/

	if(p_transaccion='RECL_COM_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into recl.tcompensacion(
			nombre,
			estado_reg,
			codigo,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_ai,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.nombre,
			'activo',
			v_parametros.codigo,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_compensacion into v_id_compensacion;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion almacenado(a) con exito (id_compensacion'||v_id_compensacion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_id_compensacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'RECL_COM_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 13:50:44
	***********************************/

	elsif(p_transaccion='RECL_COM_MOD')then

		begin
			--Sentencia de la modificacion
			update recl.tcompensacion set
			nombre = v_parametros.nombre,
			codigo = v_parametros.codigo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_compensacion=v_parametros.id_compensacion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_parametros.id_compensacion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'RECL_COM_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 13:50:44
	***********************************/

	elsif(p_transaccion='RECL_COM_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from recl.tcompensacion
            where id_compensacion=v_parametros.id_compensacion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_parametros.id_compensacion::varchar);
              
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
ALTER FUNCTION "recl"."ft_compensacion_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
