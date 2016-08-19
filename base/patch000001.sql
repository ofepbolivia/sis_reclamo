/***********************************I-SCP-MAM-REC-1-10/08/2016****************************************/
CREATE TABLE rec.tcompensacion (
  id_compensacion SERIAL,
  nombre VARCHAR(100) NOT NULL,
  codigo VARCHAR(100) NOT NULL,
  CONSTRAINT tcompensacion_pkey PRIMARY KEY(id_compensacion)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE rec.trespuesta (
  id_respuesta SERIAL,
  id_reclamo INTEGER NOT NULL,
  nro_cite INTEGER NOT NULL,
  fecha_respuesta DATE NOT NULL,
  respuesta VARCHAR(100) NOT NULL,
  procedimiento VARCHAR(100) NOT NULL,
  recomendaciones VARCHAR(100) NOT NULL,
  fecha_notificacion DATE NOT NULL,
  CONSTRAINT trespuesta_pkey PRIMARY KEY(id_respuesta)
) INHERITS (pxp.tbase)

WITH (oids = false);

/***********************************F-SCP-MAM-REC-1-10/08/2016****************************************/

/***********************************I-SCP-FEA-REC-1-10/08/2016****************************************/

CREATE TABLE rec.treclamo (
  id_reclamo SERIAL,
  id_tipo_incidente INTEGER NOT NULL,
  id_subtipo_incidente INTEGER,
  nro_tramite INTEGER,
  id_medio_reclamo INTEGER NOT NULL,
  id_funcionario_recepcion INTEGER NOT NULL,
  fecha_hora_incidente TIMESTAMP(6) WITHOUT TIME ZONE,
  fecha_hora_recepcion TIMESTAMP(6) WITHOUT TIME ZONE,
  id_cliente INTEGER,
  pnr INTEGER,
  nro_vuelo VARCHAR(10),
  origen VARCHAR(10),
  destino VARCHAR(10),
  hora_vuelo TIME(6) WITHOUT TIME ZONE,
  id_oficina_incidente INTEGER,
  id_oficina_registro_incidente INTEGER NOT NULL,
  nro_frd INTEGER,
  nro_frsa INTEGER,
  nro_pir INTEGER,
  nro_att_canalizado INTEGER,
  nro_ripat_att INTEGER,
  nro_hoja_ruta INTEGER,
  id_funcionario_denunciado INTEGER NOT NULL,
  detalle_incidente TEXT,
  observaciones_incidente TEXT,
  id_proceso_wf INTEGER NOT NULL,
  id_estado_wf INTEGER NOT NULL,
  estado VARCHAR(100),
  CONSTRAINT treclamo_pkey PRIMARY KEY(id_reclamo)

) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE rec.ttipo_incidente (
  id_tipo_incidente SERIAL,
  nombre_incidente VARCHAR(50) NOT NULL,
  fk_tipo_incidente INTEGER,
  tiempo_respuesta INTEGER,
  nivel INTEGER,
  CONSTRAINT ttipo_incidente_pkey PRIMARY KEY(id_tipo_incidente)
) INHERITS (pxp.tbase)

WITH (oids = false);


/***********************************F-SCP-FEA-REC-1-10/08/2016****************************************/

/***********************************I-SCP-FEA-REC-1-11/08/2016****************************************/
CREATE TABLE rec.tcliente (
  id_cliente SERIAL,
  ci VARCHAR(20),
  lugar_expedicion VARCHAR(10),
  nombre VARCHAR(30),
  apellido_paterno VARCHAR(30),
  apellido_materno VARCHAR(30),
  genero CHAR(1),
  nacionalidad VARCHAR(30),
  email VARCHAR(30),
  celular INTEGER,
  telefono INTEGER,
  pais_residencia VARCHAR(30),
  ciudad_residencia VARCHAR(30),
  barrio_zona VARCHAR(50),
  direccion VARCHAR(50),
  PRIMARY KEY(id_cliente, ci)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-FEA-REC-1-11/08/2016****************************************/


/***********************************I-SCP-EAQ-REC-1-19/08/2016****************************************/
CREATE TABLE rec.tmedio_reclamo (
  id_medio_reclamo SERIAL,
  codigo VARCHAR(20),
  nombre_medio VARCHAR(30),
  PRIMARY KEY( id_medio_reclamo)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-EAQ-REC-1-19/08/2016****************************************/

/***********************************I-SCP-EAQ-REC-1-11/08/2016****************************************/
CREATE TABLE rec.tinforme (
  id_informe SERIAL,
  id_reclamo SERIAL,
  nro_informe SERIAL,
  fecha_informe DATE,
  id_funcionario SERIAL,
  lista_compensacion VARCHAR(100),
  antecedentes_informe VARCHAR(100),
  analisis_tecnico VARCHAR(100),
  sugerencia_respuesta VARCHAR(100),
  conclusion_recomendacion VARCHAR(100),
  PRIMARY KEY(tinforme)
) INHERITS (pxp.tbase)

WITH (oids = false);
/***********************************F-SCP-EAQ-REC-1-11/08/2016****************************************/