<?php
/**
*@package pXP
*@file gen-ACTReclamo.php
*@author  (admin)
*@date 10-08-2016 18:32:59
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTReclamo extends ACTbase{    
			
	function listarReclamo(){
		$this->objParam->defecto('ordenacion','id_reclamo');
		$this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_reclamo') != '' ) {
            $this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }

		if ($this->objParam->getParametro('id_gestion') != '') {
			
			$this->objParam->addFiltro("rec.id_gestion = ". $this->objParam->getParametro('id_gestion'));

		}

		if ($this->objParam->getParametro('pes_estado') == 'borrador') {
			$this->objParam->addFiltro("rec.estado not in (''pendiente_revision'',''registrado_ripat'',''pendiente_informacion'',''anulado'')");
		}else if ($this->objParam->getParametro('pes_estado') == 'pendiente_informacion') {
			$this->objParam->addFiltro("rec.estado not in (''borrador'',''registrado_ripat'',''pendiente_revision'',''anulado'')");
		} else if ($this->objParam->getParametro('pes_estado') == 'proceso') {
			$this->objParam->addFiltro("rec.estado  in (''pendiente_ripat'',''registrado_ripat'',''pendiente_inf'')");
		} else if ($this->objParam->getParametro('pes_estado') == 'finalizado') {
			$this->objParam->addFiltro("rec.estado  in (''anulado'')");
		}

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODReclamo','listarReclamo');
		}else {

            $this->objFunc = $this->create('MODReclamo');
            $this->res = $this->objFunc->listarReclamo($this->objParam);
        }
            $this->res->imprimirRespuesta($this->res->generarJson());
	}

				
	function insertarReclamo(){
		$this->objFunc=$this->create('MODReclamo');	
		if($this->objParam->insertar('id_reclamo')){
			$this->res=$this->objFunc->insertarReclamo($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarReclamo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarReclamo(){
			$this->objFunc=$this->create('MODReclamo');	
		$this->res=$this->objFunc->eliminarReclamo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}


	function siguienteEstadoReclamo(){
		$this->objFunc=$this->create('MODReclamo');

		$this->objParam->addParametro('id_funcionario_usu',$_SESSION["id_usuario_reg"]);

		$this->res=$this->objFunc->siguienteEstadoReclamo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function anteriorEstadoReclamo(){
		$this->objFunc=$this->create('MODReclamo');
		$this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
		$this->res=$this->objFunc->anteriorEstadoReclamo($this->objParam);

		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>