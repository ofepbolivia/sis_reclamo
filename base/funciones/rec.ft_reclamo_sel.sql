CREATE OR REPLACE FUNCTION rec.ft_reclamo_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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
	v_filtro			varchar;
    v_id_oficina		integer;

    v_dias_respuesta  	varchar;
    v_record 			record;

    v_id_usuario_rev	integer;
    v_id_usuario_pen	integer;

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

            --SELECT
            --CRITERIOS DE FILTRADO
            SELECT vfcl.id_oficina, vfcl.nombre_cargo,  vfcl.oficina_nombre,
            tf.id_funcionario, vfcl.desc_funcionario1 INTO v_record
            FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            WHERE tu.id_usuario = p_id_usuario ;

            IF (p_id_usuario = 1 OR p_id_usuario = 18) THEN
            	v_filtro= '0 = 0 AND ';

            ELSIF (v_record.nombre_cargo='Técnico Atención al Cliente')THEN
            	v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND ';
            ELSIF (v_record.nombre_cargo='Especialista Atención al Cliente')THEN
                    --Consulta que muestra el id_usuario del anterior estado
                    SELECT tu.id_usuario
            		INTO v_id_usuario_rev
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    WHERE tf.id_funcionario = (SELECT tew.id_funcionario
                    FROM wf.testado_wf tew
                    LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = tew.id_estado_wf
                    LEFT JOIN rec.treclamo  tr ON tr.id_estado_wf = te.id_estado_wf
                    WHERE tr.estado =  'pendiente_asignacion' LIMIT 1);

                    v_filtro = '(rec.id_usuario_mod = '||v_id_usuario_rev||' OR  tew.id_funcionario = '||v_record.id_funcionario||') AND';

                    /*IF (v_id_usuario_rev = v_id_usuario_pen) THEN
                    	v_filtro = 'rec.id_usuario_mod = '||v_id_usuario_rev--||' AND rec.estado = ''pendiente_asignacion''';
                    --v_filtro = '(vfc.id_oficina = '||v_record.id_oficina||' OR  tew.id_funcionario = '||v_record.id_funcionario||')';
                    ELSE
                   		v_filtro = ' tew.id_funcionario = '||v_record.id_funcionario;
            		END IF;
                    v_filtro = v_filtro||' AND';*/

            ELSE
            	v_filtro = 'rec.id_usuario_reg = '||p_id_usuario||
                ' AND rec.id_oficina_registro_incidente = '||v_record.id_oficina||' AND ';
                --' OR tew.id_funcionario = '||v_record.id_funcionario||' AND ';
               -- AND rec.id_oficina_registro_incidente = '||v_record.id_oficina||' AND ';
            END IF;

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
						rec.fecha_hora_vuelo,
						rec.origen,
						rec.nro_frd,
                        rec.correlativo_preimpreso_frd,
                        rec.fecha_limite_respuesta,
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
                        	rec.id_gestion,
                            rec.id_motivo_anulado,
                        med.nombre_medio as desc_nombre_medio,
                        	c.nombre_completo2 as desc_nom_cliente,
                        tip.nombre_incidente as desc_nombre_incidente,
                        of.nombre as desc_nombre_oficina,
                        ofi.nombre as desc_oficina_registro_incidente,
                        t.nombre_incidente as desc_sudnom_incidente,
                        fun.desc_funcionario1 as desc_nombre_funcionario,
                        fu.desc_funcionario1 as desc_nombre_fun_denun,
                        	tip.tiempo_respuesta,
                            rec.revisado,
                            rec.transito,
                            rec.f_dias_respuesta(now()::date, rec.fecha_limite_respuesta, ''DIAS_RESP'')::varchar as dias_respuesta,
                            rec.f_dias_respuesta(now()::date, rec.fecha_hora_recepcion::date, ''DIAS_INF'')::varchar as dias_informe,
                            ma.motivo as motivo_anulado,
                            res.nro_cite,
                            infor.conclusion_recomendacion ,
							res.recomendaciones,
                            c.genero,
                            c.ci,
                            c.telefono,
                            c.email,
                            c.ciudad_residencia,
                            rec.nro_guia_aerea,
                            fun.nombre_cargo,
                            fu.nombre_cargo as cargo

						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join orga.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf
                            LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                            LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo

				        where  '||v_filtro;
			--raise exception 'ordenacion: %',v_consulta;
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
        	raise notice 'que esta pasando: %',v_consulta;
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
			v_consulta:='select count(rec.id_reclamo)
			   			from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join orga.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left  join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf
                            LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                            LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_CRMG_SEL'
 	#DESCRIPCION:	Consulta de RMCGlobal
 	#AUTOR:		admin
 	#FECHA:		05-10-2016 12:00:59
	***********************************/
	elsif(p_transaccion='REC_CRMG_SEL')then

    	begin
            --SELECT
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
						rec.fecha_hora_vuelo,
						rec.origen,
						rec.nro_frd,
                        rec.correlativo_preimpreso_frd,
                        rec.fecha_limite_respuesta,
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
                        	rec.id_gestion,
                            rec.id_motivo_anulado,
                        med.nombre_medio as desc_nombre_medio,
                        	c.nombre_completo2 as desc_nom_cliente,
                        tip.nombre_incidente as desc_nombre_incidente,
                        of.nombre as desc_nombre_oficina,
                        ofi.nombre as desc_oficina_registro_incidente,
                        t.nombre_incidente as desc_sudnom_incidente,
                        fun.desc_funcionario1 as desc_nombre_funcionario,
                        fu.desc_funcionario1 as desc_nombre_fun_denun,
                        	tip.tiempo_respuesta,
                            rec.revisado,
                            rec.transito,
                            rec.f_dias_respuesta(now()::date, rec.fecha_limite_respuesta, ''DIAS_RESP'')::varchar as dias_respuesta,
                            rec.f_dias_respuesta(now()::date, rec.fecha_hora_recepcion::date, ''DIAS_INF'')::varchar as dias_informe,
                            ma.motivo as motivo_anulado,
                            res.nro_cite,
                            infor.conclusion_recomendacion ,
							res.recomendaciones,
                            c.genero,
                            c.ci,
                            c.telefono,
                            c.email,
                            c.ciudad_residencia,
                            rec.nro_guia_aerea,
                            fun.nombre_cargo

						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join orga.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf
                            LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                            LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
				        WHERE ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
        	raise notice 'que esta pasando: %',v_consulta;
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_REC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	elsif(p_transaccion='REC_CRMG_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(rec.id_reclamo)
			   			from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join orga.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf
                            LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                            LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_REPOR_SEL'
 	#DESCRIPCION:	Reporte reclamo doc
 	#AUTOR:		MMV
 	#FECHA:		27-10-2016 18:32:59
	***********************************/
     elsif(p_transaccion='REC_REPOR_SEL')then

    	begin
   			--Sentencia de la consulta
		  v_consulta:='select
						rec.id_reclamo,
						rec.id_proceso_wf,
						rec.id_estado_wf,
						rec.estado,
						rec.fecha_hora_incidente,
						rec.fecha_hora_recepcion,
						rec.estado_reg,
						rec.fecha_hora_vuelo,
						rec.origen,
						rec.nro_frd,
                        rec.correlativo_preimpreso_frd,
                        rec.fecha_limite_respuesta,
						rec.observaciones_incidente,
						rec.destino,
						rec.nro_att_canalizado,
                        rec.nro_tramite,
						rec.detalle_incidente,
						rec.nro_vuelo,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        med.nombre_medio as desc_nombre_medio,
                        cli.nombre_completo1 as desc_nom_cliente,
                        tip.nombre_incidente as desc_incidente,
                        t.nombre_incidente as desc_sudnom_incidente,
                        of.nombre as desc_oficina,
                        ofi.nombre as desc_oficina_registro_incidente,
                        fun.desc_funcionario1 as desc_nombre_funcionario,
                        fu.desc_funcionario1 as desc_nombre_fun_denun,
                        cl.nombre as nombre_cliente,
                        cli.apellidos,
                        cl.ci,
                        cl.celular,
                        cl.email,
                        lu.nombre as pais,
                        cl.ciudad_residencia as ciudad,
                        cl.direccion,
                        cl.barrio_zona,
                        cl.lugar_expedicion,
                        usu1.fecha_reg
                        from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        inner join rec.vcliente cli on cli.id_cliente = rec.id_cliente
                        inner join rec.tcliente cl on cl.id_cliente =rec.id_cliente
                        join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        inner join orga.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join orga.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        inner join param.tlugar lu on lu.id_lugar =cl.id_pais_residencia
           				where rec.id_proceso_wf ='||v_parametros.id_proceso_wf;

                        raise notice '%', v_consulta;

            return v_consulta;
            END;
     elsif(p_transaccion='REC_REST_SEL')then

          BEGIN
              v_consulta = 'SELECT
                            tr.nro_tramite AS tramite,
                            tr.id_estado_wf AS id_estado,
                            tr.id_proceso_wf AS id_proceso
                            FROM rec.treclamo tr
                            WHERE tr.id_reclamo = '||v_parametros.id_reclamo;

               return v_consulta;
         END;

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