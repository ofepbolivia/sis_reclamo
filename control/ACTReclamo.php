<?php
/**
*@package pXP
*@file gen-ACTReclamo.php
*@author  (admin)
*@date 10-08-2016 18:32:59
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RReclamoPDF.php');
require_once(dirname(__FILE__).'/../reportes/RLibroRespuestaPDF.php');
require_once(dirname(__FILE__).'/../reportes/RReporteGrafico.php');

class ACTReclamo extends ACTbase{
			
	function listarReclamo(){
		//$this->objParam->count(true);
		$this->objParam->defecto('ordenacion','id_reclamo');
		$this->objParam->defecto('dir_ordenacion','asc');
        //echo $this->objParam->getParametro('tipo_interfaz');
		//$this->addParametro('interfaz',$this->objParam->getParametro('tipo_interfaz'));
        if($this->objParam->getParametro('id_reclamo') != '' ) {
            $this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }

		if ($this->objParam->getParametro('id_gestion') != '') {
			
			$this->objParam->addFiltro("rec.id_gestion = ". $this->objParam->getParametro('id_gestion'));

		}

		
		if($this->objParam->getParametro('nro_tramite')!=''){
			$this->objParam->addFiltro("rec.nro_tramite ilike ''%".$this->objParam->getParametro('nro_tramite')."%''");
		}

		if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  BETWEEN ''%".$this->objParam->getParametro('desde')."%''::date  and ''%".$this->objParam->getParametro('hasta')."%''::date)");
		}

		if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')==''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  >= ''%".$this->objParam->getParametro('desde')."%''::date)");
		}

		if($this->objParam->getParametro('desde')=='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  <= ''%".$this->objParam->getParametro('hasta')."%''::date)");
		}

		if($this->objParam->getParametro('id_oficina_registro_incidente')!=''){
			$this->objParam->addFiltro("rec.id_oficina_registro_incidente = ". $this->objParam->getParametro('id_oficina_registro_incidente'));
		}

		if($this->objParam->getParametro('id_tipo_incidente')!=''){
			$this->objParam->addFiltro("rec.id_tipo_incidente = ". $this->objParam->getParametro('id_tipo_incidente'));
		}
		if($this->objParam->getParametro('id_subtipo_incidente')!=''){
			$this->objParam->addFiltro("rec.id_subtipo_incidente = ". $this->objParam->getParametro('id_subtipo_incidente'));
		}

		

		switch($this->objParam->getParametro('pes_estado')){
			case 'borrador':
				$this->objParam->addFiltro("rec.estado = "."''borrador''");
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
			case 'pendiente_asignacion':
				$this->objParam->addFiltro("rec.estado in (''pendiente_asignacion'')");
				break;
			case 'respuesta_parcial':
				$this->objParam->addFiltro("rec.estado in (''respuesta_parcial'')");
				break;
            case 'pendiente_respuesta':
				$this->objParam->addFiltro("rec.estado in (''pendiente_respuesta'')");
				break;
			case 'archivo_con_respuesta':
				$this->objParam->addFiltro("rec.estado in (''archivo_con_respuesta'')");
				break;
			case 'respuesta_registrado_ripat':
				$this->objParam->addFiltro("rec.estado in (''respuesta_registrado_ripat'')");
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
			case 'en_avenimiento':
				$this->objParam->addFiltro("rec.estado in (''en_avenimiento'')");
				break;
			case 'formulacion_cargos':
				$this->objParam->addFiltro("rec.estado in (''formulacion_cargos'')");
				break;
			case 'resolucion_administrativa':
				$this->objParam->addFiltro("rec.estado in (''resolucion_administrativa'')");
				break;
			case 'recurso_revocatorio':
				$this->objParam->addFiltro("rec.estado in (''recurso_revocatorio'')");
				break;
			case 'recurso_jerarquico':
				$this->objParam->addFiltro("rec.estado in (''recurso_jerarquico'')");
				break;
			case 'contencioso_administrativo':
				$this->objParam->addFiltro("rec.estado in (''contencioso_administrativo'')");
				break;
            case 'en_proceso':
				$this->objParam->addFiltro("rec.estado in (''pendiente_asignacion'',''pendiente_respuesta'',''en_avenimiento'')");
				break;
            case 'concluidos':
				$this->objParam->addFiltro("rec.estado in (''archivo_con_respuesta'',''respuesta_registrado_ripatt'',''archivado_concluido'')");
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

	function listarCRMGlobal(){
		$this->objParam->defecto('ordenacion','id_reclamo');
		$this->objParam->defecto('dir_ordenacion','asc');

		if($this->objParam->getParametro('id_reclamo') != '' ) {
			$this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
		}

		if ($this->objParam->getParametro('id_gestion') != '') {

			$this->objParam->addFiltro("rec.id_gestion = ". $this->objParam->getParametro('id_gestion'));

		}

		if($this->objParam->getParametro('nro_tramite')!=''){
			$this->objParam->addFiltro("rec.nro_tramite ilike ''%".$this->objParam->getParametro('nro_tramite')."%''");
		}

		if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  BETWEEN ''%".$this->objParam->getParametro('desde')."%''::date  and ''%".$this->objParam->getParametro('hasta')."%''::date)");
		}

		if($this->objParam->getParametro('desde')!='' && $this->objParam->getParametro('hasta')==''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  >= ''%".$this->objParam->getParametro('desde')."%''::date)");
		}

		if($this->objParam->getParametro('desde')=='' && $this->objParam->getParametro('hasta')!=''){
			$this->objParam->addFiltro("(rec.fecha_reg::date  <= ''%".$this->objParam->getParametro('hasta')."%''::date)");
		}
		
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODReclamo','listarCRMGlobal');
		}else {
			$this->objFunc = $this->create('MODReclamo');
			$this->res = $this->objFunc->listarCRMGlobal($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

    function listarConsulta(){
        /*$this->objParam->defecto('ordenacion','id_reclamo');
        $this->objParam->defecto('dir_ordenacion','asc');*/

        /*if($this->objParam->getParametro('id_reclamo') != '' ) {
            $this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }*/
        //var_dump('error 400');exit;
        if ($this->objParam->getParametro('id_gestion') != '') {
            $this->objParam->addFiltro("rec.id_gestion = ". $this->objParam->getParametro('id_gestion'));
        }

        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODReclamo','listarConsulta');
        }else {
            $this->objFunc = $this->create('MODReclamo');
            $this->res = $this->objFunc->listarConsulta($this->objParam);
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

	function stadistica(){
		$this->objFunc=$this->create('MODReclamo');
		$this->res=$this->objFunc->stadistica($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function getNombreFun(){
		$this->objFunc=$this->create('MODReclamo');
		$this->res=$this->objFunc->getNombreFun($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function getDatosOficina(){
		$this->objFunc=$this->create('MODReclamo');
		$this->res=$this->objFunc->getDatosOficina($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function listarRest(){
		/*$this->objParam->defecto('ordenacion','id_log');
		$this->objParam->defecto('dir_ordenacion','desc');*/
		
		$this->objFunc=$this->create('MODReclamo');
		//$this->objParam->addParametro('id_funcionario_usu',$_SESSION["ss_id_funcionario"]);
		$this->res=$this->objFunc->listarRest($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function reporteReclamoDoc (){
		
        $this->objFunc=$this->create('MODReclamo');
        $this->res=$this->objFunc->reportesReclamo($this->objParam);
        //obtener titulo del reporte
        $titulo = 'Informe de Reclamo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new RReclamoPDF($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

	function libroRespuesta(){
		$this->objFunc=$this->create('MODReclamo');

		$this->res=$this->objFunc->listarResp($this->objParam);
		//obtener titulo del reporte
		$titulo = 'Libro De Respuestas';
		//Genera el nombre del archivo (aleatorio + titulo)
		$nombreArchivo=uniqid(md5(session_id()).$titulo);
		$nombreArchivo.='.pdf';
		$this->objParam->addParametro('orientacion','L');
		$this->objParam->addParametro('tamano','LETTER');
		$this->objParam->addParametro('nombre_archivo',$nombreArchivo);
		//Instancia la clase de pdf
		$this->objReporteFormato=new RLibroRespuestaPDF ($this->objParam);
		$this->objReporteFormato->setDatos($this->res->datos);
		$this->objReporteFormato->generarReporte();
		$this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');


		$this->mensajeExito=new Mensaje();
		$this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
			'Se generó con éxito el reporte: '.$nombreArchivo,'control');
		$this->mensajeExito->setArchivoGenerado($nombreArchivo);
		$this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
	}
	

	function listarOficinas(){
        $this->objParam->defecto('ordenacion','id_oficina');
        $this->objParam->defecto('dir_ordenacion','asc');

        $this->objFunc=$this->create('MODReclamo');
        $this->res=$this->objFunc->listarOficinas($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

	function getFRD(){
		$this->objFunc=$this->create('MODReclamo');
		$this->res=$this->objFunc->getFRD($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function generarReporteGrafico(){

	    $this->objFunc=$this->create('MODReclamo');
        $dataSource = $this->objFunc->stadistica();
        $this->dataSource=$dataSource->getDatos();
        $nombreArchivo = uniqid(md5(session_id()).'[Reporte-Grafico]').'.docx';

        $reporte = new RReporteGrafico($this->objParam);

        $reporte->datosHeader($this->dataSource);
        $reporte->write(dirname(__FILE__).'/../../reportes_generados/'.$nombreArchivo);


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function listarRegRipat(){

        if($this->objParam->getParametro('id_reclamo') != '' ) {
            $this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }

        if ($this->objParam->getParametro('id_gestion') != '') {

            $this->objParam->addFiltro("rec.id_gestion = ". $this->objParam->getParametro('id_gestion'));

        }
        /*$this->objFunc=$this->create('MODReclamo');
        $this->res=$this->objFunc->listarRegRipat($this->objParam);*/

        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODReclamo','listarRegRipat');
        }else {

            $this->objFunc = $this->create('MODReclamo');
            $this->res = $this->objFunc->listarRegRipat($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function validarReclamo(){
        $this->objFunc=$this->create('MODReclamo');
        $this->res=$this->objFunc->validarReclamo($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function listarFails(){

        $this->objParam->defecto('ordenacion','id_reclamo');
        $this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_reclamo') != '' ) {
            $this->objParam->addFiltro(" rec.id_reclamo = " . $this->objParam->getParametro('id_reclamo'));
        }

        /*if($this->objParam->getParametro('nro_tramite')!=''){
            $this->objParam->addFiltro("rec.nro_tramite ilike ''%".$this->objParam->getParametro('nro_tramite')."%''");
        }*/

		$this->objFunc=$this->create('MODReclamo');
		$this->res=$this->objFunc->listarFails($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
    }

}

?>