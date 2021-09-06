<?php
/**
*@package pXP
*@file gen-MODFeriado.php
*@author  (admin)
*@date 21-04-2017 20:09:06
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODFeriado extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarFeriado(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_feriado_sel';
		$this->transaccion='REC_DAYF_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_feriado','int4');
		$this->captura('dia','varchar');
		$this->captura('mes','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarFeriado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriado_ime';
		$this->transaccion='REC_DAYF_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('dia','dia','varchar');
		$this->setParametro('mes','mes','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarFeriado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriado_ime';
		$this->transaccion='REC_DAYF_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_feriado','id_feriado','int4');
		$this->setParametro('dia','dia','varchar');
		$this->setParametro('mes','mes','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarFeriado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_feriado_ime';
		$this->transaccion='REC_DAYF_ELI';
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