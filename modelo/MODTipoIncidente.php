<?php
/**
*@package pXP
*@file gen-MODTipoIncidente.php
*@author  (admin)
*@date 10-08-2016 13:52:38
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODTipoIncidente extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_tipo_incidente_sel';
		$this->transaccion='REC_INC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_incidente','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('nombre_incidente','varchar');
		$this->captura('nivel','int4');
		$this->captura('fk_tipo_incidente','int4');
		$this->captura('tiempo_respuesta','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
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
			
	function insertarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_INC_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nombre_incidente','nombre_incidente','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('fk_tipo_incidente','fk_tipo_incidente','int4');
		$this->setParametro('tiempo_respuesta','tiempo_respuesta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_INC_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_incidente','id_tipo_incidente','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nombre_incidente','nombre_incidente','varchar');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('fk_tipo_incidente','fk_tipo_incidente','int4');
		$this->setParametro('tiempo_respuesta','tiempo_respuesta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_INC_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_incidente','id_tipo_incidente','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>