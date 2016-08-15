<?php
/**
*@package pXP
*@file gen-ACTRespuesta.php
*@author  (admin)
*@date 11-08-2016 16:01:08
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTRespuesta extends ACTbase{    
			
	function listarRespuesta(){
		$this->objParam->defecto('ordenacion','id_respuesta');

        if($this->objParam->getParametro('id_reclamo') != '') {
            $this->objParam->addFiltro(" res.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }

		$this->objParam->defecto('dir_ordenacion','asc');
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
			
}

?>