
/***********************************I-DEP-MAM-REC-1-10/08/2016****************************************/
ALTER TABLE rec.tinforme
  ADD CONSTRAINT fk_tinforme__id_reclamo FOREIGN KEY (id_reclamo)
    REFERENCES rec.treclamo(id_reclamo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;

/***********************************F-DEP-MAM-REC-1-10/08/2016****************************************/