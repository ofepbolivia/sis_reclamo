CREATE OR REPLACE FUNCTION rec.f_procesar_estados_respuesta (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
DECLARE
    v_nombre_funcion   	 text;
    v_resp    			 varchar;
    v_mensaje 			 varchar;
    v_respuesta			 record;
BEGIN

	v_nombre_funcion = 'rec.f_procesar_estados_respuesta';

    select * into v_respuesta
    from rec.trespuesta	res
    where res.id_proceso_wf = p_id_proceso_wf;
	--raise exception 'PROCEDENTE: %',v_respuesta;
    --IF v_respuesta.procedente = TRUE THEN

        if(p_codigo_estado in ('elaboracion_respuesta')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;

        elsif(p_codigo_estado in ('revision_legal')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('vobo_respuesta')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('respuesta_aprobada')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        elsif(p_codigo_estado in ('respuesta_enviada')) then
            begin
                update rec.trespuesta r set
                    id_estado_wf =  p_id_estado_wf,
                    estado = p_codigo_estado,
                    id_usuario_mod=p_id_usuario,
                    id_usuario_ai = p_id_usuario_ai,
                    usuario_ai = p_usuario_ai,
                    fecha_mod=now()
                where id_proceso_wf = p_id_proceso_wf;
            end;
        end if;

    /*ELSE

    		RAISE EXCEPTION 'La respuesta es improcedente, Verifique información.';

	END IF;*/

	-- actualiza estado en la solicitud
   /* update rec.treclamo  r set
       id_estado_wf =  p_id_estado_wf,
       estado = p_codigo_estado,
       id_usuario_mod = p_id_usuario,
       id_usuario_ai = p_id_usuario_ai,
       usuario_ai = p_usuario_ai,
       fecha_mod=now()
    where id_proceso_wf = p_id_proceso_wf;*/

		return true;

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