<?php
/**
*@package pXP
*@file gen-ACTTipoIncidente.php
*@author  (admin)
*@date 23-08-2016 19:24:46
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
//require_once 'GenerarArbol.php';
class ACTTipoIncidente extends ACTbase{    
			
	function listarTipoIncidente(){
		
		$this->objParam->defecto('ordenacion','id_tipo_incidente');

		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoIncidente','listarTipoIncidente');
		}
		if ($this->objParam->getParametro('nivel') != '') {
			$this->objParam->addFiltro("rti.nivel  in (''". $this->objParam->getParametro('nivel') . "'')");
		}
		if ($this->objParam->getParametro('fk_tipo_incidente') != '') {
			$this->objParam->addFiltro("rti.fk_tipo_incidente  in (''". $this->objParam->getParametro('fk_tipo_incidente') . "'')");
		}

		$this->objFunc=$this->create('MODTipoIncidente');
		$this->res=$this->objFunc->listarTipoIncidente($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
	
	
	// funcion que me construye el store que va asociado al modelo.
	function listarTipoIncidenteArb(){

		//obtiene el parametro nodo enviado por la vista

		$node = $this->objParam->getParametro('node');
		$id_tipo_incidente = $this->objParam->getParametro('id_tipo_incidente');
		$tipo_nodo=$this->objParam->getParametro('tipo_nodo');


		if($node=='id'){
			$this->objParam->addParametro('id_padre','%');
		}
		else {
			$this->objParam->addParametro('id_padre',$id_tipo_incidente);
		}

		$this->objFunc=$this->create('MODTipoIncidente');
		$this->res=$this->objFunc->listarTipoIncidenteArb();
		//echo($this->res);

		$this->res->setTipoRespuestaArbol();

		$arreglo=array();

		array_push($arreglo,array('nombre'=>'id','valor'=>'id_tipo_incidente'));
		array_push($arreglo,array('nombre'=>'id_p','valor'=>'fk_tipo_incidente'));


		array_push($arreglo,array('nombre'=>'text','valores'=>'#nombre_incidente#'));
		array_push($arreglo,array('nombre'=>'cls','valor'=>'nivel'));
		array_push($arreglo,array('nombre'=>'qtip','valores'=>'<b> #nivel#</b><br><b> #nombre_incidente#</b> '));


		$this->res->addNivelArbol('tipo_nodo','raiz',array('leaf'=>false,
			'allowDelete'=>true,
			'allowEdit'=>true,
			'cls'=>'folder',
			'tipo_nodo'=>'raiz',
			'icon'=>'../../../sis_reclamo/media/incidente.png'),
			$arreglo);

		/*se ande un nivel al arbol incluyendo con tido de nivel carpeta con su arreglo de equivalencias
          es importante que entre los resultados devueltos por la base exista la variable\
          tipo_dato que tenga el valor en texto = 'hoja'*/ 

		$this->res->addNivelArbol('tipo_nodo','hijo',array(
			'leaf'=>false,
			'allowDelete'=>true,
			'allowEdit'=>true,
			'tipo_nodo'=>'hijo',
			'icon'=>'../../../sis_reclamo/media/subincidente.gif'),
			$arreglo);

		
		$this->res->addNivelArbol('tipo_nodo','hoja',array(
			'leaf'=>true,
			'allowDelete'=>true,
			'allowEdit'=>true,
			'tipo_nodo'=>'hoja',
			'icon'=>'../../../lib/imagenes/a_form.png'),
			$arreglo);


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