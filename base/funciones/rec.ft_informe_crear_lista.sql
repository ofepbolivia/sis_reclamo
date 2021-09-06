CREATE OR REPLACE FUNCTION rec.ft_informe_crear_lista (
  lista varchar
)
RETURNS varchar AS
$body$
DECLARE
  v_compensacion 		varchar[];
  cont 					integer;
  v_valores 			varchar = '';
  v_valor				varchar;
  v_tam					integer;
  v_lista_compensacion  varchar;
BEGIN

			v_compensacion = string_to_array(lista,',')::varchar[];
            v_tam = array_length(v_compensacion,1);
        	if (v_tam>0)then
            	for cont in 1..v_tam loop
                    select com.nombre  into  v_valor
                    from rec.tcompensacion com
                    where com.id_compensacion = v_compensacion[cont]::integer;
                    if (cont < v_tam) then
                    	v_valores = v_valores ||cont||'. '|| v_valor || ',';
                    else
                    	v_valores = v_valores ||cont||'. '|| v_valor;
                    end if;
                end loop;
			end if;

            return v_valores;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;