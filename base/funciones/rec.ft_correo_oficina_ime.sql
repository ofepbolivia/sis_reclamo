CREATE OR REPLACE FUNCTION rec.ft_correo_oficina_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
	v_id_correo_oficina	integer;
			    
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
			fecha_mod,
            fecha_ini,
            fecha_fin
          	) values(
			v_parametros.correo,
			v_parametros.id_oficina,
			'activo',
			upper(v_parametros.id_funcionario),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null,
            v_parametros.fecha_ini,
            v_parametros.fecha_fin
							
			
			
			)RETURNING id_correo_oficina into v_id_correo_oficina;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correo Oficina almacenado(a) con exito (id_correo_oficina'||v_id_correo_oficina||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_oficina',v_id_correo_oficina::varchar);

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
			id_funcionario = upper(v_parametros.id_funcionario),
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            fecha_ini = v_parametros.fecha_ini,
            fecha_fin = v_parametros.fecha_fin
			where id_correo_oficina=v_parametros.id_correo_oficina;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CorreoOficina modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_oficina',v_parametros.id_correo_oficina::varchar);
               
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
            where id_correo_oficina=v_parametros.id_correo_oficina;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','CorreoOficina eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_correo_oficina',v_parametros.id_correo_oficina::varchar);
              
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