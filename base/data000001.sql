/***********************************I-DAT-MAM-REC-1-10/08/2016****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'REC', E'Gestion de Reclamos', E'2016-08-09', E'REC', E'activo', E'reclamo', NULL);


select pxp.f_insert_tgui ('RECLAMO', '', 'RECL', 'si', 1, '', 1, '', '', 'REC');
select pxp.f_insert_tgui ('Compensacion', 'Compensacion', 'SISCOM', 'si', 1, 'sis_reclamo/vista/compensacion/Compensacion.php', 2, '', 'Compensacion', 'REC');
select pxp.f_insert_tgui ('Respuesta', 'Respuesta', 'SISRES', 'si', 1, 'sis_reclamo/vista/respuesta/Respuesta.php', 2, '', 'Respuesta', 'REC');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_delete_tgui ('SISINF');
select pxp.f_insert_tgui ('Informe', 'Informe', 'SISINF', 'si', 1, 'sis_reclamo/vista/informe/Informe.php', 2, '', 'Informe', 'REC');
----------------------------------
--COPY LINES TO dependencies.sql FILE
---------------------------------

select pxp.f_insert_testructura_gui ('RECL', 'SISTEMA');
select pxp.f_insert_testructura_gui ('SISCOM', 'RECL');
select pxp.f_insert_testructura_gui ('SISRES', 'RECL');
select pxp.f_delete_testructura_gui ('SISINF', 'RECL');
select pxp.f_delete_testructura_gui ('SISINF', 'RECL');
select pxp.f_delete_testructura_gui ('SISINF', 'RECL');
select pxp.f_insert_testructura_gui ('SISINF', 'RECL');

/***********************************F-DAT-MAM-REC-1-10/08/2016****************************************/