<?php
/**
*@package pXP
*@file gen-ACTFeriados.php
*@author  (breydi.vasquez)
*@date 09-05-2018 20:44:22
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTFeriados extends ACTbase{    
			
	function listarFeriados(){
		//$this->objParam->defecto('ordenacion','fecha');
		//$this->objParam->defecto('dir_ordenacion','asc');

        /*if($this->objParam->getParametro('gestion') === ''){
            $this->objParam->addFiltro("extract (year from now()) = extract(year from tfdos.fecha)");
        }
        if($this->objParam->getParametro('gestion') != '') {
            $this->objParam->addFiltro("extract(year from tfdos.fecha) = ". $this->objParam->getParametro('gestion'));
        }*/

        if($this->objParam->getParametro('id_gestion')!=''){
            $this->objParam->addFiltro("tfdos.id_gestion=".$this->objParam->getParametro('id_gestion'));
        }

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODFeriados','listarFeriados');
		} else{
			$this->objFunc=$this->create('MODFeriados');
			
			$this->res=$this->objFunc->listarFeriados($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarFeriados(){
		$this->objFunc=$this->create('MODFeriados');	
		if($this->objParam->insertar('id_feriado')){
			$this->res=$this->objFunc->insertarFeriados($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarFeriados($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarFeriados(){
			$this->objFunc=$this->create('MODFeriados');	
		$this->res=$this->objFunc->eliminarFeriados($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>