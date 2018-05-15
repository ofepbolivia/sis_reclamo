<?php
/**
*@package pXP
*@file gen-MODFeriados.php
*@author  (breydi.vasquez)
*@date 09-05-2018 20:44:22
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFeriados extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFeriados(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_feriados_sel';
		$this->transaccion='REC_TFDOS_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_feriado','int4');
		$this->captura('tipo','int4');
		$this->captura('fecha','date');
		$this->captura('id_lugar','int4');
		$this->captura('descripcion','varchar');
        $this->captura('lugar','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('estado','varchar');
		$this->captura('id_origen','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarFeriados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriados_ime';
		$this->transaccion='REC_TFDOS_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('tipo','tipo','int4');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('id_lugar','id_lugar','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('id_origen','id_origen','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFeriados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriados_ime';
		$this->transaccion='REC_TFDOS_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_feriado','id_feriado','int4');
		$this->setParametro('tipo','tipo','int4');
		$this->setParametro('fecha','fecha','date');
		$this->setParametro('id_lugar','id_lugar','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('id_origen','id_origen','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFeriados(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriados_ime';
		$this->transaccion='REC_TFDOS_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_feriado','id_feriado','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>