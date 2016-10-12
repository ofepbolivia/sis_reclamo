<?php
/**
*@package pXP
*@file gen-MODRespuesta.php
*@author  (admin)
*@date 11-08-2016 16:01:08
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODRespuesta extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarRespuesta(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_respuesta_sel';
		$this->transaccion='REC_RES_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_respuesta','int4');
		$this->captura('id_reclamo','int4');
		$this->captura('recomendaciones','varchar');
		$this->captura('nro_cite','varchar');
		$this->captura('respuesta','varchar');
		$this->captura('fecha_respuesta','date');
		$this->captura('estado_reg','varchar');
		$this->captura('procedente','boolean');
		$this->captura('fecha_notificacion','date');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
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
			
	function insertarRespuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_respuesta_ime';
		$this->transaccion='REC_RES_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('recomendaciones','recomendaciones','varchar');
		$this->setParametro('nro_cite','nro_cite','varchar');
		$this->setParametro('respuesta','respuesta','varchar');
		$this->setParametro('fecha_respuesta','fecha_respuesta','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('procedente','procedente','boolean');
		$this->setParametro('fecha_notificacion','fecha_notificacion','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarRespuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_respuesta_ime';
		$this->transaccion='REC_RES_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_respuesta','id_respuesta','int4');
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('recomendaciones','recomendaciones','varchar');
		$this->setParametro('nro_cite','nro_cite','varchar');
		$this->setParametro('respuesta','respuesta','varchar');
		$this->setParametro('fecha_respuesta','fecha_respuesta','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('procedente','procedente','boolean');
		$this->setParametro('fecha_notificacion','fecha_notificacion','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarRespuesta(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_respuesta_ime';
		$this->transaccion='REC_RES_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_respuesta','id_respuesta','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>