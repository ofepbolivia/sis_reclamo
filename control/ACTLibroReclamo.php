<?php
/**
*@package pXP
*@file gen-ACTReporte.php
*@author  (admin)
*@date 12-10-2016 19:21:51
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTLibroReclamo extends ACTbase{
			
	function listarReporte(){
		$this->objParam->defecto('ordenacion','id_reporte');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODReporte','listarReporte');
		} else{
			$this->objFunc=$this->create('MODReporte');
			
			$this->res=$this->objFunc->listarReporte($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				

			
}

?>