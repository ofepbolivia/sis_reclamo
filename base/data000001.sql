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
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('REC', 'SISTEMA');
select pxp.f_insert_testructura_gui ('SISCOM', 'REC');
select pxp.f_insert_testructura_gui ('SISRES', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_delete_testructura_gui ('SISINF', 'REC');
select pxp.f_insert_testructura_gui ('SISINF', 'REC');

/***********************************F-DAT-MAM-REC-1-10/08/2016****************************************/

/***********************************I-DAT-FEA-REC-2-11/08/2016****************************************/
select pxp.f_insert_tgui ('TipoIncidente', 'TipoIncidente', 'RECTI', 'si', 1, 'sis_reclamo/vista/tipo_incidente/TipoIncidente.php', 2, '', 'TipoIncidente', 'REC');
select pxp.f_insert_testructura_gui ('RECTI', 'REC');
/***********************************F-DAT-FEA-REC-2-11/08/2016****************************************/

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

