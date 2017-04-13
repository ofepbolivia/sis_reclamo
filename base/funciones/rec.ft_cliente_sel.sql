CREATE OR REPLACE FUNCTION rec.ft_cliente_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_cliente_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tcliente'
 AUTOR: 		 (admin)
 FECHA:	        12-08-2016 14:29:16
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
    v_fecha_fin 	date;

BEGIN

	v_nombre_funcion = 'rec.ft_cliente_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_CLI_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		12-08-2016 14:29:16
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
                        cli.id_pais_residencia,
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
						usu2.cuenta as usr_mod,
                        c.nombre_completo1 as completo,
                        c.nombre_completo2,
                        lug.nombre as pais_residencia
						from rec.tcliente cli
						inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
                        inner join rec.vcliente c on c.id_cliente = cli.id_cliente
                        left join param.tlugar lug on lug.id_lugar = cli.id_pais_residencia::integer
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
 	#FECHA:		12-08-2016 14:29:16
	***********************************/

	elsif(p_transaccion='REC_CLI_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(c.id_cliente)
					    from rec.tcliente cli
						inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
                        inner join rec.vcliente c on c.id_cliente = cli.id_cliente
                        left join param.tlugar lug on lug.id_lugar = cli.id_pais_residencia::integer
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_RELIBRO_SEL'
 	#DESCRIPCION:	libro de reclamo
 	#AUTOR:		admin
 	#FECHA:		12-08-2016 14:29:16
	***********************************/
    elsif (p_transaccion= 'REC_RELIBRO_SEL')then

    	begin

        v_where = '';

          	if (v_parametros.id_oficina_registro_incidente <> -1) then

                v_where = v_where ||' and re.id_oficina_registro_incidente = '||v_parametros.id_oficina_registro_incidente ;
            	--raise exception '%',v_where;
            end if ;

        raise notice '%',v_where;
                            v_consulta ='SELECT
                                                re.id_reclamo,
                                                re.nro_frd,
                                                re.correlativo_preimpreso_frd,
                                                re.fecha_hora_incidente,
                                                re.fecha_hora_recepcion,
                                                re.fecha_recepcion_sac,
                                                re.detalle_incidente,
                                                cl.nombre_completo1 as nombre,
                                                c.celular,
                                                c.telefono,
                                                ti.nombre_incidente,
                                                tip.nombre_incidente as sub_incidente
                                                FROM rec.treclamo re
                                                inner join rec.vcliente cl on cl.id_cliente = re.id_cliente
                                                inner join rec.tcliente c on c.id_cliente = re.id_cliente
                                                inner join rec.ttipo_incidente ti on ti.id_tipo_incidente = re.id_tipo_incidente
                                                inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = re.id_subtipo_incidente
                                                WHERE  re.fecha_hora_recepcion >= '''||v_parametros.fecha_ini||''' and re.fecha_hora_recepcion <= '''||v_parametros.fecha_fin||'''
                                                and re.id_oficina_registro_incidente = '''||v_parametros.id_oficina_registro_incidente ||''' ORDER BY
                                                re.nro_frd, re.correlativo_preimpreso_frd ASC';

                                                --Definicion de la respuesta
                                                --v_consulta:=v_consulta||v_parametros.filtro;

                         return v_consulta;


	end;
    /*********************************
 		#TRANSACCION:  'CLI_LUGSEL'
 		#DESCRIPCION:	Permite recuperar las oficinas para la situacion de Ambiente del incidente y oficina de registro
 		#AUTOR:		FEA
 		#FECHA:		27-10-2016 18:32:59
		***********************************/
       ELSIF(p_transaccion = 'CLI_LUG_SEL')THEN
    	BEGIN
        	v_consulta = 'select
						lug.id_lugar,
						lug.codigo,
						lug.estado_reg,
						lug.id_lugar_fk,
						lug.nombre,
						lug.sw_impuesto,
						lug.sw_municipio,
						lug.tipo,
						lug.fecha_reg,
						lug.id_usuario_reg,
						lug.fecha_mod,
						lug.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						lug.es_regional
						from param.tlugar lug
						inner join segu.tusuario usu1 on usu1.id_usuario = lug.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = lug.id_usuario_mod
				        where  (lug.estado_reg = ''activo'' OR lug.estado_reg = ''inactivo'') AND lug.tipo = ''pais''';
        	raise notice 'Consulta: %',v_consulta;
        	RETURN v_consulta;
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