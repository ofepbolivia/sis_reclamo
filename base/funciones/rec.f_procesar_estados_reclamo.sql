CREATE OR REPLACE FUNCTION rec.f_procesar_estados_reclamo (
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
    v_reclamo 			 record;
    v_respuesta			record;
    v_valor   varchar = '';
    v_cont integer = 0;
    v_record 			record;
BEGIN

	v_nombre_funcion = 'rec.f_procesar_estados_reclamo';

    select r.* into v_reclamo
    from rec.treclamo	r
    where r.id_proceso_wf = p_id_proceso_wf;
    --RAISE EXCEPTION 'RECLAMO: %', p_id_proceso_wf;
    select  resp.* into v_respuesta
    from rec.trespuesta resp
    where resp.id_reclamo = v_reclamo.id_reclamo;

    if(p_codigo_estado in ('pendiente_revision','registrado_ripat','derivado','anulado')) then
    	begin
        --raise EXCEPTION 'pendiente_revision';
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
        end;

    elsif(p_codigo_estado in ('pendiente_informacion')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('pendiente_respuesta')) then
    	begin


    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('archivo_con_respuesta')) then
    	begin
        for v_valor in
        	select  resp.estado
            from rec.trespuesta resp
        	where resp.id_reclamo = v_reclamo.id_reclamo loop

           	if (btrim(v_valor,'()') = 'respuesta_enviada') then
            	v_cont = v_cont + 1 ;
            end if;

        end loop;
        if (v_cont = v_reclamo.cont_respuesta and v_reclamo.cont_respuesta>0) then
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
        else
        	if( v_reclamo.cont_respuesta = 0) then
            	raise exception 'No tiene respuesta para este Reclamo, Cree una.';
            else
        		raise exception 'Falta algunas Respuestas por finalizar ';
            end if;
        end if;

    	end;
    elsif(p_codigo_estado in ('archivado_concluido')) then
    	begin
        	select  resp.tipo_respuesta
            into v_record
            from rec.trespuesta resp
        	where resp.id_reclamo = v_reclamo.id_reclamo;
        	if(v_record = 'respuesta_parcial') then
            	-- agregar el contador de dias en respuesta
            end if;
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('en_avenimiento')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('formulacion_cargos')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('archivado_concluido')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('resolucion_administrativa')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('recurso_revocatorio')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('recurso_revocatorio')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('recurso_jerarquico')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    elsif(p_codigo_estado in ('contencioso_administrativo')) then
    	begin
    		update rec.treclamo r set
       			id_estado_wf =  p_id_estado_wf,
      			estado = p_codigo_estado,
       			id_usuario_mod=p_id_usuario,
       			id_usuario_ai = p_id_usuario_ai,
		       	usuario_ai = p_usuario_ai,
       			fecha_mod=now()
    		where id_proceso_wf = p_id_proceso_wf;
    	end;
    end if;


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