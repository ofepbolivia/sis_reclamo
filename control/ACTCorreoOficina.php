<?php
/**
*@package pXP
*@file gen-ACTCorreoOficina.php
*@author  (franklin.espinoza)
*@date 11-05-2018 22:27:57
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTCorreoOficina extends ACTbase{    
			
	function listarCorreoOficina(){
		$this->objParam->defecto('ordenacion','id_correo_oficina');

		$this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_oficina') != '') {
            $this->objParam->addFiltro("cof.id_oficina = " . $this->objParam->getParametro('id_oficina'));
        }

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCorreoOficina','listarCorreoOficina');
		} else{
			$this->objFunc=$this->create('MODCorreoOficina');
			
			$this->res=$this->objFunc->listarCorreoOficina($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarCorreoOficina(){
		$this->objFunc=$this->create('MODCorreoOficina');	
		if($this->objParam->insertar('id_correo_oficina')){
			$this->res=$this->objFunc->insertarCorreoOficina($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarCorreoOficina($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarCorreoOficina(){
			$this->objFunc=$this->create('MODCorreoOficina');	
		$this->res=$this->objFunc->eliminarCorreoOficina($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>