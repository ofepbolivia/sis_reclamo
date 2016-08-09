CREATE OR REPLACE FUNCTION "recl"."ft_respuesta_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		recl.ft_respuesta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'recl.trespuesta'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'recl.ft_respuesta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'RECL_RES_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 15:08:00
	***********************************/

	if(p_transaccion='RECL_RES_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						res.id_respuesta,
						res.id_reclamo,
						res.recomendaciones,
						res.estado_reg,
						res.procedimiento,
						res.fecha_notificacion,
						res.nro_cite,
						res.respuesta,
						res.fecha_respuesta,
						res.usuario_ai,
						res.fecha_reg,
						res.id_usuario_reg,
						res.id_usuario_ai,
						res.fecha_mod,
						res.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from recl.trespuesta res
						inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'RECL_RES_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		09-08-2016 15:08:00
	***********************************/

	elsif(p_transaccion='RECL_RES_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_respuesta)
					    from recl.trespuesta res
					    inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
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
ALTER FUNCTION "recl"."ft_respuesta_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
