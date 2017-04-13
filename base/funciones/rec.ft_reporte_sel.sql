CREATE OR REPLACE FUNCTION rec.ft_reporte_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Reclamos
 FUNCION: 		rec.ft_reporte_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.treporte'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

BEGIN

	v_nombre_funcion = 'rec.ft_reporte_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_REP_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		12-10-2016 19:21:51
	***********************************/

	if(p_transaccion='REC_REP_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						rep.id_reporte,
						rep.agrupar_por,
						rep.hoja_posicion,
						rep.mostrar_codigo_cargo,
						rep.mostrar_codigo_empleado,
						rep.mostrar_doc_id,
						rep.mostrar_nombre,
						rep.numerar,
						rep.ordenar_por,
						rep.id_tipo_incidente,
						rep.titulo_reporte,
						rep.ancho_utilizado,
						rep.ancho_total,
						rep.estado_reg,
						rep.control_reporte,
						rep.id_usuario_ai,
						rep.fecha_reg,
						rep.usuario_ai,
						rep.id_usuario_reg,
						rep.fecha_mod,
						rep.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from rec.treporte rep
						inner join segu.tusuario usu1 on usu1.id_usuario = rep.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rep.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'REC_REP_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		12-10-2016 19:21:51
	***********************************/

	elsif(p_transaccion='REC_REP_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_reporte)
					    from rec.treporte rep
					    inner join segu.tusuario usu1 on usu1.id_usuario = rep.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rep.id_usuario_mod
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;