CREATE OR REPLACE FUNCTION rec.f_verificar_dias (
  p_fecha_actual date,
  p_fecha_limite date
)
RETURNS integer AS
$body$
/**************************************************************************
 SISTEMA:        Parametros
 FUNCION:        adq.f_verificar_dias_form45
 DESCRIPCION:    Funcion que devuelve el numero de dias de plazo para subir formulario 400 y 500.
 AUTOR:          FEA
 FECHA:          21/02/2018
 COMENTARIOS:
**************************************************************************/

DECLARE

    v_nombre_funcion  	varchar;
    v_consulta        	varchar;
    v_parametros      	record;
    v_record          	record;
    v_respuesta		  	varchar;
    v_fecha_actual    	date;
	v_contador		  	integer=0;
    v_fecha_feriado	  	date;

BEGIN
    v_nombre_funcion='adq.f_verificar_dias_form45';

      v_fecha_actual = p_fecha_actual;
      --raise exception 'a: %, b: %',p_fecha_actual, p_fecha_limite;
      if date_part('dow', v_fecha_actual) in (6) then
      	v_fecha_actual = v_fecha_actual + 1;
      end if;

      IF(v_fecha_actual > p_fecha_limite) THEN
          v_contador=-1;
      ELSE
        WHILE  v_fecha_actual < p_fecha_limite LOOP
            IF (date_part('dow',v_fecha_actual) in (1,2,3,4,0))THEN
                if(param.f_es_feriado(v_fecha_actual))then
                    v_fecha_actual = param.f_feriado_consecutivo(v_fecha_actual);
                else
                	v_contador = v_contador + 1;
                    v_fecha_actual = v_fecha_actual + 1;
                end if;
            ELSIF date_part('dow',v_fecha_actual) in (5) THEN
            	if(param.f_es_feriado(v_fecha_actual))then
                    v_fecha_actual = param.f_feriado_consecutivo(v_fecha_actual);
                else
                  	v_contador = v_contador + 1;
                  	v_fecha_actual = v_fecha_actual + 3;
                end if;
            END IF;
        END LOOP;
      END IF;

      return v_contador;

EXCEPTION
  WHEN OTHERS THEN
    v_respuesta='';
    v_respuesta=pxp.f_agrega_clave(v_respuesta,'mensaje',SQLERRM);
    v_respuesta=pxp.f_agrega_clave(v_respuesta,'codigo_error',SQLSTATE);
    v_respuesta=pxp.f_agrega_clave(v_respuesta,'procedimiento',v_nombre_funcion);
    raise exception '%',v_respuesta;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;