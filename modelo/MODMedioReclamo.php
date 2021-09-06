<?php
/**
 *@package pXP
 *@file gen-MODMedioReclamo.php
 *@author  (admin)
 *@date 11-08-2016 01:21:34
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODMedioReclamo extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_medio_reclamo_sel';
		$this->transaccion='REC_MERA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_medio_reclamo','int4');
		$this->captura('codigo','varchar');
		$this->captura('nombre_medio','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('orden','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='REC_MERA_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('nombre_medio','nombre_medio','varchar');
        $this->setParametro('orden','orden','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='REC_MERA_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','int4');
		$this->setParametro('codigo','codigo','varchar');
        $this->setParametro('nombre_medio','nombre_medio','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('orden','orden','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarMedioReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_medio_reclamo_ime';
		$this->transaccion='REC_MERA_ELI';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

}
?>