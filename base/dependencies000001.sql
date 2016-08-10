
/***********************************I-DEP-MAM-REC-1-10/08/2016****************************************/
ALTER TABLE rec.tinforme
  ADD CONSTRAINT fk_tinforme__id_reclamo FOREIGN KEY (id_reclamo)
    REFERENCES rec.treclamo(id_reclamo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

/***********************************F-DEP-MAM-REC-1-10/08/2016****************************************/

/***********************************I-DEP-FEA-REC-1-10/08/2016****************************************/
ALTER TABLE rec.ttipo_incidente
ADD CONSTRAINT ttipo_incidente_fk FOREIGN KEY (fk_tipo_incidente)
    REFERENCES rec.ttipo_incidente(id_tipo_incidente)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
ADD CONSTRAINT id_estado_wf_fk FOREIGN KEY (id_estado_wf)
    REFERENCES wf.testado_wf(id_estado_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
ADD   CONSTRAINT id_funcionario_denunciado_fk FOREIGN KEY (id_funcionario_denunciado)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_funcionario_recepcion_fk FOREIGN KEY (id_funcionario_recepcion)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_medio_reclamo_fk FOREIGN KEY (id_medio_reclamo)
    REFERENCES rec.tmedio_reclamo(id_medio_reclamo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_oficina_incidente_fk FOREIGN KEY (id_oficina_incidente)
    REFERENCES orga.toficina(id_oficina)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_oficina_registro_incidente_fk FOREIGN KEY (id_oficina_registro_incidente)
    REFERENCES orga.toficina(id_oficina)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_proceso_wf_fk FOREIGN KEY (id_proceso_wf)
    REFERENCES wf.tproceso_wf(id_proceso_wf)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
 ADD  CONSTRAINT id_subtipo_incidente_fk FOREIGN KEY (id_subtipo_incidente)
    REFERENCES rec.ttipo_incidente(id_tipo_incidente)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

ALTER TABLE rec.treclamo
  ADD CONSTRAINT treclamo_id_tipo_incidente_fkey FOREIGN KEY (id_tipo_incidente)
    REFERENCES rec.ttipo_incidente(id_tipo_incidente)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

/***********************************F-DEP-FEA-REC-1-10/08/2016****************************************/