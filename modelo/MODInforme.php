<?php
/**
 *@package pXP
 *@file gen-MODInforme.php
 *@author  (admin)
 *@date 11-08-2016 01:52:07
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODInforme extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarInforme(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_informe_sel';
		$this->transaccion='REC_INFOR_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_informe','int4');
		$this->captura('sugerencia_respuesta','varchar');
		$this->captura('id_reclamo','int4');
		$this->captura('antecedentes_informe','varchar');
		$this->captura('nro_informe','varchar');
		$this->captura('id_funcionario','int4');
		$this->captura('conclusion_recomendacion','varchar');
		$this->captura('fecha_informe','date');
		$this->captura('estado_reg','varchar');
		$this->captura('lista_compensacion','varchar');
		$this->captura('analisis_tecnico','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
        $this->captura('desc_nombre_compensacion','varchar');
        $this->captura('desc_nombre_fun','text');
        $this->captura('desc_funcionario1','varchar');





		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_informe_ime';
		$this->transaccion='REC_INFOR_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('sugerencia_respuesta','sugerencia_respuesta','varchar');
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('antecedentes_informe','antecedentes_informe','varchar');
		$this->setParametro('nro_informe','nro_informe','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('conclusion_recomendacion','conclusion_recomendacion','varchar');
		$this->setParametro('fecha_informe','fecha_informe','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('lista_compensacion','lista_compensacion','varchar');
		$this->setParametro('analisis_tecnico','analisis_tecnico','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_informe_ime';
		$this->transaccion='REC_INFOR_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_informe','id_informe','int4');
		$this->setParametro('sugerencia_respuesta','sugerencia_respuesta','varchar');
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('antecedentes_informe','antecedentes_informe','varchar');
		$this->setParametro('nro_informe','nro_informe','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('conclusion_recomendacion','conclusion_recomendacion','varchar');
		$this->setParametro('fecha_informe','fecha_informe','date');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('lista_compensacion','lista_compensacion','varchar');
		$this->setParametro('analisis_tecnico','analisis_tecnico','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarInforme(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_informe_ime';
		$this->transaccion='REC_INFOR_ELI';
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