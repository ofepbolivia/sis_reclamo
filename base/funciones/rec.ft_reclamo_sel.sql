CREATE OR REPLACE FUNCTION "rec"."ft_reclamo_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Reclamos
 FUNCION: 		rec.ft_reclamo_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.treclamo'
 AUTOR: 		 (admin)
 FECHA:	        10-08-2016 18:32:59
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

	v_nombre_funcion = 'rec.ft_reclamo_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_REC_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	if(p_transaccion='REC_REC_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						rec.id_reclamo,
						rec.id_tipo_incidente,
						rec.id_subtipo_incidente,
						rec.id_medio_reclamo,
						rec.id_funcionario_recepcion,
						rec.id_funcionario_denunciado,
						rec.id_oficina_incidente,
						rec.id_oficina_registro_incidente,
						rec.id_proceso_wf,
						rec.id_estado_wf,
						rec.id_cliente,
						rec.estado,
						rec.fecha_hora_incidente,
						rec.nro_ripat_att,
						rec.nro_hoja_ruta,
						rec.fecha_hora_recepcion,
						rec.estado_reg,
						rec.hora_vuelo,
						rec.origen,
						rec.nro_frd,
						rec.observaciones_incidente,
						rec.destino,
						rec.nro_pir,
						rec.nro_frsa,
						rec.nro_att_canalizado,
						rec.nro_tramite,
						rec.detalle_incidente,
						rec.pnr,
						rec.nro_vuelo,
						rec.id_usuario_reg,
						rec.fecha_reg,
						rec.usuario_ai,
						rec.id_usuario_ai,
						rec.fecha_mod,
						rec.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        med.nombre_medio as desc_nombre_medio,
                        cli.nombre_completo2 as desc_nom_cliente,
                        tip.nombre_incidente as desc_nombre_incidente,
                        of.nombre as desc_nombre_oficina,
                        t.nombre_incidente as desc_sudnom_incidente,
                        fun.desc_funcionario1 as desc_nombre_funcionario,
                        fu.desc_funcionario1 as desc_nombre_fun_denun
						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.tcliente cl on cl.id_cliente =rec.id_cliente
                        inner join rec.vcliente cli on cli.id_cliente = cli.id_cliente
                        join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        inner join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                        inner join rec.ttipo_incidente t on t.fk_tipo_incidente = rec.id_tipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        inner join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
				        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'REC_REC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	elsif(p_transaccion='REC_REC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_reclamo)
			   			from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.tcliente cl on cl.id_cliente =rec.id_cliente
                        inner join rec.vcliente cli on cli.id_cliente = cli.id_cliente
                        join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        inner join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                        inner join rec.ttipo_incidente t on t.fk_tipo_incidente = rec.id_tipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        inner join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
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
ALTER FUNCTION "rec"."ft_reclamo_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
