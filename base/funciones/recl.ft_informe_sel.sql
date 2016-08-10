CREATE OR REPLACE FUNCTION "recl"."ft_informe_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		recl.ft_informe_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'recl.tinforme'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'recl.ft_informe_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'RECL_INF_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 16:42:40
	***********************************/

	if(p_transaccion='RECL_INF_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						inf.id_informe,
						inf.id_funcionario,
						inf.id_compensacion,
						inf.sugerncia_respuesta,
						inf.antecedentes_informe,
						inf.id_reclamo,
						inf.conclusion_recomendacion,
						inf.fecha_informe,
						inf.nro_informe,
						inf.analisis_tecnico,
						inf.estado_reg,
						inf.id_usuario_ai,
						inf.id_usuario_reg,
						inf.fecha_reg,
						inf.usuario_ai,
						inf.fecha_mod,
						inf.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from recl.tinforme inf
						inner join segu.tusuario usu1 on usu1.id_usuario = inf.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = inf.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'RECL_INF_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		10-08-2016 16:42:40
	***********************************/

	elsif(p_transaccion='RECL_INF_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_informe)
					    from recl.tinforme inf
					    inner join segu.tusuario usu1 on usu1.id_usuario = inf.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = inf.id_usuario_mod
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
ALTER FUNCTION "recl"."ft_informe_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
