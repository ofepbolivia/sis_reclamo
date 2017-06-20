/***********************************I-DAT-FEA-REC-1-27/01/2017****************************************/

INSERT INTO segu.tsubsistema ("codigo", "nombre", "fecha_reg", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'REC', E'Gestion de Reclamos', E'2016-08-09', E'REC', E'activo', E'reclamo', NULL);

select pxp.f_insert_tgui ('<i class="fa fa-comments-o fa-2x"></i>GESTIÓN DE RECLAMOS', '', 'REC', 'si', 1, '', 1, '', '', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-compress  fa-2x"></i>Compensaciones', 'Compensacion', 'SISCOM', 'si', 3, 'sis_reclamo/vista/compensacion/Compensacion.php', 2, '', 'Compensacion', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-pinterest fa-2x"></i>Tipos de Incidentes', 'TipoIncidente', 'RECTI', 'si', 1, 'sis_reclamo/vista/tipo_incidente/TipoIncidente.php', 2, '', 'TipoIncidente', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-info-circle fa-2x"></i>Medios de Reclamo ', 'medio reclamo', 'MEDRE', 'si', 2, 'sis_reclamo/vista/medio_reclamo/MedioReclamo.php', 2, '', 'MedioReclamo', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-users fa-2x"></i>Clientes', 'Cliente Vista', 'CLI', 'si', 5, 'sis_reclamo/vista/cliente/Cliente.php', 2, '', 'Cliente', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-archive fa-2x"></i>Registro Reclamos', 'Reclamo', 'RECM', 'si', 1, 'sis_reclamo/vista/reclamo/RegistroReclamos.php', 2, '', 'RegistroReclamos', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-home fa-2x"></i>Catalogos', 'Catalogos', 'CAT', 'si', 1, '', 2, '', '', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-folder fa-2x"></i>Procesos', 'Procesos', 'PROC2', 'si', 2, '', 2, '', '', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-file-o fa-2x"></i>Reportes', 'Reportes', 'RE2', 'si', 3, '', 2, '', '', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-globe  fa-2x"></i>CRM Global', 'CRM Global', 'CRM', 'si', 2, 'sis_reclamo/vista/reporte/FormFiltros.php', 3, '', 'FormFiltros', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-folder-open fa-2x"></i>Consulta de Reclamos', 'Consulta de Reclamos', 'CONSREC', 'si', 7, 'sis_reclamo/vista/reclamo/ConsultaReclamo.php', 3, '', 'ConsultaReclamo', 'REC');
select pxp.f_insert_tgui ('<i class="fa  fa-ban fa-2x"></i>Motivos de Anulación', 'Motivos de Anulacion', 'MOTAN', 'si', 4, 'sis_reclamo/vista/motivo_anulado/MotivoAnulado.php', 3, '', 'MotivoAnulado', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-signal  fa-2x"></i>Reportes Estadisticos', 'Reportes Estadisticos', 'REPEST', 'si', 3, 'sis_reclamo/vista/reporte/ReportesEstadisticos.php', 3, '', 'ReportesEstadisticos', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-list-ul fa-2x"></i>Revisión Reclamos', 'Revision', 'REVREC', 'si', 2, 'sis_reclamo/vista/reclamo/RevisionReclamo.php', 3, '', 'RevisionReclamo', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-history fa-2x"></i>Pendiente Respuesta', 'Pendiente', 'PENRES', 'si', 3, 'sis_reclamo/vista/reclamo/PendienteRespuesta.php', 3, '', 'PendienteRespuesta', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-openid fa-2x"></i>VoBo Respuesta', 'VoBo Respuesta', 'VBRES', 'si', 4, 'sis_reclamo/vista/respuesta/VoBoRespuesta.php', 3, '', 'VoBoRespuesta', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-legal fa-2x"></i>Revision Legal', 'Revision Legal', 'RL', 'si', 5, 'sis_reclamo/vista/respuesta/RevisionLegal.php', 3, '', 'RevisionLegal', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-legal fa-2x"></i>Reclamo Administrativo', 'Administrativo', 'RECADM', 'si', 6, 'sis_reclamo/vista/reclamo/ReclamoAdministrativo.php', 3, '', 'ReclamoAdministrativo', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-clipboard fa-2x"></i>Libro de Reclamos', 'Libro de Reclamos', 'LIBREC', 'si', 1, 'sis_reclamo/vista/reporte/LibroReclamo.php', 3, '', 'LibroReclamo', 'REC');
select pxp.f_insert_tgui ('<i class="fa fa-clipboard fa-2x"></i>Libro de Respuestas', 'Libro de Respuestas', 'LIBRES', 'si', 4, 'sis_reclamo/vista/reporte/LibroRespuesta.php', 3, '', 'LibroRespuesta', 'REC');

/***********************************F-DAT-FEA-REC-1-27/01/2017****************************************/


/***********************************I-DAT-FEA-REC-2-20/06/2017****************************************/
select pxp.f_insert_tgui ('<i class="fa fa-search fa-2x"></i>Consulta Faltas', 'CONFAL', 'CONFAL', 'si', 9, 'sis_reclamo/vista/logs/LogsFaltas.php', 3, '', 'LogsFaltas', 'REC');

/***********************************F-DAT-FEA-REC-2-20/06/2017****************************************/