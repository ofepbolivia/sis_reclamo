CREATE OR REPLACE FUNCTION rec.ft_cliente_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Gestion de Reclamos
 FUNCION: 		rec.ft_cliente_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'rec.tcliente'
 AUTOR: 		 (admin)
 FECHA:	        12-08-2016 14:29:16
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
	v_id_cliente			integer;
    v_nombre				varchar;
    v_contador				integer;
    v_valid					varchar;
    v_func  				varchar='';
    v_record				record;

BEGIN

    v_nombre_funcion = 'rec.ft_cliente_ime';

    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'REC_CLI_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin
 	#FECHA:		12-08-2016 14:29:16
	***********************************/

	if(p_transaccion='REC_CLI_INS')then

        begin

        	--Sentencia de la insercion
        	insert into rec.tcliente(
			genero,
			ci,
			email,
            email2,
			direccion,
			celular,
			nombre,
			lugar_expedicion,
			apellido_paterno,
			telefono,
			ciudad_residencia,
			id_pais_residencia,
			nacionalidad,
			barrio_zona,
			estado_reg,
			apellido_materno,
			id_usuario_ai,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.genero,
			v_parametros.ci,
			v_parametros.email,
            v_parametros.email2,
			v_parametros.direccion,
			v_parametros.celular,
			upper(v_parametros.nombre),
			v_parametros.lugar_expedicion,
			upper(v_parametros.apellido_paterno),
			v_parametros.telefono,
			upper(v_parametros.ciudad_residencia),
			v_parametros.id_pais_residencia,
			upper(v_parametros.nacionalidad),
			upper(v_parametros.barrio_zona),
			'activo',
			upper(v_parametros.apellido_materno),
			v_parametros._id_usuario_ai,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			null,
			null



			)RETURNING id_cliente into v_id_cliente;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','cliente almacenado(a) con exito (id_cliente'||v_id_cliente||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_id_cliente::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_momento', 'new');

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_CLI_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin
 	#FECHA:		12-08-2016 14:29:16
	***********************************/

	elsif(p_transaccion='REC_CLI_MOD')then

		begin
        	--RAISE EXCEPTION 'MODIFICAR';
			--Sentencia de la modificacion
			update rec.tcliente set
			genero = v_parametros.genero,
			ci = v_parametros.ci,
			email = v_parametros.email,
            email2 = v_parametros.email2,
			direccion = v_parametros.direccion,
			celular = v_parametros.celular,
			nombre = upper(v_parametros.nombre),
			lugar_expedicion = v_parametros.lugar_expedicion,
			apellido_paterno = upper(v_parametros.apellido_paterno),
			telefono = v_parametros.telefono,
			ciudad_residencia = upper(v_parametros.ciudad_residencia),
			id_pais_residencia = v_parametros.id_pais_residencia,
			nacionalidad = upper(v_parametros.nacionalidad),
			barrio_zona = upper(v_parametros.barrio_zona),
			apellido_materno = upper(v_parametros.apellido_materno),
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_cliente=v_parametros.id_cliente;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','cliente modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_parametros.id_cliente::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'v_momento', 'edit');

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'REC_CLI_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin
 	#FECHA:		12-08-2016 14:29:16
	***********************************/

	elsif(p_transaccion='REC_CLI_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from rec.tcliente
            where id_cliente=v_parametros.id_cliente;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','cliente eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente',v_parametros.id_cliente::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'REC_NOMCLI_GET'
 	#DESCRIPCION:	RECUPERA EL NOMBRE COMPLETO DE UN CLIENTE
 	#AUTOR:		admin
 	#FECHA:		31-10-2016 14:29:16
	***********************************/

	elsif(p_transaccion='REC_NOMCLI_GET')then

		begin
			select vc.nombre_completo1 into v_nombre
            from rec.vcliente vc
            where vc.id_cliente = v_parametros.id_cliente;


            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Nombre Cliente');
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_completo1',v_nombre);


            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'CLI_VALIDAR_GET'
 	#DESCRIPCION:	VERIFICA LA DUPLICIDAD DE CLIENTES
 	#AUTOR:		Franklin Espinoza
 	#FECHA:		31-12-2016 14:29:16
	***********************************/

	elsif(p_transaccion='CLI_VALIDAR_GET')then

		begin
			select count(tc.ci)
            INTO v_contador
            from rec.tcliente tc
            where tc.nombre % trim(both ' ' from upper(v_parametros.nombre)) AND tc.apellido_paterno % trim(both ' ' from upper(v_parametros.apellido)) AND similarity_dist(tc.ci, trim(both ' ' from v_parametros.ci)) = 0; -- tc.ci % trim(both ' ' from v_parametros.ci);
            IF(v_contador>=1)THEN
        		    v_valid = 'true';

        		    SELECT vf.desc_funcionario1
                INTO v_func
                FROM rec.tcliente tcl
                INNER JOIN segu.tusuario tu ON tu.id_usuario = tcl.id_usuario_reg
                INNER JOIN orga.vfuncionario_persona vf ON vf.id_persona = tu.id_persona
                WHERE tcl.nombre % trim(both ' ' from upper(v_parametros.nombre)) AND tcl.apellido_paterno % trim(both ' ' from upper(v_parametros.apellido)) AND tcl.ci % trim(both ' ' from v_parametros.ci);
            ELSE
            	  v_valid = 'false';
			      END IF;
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Existe el Cliente');
            v_resp = pxp.f_agrega_clave(v_resp,'v_valid',v_valid);
            v_resp = pxp.f_agrega_clave(v_resp,'v_desc_func',v_func::varchar);
            --Devuelve la respuesta
            return v_resp;

		end;
		/*********************************
 	#TRANSACCION:  'REC_CLI_GET'
 	#DESCRIPCION:	OBTENER DATOS DE CLIENTE PARA EDITARLO EN EL FORMULARIO DE RESPUESTA
 	#AUTOR:		Franklin Espinoza
 	#FECHA:		31-12-2016 14:29:16
	***********************************/

	elsif(p_transaccion='REC_CLI_GET')then

		begin
			select
              cli.id_cliente,
              cli.genero,
              cli.ci,
              cli.email,
              cli.email2,
              cli.direccion,
              cli.celular,
              cli.nombre,
              cli.lugar_expedicion,
              cli.apellido_paterno,
              cli.telefono,
              cli.ciudad_residencia,
              cli.id_pais_residencia,
              cli.nacionalidad,
              cli.barrio_zona,
              cli.estado_reg,
              cli.apellido_materno,
              cli.id_usuario_ai,
              cli.fecha_reg,
              cli.usuario_ai,
              cli.id_usuario_reg,
              cli.fecha_mod,
              cli.id_usuario_mod,
              usu1.cuenta as usr_reg,
              usu2.cuenta as usr_mod,
              c.nombre_completo1 as completo,
              c.nombre_completo2,
              lug.nombre as pais_residencia
              into v_record
              from rec.tcliente cli
              inner join segu.tusuario usu1 on usu1.id_usuario = cli.id_usuario_reg
              left join segu.tusuario usu2 on usu2.id_usuario = cli.id_usuario_mod
              inner join rec.vcliente c on c.id_cliente = cli.id_cliente
              left join param.tlugar lug on lug.id_lugar = cli.id_pais_residencia::integer
              where cli.id_cliente = v_parametros.id_cliente;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'id_cliente', v_record.id_cliente::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'genero', v_record.genero::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'ci', v_record.ci::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'email', v_record.email::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'email2', v_record.email2::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'direccion', v_record.direccion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'celular', v_record.celular::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre', v_record.nombre::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'lugar_expedicion', v_record.lugar_expedicion::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'apellido_paterno', v_record.apellido_paterno::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'telefono', v_record.telefono::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'ciudad_residencia', v_record.ciudad_residencia::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'id_pais_residencia', v_record.id_pais_residencia::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nacionalidad', v_record.nacionalidad::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'barrio_zona', v_record.barrio_zona::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'apellido_materno', v_record.apellido_materno::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'completo',v_record.completo::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'nombre_completo2',v_record.nombre_completo2::varchar);
            v_resp = pxp.f_agrega_clave(v_resp,'pais_residencia',v_record.pais_residencia::varchar);
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