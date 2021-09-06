
/***********************************I-DEP-FEA-REC-1-07/01/2017****************************************/
select pxp.f_insert_testructura_gui ('REC', 'SISTEMA');
select pxp.f_insert_testructura_gui ('CAT', 'REC');
select pxp.f_insert_testructura_gui ('PROC2', 'REC');
select pxp.f_insert_testructura_gui ('RE2', 'REC');
select pxp.f_insert_testructura_gui ('RECTI', 'CAT');
select pxp.f_insert_testructura_gui ('MEDRE', 'CAT');
select pxp.f_insert_testructura_gui ('SISCOM', 'CAT');
select pxp.f_insert_testructura_gui ('MOTAN', 'CAT');
select pxp.f_insert_testructura_gui ('CLI', 'CAT');
select pxp.f_insert_testructura_gui ('RECM', 'PROC2');
select pxp.f_insert_testructura_gui ('REVREC', 'PROC2');
select pxp.f_insert_testructura_gui ('PENRES', 'PROC2');
select pxp.f_insert_testructura_gui ('VBRES', 'PROC2');
select pxp.f_insert_testructura_gui ('RL', 'PROC2');
select pxp.f_insert_testructura_gui ('RECADM', 'PROC2');
select pxp.f_insert_testructura_gui ('CONSREC', 'PROC2');
select pxp.f_insert_testructura_gui ('LIBREC', 'RE2');
select pxp.f_insert_testructura_gui ('CRM', 'RE2');
select pxp.f_insert_testructura_gui ('REPEST', 'RE2');
select pxp.f_insert_testructura_gui ('LIBRES', 'RE2');

CREATE OR REPLACE VIEW rec.vcliente (
    id_cliente,
    ap_paterno,
    ap_materno,
    nombre,
    nombre_completo1,
    nombre_completo2,
    ci,
    genero,
    ciudad_residencia,
    email,
    telefono,
    celular,
    apellidos)
AS
SELECT c.id_cliente,
    c.apellido_paterno AS ap_paterno,
    c.apellido_materno AS ap_materno,
    c.nombre,
    (((COALESCE(c.nombre, ''::character varying)::text || ' '::text) ||
        COALESCE(c.apellido_paterno, ''::character varying)::text) || ' '::text) || COALESCE(c.apellido_materno, ''::character varying)::text AS nombre_completo1,
    (((COALESCE(c.apellido_paterno, ''::character varying)::text || ' '::text)
        || COALESCE(c.apellido_materno, ''::character varying)::text) || ' '::text) || COALESCE(c.nombre, ''::character varying)::text AS nombre_completo2,
    c.ci,
    c.genero,
    c.ciudad_residencia,
    c.email,
    c.telefono,
    c.celular,
    ((COALESCE(c.apellido_paterno, ''::character varying)::text || ' '::text)
        || COALESCE(c.apellido_materno, ''::character varying)::text) || ' '::text AS apellidos
FROM rec.tcliente c;


CREATE OR REPLACE VIEW rec.vreclamo (
    nombre_incidente,
    id_reclamo,
    id_tipo_incidente,
    id_subtipo_incidente,
    nro_tramite,
    id_medio_reclamo,
    id_funcionario_recepcion,
    fecha_hora_incidente,
    fecha_hora_recepcion,
    id_cliente,
    nro_vuelo,
    origen,
    destino,
    id_oficina_incidente,
    id_oficina_registro_incidente,
    nro_frd,
    id_funcionario_denunciado,
    detalle_incidente,
    observaciones_incidente,
    id_proceso_wf,
    id_estado_wf,
    estado,
    correlativo_preimpreso_frd,
    fecha_limite_respuesta,
    fecha_hora_vuelo,
    nro_frsa,
    nro_hoja_ruta,
    nro_pir,
    nro_ripat_att,
    nro_att_canalizado,
    pnr)
