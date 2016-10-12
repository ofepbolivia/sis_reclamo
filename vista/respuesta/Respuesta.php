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

	constructor: function (config) {
		this.maestro = config.maestro;
		//llama al constructor de la clase padre
		Phx.vista.Respuesta.superclass.constructor.call(this, config);
		this.init();
		/*this.store.baseParams = {id_reclamo: this.maestro.id_reclamo};
		this.load({params:{start:0, limit: 50}});*/
		//this.onReloadPage();
		this.bloquearMenus();
		//this.iniciarEventos();

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
			config: {
				name: 'nro_cite',
				fieldLabel: 'Nro. de Cite',
				allowBlank: false,
				anchor: '50%',
				gwidth: 150,
				maxLength: 50
			},
			type: 'TextField',
			filters: {pfiltro: 'res.nro_cite', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
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
				name: 'respuesta',
				fieldLabel: 'Respuesta',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength: 1000
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
				fieldLabel: 'Recomendación',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength: 1000
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
				renderer: function (value, p, record) {
					return value ? 'SI' : 'NO';
				}
			},
			type: 'Checkbox',
			filters: {pfiltro: 'res.procedente', type: 'boolean'},
			id_grupo: 1,
			grid: true,
			form: true
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
			form: true
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
		{name: 'procedente', type: 'boolean'},
		{name: 'fecha_notificacion', type: 'date', dateFormat: 'Y-m-d'},
		{name: 'id_usuario_ai', type: 'numeric'},
		{name: 'id_usuario_reg', type: 'numeric'},
		{name: 'usuario_ai', type: 'string'},
		{name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'id_usuario_mod', type: 'numeric'},
		{name: 'usr_reg', type: 'string'},
		{name: 'usr_mod', type: 'string'},

	],
	sortInfo: {
		field: 'id_respuesta',
		direction: 'ASC'
	},
	bdel: true,
	bsave: false,
	fwidth: '50%',
	fheight: '60%',

	onButtonEdit: function() {
		Phx.vista.Respuesta.superclass.onButtonEdit.call(this);
		var rec = this.sm.getSelected();
	},

	onReloadPage: function (m) {
		this.maestro = m;
		this.store.baseParams = {id_reclamo: this.maestro.id_reclamo};
		this.load({params: {start: 0, limit: 50}});
	},

	saludo: function(){
		alert('hola');
	},
	
	loadValoresIniciales: function () {
		this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
		Phx.vista.Respuesta.superclass.loadValoresIniciales.call(this);
	}

});



</script>
		
		