CREATE OR REPLACE FUNCTION "rec"."ft_reporte_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Gestión de Reclamos
 FUNCION: 		rec.ft_reporte_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.treporte'
 AUTOR: 		 (admin)
 FECHA:	        12-10-2016 19:21:51
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
	v_id_reporte	integer;
			    
BEGIN

    v_nombre_funcion = 'rec.ft_reporte_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_REP_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:21:51
	***********************************/

	if(p_transaccion='REC_REP_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into rec.treporte(
			agrupar_por,
			hoja_posicion,
			mostrar_codigo_cargo,
			mostrar_codigo_empleado,
			mostrar_doc_id,
			mostrar_nombre,
			numerar,
			ordenar_por,
			id_tipo_incidente,
			titulo_reporte,
			ancho_utilizado,
			ancho_total,
			estado_reg,
			control_reporte,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.agrupar_por,
			v_parametros.hoja_posicion,
			v_parametros.mostrar_codigo_cargo,
			v_parametros.mostrar_codigo_empleado,
			v_parametros.mostrar_doc_id,
			v_parametros.mostrar_nombre,
			v_parametros.numerar,
			v_parametros.ordenar_por,
			v_parametros.id_tipo_incidente,
			v_parametros.titulo_reporte,
			v_parametros.ancho_utilizado,
			v_parametros.ancho_total,
			'activo',
			v_parametros.control_reporte,
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_reporte into v_id_reporte;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reporte almacenado(a) con exito (id_reporte'||v_id_reporte||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_reporte',v_id_reporte::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'REC_REP_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:21:51
	***********************************/

	elsif(p_transaccion='REC_REP_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.treporte set
			agrupar_por = v_parametros.agrupar_por,
			hoja_posicion = v_parametros.hoja_posicion,
			mostrar_codigo_cargo = v_parametros.mostrar_codigo_cargo,
			mostrar_codigo_empleado = v_parametros.mostrar_codigo_empleado,
			mostrar_doc_id = v_parametros.mostrar_doc_id,
			mostrar_nombre = v_parametros.mostrar_nombre,
			numerar = v_parametros.numerar,
			ordenar_por = v_parametros.ordenar_por,
			id_tipo_incidente = v_parametros.id_tipo_incidente,
			titulo_reporte = v_parametros.titulo_reporte,
			ancho_utilizado = v_parametros.ancho_utilizado,
			ancho_total = v_parametros.ancho_total,
			control_reporte = v_parametros.control_reporte,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_reporte=v_parametros.id_reporte;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reporte modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_reporte',v_parametros.id_reporte::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'REC_REP_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		12-10-2016 19:21:51
	***********************************/

	elsif(p_transaccion='REC_REP_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.treporte
            where id_reporte=v_parametros.id_reporte;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','reporte eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_reporte',v_parametros.id_reporte::varchar);
              
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
ALTER FUNCTION "rec"."ft_reporte_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
