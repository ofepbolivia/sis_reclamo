CREATE OR REPLACE FUNCTION rec.ft_correo_oficina_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_correo_oficina_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tcorreo_oficina'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        11-05-2018 22:27:57
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				11-05-2018 22:27:57								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'rec.tcorreo_oficina'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'rec.ft_correo_oficina_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'REC_cof_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		11-05-2018 22:27:57
	***********************************/

	if(p_transaccion='REC_cof_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						cof.id_correo_oficina,
						cof.correo,
						cof.id_oficina,
						cof.estado_reg,
						cof.id_funcionario,
						cof.id_usuario_ai,
						cof.usuario_ai,
						cof.fecha_reg,
						cof.id_usuario_reg,
						cof.id_usuario_mod,
						cof.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        tof.nombre as desc_oficina,
                        cof.fecha_ini,
                        cof.fecha_fin	
						from rec.tcorreo_oficina cof
						inner join segu.tusuario usu1 on usu1.id_usuario = cof.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cof.id_usuario_mod
                        inner join rec.toficina tof on tof.id_oficina = cof.id_oficina
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'REC_cof_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		franklin.espinoza	
 	#FECHA:		11-05-2018 22:27:57
	***********************************/

	elsif(p_transaccion='REC_cof_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correo_oficina)
					    from rec.tcorreo_oficina cof
					    inner join segu.tusuario usu1 on usu1.id_usuario = cof.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = cof.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
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