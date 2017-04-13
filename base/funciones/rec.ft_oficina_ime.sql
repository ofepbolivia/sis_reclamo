CREATE OR REPLACE FUNCTION rec.ft_oficina_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Reclamo
 FUNCION: 		rec.ft_oficina_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.toficina'
 AUTOR: 		F.E.A.
 FECHA:	        15-01-2014 16:05:34
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
	v_id_oficina	integer;

BEGIN

    v_nombre_funcion = 'rec.ft_oficina_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_OFI_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		F.E.A.
 	#FECHA:		15-01-2014 16:05:34
	***********************************/

	if(p_transaccion='REC_OFI_INS')then

        begin
        	--Sentencia de la insercion
        	insert into rec.toficina(
			aeropuerto,
			id_lugar,
			nombre,
			codigo,
			estado_reg,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
			--zona_franca,
			--frontera,
            correo_oficina,
			direccion
          	) values(
			v_parametros.aeropuerto,
			v_parametros.id_lugar,
			v_parametros.nombre,
			v_parametros.codigo,
			'activo',
			now(),
			p_id_usuario,
			null,
			null,
			--v_parametros.zona_franca,
			--v_parametros.frontera,
            v_parametros.correo_oficina,
			v_parametros.direccion

			)RETURNING id_oficina into v_id_oficina;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Oficinas almacenado(a) con exito (id_oficina'||v_id_oficina||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_oficina',v_id_oficina::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_OFI_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		F.E.A.
 	#FECHA:		15-01-2014 16:05:34
	***********************************/

	elsif(p_transaccion='REC_OFI_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.toficina set
			aeropuerto = v_parametros.aeropuerto,
			id_lugar = v_parametros.id_lugar,
			nombre = v_parametros.nombre,
			codigo = v_parametros.codigo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			--zona_franca = v_parametros.zona_franca,
			--frontera = v_parametros.frontera,
            correo_oficina = v_parametros.correo_oficina,
			direccion = v_parametros.direccion
			where id_oficina=v_parametros.id_oficina;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Oficinas modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_oficina',v_parametros.id_oficina::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_OFI_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		F.E.A.
 	#FECHA:		15-01-2014 16:05:34
	***********************************/

	elsif(p_transaccion='REC_OFI_ELI')then

		begin
			--Sentencia de la eliminacion
			update rec.toficina
			set estado_reg = 'inactivo'
            where id_oficina=v_parametros.id_oficina;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Oficinas eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_oficina',v_parametros.id_oficina::varchar);

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