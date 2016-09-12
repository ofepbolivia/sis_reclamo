<?php
/**
*@package pXP
*@file gen-ACTCompensacion.php
*@author  (admin)
*@date 11-08-2016 15:38:39
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCompensacion extends ACTbase{    

	function listarCompensacion(){
		$this->objParam->defecto('ordenacion','id_compensacion');
,
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCompensacion','listarCompensacion');
		} else{
			$this->objFunc=$this->create('MODCompensacion');
			
			$this->res=$this->objFunc->listarCompensacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCompensacion(){
		$this->objFunc=$this->create('MODCompensacion');	
		if($this->objParam->insertar('id_compensacion')){
			$this->res=$this->objFunc->insertarCompensacion($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCompensacion($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCompensacion(){
			$this->objFunc=$this->create('MODCompensacion');	
		$this->res=$this->objFunc->eliminarCompensacion($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>