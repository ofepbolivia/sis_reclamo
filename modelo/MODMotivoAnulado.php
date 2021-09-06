<?php
/**
*@package pXP
*@file gen-MODMotivoAnulado.php
*@author  (admin)
*@date 12-10-2016 19:36:54
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODMotivoAnulado extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarMotivoAnulado(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_motivo_anulado_sel';
		$this->transaccion='REC_RMA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_motivo_anulado','int4');
		$this->captura('motivo','varchar');
		$this->captura('orden','numeric');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
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
			
	function insertarMotivoAnulado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_motivo_anulado_ime';
		$this->transaccion='REC_RMA_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('motivo','motivo','varchar');
		$this->setParametro('orden','orden','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarMotivoAnulado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_motivo_anulado_ime';
		$this->transaccion='REC_RMA_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_motivo_anulado','id_motivo_anulado','int4');
		$this->setParametro('motivo','motivo','varchar');
		$this->setParametro('orden','orden','numeric');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarMotivoAnulado(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_motivo_anulado_ime';
		$this->transaccion='REC_RMA_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_motivo_anulado','id_motivo_anulado','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>