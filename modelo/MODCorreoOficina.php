<?php
/**
*@package pXP
*@file gen-MODCorreoOficina.php
*@author  (franklin.espinoza)
*@date 11-05-2018 22:27:57
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCorreoOficina extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCorreoOficina(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_correo_oficina_sel';
		$this->transaccion='REC_cof_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_correo_oficina','int4');
		$this->captura('correo','varchar');
		$this->captura('id_oficina','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('id_funcionario','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_oficina','varchar');
        $this->captura('fecha_ini','date');
        $this->captura('fecha_fin','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCorreoOficina(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_correo_oficina_ime';
		$this->transaccion='REC_cof_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('id_oficina','id_oficina','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario','id_funcionario','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCorreoOficina(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_correo_oficina_ime';
		$this->transaccion='REC_cof_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correo_oficina','id_correo_oficina','int4');
		$this->setParametro('correo','correo','varchar');
		$this->setParametro('id_oficina','id_oficina','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_funcionario','id_funcionario','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCorreoOficina(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_correo_oficina_ime';
		$this->transaccion='REC_cof_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_correo_oficina','id_correo_oficina','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>