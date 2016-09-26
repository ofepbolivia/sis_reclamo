<?php
/**
 *@package pXP
 *@file gen-MODTipoIncidente.php
 *@author  (admin)
 *@date 23-08-2016 19:24:46
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODTipoIncidente extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_tipo_incidente_sel';

		$this->transaccion='REC_RTI_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion


		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_incidente','int4');
		$this->captura('fk_tipo_incidente','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('tiempo_respuesta','int4');
		$this->captura('nivel','int4');
		$this->captura('nombre_incidente','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');

		//$this->captura('usr_mod','varchar');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//echo $this->consulta;
		//exit;
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function listarTipoIncidenteArb(){

		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_tipo_incidente_sel';
		$this-> setCount(false);
		$this->transaccion='REC_RTI_ARB_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		$id_padre = $this->objParam->getParametro('id_padre');

		$this->setParametro('id_padre','id_padre','varchar');
		//$this->setParametro('tipo_nodo','tipo_nodo','varchar');


		//$this->setParametro('id_subsistema','id_subsistema','integer');

		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_incidente','int4');
		$this->captura('fk_tipo_incidente','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('tiempo_respuesta','int4');
		$this->captura('nivel','int4');
		$this->captura('nombre_incidente','varchar');

		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('tipo_nodo','varchar');
		//$this->captura('usr_mod','varchar');



		//Ejecuta la instruccion
		$this->armarConsulta();
		$consulta = $this->getConsulta();
		$this->ejecutarConsulta();

		return $this->respuesta;
	}

	function insertarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_RTI_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('fk_tipo_incidente','fk_tipo_incidente','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tiempo_respuesta','tiempo_respuesta','int4');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nombre_incidente','nombre_incidente','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_RTI_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_tipo_incidente','id_tipo_incidente','int4');
		$this->setParametro('fk_tipo_incidente','fk_tipo_incidente','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('tiempo_respuesta','tiempo_respuesta','int4');
		$this->setParametro('nivel','nivel','int4');
		$this->setParametro('nombre_incidente','nombre_incidente','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarTipoIncidente(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_tipo_incidente_ime';
		$this->transaccion='REC_RTI_ELI';
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