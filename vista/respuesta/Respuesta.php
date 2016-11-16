<?php
/**
*@package pXP
*@file gen-Respuesta.php
*@author  (admin)
*@date 11-08-2016 16:01:08
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Respuesta=Ext.extend(Phx.gridInterfaz, {

	nombreVista: 'Respuesta',
	constructor: function (config) {
		this.maestro = config.maestro;
		//llama al constructor de la clase padre
		Phx.vista.Respuesta.superclass.constructor.call(this, config);
		this.init();
		this.store.baseParams.pes_estado = 'elaboracion_respuesta';
		
		this.addButton('ant_estado',{
			grupo: [0,1,2,3],
			argument: {estado: 'anterior'},
			text: 'Anterior',
			iconCls: 'batras',
			disabled: true,
			/*hidden: true,*/
			handler: this.antEstado,
			tooltip: '<b>Volver al Anterior Estado</b>'
		});
		this.addButton('sig_estado',{
			grupo:[0,1,2],
			text:'Siguiente',
			iconCls: 'badelante',
			disabled:true,
			/*hidden:true,*/
			handler:this.sigEstado,
			tooltip: '<b>Pasar al Siguiente Estado</b>'
		});

		this.addButton('btnChequeoDocumentosWf',{
			text: 'Documentos',
			grupo: [0,1,2,3],
			iconCls: 'bchecklist',
			disabled: true,
			handler: this.loadCheckDocumentosRecWf,
			tooltip: '<b>Documentos de la Respuesta</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
		});

		this.addButton('btnObs',{
			grupo:[0,1,2,3],
			text :'Obs Wf.',
			iconCls : 'bchecklist',
			disabled: true,
			handler : this.onOpenObs,
			tooltip : '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
		});

		this.addButton('diagrama_gantt',{
			grupo:[0,1,2,3],
			text:'Gant',
			iconCls: 'bgantt',
			disabled:true,
			handler:diagramGantt,
			tooltip: '<b>Diagrama Gantt de proceso macro</b>'
		});
		
		function diagramGantt(){
			var data=this.sm.getSelected().data.id_proceso_wf;
			Phx.CP.loadingShow();
			Ext.Ajax.request({
				url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
				params:{'id_proceso_wf':data},
				success:this.successExport,
				failure: this.conexionFailure,
				timeout:this.timeout,
				scope:this
			});
		};

	},
	Atributos: [
		{
			//configuracion del componente
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_respuesta'
			},
			type: 'Field',
			form: true
		},
		{

			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_reclamo'
			},
			type: 'Field',
			form: true
		},
		{
			config:{
				name: 'nro_respuesta',
				fieldLabel: 'No. Respuesta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength:100
			},
			type:'TextField',
			filters:{pfiltro:'res.nro_respuesta',type:'string'},
			/*id_grupo:1,*/
			grid:true,
			form:false,
			bottom_filter : true
		},
		{
			config: {
				name: 'estado',
				fieldLabel: 'Estado',
				allowBlank: true,
				anchor: '100%',
				gwidth: 150,
				maxLength: 100
			},
			type: 'TextField',
			filters: {pfiltro: 'res.estado', type: 'string'},
			/*id_grupo: 1,*/
			grid: true,
			form: false
		},
		{
			config: {
				name: 'fecha_respuesta',
				fieldLabel: 'Fecha Respuesta',
				allowBlank: false,
				anchor: '50%',
				gwidth: 100,


				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'res.fecha_respuesta', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_cite',
				fieldLabel: 'Nro. de Cite',
				allowBlank: false,
				/*regex: '/[A-Z]/',
				regexText: "<b>Error</b></br>Invalid Number entered.",*/
				anchor: '50%',
				gwidth: 150,
				maxLength: 50,
				style:'text-transform:uppercase;'
			},
			type: 'TextField',
			filters: {pfiltro: 'res.nro_cite', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'asunto',
				fieldLabel: 'Referencia / Asunto',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength: 100000
			},
			type: 'TextArea',
			filters: {pfiltro: 'res.asunto', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'respuesta',
				fieldLabel: 'Contenido de la Respuesta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength: 1000000
			},
			type: 'TextArea',
			filters: {pfiltro: 'res.respuesta', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'recomendaciones',
				fieldLabel: 'Recomendación para Evitar Futuros Reclamos',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength: 1000000
			},
			type: 'TextArea',
			filters: {pfiltro: 'res.recomendaciones', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'procedente',
				fieldLabel: 'Procedente',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength: 100,
				gdisplayField: 'procedente',
				typeAhead:true,
				triggerAction:'all',
				mode:'local',
				store:['SI','NO','NINGUNO']
			},
			type: 'ComboBox',
			filters: {pfiltro: 'res.procedente', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'tipo_respuesta',
				fieldLabel: 'Tipo Respuesta',
				allowBlank: false,
				anchor: '80%',
				maxLength: 300,
				gwidth: 100,
				typeAhead:true,
				triggerAction:'all',
				mode:'local',
				store:['respuesta_final','respuesta_parcial']
			},
			type:'ComboBox',
			filters: {pfiltro: 'res.asunto', type: 'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config: {
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 10
			},
			type: 'TextField',
			filters: {pfiltro: 'res.estado_reg', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'fecha_notificacion',
				fieldLabel: 'Fecha Notificacion',
				allowBlank: true,
				anchor: '50%',
				gwidth: 100,
				/*inputType:'hidden',*/
				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'res.fecha_notificacion', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'Field',
			filters: {pfiltro: 'res.id_usuario_ai', type: 'numeric'},
			id_grupo: 1,
			grid: false,
			form: false
		},
		{
			config: {
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'Field',
			filters: {pfiltro: 'usu1.cuenta', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 300
			},
			type: 'TextField',
			filters: {pfiltro: 'res.usuario_ai', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i:s') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'res.fecha_reg', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i:s') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'res.fecha_mod', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'Field',
			filters: {pfiltro: 'usu2.cuenta', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: false
		}
	],
	tam_pag: 50,
	title: 'Respuesta',
	ActSave: '../../sis_reclamo/control/Respuesta/insertarRespuesta',
	ActDel: '../../sis_reclamo/control/Respuesta/eliminarRespuesta',
	ActList: '../../sis_reclamo/control/Respuesta/listarRespuesta',
	id_store: 'id_respuesta',
	fields: [
		{name: 'id_respuesta', type: 'numeric'},
		{name: 'id_reclamo', type: 'numeric'},
		{name: 'recomendaciones', type: 'string'},
		{name: 'nro_cite', type: 'string'},
		{name: 'respuesta', type: 'string'},
		{name: 'fecha_respuesta', type: 'date', dateFormat: 'Y-m-d'},
		{name: 'estado_reg', type: 'string'},
		{name: 'procedente', type: 'string'},
		{name: 'fecha_notificacion', type: 'date', dateFormat: 'Y-m-d'},
		{name: 'id_usuario_ai', type: 'numeric'},
		{name: 'id_usuario_reg', type: 'numeric'},
		{name: 'usuario_ai', type: 'string'},
		{name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'id_usuario_mod', type: 'numeric'},
		{name: 'usr_reg', type: 'string'},
		{name: 'usr_mod', type: 'string'},
		'tipo_respuesta',
		'asunto',
		{name: 'id_proceso_wf', type: 'numeric'},
		{name: 'id_estado_wf', type: 'numeric'},
		{name: 'estado', type: 'string'},
		{name: 'nro_respuesta', type: 'numeric'}

	],
	sortInfo: {
		field: 'id_respuesta',
		direction: 'ASC'
	},
	bdel: true,
	bsave: false,
	btest: false,
	fwidth: '50%',
	fheight: '80%',
	collapsible:true,
	onOpenObs: function() {
		var rec=this.sm.getSelected();
		var data = {
			id_proceso_wf: rec.data.id_proceso_wf,
			id_estado_wf: rec.data.id_estado_wf,
			num_tramite: rec.data.nro_tramite
		}

		Phx.CP.loadWindows('../../../sis_workflow/vista/obs/Obs.php',
			'Observaciones del WF',
			{
				width:'80%',
				height:'70%'
			},
			data,
			this.idContenedor,
			'Obs'
		)
	},

	loadCheckDocumentosRecWf:function() {
		var rec=this.sm.getSelected();
		rec.data.nombreVista = this.nombreVista;
		Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
			'Chequear documento del WF',
			{
				width:'90%',
				height:500
			},
			rec.data,
			this.idContenedor,
			'DocumentoWf'
		)
	},

	antEstado:function(res){
		var rec=this.sm.getSelected();
		Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
			'Estado de Wf',
			{
				modal:true,
				width:450,
				height:250
			}, { data:rec.data, estado_destino: res.argument.estado }, this.idContenedor,'AntFormEstadoWf',
			{
				config:[{
					event:'beforesave',
					delegate: this.onAntEstado,
				}
				],
				scope:this
			})
	},

	onAntEstado: function(wizard,resp){
		Phx.CP.loadingShow();
		Ext.Ajax.request({
			url:'../../sis_reclamo/control/Respuesta/anteriorEstadoRespuesta',
			params:{
				id_proceso_wf: resp.id_proceso_wf,
				id_estado_wf:  resp.id_estado_wf,
				obs: resp.obs,
				estado_destino: resp.estado_destino
			},
			argument:{wizard:wizard},
			success:this.successEstadoSinc,
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});
	},

	successEstadoSinc:function(resp){
		Phx.CP.loadingHide();
		resp.argument.wizard.panel.destroy()
		this.reload();
	},

	sigEstado: function(){

		var rec = this.sm.getSelected();
		this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
			'Estado de Wf',
			{
				modal:true,
				width:700,
				height:450
			},
			{
				data:{
					id_estado_wf:rec.data.id_estado_wf,
					id_proceso_wf:rec.data.id_proceso_wf
				}
			}, this.idContenedor,'FormEstadoWf',
			{
				config:[{
					event:'beforesave',
					delegate: this.onSaveWizard,
				}],
				scope:this
			}
		);

	},

	onSaveWizard:function(wizard,resp){
		Phx.CP.loadingShow();
		
		Ext.Ajax.request({
			url:'../../sis_reclamo/control/Respuesta/siguienteEstadoRespuesta',
			params:{

				id_proceso_wf_act:  resp.id_proceso_wf_act,
				id_estado_wf_act:   resp.id_estado_wf_act,
				id_tipo_estado:     resp.id_tipo_estado,
				id_funcionario_wf:  resp.id_funcionario_wf,
				id_depto_wf:        resp.id_depto_wf,
				obs:                resp.obs,
				json_procesos:      Ext.util.JSON.encode(resp.procesos)
			},
			success:this.successWizard,
			failure: this.conexionFailure,
			argument:{wizard:wizard},
			timeout:this.timeout,
			scope:this
		});
	},

	successWizard:function(resp){
		Phx.CP.loadingHide();
		resp.argument.wizard.panel.destroy();
		this.reload();
	},

	onButtonNew : function () {
		Phx.vista.Respuesta.superclass.onButtonNew.call(this);

		var fecha = this.sumarDias(new Date(),parseInt(this.maestro.tiempo_respuesta));
		this.Cmp.fecha_respuesta.setValue(fecha);
		this.Cmp.nro_cite.setValue(1234567);
		/*Ext.Ajax.request({
			url:'../../sis_reclamo/control/Respuesta/getDiasRespuesta',
			params:{id_tipo_incidente:this.store.baseParams.id_tipo_incidente},
			success:this.successTI,
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});*/
	},
	onButtonDel: function(){
		Phx.vista.Respuesta.superclass.onButtonDel.call(this);
		this.argumentExtraSubmit.id_reclamo = this.maestro.id_reclamo;
	},
	dia: ['domingo','lunes','martes','miercoles','jueves','viernes','sabado'],
	/*successTI:function(resp){
		//Phx.CP.loadingHide();
		var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
		var fecha = this.sumarDias(new Date(),parseInt(reg.ROOT.datos.tiempo_respuesta));
		this.Cmp.fecha_respuesta.setValue(fecha);
	},*/
	sumarDias: function (fecha, dias){
		//var dia = ;
		console.log(dias+' DIA '+this.dia[fecha.getDay()]);
		if(this.dia[fecha.getDay()]=='lunes' && this.maestro.tiempo_respuesta==10) {
			fecha.setDate(fecha.getDate() + dias + 1);

		}
		else if((this.dia[fecha.getDay()]=='martes' || this.dia[fecha.getDay()]=='miercoles' || this.dia[fecha.getDay()]=='jueves' || this.dia[fecha.getDay()]=='viernes') && this.maestro.tiempo_respuesta==10){
			fecha.setDate(fecha.getDate() + dias + 3);

		}else if(this.dia[fecha.getDay()]=='viernes' && this.maestro.tiempo_respuesta==7){
			fecha.setDate(fecha.getDate() + dias + 3);

		}else{
			fecha.setDate(fecha.getDate() + dias + 1);

		}
		return fecha;

	},

	onButtonEdit: function() {
		Phx.vista.Respuesta.superclass.onButtonEdit.call(this);

	}
	/*preparaMenu: function(n){

		var data = this.getSelectedData();
		var tb =this.tbar;
		Phx.vista.Respuesta.superclass.preparaMenu.call(this,n);
		
		this.getBoton('sig_estado').setVisible(true);
		this.getBoton('ant_estado').setVisible(true);
		if (data['estado'] == 'elaboracion_respuesta'){
			this.getBoton('sig_estado').setVisible(true);
			this.getBoton('ant_estado').setVisible(false);
			this.getBoton('sig_estado').enable();
			this.getBoton('diagrama_gantt').enable();
			this.getBoton('btnObs').enable();
		}else if(data['estado'] == 'revision_legal' || data['estado'] == 'vobo_respuesta' || data['estado'] == 'respuesta_aprobada'){
			this.getBoton('sig_estado').enable();
			this.getBoton('ant_estado').setVisible(true);
			this.getBoton('diagrama_gantt').enable();
			this.getBoton('btnObs').enable();
		}else if(data['estado'] == 'respuesta_enviada'){
			this.getBoton('sig_estado').setVisible(false);
		}

		return tb;
	},
	liberaMenu: function(){
		var tb = Phx.vista.Respuesta.superclass.liberaMenu.call(this);
		if(tb){
			this.getBoton('diagrama_gantt').disable();
			this.getBoton('sig_estado').disable();
			this.getBoton('btnObs').disable();
		}
		return tb;
	}*/
});
</script>
		
		