<?php
/**
*@package pXP
*@file gen-ACTReclamo.php
*@author  (admin)
*@date 10-08-2016 18:32:59
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RReclamoDoc.php');
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

		switch($this->objParam->getParametro('pes_estado')){
			case 'borrador':
				$this->objParam->addFiltro("rec.estado in (''borrador'')");
				break;
			case 'pendiente_informacion':
				$this->objParam->addFiltro("rec.estado in (''pendiente_informacion'')");
				break;
			case 'pendiente_revision':
				$this->objParam->addFiltro("rec.estado in (''pendiente_revision'')");
				break;
			case 'registrado_ripat':
				$this->objParam->addFiltro("rec.estado in (''registrado_ripat'')");
				break;
			case 'derivado':
				$this->objParam->addFiltro("rec.estado in (''derivado'')");
				break;
			case 'anulado':
				$this->objParam->addFiltro("rec.estado in (''anulado'')");
				break;
			case 'pendiente_respuesta':
				$this->objParam->addFiltro("rec.estado in (''pendiente_respuesta'')");
				break;
			case 'archivo_con_respuesta':
				$this->objParam->addFiltro("rec.estado in (''archivo_con_respuesta'')");
				break;
			case 'archivado_concluido':
				$this->objParam->addFiltro("rec.estado in (''archivado_concluido'')");
				break;
			case 'revision_legal':
				$this->objParam->addFiltro("rec.estado in (''revision_legal'')");
				break;
			case 'vobo_respuesta':
				$this->objParam->addFiltro("rec.estado in (''vobo_respuesta'')");
				break;

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

	function reporteReclamoDoc (){

        $this->objFunc=$this->create('MODReclamo');
        $dataSource = $this->objFunc->reportesReclamo();
        $nombreArchivo = uniqid(md5(session_id()).'RReclamoDoc').'.docx';
        $reporte = new RReclamoDoc($this->objParam);

        $reporte->datosHeader($dataSource->getDatos());
        $reporte->write(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo);


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }

	
			
}

?>