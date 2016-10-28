CREATE OR REPLACE FUNCTION "rec"."ft_respuesta_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_respuesta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.trespuesta'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 16:01:08
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
    v_gaf				varchar;

BEGIN

	v_nombre_funcion = 'rec.ft_respuesta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_RES_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/

	if(p_transaccion='REC_RES_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						res.id_respuesta,
						res.id_reclamo,
						res.recomendaciones,
						res.nro_cite,
						res.respuesta,
						res.fecha_respuesta,
						res.estado_reg,
						res.procedente,
						res.fecha_notificacion,
						res.id_usuario_ai,
						res.id_usuario_reg,
						res.usuario_ai,
						res.fecha_reg,
						res.fecha_mod,
						res.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        res.tipo_respuesta,
                        res.asunto,
                        res.id_proceso_wf,
						res.id_estado_wf,
                        res.estado,
                        res.nro_respuesta
						from rec.trespuesta res
						inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
				        where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'REC_RES_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/

	elsif(p_transaccion='REC_RES_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_respuesta)
					    from rec.trespuesta res
					    inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_REPORDOC_SEL'
 	#DESCRIPCION:	Reportes en word
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 16:01:08
	***********************************/
    elsif(p_transaccion='REC_REPORDOC_SEL')then

    	begin

           --recupera el gerente reclamo ...
          v_gaf = orga.f_obtener_gerente_x_codigo_uo('gerente_financiero', now()::Date);
           --Sentencia de la consulta
		  v_consulta:='
 select
						DISTINCT ON (res.id_respuesta) res.id_respuesta,
						res.id_reclamo,
						res.recomendaciones,
						res.nro_cite,
						res.respuesta,
						res.fecha_respuesta,
						res.estado_reg,
						res.procedente,
						res.fecha_notificacion,
						res.id_usuario_ai,
						res.id_usuario_reg,
						res.usuario_ai,
						res.fecha_reg,
						res.fecha_mod,
						res.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        res.tipo_respuesta,
                        res.asunto,
                        re.nro_tramite,
                        re.id_proceso_wf,
                        re.id_estado_wf,
                        re.estado,
                        cl.nombre_completo1,
                        cl.nombre,
                      	funapro.desc_funcionario1 as aprobador,
                        upper(orga.f_get_cargo_x_funcionario_str(funapro.id_funcionario,CURRENT_DATE)) as cargo_aprobador,
                        fun.desc_funcionario1 as desc_funcionario,
                    	uo.nombre_unidad,
                          CASE
           			 			WHEN c.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''Sr''::text
            					WHEN c.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''Sra''::text
           					ELSE ''''::text
        					END::character varying AS genero,
                            CASE
           			 			WHEN c.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''Apreciado''::text
            					WHEN c.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''Apreciada''::text
           					ELSE ''''::text
        					END::character varying AS genero_apre
                        from rec.trespuesta res
						inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
				        inner join rec.treclamo re on re.id_reclamo = res.id_reclamo
                        inner join rec.tcliente c on c.id_cliente = re.id_cliente
                        inner join rec.vcliente cl on cl.id_cliente = re.id_cliente
                        inner join orga.vfuncionario fun on fun.id_funcionario = re.id_funcionario_recepcion
                        left join orga.vfuncionario funapro on funapro.id_funcionario = re.id_funcionario_recepcion
                        inner join orga.tuo_funcionario uof ON uof.id_funcionario = fun.id_funcionario
                        inner join  orga.tuo uo ON uo.id_uo = orga.f_get_uo_gerencia(uof.id_uo, NULL::integer, NULL::date)
                        where re.id_proceso_wf ='||v_parametros.id_proceso_wf;

                        raise notice '%', v_consulta;

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
ALTER FUNCTION "rec"."ft_respuesta_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
