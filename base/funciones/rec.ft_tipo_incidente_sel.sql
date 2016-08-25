CREATE OR REPLACE FUNCTION "rec"."ft_tipo_incidente_sel"(
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_tipo_incidente_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.ttipo_incidente'
 AUTOR: 		 (admin)
 FECHA:	        23-08-2016 19:24:46
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
	v_where				varchar;
  v_join varchar;

BEGIN

	v_nombre_funcion = 'rec.ft_tipo_incidente_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_RTI_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-08-2016 19:24:46
	***********************************/
  if(p_transaccion='REC_RTI_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						rti.id_tipo_incidente,
						rti.fk_tipo_incidente,
						rti.estado_reg,
						rti.tiempo_respuesta,
						rti.nivel,
						rti.nombre_incidente,
						rti.fecha_reg,
						rti.usuario_ai,
						rti.id_usuario_reg,
						rti.id_usuario_ai,
						rti.id_usuario_mod,
						rti.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod
						from rec.ttipo_incidente rti
						inner join segu.tusuario usu1 on usu1.id_usuario = rti.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rti.id_usuario_mod
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

	end;
	/*********************************
 	#TRANSACCION:  'REC_RTI_ARB_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		23-08-2016 19:24:46
	***********************************/
	elseif(p_transaccion='REC_RTI_ARB_SEL')then

    	begin
    	        if(v_parametros.id_padre = '%') then
                v_where := ' rti.fk_tipo_incidente is NULL';
                v_join := 'LEFT';
              else
                v_where := ' rti.fk_tipo_incidente = '||v_parametros.id_padre;
                v_join := 'INNER';
              end if;
    		--Sentencia de la consulta
			v_consulta:='select
						rti.id_tipo_incidente,
						rti.fk_tipo_incidente,
						rti.estado_reg,
						rti.tiempo_respuesta,
						rti.nivel,
						rti.nombre_incidente,
						rti.fecha_reg,
						rti.usuario_ai,
						rti.id_usuario_reg,
						rti.id_usuario_ai,
						rti.id_usuario_mod,
						rti.fecha_mod,
						usu1.cuenta as usr_reg,
						case
              when ( rti.fk_tipo_incidente is null )then
                ''raiz''::varchar
              ELSE
                ''hijo''::varchar
              END as tipo_nodo
						from rec.ttipo_incidente rti
						inner join segu.tusuario usu1 on usu1.id_usuario = rti.id_usuario_reg
				    where '||v_where||'
				    ORDER BY rti.id_tipo_incidente';


			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'REC_RTI_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		23-08-2016 19:24:46
	***********************************/

	elsif(p_transaccion='REC_RTI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_tipo_incidente)
					    from rec.ttipo_incidente rti
					    inner join segu.tusuario usu1 on usu1.id_usuario = rti.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rti.id_usuario_mod
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
ALTER FUNCTION "rec"."ft_tipo_incidente_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
