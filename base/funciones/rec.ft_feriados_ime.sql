CREATE OR REPLACE FUNCTION rec.ft_feriados_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_feriados_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tferiados'
 AUTOR: 		 (breydi.vasquez)
 FECHA:	        09-05-2018 20:44:22
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				09-05-2018 20:44:22								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tferiados'	
 #
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

    v_nombre_funcion = 'rec.ft_feriados_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_TFDOS_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		breydi.vasquez	
 	#FECHA:		09-05-2018 20:44:22
	***********************************/

	if(p_transaccion='REC_TFDOS_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.tferiados(
			tipo,
			fecha,
			id_lugar,
			descripcion,
			estado_reg,
			estado,
			id_origen,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			v_parametros.tipo,
			v_parametros.fecha,
			v_parametros.id_lugar,
			v_parametros.descripcion,
			'activo',
			v_parametros.estado,
			v_parametros.id_origen,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_feriado into v_id_feriado;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Feriados almacenado(a) con exito (id_feriado'||v_id_feriado||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_feriado',v_id_feriado::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'REC_TFDOS_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		breydi.vasquez	
 	#FECHA:		09-05-2018 20:44:22
	***********************************/

	elsif(p_transaccion='REC_TFDOS_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tferiados set
			tipo = v_parametros.tipo,
			fecha = v_parametros.fecha,
			id_lugar = v_parametros.id_lugar,
			descripcion = v_parametros.descripcion,
			estado = v_parametros.estado,
			id_origen = v_parametros.id_origen,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_feriado=v_parametros.id_feriado;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Feriados modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_feriado',v_parametros.id_feriado::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'REC_TFDOS_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		breydi.vasquez	
 	#FECHA:		09-05-2018 20:44:22
	***********************************/

	elsif(p_transaccion='REC_TFDOS_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tferiados
            where id_feriado=v_parametros.id_feriado;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Feriados eliminado(a)'); 
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;