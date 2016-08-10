<?php
/**
*@package pXP
*@file gen-MODInforme.php
*@author  (admin)
*@date 10-08-2016 16:42:40
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODInforme extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarInforme(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='recl.ft_informe_sel';
		$this->transaccion='RECL_INF_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_informe','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_compensacion','int4');
		$this->captura('sugerncia_respuesta','varchar');
		$this->captura('antecedentes_informe','varchar');
		$this->captura('id_reclamo','int4');
		$this->captura('conclusion_recomendacion','varchar');
		$this->captura('fecha_informe','date');
		$this->captura('nro_informe','int4');
		$this->captura('analisis_tecnico','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
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
			
	function insertarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='recl.ft_informe_ime';
		$this->transaccion='RECL_INF_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_compensacion','id_compensacion','int4');
		$this->setParametro('sugerncia_respuesta','sugerncia_respuesta','varchar');
		$this->setParametro('antecedentes_informe','antecedentes_informe','varchar');
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('conclusion_recomendacion','conclusion_recomendacion','varchar');
		$this->setParametro('fecha_informe','fecha_informe','date');
		$this->setParametro('nro_informe','nro_informe','int4');
		$this->setParametro('analisis_tecnico','analisis_tecnico','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='recl.ft_informe_ime';
		$this->transaccion='RECL_INF_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_informe','id_informe','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_compensacion','id_compensacion','int4');
		$this->setParametro('sugerncia_respuesta','sugerncia_respuesta','varchar');
		$this->setParametro('antecedentes_informe','antecedentes_informe','varchar');
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('conclusion_recomendacion','conclusion_recomendacion','varchar');
		$this->setParametro('fecha_informe','fecha_informe','date');
		$this->setParametro('nro_informe','nro_informe','int4');
		$this->setParametro('analisis_tecnico','analisis_tecnico','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='recl.ft_informe_ime';
		$this->transaccion='RECL_INF_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_informe','id_informe','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>