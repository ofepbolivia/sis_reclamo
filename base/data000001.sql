/***********************************I-DAT-MAM-REC-1-10/08/2016****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'REC', E'Gestion de Reclamos', E'2016-08-09', E'REC', E'activo', E'reclamo', NULL);


select pxp.f_insert_tgui ('RECLAMO', '', 'REC', 'si', 1, '', 1, '', '', 'REC');
select pxp.f_insert_tgui ('Compensacion', 'Compensacion', 'SISCOM', 'si', 1, 'sis_reclamo/vista/compensacion/Compensacion.php', 2, '', 'Compensacion', 'REC');
select pxp.f_insert_tgui ('Respuesta', 'Respuesta', 'SISRES', 'si', 1, 'sis_reclamo/vista/respuesta/Respuesta.php', 2, '', 'Respuesta', 'REC');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_insert_tgui ('Informe', 'Informe', 'SISINF', 'si', 1, 'sis_reclamo/vista/informe/Informe.php', 2, '', 'Informe', 'REC');


select pxp.f_insert_testructura_gui ('REC', 'SISTEMA');
select pxp.f_insert_testructura_gui ('SISCOM', 'REC');
select pxp.f_insert_testructura_gui ('SISRES', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_insert_testructura_gui ('SISINF', 'REC');

/***********************************F-DAT-MAM-REC-1-10/08/2016****************************************/

/***********************************I-DAT-EAQ-REC-1-10/08/2016****************************************/
select pxp.f_insert_tgui ('medio reclamo', 'medio reclamo', 'MEDRE', 'si', 5, 'sis_reclamo/vista/medio_reclamo/MedioReclamo.php', 2, '', 'MedioReclamo', 'REC');
select pxp.f_insert_tgui ('informe', 'informe', 'INFORM', 'si', 6, 'sis_reclamo/vista/informe/Informe.php', 2, '', 'Informe', 'REC');
/***********************************F-DAT-EAQ-REC-1-10/08/2016****************************************/

/***********************************I-DAT-FEA-REC-1-11/08/2016****************************************/
select pxp.f_delete_tgui ('INFORM');
select pxp.f_delete_testructura_gui ('INFORM', 'REC');
/***********************************F-DAT-FEA-REC-1-11/08/2016****************************************/


/***********************************I-DAT-MAM-REC-1-15/08/2016****************************************/
select pxp.f_insert_tgui ('Respuesta', 'Respuesta', 'SISRES', 'si', 1, 'sis_reclamo/vista/respuesta/Respuesta.php', 2, '', 'Respuesta', 'REC');
select pxp.f_insert_tgui ('Reclamo', 'Reclamo', 'recl', 'si', 1, 'sis_reclamo/vista/reclamo/Reclamo.php', 2, '', 'Reclamo', 'REC');
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('recl', 'REC');
/***********************************F-DAT-MAM-REC-1-15/08/2016****************************************/


/***********************************I-DAT-MAM-REC-1-19/08/2016****************************************/
select pxp.f_delete_tgui ('SISRES');
select pxp.f_delete_tgui ('recl');
select pxp.f_delete_tgui ('RED');
select pxp.f_delete_tgui ('RECR');
select pxp.f_insert_tgui ('Respuesta Reclamo', 'Respuesta Reclamo', 'RESRE', 'si', 1, 'sis_reclamo/vista/respuesta/RespuestaRecla.php', 2, '', 'RespuestaRecla', 'REC');

select pxp.f_delete_testructura_gui ('SISRES', 'REC');
select pxp.f_delete_testructura_gui ('recl', 'REC');
select pxp.f_delete_testructura_gui ('RED', 'REC');
select pxp.f_delete_testructura_gui ('RECR', 'REC');
select pxp.f_insert_testructura_gui ('RESRE', 'REC');
/***********************************F-DAT-MAM-REC-1-19/08/2016****************************************/

/***********************************I-DAT-EAQ-REC-1-19/08/2016****************************************/
select pxp.f_insert_testructura_gui ('MEDRE', 'REC');
/***********************************F-DAT-EAQ-REC-1-19/08/2016****************************************/

/***********************************I-DAT-MAM-REC-1-23/08/2016****************************************/
select pxp.f_delete_testructura_gui ('RECR', 'REC');
select pxp.f_insert_testructura_gui ('RECM', 'REC');
/***********************************F-DAT-MAM-REC-1-23/08/2016****************************************/

/***********************************I-DAT-FEA-REC-1-23/08/2016****************************************/
select pxp.f_insert_tgui ('Cliente', 'Cliente Vista', 'CLI', 'si', 1, 'sis_reclamo/vista/cliente/Cliente.php', 2, '', 'Cliente', 'REC');
select pxp.f_insert_tgui ('TipoIncidente', 'TipoIncidente', 'RTI', 'si', 1, 'sis_reclamo/vista/tipo_incidente/TipoIncidente.php', 2, '', 'TipoIncidente', 'REC');

select pxp.f_insert_testructura_gui ('CLI', 'REC');
select pxp.f_insert_testructura_gui ('RTI', 'REC');
/***********************************F-DAT-FEA-REC-1-23/08/2016****************************************/

/***********************************I-DAT-MAM-REC-2-23/08/2016****************************************/
select pxp.f_insert_tgui ('Reclamo', 'Reclamo', 'RECM', 'si', 1, 'sis_reclamo/vista/reclamo/Reclamo.php', 2, '', 'Reclamo', 'REC');
select pxp.f_insert_testructura_gui ('RECM', 'REC');
/***********************************F-DAT-MAM-REC-2-23/08/2016****************************************/

