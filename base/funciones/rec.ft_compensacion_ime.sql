CREATE OR REPLACE FUNCTION rec.ft_compensacion_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_compensacion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tcompensacion'
 AUTOR: 		 (admin)
 FECHA:	        11-08-2016 15:38:39
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
	v_id_compensacion	integer;

BEGIN

    v_nombre_funcion = 'rec.ft_compensacion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_Com_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 15:38:39
	***********************************/

	if(p_transaccion='REC_Com_INS')then

        begin
        	--Sentencia de la insercion
        	insert into rec.tcompensacion(
			nombre,
			codigo,
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
            orden
          	) values(
			v_parametros.nombre,
			v_parametros.codigo,
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null,
			v_parametros.orden


			)RETURNING id_compensacion into v_id_compensacion;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion almacenado(a) con exito (id_compensacion'||v_id_compensacion||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_id_compensacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_Com_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 15:38:39
	***********************************/

	elsif(p_transaccion='REC_Com_MOD')then

		begin
			--Sentencia de la modificacion
			update rec.tcompensacion set
			nombre = v_parametros.nombre,
			codigo = v_parametros.codigo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            orden=v_parametros.orden
			where id_compensacion=v_parametros.id_compensacion;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_parametros.id_compensacion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_Com_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		11-08-2016 15:38:39
	***********************************/

	elsif(p_transaccion='REC_Com_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tcompensacion
            where id_compensacion=v_parametros.id_compensacion;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Compensacion eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_compensacion',v_parametros.id_compensacion::varchar);

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