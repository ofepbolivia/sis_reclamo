CREATE OR REPLACE FUNCTION rec.f_inserta_respuesta (
  p_administrador integer,
  p_id_usuario integer,
  p_hstore_respuesta public.hstore
)
RETURNS varchar AS
$body$
DECLARE
    v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
    v_reclamo			record;

    v_num_tramite		varchar;
    v_id_proceso_wf_rec	integer;
    v_id_estado_wf_rec 	integer;
    v_estado_rec		varchar;
    v_id_reclamo		integer;

    va_id_tipo_estado_res 	integer[];
    va_codigo_estado_res 	varchar[];
    va_disparador_res		varchar[];
    va_regla_res			varchar[];
    va_prioridad_res		integer[];

    v_id_estado_actual		integer;

    v_codigo_tipo_proceso	varchar;
    v_id_proceso_macro		integer;
    v_nro_respuesta			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado			varchar;

    v_id_respuesta			integer;
    v_codigo				varchar;
    v_num_cite				varchar;

    v_respuesta				record;
    v_gestion				integer;
    v_cont_resp 			integer;

    v_record				record;
    v_res					varchar;

BEGIN
	 v_nombre_funcion = 'rec.f_inserta_respuesta';


           --obtener datos del proceso de reclamo

           select
            r.nro_tramite,
            r.id_proceso_wf,
            r.id_estado_wf,
           	r.estado,
            r.id_reclamo

           into
            v_num_tramite,
            v_id_proceso_wf_rec,
            v_id_estado_wf_rec,
            v_estado_rec,
            v_id_reclamo
           from rec.treclamo r
           where r.id_reclamo = (p_hstore_respuesta->'id_reclamo')::integer;

           --Datos Respuesta
           --begin
           select res.*
           into v_respuesta
           from rec.trespuesta res
           where res.id_reclamo = (p_hstore_respuesta->'id_reclamo')::integer;
           --end;

		  IF (v_estado_rec = 'pendiente_respuesta' OR v_estado_rec = 'respuesta_parcial')  THEN

                select * into v_reclamo
              	from rec.treclamo r
              	where r.id_reclamo = (p_hstore_respuesta->'id_reclamo')::integer;

                SELECT tp.codigo, tp.id_proceso_macro
                INTO v_codigo_tipo_proceso, v_id_proceso_macro
                FROM wf.ttipo_proceso tp
                WHERE tp.codigo = 'RESP' AND tp.estado_reg = 'activo';
			--raise exception 'id_reclamo %', (p_hstore_respuesta->'id_reclamo')::integer;
            SELECT count(*)
            INTO v_cont_resp
            FROM rec.trespuesta
            WHERE id_reclamo = (p_hstore_respuesta->'id_reclamo')::integer;

            IF (v_cont_resp < 2) THEN
              IF (v_cont_resp=1 and v_respuesta.tipo_respuesta = 'respuesta_final') THEN
              	RAISE EXCEPTION 'NO ES POSIBLE REGISTRAR NUEVA RESPUESTA EL RECLAMO YA CUENTA CON UN RESPUESTA...';
              ELSIF (v_respuesta.tipo_respuesta = 'respuesta_parcial' OR v_cont_resp=0)THEN
              	IF (p_hstore_respuesta->'tipo_respuesta' = 'respuesta_final' OR v_cont_resp=0)THEN
                  IF (v_respuesta.estado='respuesta_enviada' OR v_cont_resp=0)THEN
                    --OBTENEMOS GESTION
                    --begin
                    select g.gestion
                    into v_gestion
                    from param.tgestion g
                    where g.id_gestion = v_reclamo.id_gestion;
                    --end
                    --codigo de numero de Respuesta
                    --begin
                    v_codigo = (v_codigo_tipo_proceso||'-'||lpad((v_cont_resp+1)::varchar,2,'0')||'-['||v_reclamo.nro_tramite||']');


                    SELECT tf.id_funcionario
                    INTO v_record
					FROM segu.tusuario tu
					INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
					WHERE tu.id_usuario = p_id_usuario ;



                    SELECT
                                 ps_id_proceso_wf,
                                 ps_id_estado_wf,
                                 ps_codigo_estado,
                                 ps_nro_tramite
                    INTO
                                 v_id_proceso_wf,
                                 v_id_estado_wf,
                                 v_codigo_estado,
                                 v_nro_respuesta
                   	FROM wf.f_registra_proceso_disparado_wf(
                                p_id_usuario,
                                (p_hstore_respuesta->'_id_usuario_ai')::integer,
                                (p_hstore_respuesta->'_nombre_usuario_ai')::varchar,
                                v_reclamo.id_estado_wf,
                                v_record.id_funcionario,  --id_funcionario wf
                                null,
                                v_codigo,--'Respuesta'||(p_hstore_respuesta->'nro_cite')::varchar,
                                'RESP',
                                v_codigo);


                    SELECT  tr.nro_cite
                    INTO v_num_cite
                    FROM rec.trespuesta tr
                    inner join rec.treclamo trec on trec.id_reclamo = tr.id_reclamo
                    WHERE tr.nro_cite = (p_hstore_respuesta->'nro_cite')::varchar and trec.id_gestion = v_reclamo.id_gestion and (tr.fecha_respuesta between ('01/01/'||date_part('year',tr.fecha_respuesta))::date and ('31/12/'||date_part('year',tr.fecha_respuesta))::date);

                    IF v_num_cite = (p_hstore_respuesta->'nro_cite')THEN
                    	v_num_cite = (v_num_cite::integer + 1)::varchar;
                    ELSE
                    	v_num_cite = (p_hstore_respuesta->'nro_cite');
                    END IF;

                    v_res = replace((p_hstore_respuesta->'respuesta')::varchar,' align="right" ',' ');
                    v_res = replace(v_res,' align="center" ',' ');
                    v_res = replace(v_res,' align="left" ',' ');
                    v_res = replace(v_res,'text-align: center','text-align: justify;');
                    v_res = replace(v_res,'text-align: left;','text-align: justify;');
                    v_res = replace(v_res,'text-align: right;','text-align: justify;');
                    v_res = replace(v_res,'text-align:center','text-align: justify;');
                    v_res = replace(v_res,'text-align:left;','text-align: justify;');
                    v_res = replace(v_res,'text-align:right;','text-align: justify;');
                    v_res = replace(v_res,'<p class="MsoNormal" style="','<p class="MsoNormal" style="text-align:justify; ');

                  insert into rec.trespuesta(
                    id_reclamo,
                    recomendaciones,
                    nro_cite,
                    respuesta,
                    fecha_respuesta,
                    estado_reg,
                    procedente,
                    fecha_notificacion,
                    id_usuario_ai,
                    id_usuario_reg,
                    usuario_ai,
                    fecha_reg,
                    fecha_mod,
                    id_usuario_mod,
                    asunto,
                    tipo_respuesta,
                    id_proceso_wf,
                    id_estado_wf,
                    estado,
                    nro_respuesta
                    ) values(
                    (p_hstore_respuesta->'id_reclamo')::integer,
                    (p_hstore_respuesta->'recomendaciones')::varchar,
                    upper(v_num_cite),
                    (p_hstore_respuesta->'respuesta')::varchar,
                    (p_hstore_respuesta->'fecha_respuesta')::date,
                    'activo',
                    (p_hstore_respuesta->'procedente')::varchar,
                    (p_hstore_respuesta->'fecha_notificacion')::date,
                    (p_hstore_respuesta->'_id_usuario_ai')::integer,
                    p_id_usuario,
                    (p_hstore_respuesta->'__nombre_usuario_ai')::integer,
                    now(),
                    null,
                    null,
                    (p_hstore_respuesta->'asunto')::varchar,
                    (p_hstore_respuesta->'tipo_respuesta')::varchar,
                    v_id_proceso_wf,
                    v_id_estado_wf,
                    v_codigo_estado,
                    v_codigo
                    )RETURNING id_respuesta into v_id_respuesta;
        			UPDATE rec.treclamo SET
                    	revisado = 'proceso'
                    WHERE id_reclamo=(p_hstore_respuesta->'id_reclamo')::integer;
                    --Definicion de la respuesta
                    v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Respuesta almacenado(a) con exito (id_respuesta'||v_id_respuesta||')');
                    v_resp = pxp.f_agrega_clave(v_resp,'id_respuesta',v_id_respuesta::varchar);
                    --Devuelve la respuesta

                    return v_resp;
                  ELSE
                  	RAISE EXCEPTION 'LA RESPUESTA PARCIAL NO HA SIDO CONCLUIDA...';
                  END IF;
                ELSE
                	RAISE EXCEPTION 'LA RESPUESTA TIENE QUE SER FINAL...';
              	END IF;
              ELSE
              	RAISE EXCEPTION 'YA TIENE UNA RESPUESTA PARCIAL, INCLUYA UNA RESPUESTA FINAL...';
              END IF;
            ELSE
            	RAISE EXCEPTION 'NO ES POSIBLE REGISTRAR NUEVA RESPUESTA EL RECLAMO YA CUENTA CON UN RESPUESTA......';
            END IF;
          ELSE
          		RAISE EXCEPTION 'NO ES POSIBLE REGISTRAR NUEVA RESPUESTA EL RECLAMO YA CUENTA CON UN RESPUESTA......';
          END IF;


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

ALTER FUNCTION rec.f_inserta_respuesta (p_administrador integer, p_id_usuario integer, p_hstore_respuesta public.hstore)
  OWNER TO postgres;