AS
 SELECT ti.nombre_incidente,
    tr.id_reclamo,
    tr.id_tipo_incidente,
    tr.id_subtipo_incidente,
    tr.nro_tramite,
    tr.id_medio_reclamo,
    tr.id_funcionario_recepcion,
    tr.fecha_hora_incidente,
    tr.fecha_hora_recepcion,
    tr.id_cliente,
    tr.nro_vuelo,
    tr.origen,
    tr.destino,
    tr.id_oficina_incidente,
    tr.id_oficina_registro_incidente,
    tr.nro_frd,
    tr.id_funcionario_denunciado,
    tr.detalle_incidente,
    tr.observaciones_incidente,
    tr.id_proceso_wf,
    tr.id_estado_wf,
    tr.estado,
    tr.correlativo_preimpreso_frd,
    tr.fecha_limite_respuesta,
    tr.fecha_hora_vuelo,
    tr.nro_frsa,
    tr.nro_hoja_ruta,
    tr.nro_pir,
    tr.nro_ripat_att,
    tr.nro_att_canalizado,
    tr.pnr
   FROM rec.treclamo tr
   JOIN rec.ttipo_incidente ti ON ti.id_tipo_incidente = tr.id_tipo_incidente;

   CREATE OR REPLACE VIEW rec.vrespuesta (
    id_respuesta,
    id_reclamo,
    recomendaciones,
    nro_cite,
    respuesta,
    fecha_respuesta,
    estado_reg,
    procedente,
    fecha_notificacion,
    id_usuario_ai,
    id_usuario_reg,
    usuario_ai,
    fecha_reg,
    fecha_mod,
    id_usuario_mod,
    usr_reg,
    usr_mod,
    tipo_respuesta,
    asunto,
    nro_tramite,
    id_proceso_wf,
    id_estado_wf,
    estado,
    nombre_completo1,
    nombre,
    aprobador,
    cargo_aprobador,
    desc_funcionario,
    nombre_unidad,
    email,
    nro_tramite_rec)
AS
 SELECT DISTINCT ON (res.id_respuesta) res.id_respuesta,
    res.id_reclamo,
    res.recomendaciones,
    res.nro_cite,
    res.respuesta,
    res.fecha_respuesta,
    res.estado_reg,
    res.procedente,
    res.fecha_notificacion,
    res.id_usuario_ai,
    res.id_usuario_reg,
    res.usuario_ai,
    res.fecha_reg,
    res.fecha_mod,
    res.id_usuario_mod,
    usu1.cuenta AS usr_reg,
    usu2.cuenta AS usr_mod,
    res.tipo_respuesta,
    res.asunto,
    re.nro_tramite,
    re.id_proceso_wf,
    re.id_estado_wf,
    re.estado,
    cli.nombre_completo1,
    cli.nombre,
    funapro.desc_funcionario1 AS aprobador,
    upper(orga.f_get_cargo_x_funcionario_str(funapro.id_funcionario, 'now'::text::date)::text) AS cargo_aprobador,
    fun.desc_funcionario1 AS desc_funcionario,
    uo.nombre_unidad,
    cli.email,
    re.nro_tramite AS nro_tramite_rec
   FROM rec.trespuesta res
   JOIN segu.tusuario usu1 ON usu1.id_usuario = res.id_usuario_reg
   LEFT JOIN segu.tusuario usu2 ON usu2.id_usuario = res.id_usuario_mod
   JOIN rec.treclamo re ON re.id_reclamo = res.id_reclamo
   LEFT JOIN rec.vcliente cli ON cli.id_cliente = re.id_cliente
   JOIN orga.vfuncionario fun ON fun.id_funcionario = re.id_funcionario_recepcion
   LEFT JOIN orga.vfuncionario funapro ON funapro.id_funcionario = re.id_funcionario_recepcion
   JOIN orga.tuo_funcionario uof ON uof.id_funcionario = fun.id_funcionario
   JOIN orga.tuo uo ON uo.id_uo = orga.f_get_uo_gerencia(uof.id_uo, NULL::integer, NULL::date);

/***********************************F-DEP-FEA-REC-1-07/01/2017****************************************/

/***********************************I-DEP-FEA-REC-2-20/06/2017****************************************/
select pxp.f_insert_testructura_gui ('CONFAL', 'PROC2');
select pxp.f_insert_tprocedimiento_gui ('REC_LOGS_FAL_SEL', 'CONFAL', 'no');

select pxp.f_insert_tgui_rol ('CONFAL', 'REC - Administrador Reclamos');
select pxp.f_insert_tgui_rol ('CONFAL', 'REC - Tecnico SAC');

select pxp.f_insert_trol_procedimiento_gui ('REC - Administrador Reclamos', 'REC_LOGS_FAL_SEL', 'CONFAL');
select pxp.f_insert_trol_procedimiento_gui ('REC - Tecnico SAC', 'REC_LOGS_FAL_SEL', 'CONFAL');
/***********************************F-DEP-FEA-REC-2-20/06/2017****************************************/