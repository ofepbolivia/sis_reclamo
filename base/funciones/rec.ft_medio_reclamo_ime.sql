CREATE OR REPLACE FUNCTION rec.ft_medio_reclamo_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_medio_reclamo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tmedio_reclamo'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 01:21:34
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
	v_id_medio_reclamo	integer;

BEGIN

	v_nombre_funcion = 'rec.ft_medio_reclamo_ime';
	v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_MERA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 01:21:34
	***********************************/

	if(p_transaccion='REC_MERA_INS')then

		begin
			--Sentencia de la insercion
			insert into rec.tmedio_reclamo(
				codigo,
				estado_reg,
				nombre_medio,
				id_usuario_reg,
				fecha_reg,
				usuario_ai,
				id_usuario_ai,
				fecha_mod,
				id_usuario_mod,
                orden
			) values(
				v_parametros.codigo,
				'activo',
				v_parametros.nombre_medio,
				p_id_usuario,
				now(),
				v_parametros._nombre_usuario_ai,
				v_parametros._id_usuario_ai,
				null,
				null,
				v_parametros.orden


			)RETURNING id_medio_reclamo into v_id_medio_reclamo;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medio Reclamo almacenado(a) con exito (id_medio_reclamo'||v_id_medio_reclamo||')');
			v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_id_medio_reclamo::varchar);

			--Devuelve la respuesta
			return v_resp;

		end;

		/*********************************
     #TRANSACCION:  'REC_MERA_MOD'
     #DESCRIPCION:	Modificacion de registros
     #AUTOR:		admin
     #FECHA:		11-08-2016 01:21:34
    ***********************************/

	elsif(p_transaccion='REC_MERA_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tmedio_reclamo set
				codigo = v_parametros.codigo,
				nombre_medio = v_parametros.nombre_medio,
				fecha_mod = now(),
				id_usuario_mod = p_id_usuario,
				id_usuario_ai = v_parametros._id_usuario_ai,
				usuario_ai = v_parametros._nombre_usuario_ai,
                orden = v_parametros.orden
			where id_medio_reclamo=v_parametros.id_medio_reclamo;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medio Reclamo modificado(a)');
			v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_parametros.id_medio_reclamo::varchar);

			--Devuelve la respuesta
			return v_resp;

		end;

		/*********************************
     #TRANSACCION:  'REC_MERA_ELI'
     #DESCRIPCION:	Eliminacion de registros
     #AUTOR:		admin
     #FECHA:		11-08-2016 01:21:34
    ***********************************/

	elsif(p_transaccion='REC_MERA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tmedio_reclamo
			where id_medio_reclamo=v_parametros.id_medio_reclamo;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Medio Reclamo eliminado(a)');
			v_resp = pxp.f_agrega_clave(v_resp,'id_medio_reclamo',v_parametros.id_medio_reclamo::varchar);

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