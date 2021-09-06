CREATE OR REPLACE FUNCTION rec.ft_motivo_anulado_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestión de Reclamos
 FUNCION: 		rec.ft_motivo_anulado_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tmotivo_anulado'
 AUTOR: 		 (admin)
 FECHA:	        12-10-2016 19:36:54
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

	v_nombre_funcion = 'rec.ft_motivo_anulado_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_RMA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		12-10-2016 19:36:54
	***********************************/

	if(p_transaccion='REC_RMA_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						rma.id_motivo_anulado,
						rma.motivo,
                        rma.orden,
						rma.estado_reg,
						rma.fecha_reg,
						rma.usuario_ai,
						rma.id_usuario_reg,
						rma.id_usuario_ai,
						rma.fecha_mod,
						rma.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from rec.tmotivo_anulado rma
						inner join segu.tusuario usu1 on usu1.id_usuario = rma.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rma.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'REC_RMA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		12-10-2016 19:36:54
	***********************************/

	elsif(p_transaccion='REC_RMA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_motivo_anulado)
					    from rec.tmotivo_anulado rma
					    inner join segu.tusuario usu1 on usu1.id_usuario = rma.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rma.id_usuario_mod
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