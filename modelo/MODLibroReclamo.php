<?php
/**
*@package pXP
*@file gen-MODReporte.php
*@author  (admin)
*@date 12-10-2016 19:21:51
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODLibroReclamo extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarReporte(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_reporte_sel';
		$this->transaccion='REC_REP_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_reporte','int4');
		$this->captura('agrupar_por','varchar');
		$this->captura('hoja_posicion','varchar');
		$this->captura('mostrar_codigo_cargo','varchar');
		$this->captura('mostrar_codigo_empleado','varchar');
		$this->captura('mostrar_doc_id','varchar');
		$this->captura('mostrar_nombre','varchar');
		$this->captura('numerar','varchar');
		$this->captura('ordenar_por','varchar');
		$this->captura('id_tipo_incidente','int4');
		$this->captura('titulo_reporte','varchar');
		$this->captura('ancho_utilizado','int4');
		$this->captura('ancho_total','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('control_reporte','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
	
			
}
?>