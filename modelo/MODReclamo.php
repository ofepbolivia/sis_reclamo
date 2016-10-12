<?php
/**
*@package pXP
*@file gen-MODReclamo.php
*@author  (admin)
*@date 10-08-2016 18:32:59
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODReclamo extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarReclamo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='rec.ft_reclamo_sel';
		$this->transaccion='REC_REC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_reclamo','int4');
		$this->captura('id_tipo_incidente','int4');
		$this->captura('id_subtipo_incidente','int4');
		$this->captura('id_medio_reclamo','int4');
		$this->captura('id_funcionario_recepcion','int4');
		$this->captura('id_funcionario_denunciado','int4');
		$this->captura('id_oficina_incidente','int4');
		$this->captura('id_oficina_registro_incidente','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('id_cliente','int4');
		$this->captura('estado','varchar');
		$this->captura('fecha_hora_incidente','timestamp');
		$this->captura('nro_ripat_att','int4');
		$this->captura('nro_hoja_ruta','int4');
		$this->captura('fecha_hora_recepcion','timestamp');
		$this->captura('estado_reg','varchar');
		$this->captura('fecha_hora_vuelo','timestamp');
		$this->captura('origen','varchar');
		$this->captura('nro_frd','varchar');
		$this->captura('correlativo_preimpreso_frd','int4');
		$this->captura('fecha_limite_respuesta','date');
		$this->captura('observaciones_incidente','text');
		$this->captura('destino','varchar');
		$this->captura('nro_pir','int4');
		$this->captura('nro_frsa','int4');
		$this->captura('nro_att_canalizado','int4');
		$this->captura('nro_tramite','varchar');
		$this->captura('detalle_incidente','text');
		$this->captura('pnr','varchar');
		$this->captura('nro_vuelo','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');

		//$this->captura('id_gestion','int4');
		//$this->captura('id_motivo_anulado','int4');


        $this->captura('desc_nombre_medio','varchar');
        $this->captura('desc_nom_cliente','text');
        $this->captura('desc_nombre_incidente','varchar');
        $this->captura('desc_nombre_oficina','varchar');
		$this->captura('desc_oficina_registro_incidente','varchar');
        $this->captura('desc_sudnom_incidente','varchar');
        $this->captura('desc_nombre_funcionario','text');
        $this->captura('desc_nombre_fun_denun','text');


		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta

		return $this->respuesta;
	}

	function insertarReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_reclamo_ime';
		$this->transaccion='REC_REC_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_tipo_incidente','id_tipo_incidente','int4');
		$this->setParametro('id_subtipo_incidente','id_subtipo_incidente','int4');
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','int4');
		$this->setParametro('id_funcionario_recepcion','id_funcionario_recepcion','int4');
		$this->setParametro('id_funcionario_denunciado','id_funcionario_denunciado','int4');
		$this->setParametro('id_oficina_incidente','id_oficina_incidente','int4');
		$this->setParametro('id_oficina_registro_incidente','id_oficina_registro_incidente','int4');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('id_proceso_macro','id_proceso_macro','int4');
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha_hora_incidente','fecha_hora_incidente','timestamp');
		$this->setParametro('nro_ripat_att','nro_ripat_att','int4');
		$this->setParametro('nro_hoja_ruta','nro_hoja_ruta','int4');
		$this->setParametro('fecha_hora_recepcion','fecha_hora_recepcion','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_hora_vuelo','fecha_hora_vuelo','timestamp');
		$this->setParametro('origen','origen','varchar');
		$this->setParametro('nro_frd','nro_frd','varchar');
		$this->setParametro('correlativo_preimpreso_frd','correlativo_preimpreso_frd','int4');
		$this->setParametro('fecha_limite_respuesta','fecha_limite_respuesta','date');
		$this->setParametro('observaciones_incidente','observaciones_incidente','text');
		$this->setParametro('destino','destino','varchar');
		$this->setParametro('nro_pir','nro_pir','int4');
		$this->setParametro('nro_frsa','nro_frsa','int4');
		$this->setParametro('nro_att_canalizado','nro_att_canalizado','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('detalle_incidente','detalle_incidente','text');
		$this->setParametro('pnr','pnr','varchar');
		$this->setParametro('nro_vuelo','nro_vuelo','varchar');


		//$this->setParametro('correlativo','correlativo',  'int4');
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_reclamo_ime';
		$this->transaccion='REC_REC_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_reclamo','id_reclamo','int4');
		$this->setParametro('id_tipo_incidente','id_tipo_incidente','int4');
		$this->setParametro('id_subtipo_incidente','id_subtipo_incidente','int4');
		$this->setParametro('id_medio_reclamo','id_medio_reclamo','int4');
		$this->setParametro('id_funcionario_recepcion','id_funcionario_recepcion','int4');
		$this->setParametro('id_funcionario_denunciado','id_funcionario_denunciado','int4');
		$this->setParametro('id_oficina_incidente','id_oficina_incidente','int4');
		$this->setParametro('id_oficina_registro_incidente','id_oficina_registro_incidente','int4');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
        $this->setParametro('id_proceso_macro','id_proceso_macro','int4');
		$this->setParametro('id_cliente','id_cliente','int4');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha_hora_incidente','fecha_hora_incidente','timestamp');
		$this->setParametro('nro_ripat_att','nro_ripat_att','int4');
		$this->setParametro('nro_hoja_ruta','nro_hoja_ruta','int4');
		$this->setParametro('fecha_hora_recepcion','fecha_hora_recepcion','timestamp');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('fecha_hora_vuelo','fecha_hora_vuelo','timestamp');
		$this->setParametro('origen','origen','varchar');
		$this->setParametro('nro_frd','nro_frd','varchar');
		$this->setParametro('correlativo_preimpreso_frd','correlativo_preimpreso_frd','int4');
		$this->setParametro('fecha_limite_respuesta','fecha_limite_respuesta','date');
		$this->setParametro('observaciones_incidente','observaciones_incidente','text');
		$this->setParametro('destino','destino','varchar');
		$this->setParametro('nro_pir','nro_pir','int4');
		$this->setParametro('nro_frsa','nro_frsa','int4');
		$this->setParametro('nro_att_canalizado','nro_att_canalizado','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('detalle_incidente','detalle_incidente','text');
		$this->setParametro('pnr','pnr','varchar');
		$this->setParametro('nro_vuelo','nro_vuelo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_reclamo_ime';
		$this->transaccion='REC_REC_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_reclamo','id_reclamo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


	function siguienteEstadoReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_reclamo_ime';
		$this->transaccion='REC_SIGEREC_IME';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_proceso_wf_act','id_proceso_wf_act','int4');
		$this->setParametro('id_estado_wf_act','id_estado_wf_act','int4');
		$this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
		$this->setParametro('id_tipo_estado','id_tipo_estado','int4');
		$this->setParametro('id_funcionario_wf','id_funcionario_wf','int4');
		$this->setParametro('id_depto_wf','id_depto_wf','int4');
		$this->setParametro('obs','obs','text');
		$this->setParametro('json_procesos','json_procesos','text');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function anteriorEstadoReclamo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='rec.ft_reclamo_ime';
		$this->transaccion='PLA_ANTEREC_IME';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_plan_pago','id_plan_pago','int4');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('id_funcionario_usu','id_funcionario_usu','int4');
		$this->setParametro('operacion','operacion','varchar');

		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_tipo_estado','id_tipo_estado','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
		$this->setParametro('obs','obs','text');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
}
?>