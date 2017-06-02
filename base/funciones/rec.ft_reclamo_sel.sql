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

    v_id_usuario_rev	record;
    v_id_usuario_pen	integer;

    va_id_depto 		integer[];
    v_gestion		integer;

    --Modifica las alarmas que fallaron
	  v_ids_alarma		INTEGER[];
    v_index				integer = 1;
    v_nro_tramites		varchar[];
    v_titulo_correo		varchar[];
    v_correo			varchar[];
    v_fecha_reg			TIMESTAMP[];
    v_cadena			varchar;


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


           	IF (p_administrador=1 OR v_parametros.tipo_interfaz='ConsultaReclamo' OR v_parametros.tipo_interfaz='filtros') THEN
            	v_filtro= '0 = 0 AND ';
	 	   	ELSE
           		IF (v_parametros.tipo_interfaz='RevisionReclamo')THEN


            		v_filtro = '(tew.id_funcionario = '||v_record.id_funcionario||' OR rec.id_usuario_mod = '|| p_id_usuario||') AND rec.estado_reg <> ''inactivo'' AND ';

           		ELSIF (v_parametros.tipo_interfaz::varchar = 'PendienteRespuesta' OR v_parametros.tipo_interfaz='ReclamoAdministrativo')THEN
                    --Consulta que muestra el id_usuario del anterior estado
                    SELECT tu.id_usuario, count(tu.id_usuario)::varchar as cant_reg
            		INTO v_id_usuario_rev
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    WHERE tf.id_funcionario = (SELECT tew.id_funcionario
                    FROM wf.testado_wf tew
                    LEFT JOIN wf.testado_wf te ON te.id_estado_anterior = tew.id_estado_wf
                    LEFT JOIN rec.treclamo  tr ON tr.id_estado_wf = te.id_estado_wf
                    WHERE tr.estado =  'pendiente_asignacion' LIMIT 1)
                    GROUP BY tu.id_usuario;

                    select
                       pxp.aggarray(depu.id_depto)
                    into
                       va_id_depto
                    from param.tdepto_usuario depu
                    where depu.id_usuario =  p_id_usuario;

                    IF(v_id_usuario_rev.cant_reg IS NULL)THEN
                    	v_filtro = 'tew.id_funcionario = '||v_record.id_funcionario||' AND rec.estado_reg <> ''inactivo'' AND ';
                    ELSE
                    	v_filtro = '( rec.id_usuario_mod = '||p_id_usuario||' OR tew.id_depto in ('|| COALESCE(array_to_string(va_id_depto,','),'0')||') OR tew.id_funcionario = '||v_record.id_funcionario||') AND rec.estado_reg <> ''inactivo'' AND ';
                    END IF;
            	--END IF;
            	ELSIF v_parametros.tipo_interfaz='RegistroReclamos' THEN
            		v_filtro = '(rec.id_usuario_reg = '||p_id_usuario||
               	 	' OR rec.id_oficina_registro_incidente = '||v_record.id_oficina||') AND ';
            	ELSE
            		v_filtro= '0 = 0 AND ';
            	END IF;
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

                            c.nombre_completo2 as desc_nom_cliente,
							'||p_administrador||'::integer AS administrador,
							tri.id_informe
						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        left join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
                            LEFT JOIN rec.treclamo_informe tri ON tri.id_reclamo = rec.id_reclamo

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
                        left join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf

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
			v_consulta:='select distinct on (rec.id_reclamo)
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
                        left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                        left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                        left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf

                        LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
						LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
				        WHERE ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by id_reclamo, ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
        	raise notice 'que esta pasando: %',v_consulta;
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_CRMG_CONT'
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
                        left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario_cargo_lugar fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario_cargo_lugar fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                        left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                        left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf

                        LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
						LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_CONSULTA_SEL'
 	#DESCRIPCION:	Consulta PARA LA VISTA CONSULTA RECLAMO
 	#AUTOR:		admin
 	#FECHA:		01-02-2017 12:00:59
	***********************************/
	elsif(p_transaccion='REC_CONSULTA_SEL')then

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
        					rec.revisado,
        					rec.transito,
        					ma.motivo as motivo_anulado,
        					rec.nro_guia_aerea,
                            c.nombre_completo2
						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						INNER join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
						LEFT join rec.vcliente c on c.id_cliente = rec.id_cliente
						INNER join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
						left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
						INNER join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
						INNER join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
						INNER join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
						left join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
						inner join param.tgestion gest on gest.id_gestion = rec.id_gestion
						left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
				        WHERE ';
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
        	raise notice 'que esta pasando: %',v_consulta;
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'REC_CONSULTA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		10-08-2016 18:32:59
	***********************************/

	elsif(p_transaccion='REC_CONSULTA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(rec.id_reclamo)
			   			from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						INNER join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
						LEFT join rec.vcliente c on c.id_cliente = rec.id_cliente
						INNER join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
						left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
						INNER join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
						INNER join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
						INNER join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
						left join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
						inner join param.tgestion gest on gest.id_gestion = rec.id_gestion
						left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
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
                        inner join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left outer join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        inner join param.tlugar lu on lu.id_lugar =cl.id_pais_residencia
           				where rec.id_proceso_wf ='||v_parametros.id_proceso_wf;

                        raise notice '%', v_consulta;

            return v_consulta;
            END;

     ELSIF(p_transaccion = 'REC_LIBRESP_SEL')THEN
    	BEGIN

          v_consulta = 'SELECT DISTINCT ON (correlativo)
          trp.fecha_respuesta::date AS fecha,
          (SUBSTRING(trc.nro_tramite FROM 5 FOR 6))::varchar AS correlativo,
          ti.nombre_incidente::varchar AS tipo,
          sti.nombre_incidente::varchar AS subtipo,
          vfcl.oficina_nombre::varchar AS oficina,
          vc.nombre_completo1::varchar AS cliente
          FROM rec.treclamo trc
          INNER JOIN rec.trespuesta trp ON trp.id_reclamo = trc.id_reclamo
          INNER JOIN rec.ttipo_incidente 	ti ON ti.id_tipo_incidente = trc.id_tipo_incidente
          INNER JOIN rec.ttipo_incidente sti ON sti.id_tipo_incidente = trc.id_subtipo_incidente
          INNER JOIN orga.vfuncionario_cargo_lugar vfcl ON vfcl.id_oficina = trc.id_oficina_incidente
          INNER JOIN rec.vcliente vc ON vc.id_cliente = trc.id_cliente
          WHERE trp.fecha_respuesta >= '''|| to_char(v_parametros.fecha_ini,'DD-MM-YYYY')||''' AND trp.fecha_respuesta <= '''||to_char(v_parametros.fecha_fin,'DD-MM-YYYY')||'''';


          RETURN v_consulta;

        END;
       /*********************************
 		#TRANSACCION:  'REC_OFICINAS_SEL'
 		#DESCRIPCION:	Permite recuperar las oficinas para la situacion de Ambiente del incidente y oficina de registro
 		#AUTOR:		FEA
 		#FECHA:		27-10-2016 18:32:59
		***********************************/
       ELSIF(p_transaccion = 'REC_OFICINAS_SEL')THEN
    	BEGIN
        	v_consulta = 'select
						ofi.id_oficina,
						ofi.aeropuerto,
						ofi.id_lugar,
						ofi.nombre,
						ofi.codigo,
						ofi.estado_reg,
						ofi.fecha_reg,
						ofi.id_usuario_reg,
						ofi.fecha_mod,
						ofi.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						lug.nombre as nombre_lugar,
						ofi.zona_franca,
						ofi.frontera
						from rec.toficina ofi
						inner join segu.tusuario usu1 on usu1.id_usuario = ofi.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ofi.id_usuario_mod
						inner join param.tlugar lug on lug.id_lugar = ofi.id_lugar
				        where  (ofi.estado_reg = ''activo'' OR ofi.estado_reg = ''inactivo'') AND ';

            v_consulta:=v_consulta||v_parametros.filtro;
        	v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            RETURN v_consulta;
        END;
    /*********************************
     #TRANSACCION:  'REC_OFICINAS_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		admin
     #FECHA:		15-01-2014 16:05:34
    ***********************************/

    elsif(p_transaccion='REC_OFICINAS_CONT')then

      begin
        --Sentencia de la consulta de conteo de registros
        v_consulta:='select count(id_oficina)
					   from rec.toficina ofi
						inner join segu.tusuario usu1 on usu1.id_usuario = ofi.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = ofi.id_usuario_mod
						inner join param.tlugar lug on lug.id_lugar = ofi.id_lugar
				        where  (ofi.estado_reg = ''activo'' OR ofi.estado_reg = ''inactivo'') AND ';

        --Definicion de la respuesta
        v_consulta:=v_consulta||v_parametros.filtro;

        --Devuelve la respuesta
        return v_consulta;

      end;
    elsif(p_transaccion='REC_REG_RIP')then

      begin
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

                            c.nombre_completo2 as desc_nom_cliente,
							'||p_administrador||'::integer AS administrador

						from rec.treclamo rec
						inner join segu.tusuario usu1 on usu1.id_usuario = rec.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = rec.id_usuario_mod
						left join rec.tmedio_reclamo med on med.id_medio_reclamo = rec.id_medio_reclamo
                        left join rec.vcliente c on c.id_cliente = rec.id_cliente
                        inner join rec.ttipo_incidente tip on tip.id_tipo_incidente = rec.id_tipo_incidente
                        left join rec.toficina of on of.id_oficina = rec.id_oficina_incidente
                      	inner join rec.toficina ofi on ofi.id_oficina = rec.id_oficina_registro_incidente
                        inner join rec.ttipo_incidente t on t.id_tipo_incidente = rec.id_subtipo_incidente
                        inner join orga.vfuncionario fun on fun.id_funcionario = rec.id_funcionario_recepcion
                        left join orga.vfuncionario fu on fu.id_funcionario = rec.id_funcionario_denunciado
                        	left join param.tgestion gest on gest.id_gestion = rec.id_gestion
                            left join rec.tmotivo_anulado ma on ma.id_motivo_anulado = rec.id_motivo_anulado
                            left join wf.testado_wf tew on tew.id_estado_wf = rec.id_estado_wf

                            LEFT JOIN rec.trespuesta res ON res.id_reclamo = rec.id_reclamo
							LEFT JOIN rec.tinforme infor ON infor.id_reclamo =  rec.id_reclamo

				        where  rec.estado=''registrado_ripat'' AND ';

			--raise exception 'ordenacion: %',v_consulta;
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			--Devuelve la respuesta
        	raise notice 'que esta pasando: %',v_consulta;

			return v_consulta;

      end;
  /*********************************
      #TRANSACCION:  'REC_FAILS_SEL'
      #DESCRIPCION:	Permite recuperar las alarmas que fallaron al momento de enviar la respuesta de un reclamo.
      #AUTOR:		FEA
      #FECHA:		27-04-2017 18:32:59
      ***********************************/
     ELSIF(p_transaccion = 'REC_FAILS_SEL')THEN
      BEGIN

          FOR v_record IN  (SELECT ta.id_alarma, ta.titulo_correo, ta.fecha_reg, ta.correos, tr.nro_respuesta
                              FROM rec.trespuesta tr
                              INNER JOIN param.talarma ta ON ta.id_proceso_wf = tr.id_proceso_wf
                              WHERE (ta.estado_envio = 'falla' OR ta.pendiente <> 'no' ) AND
                              (ta.fecha_reg::date BETWEEN now()::date-1 and now()::date+1)) LOOP

        	v_ids_alarma[v_index] = v_record.id_alarma;
            v_nro_tramites[v_index] = v_record.nro_respuesta;
            v_titulo_correo[v_index] = v_record.titulo_correo;
            v_correo[v_index] = v_record.correos;
            v_fecha_reg[v_index] = v_record.fecha_reg;

            v_index = v_index + 1;
        END LOOP;

        FOR v_index IN 1..array_length(v_ids_alarma,1) LOOP
        	v_cadena = substr(v_correo[v_index], 1, position(',' IN v_correo[v_index])-1);
       		UPDATE param.talarma  SET
              descripcion ='<div  style="font-size: 12px; color: #000080; font-family: Verdana, Arial;">
              					<p>
                                	<span>De: <b>Sistema ERP BOA</b></span><br>
                                    <span>Fecha: '||v_fecha_reg[v_index]||'</span><br>
                                    <span>Asunto: '||v_titulo_correo[v_index]||'</span><br>
                                    <span>Para: "'||v_cadena||'" </span><br>
                                    <span>Cc: "sac@boa.bo" </span>
                                </p>
              				</div><br><br>
              				<div style="font-size: 12px; color: #000080; font-family: Verdana, Arial;">
                                <span><b>Estimados Señores:</b></span><br><br>
                                <p><img src="../../../sis_reclamo/reportes/sac.png"></p><br><br>
                                <span>Se presento un error al enviar el correo.</span><br><br>
                                <p><img src="../../../sis_reclamo/media/error_mail.png"></p><br><br><br>
                                <span>La falla se debe a un error de nombre de correo, pongase en contacto </span><br>
                                <span>con el cliente para confirmar la veracidad del correo al que se envio la respuesta.</span><br><br>
                                <span><b>Nro. Tramite:</b> </span>'||v_nro_tramites[v_index]||'
							</div>',
              titulo_correo = regexp_replace(titulo_correo,'Respuesta al Reclamo','Error al enviar el correo,'),
              estado_envio = 'exito',
              desc_falla = '',
              pendiente = 'no'
            WHERE id_alarma = v_ids_alarma[v_index]::integer;
        END LOOP;
        
          v_consulta = 'select
          				trec.id_reclamo,
          				 trec.nro_tramite,
                        trec.id_cliente,
                        case when substring(ta.desc_falla, position(''D'' in ta.desc_falla), position(''!'' in ta.desc_falla)-8) like ''Domain Email address % is invalid -- aborting!'' then ''Dominio de Correo no Existe, Consulte con el Cliente via Telefono''::VARCHAR
						else ''Cuenta de Correo no existe, Consulte con el Cliente via Telefono''::VARCHAR
						end as falla,
                        vc.nombre_completo2::varchar as desc_funcionario
						from rec.trespuesta tr
						inner join param.talarma ta on ta.id_proceso_wf = tr.id_proceso_wf
						inner join rec.treclamo trec on trec.id_reclamo = tr.id_reclamo
						inner join rec.vcliente vc on vc.id_cliente = trec.id_cliente
						where (ta.estado_envio = ''falla'' or ta.pendiente <> ''no'' ) and (ta.fecha_reg::date between now()::date-1 and now()::date+1)';

          v_consulta:=v_consulta||v_parametros.filtro;
          v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

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