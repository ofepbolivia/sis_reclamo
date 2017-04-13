CREATE OR REPLACE FUNCTION rec.f_get_numero_frd (
  p_id_oficina integer,
  p_id_gestion integer,
  p_id_usuario integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 FUNCION: 		wf.f_get_numero_frd
 DESCRIPCION: 	Devuelve el siguiente numero frd de una oficina
 AUTOR: 		KPLIAN(FEA)
 FECHA:			10/02/2017
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:

***************************************************************************/
DECLARE
  v_num_siguiente INTEGER;
  v_gestion varchar;
  v_id_gestion integer;
  v_cont_gestion integer;
  v_codigo_siguiente VARCHAR(30);

BEGIN


   -- recupera datos del proceso macro

     /*select
        pm.id_proceso_macro
     into
        v_id_proceso_macro
     from wf.tproceso_macro pm
     where pm.codigo =  p_codigo_proceso;*/


    IF p_id_oficina is NULL THEN

     raise exception 'No existe la oficina Solicitada';

    END IF;

   --recupera la gestion

    select g.gestion
        into v_gestion
        from param.tgestion g
        where g.id_gestion =p_id_gestion;

  IF v_gestion is null THEN

   raise exception 'No se encontro la gestion solicitada' ;

  END IF;


  -- verifica si existe numeracion para la gestion solicitada

        select nf.numero
           into v_num_siguiente
        from rec.tnumero_frd nf
        where nf.id_gestion = p_id_gestion and nf.id_oficina = p_id_oficina;

   IF v_num_siguiente is NULL THEN

        INSERT INTO
            rec.tnumero_frd
          (
            id_usuario_reg,
            fecha_reg,
            fecha_mod,
            estado_reg,

            id_oficina,
            id_gestion,
            numero
          )
          VALUES (
            p_id_usuario,
            now(),
            NULL,
            'activo',

            p_id_oficina,
            p_id_gestion,
            1
          );

          v_num_siguiente=1;
   END IF;


--p_codigo_proceso




        UPDATE rec.tnumero_frd set
            numero = v_num_siguiente + 1
        WHERE id_gestion = p_id_gestion AND id_oficina = p_id_oficina;


        raise notice 'codigo_proceso = %',p_id_oficina;
        raise notice 'numero = %',v_num_siguiente;
        raise notice 'gestion = %',v_gestion;

    	--raise notice 'v_num_siguiente = %',v_num_siguiente;

   		RETURN v_num_siguiente::varchar;


END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY DEFINER
COST 100;