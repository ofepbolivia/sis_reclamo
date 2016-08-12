CREATE OR REPLACE FUNCTION "rec"."ft_cliente_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_cliente_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tcliente'
 AUTOR: 		 (admin)
 FECHA:	        12-08-2016 13:37:36
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

	v_nombre_funcion = 'rec.ft_cliente_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_CLI_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		12-08-2016 13:37:36
	***********************************/

	if(p_transaccion='REC_CLI_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cli.id_cliente,
						cli.genero,
						cli.ci,
						cli.email,
						cli.direccion,
						cli.celular,
						cli.nombre,
						cli.lugar_expedicion,
						cli.apellido_paterno,
						cli.telefono,
						cli.ciudad_residencia,
						cli.pais_residencia,
						cli.nacionalidad,
						cli.barrio_zona,
						cli.estado_reg,
						cli.apellido_materno,
						cli.id_usuario_ai,
						cli.fecha_reg,
						cli.usuario_ai,
						cli.id_usuario_reg,
						cli.fecha_mod,
						cli.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from rec.tcliente cli
						inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'REC_CLI_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		12-08-2016 13:37:36
	***********************************/

	elsif(p_transaccion='REC_CLI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cliente)
					    from rec.tcliente cli
					    inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
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
ALTER FUNCTION "rec"."ft_cliente_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
