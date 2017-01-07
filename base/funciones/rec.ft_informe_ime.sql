CREATE OR REPLACE FUNCTION rec.ft_informe_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_informe_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tinforme'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 01:52:07
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_informe	integer;



BEGIN

    v_nombre_funcion = 'rec.ft_informe_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_INFOR_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 01:52:07
	***********************************/

	if(p_transaccion='REC_INFOR_INS')then

        begin
        	--Sentencia de la insercion
        	insert into rec.tinforme(
			sugerencia_respuesta,
			id_reclamo,
			antecedentes_informe,
			nro_informe,
			id_funcionario,
			conclusion_recomendacion,
			fecha_informe,
			estado_reg,
			lista_compensacion,
			analisis_tecnico,
			id_usuario_ai,
			id_usuario_reg,
			usuario_ai,
			fecha_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.sugerencia_respuesta,
			v_parametros.id_reclamo,
			v_parametros.antecedentes_informe,
			v_parametros.nro_informe,
			v_parametros.id_funcionario,
			v_parametros.conclusion_recomendacion,
			v_parametros.fecha_informe,
			'activo',
			v_parametros.lista_compensacion,
			v_parametros.analisis_tecnico,
			v_parametros._id_usuario_ai,
			p_id_usuario,
			v_parametros._nombre_usuario_ai,
			now(),
			null,
			null



			)RETURNING id_informe into v_id_informe;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','informe almacenado(a) con exito (id_informe'||v_id_informe||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_id_informe::varchar);

            --Devuelve la respuesta
            raise notice 'sal: %', v_resp;
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_INFOR_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 01:52:07
	***********************************/

	elsif(p_transaccion='REC_INFOR_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tinforme set
			sugerencia_respuesta = v_parametros.sugerencia_respuesta,
				id_reclamo = v_parametros.id_reclamo,
			antecedentes_informe = v_parametros.antecedentes_informe,
			nro_informe = v_parametros.nro_informe,
			id_funcionario = v_parametros.id_funcionario,
			conclusion_recomendacion = v_parametros.conclusion_recomendacion,
			fecha_informe = v_parametros.fecha_informe,
			lista_compensacion = v_parametros.lista_compensacion,
			analisis_tecnico = v_parametros.analisis_tecnico,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_informe=v_parametros.id_informe;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','informe modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_parametros.id_informe::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_INFOR_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 01:52:07
	***********************************/

	elsif(p_transaccion='REC_INFOR_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tinforme
            where id_informe=v_parametros.id_informe;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','informe eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_informe',v_parametros.id_informe::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

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