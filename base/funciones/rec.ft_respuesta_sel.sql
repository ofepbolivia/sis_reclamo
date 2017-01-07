CREATE OR REPLACE FUNCTION rec.ft_respuesta_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
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

    v_record			record;

    v_filtro 			varchar;

    v_respuesta			record;
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

        	SELECT vfcl.id_oficina, vfcl.nombre_cargo,  vfcl.oficina_nombre,
            tf.id_funcionario, vfcl.desc_funcionario1/*, tr.id_reclamo*/ INTO v_record
            FROM segu.tusuario tu
            INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
            INNER JOIN orga.vfuncionario_cargo_lugar vfcl on vfcl.id_funcionario = tf.id_funcionario
            --INNER JOIN rec.treclamo tr on tr.id_usuario_reg = tu.id_usuario
            WHERE tu.id_usuario = p_id_usuario ;

            /*SELECT *
            INTO v_respuesta
            FROM rec.trespuesta res
            where	res.id_reclamo = v_record.id_reclamo;*/

            IF (p_id_usuario = 1 OR p_id_usuario = 18) THEN
            	v_filtro= '0 = 0 AND ';
            ELSIF (v_record.nombre_cargo='Especialista Atención al Cliente')THEN
            		v_filtro = '(vfc.id_oficina = '||v_record.id_oficina||' OR tew.id_funcionario = '||v_record.id_funcionario||') AND';
--                     AND res.estado in (''archivo_con_respuesta'',''respuesta_registrado_ripat'')
            ELSE
            	v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND ';
            END IF;

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
                        res.nro_respuesta,
                        vc.email
						from rec.trespuesta res
						inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
                        inner join rec.treclamo tr on tr.id_reclamo = res.id_reclamo
                        inner join rec.vcliente vc on vc.id_cliente = tr.id_cliente
                        left join wf.testado_wf tew on tew.id_estado_wf = res.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario
				        where '||v_filtro;

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
                        inner join rec.treclamo tr on tr.id_reclamo = res.id_reclamo
                        inner join rec.vcliente vc on vc.id_cliente = tr.id_cliente
                        left join wf.testado_wf tew on tew.id_estado_wf = res.id_estado_wf
                        LEFT JOIN wf.testado_wf tewf on tewf.id_estado_wf = tew.id_estado_anterior
                        LEFT JOIN orga.vfuncionario_cargo_lugar vfc on vfc.id_funcionario =  tewf.id_funcionario
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


           --Sentencia de la consulta
		  v_consulta:='select
						res.id_respuesta,
						res.id_reclamo,
						res.recomendaciones,
                        ''OB.GC.NE.''||res.nro_cite||''.''||pa.gestion::text as num_cite,
						--res.nro_cite,
						res.respuesta,
                        pxp.f_fecha_literal(res.fecha_respuesta) as fecha_respuesta,
						res.fecha_notificacion,
						res.tipo_respuesta,
                        res.asunto,
                        re.nro_tramite,
                      	re.estado,
                        initcap(cl.nombre_completo1) as nombre_completo1,
                          CASE
           			 			WHEN c.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''Señor''::text
            					WHEN c.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''Señora''::text
           					ELSE'' ''::text
        					END::character varying AS genero,
                        --pa.gestion,
                        CASE
                        	 WHEN res.procedente::text =ANY(ARRAY[''si''::CHARACTER VARYING,''SI''::character varying, ''si''::character varying]::text[]) THEN ''procedente''::text
                        	 WHEN res.procedente::text =ANY(ARRAY[''no''::CHARACTER VARYING,''NO''::character varying, ''no''::character varying]::text[]) THEN ''improcedente''::text
                        ELSE''''::text
                        END::character varying as prodedente,
                        tip.nombre_estado
                        from rec.trespuesta res
						inner join segu.tusuario usu1 on usu1.id_usuario = res.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = res.id_usuario_mod
				        inner join rec.treclamo re on re.id_reclamo = res.id_reclamo
                        inner join rec.tcliente c on c.id_cliente = re.id_cliente
                        inner join rec.vcliente cl on cl.id_cliente = re.id_cliente
                        left join orga.vfuncionario funapro on funapro.id_funcionario = re.id_funcionario_recepcion
                        inner join param.tgestion pa on pa.id_gestion = re.id_gestion
                        inner join wf.testado_wf eswf on eswf.id_estado_wf = res.id_estado_wf
                        inner join wf.ttipo_estado tip on tip.id_tipo_estado = eswf.id_tipo_estado
                    	where res.id_proceso_wf ='||v_parametros.id_proceso_wf;

                        raise notice '%', v_consulta;

            return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'REC_RES_QR_SEL'
 	#DESCRIPCION:  Generarar codigo QR doc
 	#AUTOR:		MAM
 	#FECHA:		11-08-2016 16:01:08
	***********************************/

    elsif(p_transaccion='REC_RES_QR_SEL')then
      begin
   		--Sentencia de la consulta
		  v_consulta:=' SELECT  resp.id_proceso_wf,
          						resp.id_respuesta,
          						''OB.GC.NE.''||resp.nro_cite||''.''||ge.gestion::text as num_cite,
          						recl.nro_frd,
          						of.nombre as  oficina,
          						resp.estado,
          						left(pe.nombre, 1)||''.''||left(pe.apellido_paterno, 1)||''.''||left(pe.apellido_materno, 1)||''.''::text as  iniciales_fun_reg,
          						left(fu.nombre,1)||''.''||left(fu.ap_paterno,1)||''.''||left(fu.ap_materno,1)||''.''::text as iniciales_fun_vis
                                FROM rec.treclamo recl
                                inner join rec.trespuesta resp on resp.id_reclamo = recl.id_reclamo
                                inner join orga.toficina  of on of.id_oficina = recl.id_oficina_registro_incidente
                                inner join segu.tusuario usu1 on usu1.id_usuario = resp.id_usuario_reg
                                inner join segu.tpersona pe on pe.id_persona = usu1.id_persona
                                inner join param.tgestion ge on ge.id_gestion = recl.id_gestion
                                inner join wf.testado_wf wfs on wfs.id_estado_wf = resp.id_estado_wf
                                inner join orga.vfuncionario_personareporte fu on fu.id_funcionario = wfs.id_funcionario
                                where resp.id_proceso_wf='||v_parametros.id_proceso_wf;

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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;