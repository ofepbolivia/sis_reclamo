<?php
/**
*@package pXP
*@file gen-ACTMedioReclamo.php
*@author  (admin)
*@date 10-08-2016 20:59:01
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTMedioReclamo extends ACTbase{    
			
	function listarMedioReclamo(){
		$this->objParam->defecto('ordenacion','id_medio_reclamo');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODMedioReclamo','listarMedioReclamo');
		} else{
			$this->objFunc=$this->create('MODMedioReclamo');
			
			$this->res=$this->objFunc->listarMedioReclamo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarMedioReclamo(){
		$this->objFunc=$this->create('MODMedioReclamo');	
		if($this->objParam->insertar('id_medio_reclamo')){
			$this->res=$this->objFunc->insertarMedioReclamo($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarMedioReclamo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarMedioReclamo(){
			$this->objFunc=$this->create('MODMedioReclamo');	
		$this->res=$this->objFunc->eliminarMedioReclamo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>