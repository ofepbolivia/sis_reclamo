<?php
/**
 *@package pXP
 *@file gen-ACTInforme.php
 *@author  (admin)
 *@date 11-08-2016 01:52:07
 *@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../reportes/RInformeDoc.php');
class ACTInforme extends ACTbase{

	function listarInforme(){

		$this->objParam->defecto('ordenacion','id_informe');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_reclamo') != '') {
            $this->objParam->addFiltro("infor.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODInforme','listarInforme');
		}else{
			$this->objFunc=$this->create('MODInforme');

			$this->res=$this->objFunc->listarInforme($this->objParam);
		}

		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function insertarInforme(){
		$this->objFunc=$this->create('MODInforme');
		if($this->objParam->insertar('id_informe')){
			$this->res=$this->objFunc->insertarInforme($this->objParam);
		} else{
			$this->res=$this->objFunc->modificarInforme($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function eliminarInforme(){
		$this->objFunc=$this->create('MODInforme');
		$this->res=$this->objFunc->eliminarInforme($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
   function reporteInformeTecnico(){

        $this->objFunc=$this->create('MODInforme');
        $dataSource = $this->objFunc->reporteInformeDoc();
       $this->dataSource=$dataSource->getDatos();
        $nombreArchivo = uniqid(md5(session_id()).'[Informe-'.$this->dataSource[0]['nro_informe'].']').'.docx';
        $reporte = new RInformeDoc($this->objParam);
        $reporte->datosHeader($dataSource->getDatos());
        $reporte->write(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo);

        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

}

?>