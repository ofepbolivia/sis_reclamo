<?php
/**
*@package pXP
*@file gen-ACTTipoIncidente.php
*@author  (admin)
*@date 10-08-2016 13:52:38
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTipoIncidente extends ACTbase{    
			
	function listarTipoIncidente(){
		$this->objParam->defecto('ordenacion','id_tipo_incidente');

		$this->objParam->defecto('dir_ordenacion','asc');
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoIncidente','listarTipoIncidente');
		} else{
			$this->objFunc=$this->create('MODTipoIncidente');
			
			$this->res=$this->objFunc->listarTipoIncidente($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarTipoIncidente(){
		$this->objFunc=$this->create('MODTipoIncidente');	
		if($this->objParam->insertar('id_tipo_incidente')){
			$this->res=$this->objFunc->insertarTipoIncidente($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarTipoIncidente($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarTipoIncidente(){
			$this->objFunc=$this->create('MODTipoIncidente');	
		$this->res=$this->objFunc->eliminarTipoIncidente($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>