CREATE OR REPLACE FUNCTION rec.ft_informe_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_informe_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tinforme'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 01:52:07
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
  v_gaf					varchar;

BEGIN

  v_nombre_funcion = 'rec.ft_informe_sel';
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
   #TRANSACCION:  'REC_INFOR_SEL'
   #DESCRIPCION:	Consulta de datos
   #AUTOR:		admin
   #FECHA:		11-08-2016 01:52:07
  ***********************************/

  if(p_transaccion='REC_INFOR_SEL')then

    begin
      --Sentencia de la consulta
      v_consulta:='select
						infor.id_informe,
						infor.sugerencia_respuesta,
						infor.id_reclamo,
						infor.antecedentes_informe,
						infor.nro_informe,
						infor.id_funcionario,
						infor.conclusion_recomendacion,
						infor.fecha_informe,
						infor.estado_reg,
						infor.lista_compensacion,
						infor.analisis_tecnico,
						infor.id_usuario_ai,
						infor.id_usuario_reg,
						infor.usuario_ai,
						infor.fecha_reg,
						infor.fecha_mod,
						infor.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,

                        fun.desc_funcionario1 as desc_fun,
                        rec.ft_informe_crear_lista(infor.lista_compensacion) as lista
						from rec.tinforme infor
						inner join segu.tusuario usu1 on usu1.id_usuario = infor.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = infor.id_usuario_mod

                        inner join orga.vfuncionario fun on fun.id_funcionario = infor.id_funcionario
                        left join rec.treclamo_informe tre ON tre.id_informe = infor.id_informe
				        where  ';

      --Definicion de la respuesta
      v_consulta:=v_consulta||v_parametros.filtro;
      v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
      --Devuelve la respuesta
      raise notice 'consulta %',v_consulta;
      return v_consulta;

    end;

    /*********************************
     #TRANSACCION:  'REC_INFOR_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		admin
     #FECHA:		11-08-2016 01:52:07
    ***********************************/

  elsif(p_transaccion='REC_INFOR_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
      v_consulta:='select count(id_informe)
					    from rec.tinforme infor
						inner join segu.tusuario usu1 on usu1.id_usuario = infor.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = infor.id_usuario_mod
                        join rec.tcompensacion com on com.id_compensacion = com.id_compensacion
                        inner join orga.vfuncionario fun on fun.id_funcionario = infor.id_funcionario
					    where ';

      --Definicion de la respuesta
      v_consulta:=v_consulta||v_parametros.filtro;

      --Devuelve la respuesta
      return v_consulta;

    end;
    /*********************************
     #TRANSACCION:  'REC_INFORREP_SEL'
     #DESCRIPCION:	Reporte informacion doc
     #AUTOR:		MMV
     #FECHA:		18-10-2016 01:52:07
    ***********************************/
     elsif(p_transaccion='REC_INFORREP_SEL')then

    	begin
   			--RAISE EXCEPTION 'PRUEBA: %',v_parametros.id_proceso_wf;
           --recupera el funcionario ...
          --v_gaf = orga.f_obtener_gerente_x_codigo_uo('gerente_financiero', now()::Date);
           --Sentencia de la consulta
		  v_consulta:=' select
						distinct on (infor.id_informe) infor.id_informe,
						infor.sugerencia_respuesta,
						infor.id_reclamo,
						infor.antecedentes_informe,
						infor.nro_informe,
						infor.id_funcionario,
						infor.conclusion_recomendacion,
						infor.fecha_informe,
						infor.estado_reg,
						infor.lista_compensacion,
						infor.analisis_tecnico,
						infor.id_usuario_ai,
						infor.id_usuario_reg,
						infor.usuario_ai,
						infor.fecha_reg,
						infor.fecha_mod,
						infor.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        com.nombre as desc_nombre_compensacion,
                        fun.desc_funcionario1 as desc_fun,
                        rec.ft_informe_crear_lista(infor.lista_compensacion) as lista,
                        re.id_proceso_wf,
                        re.id_estado_wf,
                        re.nro_frd,
                        re.correlativo_preimpreso_frd,
                        re.nro_vuelo,
                        re.fecha_hora_incidente,
                        of.nombre,
                        re.origen,
                        re.destino,
                        cli.nombre_completo1,
                        cli.email,
                        cli.celular,
                        re.detalle_incidente,
                        fun.desc_funcionario1 as funcionario_reg
						from rec.tinforme infor
						inner join segu.tusuario usu1 on usu1.id_usuario = infor.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = infor.id_usuario_mod
                        INNER JOIN rec.tcompensacion com on com.id_compensacion = com.id_compensacion
                        inner join orga.vfuncionario fun on fun.id_funcionario = infor.id_funcionario
                        inner join rec.treclamo re on re.id_reclamo = infor.id_reclamo
                        inner join rec.toficina of on of.id_oficina = re.id_oficina_registro_incidente
                        inner join rec.vcliente cli on cli.id_cliente = re.id_cliente
                        where re.id_proceso_wf = '||v_parametros.id_proceso_wf;

                        raise notice '% ', v_consulta;
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