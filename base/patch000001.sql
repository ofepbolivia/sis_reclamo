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