/***********************************I-DAT-FEA-REC-1-26/08/2016****************************************/
INSERT INTO rec.tcliente ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_cliente", "ci", "lugar_expedicion", "nombre", "apellido_paterno", "apellido_materno", "genero", "nacionalidad", "email", "celular", "telefono", "pais_residencia", "ciudad_residencia", "barrio_zona", "direccion")
VALUES
  (1, 1, E'2016-08-12 11:53:54.216', E'2016-08-15 12:19:30.336', E'activo', NULL, E'NULL', 1, E'prueba', E'prueba', E'Grover', E'prueba', E'prueba', E'h', E'prueba', E'prueba', 1, 1, E'prueba', E'prueba', E'prueba', E'prueba'),
  (1, 1, E'2016-08-12 11:53:59.700', E'2016-08-22 10:17:43.410', E'activo', NULL, E'NULL', 2, E'prueba', E'prueba', E'Franklin', E'prueba', E'prueba', E'm', E'prueba', E'prueba', 1, 1, E'prueba', E'prueba', E'prueba', E'prueba'),
  (1, 1, E'2016-08-22 11:31:07.069', E'2016-08-26 11:27:42.693', E'activo', NULL, E'NULL', 3, E'4564654', E'SC', E'federico', E'fernandez', E'mendez', E'VARON', E'boliviano', E'federico@gmail.com', 78546546, 4564655, E'bolivia', E'Santa cruz', E'las cuadras', E'calle ballivian # 156'),
  (1, 1, E'2016-08-22 12:15:41.848', E'2016-08-26 11:21:43.716', E'activo', NULL, E'NULL', 4, E'4845465', E'SC', E'PEDRO', E'JIMENEZ', E'PEREZ', E'varon', E'Boliviano', E'pedro@gmail.com', 78456544, 4565656, E'bolivia', E'cochabamba', E'cercado', E'Calle bolivar'),
  (1, 1, E'2016-08-22 12:19:54.270', E'2016-08-26 11:29:38.235', E'activo', NULL, E'NULL', 5, E'89894754', E'BN', E'carla', E'rojas', E'cruz', E'MUJER', E'Boliviana', E'carla@gmail.com', 75646546, 3546215, E'Bolivia', E'Tarija', E'Morados', E'Calle  la Esquina'),
  (1, 1, E'2016-08-22 12:22:04.100', E'2016-08-26 11:24:03.190', E'activo', NULL, E'NULL', 6, E'4324334', E'TJ', E'carlos', E'alvarez', E'zeballos', E'VARON', E'ccccc', E'sdfs@hotmail.com', 5456465, 4564, E'CHILE', E'LA PAZ', E'MAYORAZGO', E'Av. Ayacucho #456'),
  (1, NULL, E'2016-08-22 12:40:59.068', NULL, E'activo', NULL, E'NULL', 7, E'45656566', E'CB', E'VANESSA', E'ZEBALLOS', E'CORDOVA', E'MUJER', E'BOLIVIANA', E'zeballos@gmail.com', 72275654, 4564564, E'bolivia', E'cochabamba', E'el jardin', E'calle jordan esquina nataniel aguirre.'),
  (1, 1, E'2016-08-23 16:25:58.699', E'2016-08-26 11:31:06.647', E'activo', NULL, E'NULL', 8, E'4566456', E'CB', E'martha', E'meneces', E'espinoza', E'MUJER', E'BolivianA', E'marta@gmail.com', 78465646, 4565656, E'bolivia', E'cochabamba', E'quillacollo', E'calle oquendo # 56'),
  (1, 1, E'2016-08-23 16:44:02', E'2016-08-26 11:23:42.132', E'activo', NULL, E'NULL', 9, E'4568946', E'CB', E'adriana', E'adriazola', E'enriquez', E'MUJER', E'boliviana', E'adriana@gmail.com', 75462145, 4621312, E'bolivia', E'cochabamba', E'cercado', E'calle sucre esq. san martin');

INSERT INTO rec.ttipo_incidente ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_incidente", "nombre_incidente", "fk_tipo_incidente", "tiempo_respuesta", "nivel")
VALUES
  (1, NULL, E'2016-08-25 18:29:08.451', NULL, E'activo', NULL, E'NULL', 1, E'Reclamos', NULL, 7, 0),
  (1, NULL, E'2016-08-25 18:29:34.876', NULL, E'activo', NULL, E'NULL', 2, E'Solicitud', NULL, 5, 0),
  (1, NULL, E'2016-08-25 18:29:51.360', NULL, E'activo', NULL, E'NULL', 3, E'Comentario', NULL, 5, 0),
  (1, 1, E'2016-08-25 18:31:39.416', E'2016-08-26 10:47:48.302', E'activo', NULL, E'NULL', 4, E'Equipaje', 1, 10, 1),
  (1, NULL, E'2016-08-25 18:32:13.916', NULL, E'activo', NULL, E'NULL', 6, E'Carga/Encomienda', 1, 10, 1),
  (1, NULL, E'2016-08-26 10:47:19.913', NULL, E'activo', NULL, E'NULL', 36, E'Vuelo', 1, 7, 1),
  (1, NULL, E'2016-08-26 10:49:52.246', NULL, E'activo', NULL, E'NULL', 37, E'Pasaje/Boleto', 1, 10, 1),
  (1, NULL, E'2016-08-26 10:51:05.468', NULL, E'activo', NULL, E'NULL', 38, E'Atencion al Usuario', 1, 10, 1),
  (1, NULL, E'2016-08-26 12:25:06.273', NULL, E'activo', NULL, E'NULL', 39, E'jkhjk', 38, 4, 2);
/***********************************F-DAT-FEA-REC-1-26/08/2016****************************************/