<?php
/**
*@package pXP
*@file gen-ACTMotivoAnulado.php
*@author  (admin)
*@date 12-10-2016 19:36:54
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTMotivoAnulado extends ACTbase{    
			
	function listarMotivoAnulado(){
		$this->objParam->defecto('ordenacion','id_motivo_anulado');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODMotivoAnulado','listarMotivoAnulado');
		} else{
			$this->objFunc=$this->create('MODMotivoAnulado');
			
			$this->res=$this->objFunc->listarMotivoAnulado($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarMotivoAnulado(){
		$this->objFunc=$this->create('MODMotivoAnulado');	
		if($this->objParam->insertar('id_motivo_anulado')){
			$this->res=$this->objFunc->insertarMotivoAnulado($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarMotivoAnulado($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarMotivoAnulado(){
			$this->objFunc=$this->create('MODMotivoAnulado');	
		$this->res=$this->objFunc->eliminarMotivoAnulado($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>