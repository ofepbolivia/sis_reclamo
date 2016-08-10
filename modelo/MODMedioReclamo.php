<?php
/**
*@package pXP
*@file gen-MODMedioReclamo.php
*@author  (admin)
*@date 10-08-2016 20:59:01
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODMedioReclamo extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_medio_reclamo_sel';
		$this->transaccion='rc_rec_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_medio_reclamo','varchar');
		$this->captura('llave','varchar');
		$this->captura('nombre_medio','varchar');
		$this->captura('obs','text');
		$this->captura('id_forenkey','int4');
		$this->captura('codigo','varchar');
		$this->captura('tabla','varchar');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='rc_rec_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('llave','llave','varchar');
		$this->setParametro('nombre_medio','nombre_medio','varchar');
		$this->setParametro('obs','obs','text');
		$this->setParametro('id_forenkey','id_forenkey','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('tabla','tabla','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='rc_rec_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','varchar');
		$this->setParametro('llave','llave','varchar');
		$this->setParametro('nombre_medio','nombre_medio','varchar');
		$this->setParametro('obs','obs','text');
		$this->setParametro('id_forenkey','id_forenkey','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('tabla','tabla','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='rc_rec_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>