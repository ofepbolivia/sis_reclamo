CREATE OR REPLACE FUNCTION rec.f_dia_literal (
  fecha date
)
RETURNS text AS
$body$
DECLARE
res int4;

begin 

res = extract(dow from fecha);

if(res = 1)then
	return 'Lunes';
elsif(res = 2)then
	return 'Martes';
elsif (res = 3)then
	return 'Miercoles';
elsif( res = 4)then
	return 'Jueves';
elsif( res = 5)then
	return 'Viernes';
elsif(res = 6)then
	return 'Sabado';
else
	return 'Domingo';
end if;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;