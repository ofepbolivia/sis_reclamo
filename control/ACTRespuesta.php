<?php
/**
 *@package pXP
 *@file gen-ACTRespuesta.php
 *@author  (admin)
 *@date 11-08-2016 16:01:08
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../reportes/RRespuesta.php');

class ACTRespuesta extends ACTbase{

    function listarRespuesta(){
        $this->objParam->defecto('ordenacion','id_respuesta');
        $this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_reclamo') != '') {
            $this->objParam->addFiltro(" res.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }
        
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODRespuesta','listarRespuesta');
        } else{
            $this->objFunc=$this->create('MODRespuesta');

            $this->res=$this->objFunc->listarRespuesta($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarRespuesta(){
        $this->objFunc=$this->create('MODRespuesta');
        if($this->objParam->insertar('id_respuesta')){
            $this->res=$this->objFunc->insertarRespuesta($this->objParam);
        } else{
            $this->res=$this->objFunc->modificarRespuesta($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarRespuesta(){
        $this->objFunc=$this->create('MODRespuesta');
        $this->res=$this->objFunc->eliminarRespuesta($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function reporteRespuesta(){

        $dataSource = $this->reportesRespuesta();
        $nombreArchivo = uniqid(md5(session_id()).'MemoAsignación').'.docx';
        $reporte = new RMemoAsignacion($this->objParam);


        $reporte->datosHeader($dataSource->getDatos());

        $reporte->write(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo);

        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }

}

?>