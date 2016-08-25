<?php
/**
*@package pXP
*@file gen-MODCliente.php
*@author  (admin)
*@date 12-08-2016 14:29:16
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCliente extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCliente(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_cliente_sel';
		$this->transaccion='REC_CLI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_cliente','int4');
		$this->captura('genero','varchar');
		$this->captura('ci','varchar');
		$this->captura('email','varchar');
		$this->captura('direccion','varchar');
		$this->captura('celular','int4');
		$this->captura('nombre','varchar');
		$this->captura('lugar_expedicion','varchar');
		$this->captura('apellido_paterno','varchar');
		$this->captura('telefono','int4');
		$this->captura('ciudad_residencia','varchar');
		$this->captura('pais_residencia','varchar');
		$this->captura('nacionalidad','varchar');
		$this->captura('barrio_zona','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('apellido_materno','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('nombre_completo1','text');
        $this->captura('nombre_completo2','text');

		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_cliente_ime';
		$this->transaccion='REC_CLI_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('genero','genero','varchar');
		$this->setParametro('ci','ci','varchar');
		$this->setParametro('email','email','varchar');
		$this->setParametro('direccion','direccion','varchar');
		$this->setParametro('celular','celular','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('lugar_expedicion','lugar_expedicion','varchar');
		$this->setParametro('apellido_paterno','apellido_paterno','varchar');
		$this->setParametro('telefono','telefono','int4');
		$this->setParametro('ciudad_residencia','ciudad_residencia','varchar');
		$this->setParametro('pais_residencia','pais_residencia','varchar');
		$this->setParametro('nacionalidad','nacionalidad','varchar');
		$this->setParametro('barrio_zona','barrio_zona','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('apellido_materno','apellido_materno','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_cliente_ime';
		$this->transaccion='REC_CLI_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('genero','genero','varchar');
		$this->setParametro('ci','ci','varchar');
		$this->setParametro('email','email','varchar');
		$this->setParametro('direccion','direccion','varchar');
		$this->setParametro('celular','celular','int4');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('lugar_expedicion','lugar_expedicion','varchar');
		$this->setParametro('apellido_paterno','apellido_paterno','varchar');
		$this->setParametro('telefono','telefono','int4');
		$this->setParametro('ciudad_residencia','ciudad_residencia','varchar');
		$this->setParametro('pais_residencia','pais_residencia','varchar');
		$this->setParametro('nacionalidad','nacionalidad','varchar');
		$this->setParametro('barrio_zona','barrio_zona','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('apellido_materno','apellido_materno','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCliente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_cliente_ime';
		$this->transaccion='REC_CLI_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_cliente','id_cliente','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>