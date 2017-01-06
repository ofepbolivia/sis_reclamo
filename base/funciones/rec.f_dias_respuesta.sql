CREATE OR REPLACE FUNCTION rec.f_dias_respuesta (
  p_fecha_actual date,
  p_fecha_limite date,
  p_transaccion varchar
)
RETURNS integer AS
$body$
DECLARE
    v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje 			varchar;
	v_diferencia		integer;

    v_contador			integer=0;
    v_fecha_actual		date;
    v_fecha_limite      date;

BEGIN
	IF(p_transaccion='DIAS_RESP')THEN

      v_fecha_actual = p_fecha_actual;
      WHILE  v_fecha_actual < p_fecha_limite LOOP
          IF (date_part('dow',v_fecha_actual) in (1,2,3,4,5))THEN
              v_contador = v_contador + 1;
          END IF;
          v_fecha_actual = v_fecha_actual + 1;
      END LOOP;

      IF(v_fecha_actual > p_fecha_limite) THEN
          v_contador=-1;
      END IF;

      return v_contador;
    ELSIF(p_transaccion='DIAS_INF')THEN
      --Dias para Adjuntar Informacion Adicional
      --begin
      IF (date_part('dow',p_fecha_limite) in (1,2,3))THEN
      	v_fecha_limite =  p_fecha_limite + 2;
        v_diferencia = v_fecha_limite - p_fecha_actual;
      	IF(v_diferencia>=0 AND v_diferencia<=2 )THEN
          v_contador = v_diferencia;
      	ELSE
          v_contador = -1;
      	END IF;
      ELSIF (date_part('dow',p_fecha_limite) in (4,5))THEN
      	v_fecha_limite =  p_fecha_limite + 4;
      	v_diferencia = v_fecha_limite - p_fecha_actual - 2;
      	IF(v_diferencia>=0 AND v_diferencia<=2 )THEN
        	v_contador = v_diferencia;
        ELSE
      		v_contador = -1;
        END IF;
      END IF;
      RETURN v_contador;
      --end
    ELSIF(p_transaccion='CONT_DIAS')THEN
        IF (date_part('dow',p_fecha_limite) in (1,2,3,4,5))THEN
    		v_fecha_actual = p_fecha_limite + 21;
            IF(v_fecha_actual=p_fecha_actual)THEN
        		v_contador = 15;
        	ELSE
        		v_contador = -1;
        	END IF;
        END IF;
        RETURN v_contador;
    END IF;

/*EXCEPTION
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;*/
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;