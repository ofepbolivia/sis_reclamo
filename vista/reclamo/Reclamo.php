<?php
/**
 *@package pXP
 *@file gen-Reclamo.php
 *@author  Franklin Espinoza Alvarez
 *@date 10-08-2016 18:32:59
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.Reclamo=Ext.extend(Phx.gridInterfaz, {


	constructor: function (config) {
		this.maestro = config.maestro;
		this.fheight = '95%';
		//llama al constructor de la clase padre
		Phx.vista.Reclamo.superclass.constructor.call(this, config);
		this.init();
		//this.store.baseParams.pes_estado = 'otro';
		this.iniciarEvento();
		this.load({params: {start: 0, limit: this.tam_pag}});
		this.finCons = true;

		this.addButton('ant_estado',{
				grupo: [0],
				argument: {estado: 'anterior'},
				text: 'Anterior',
				iconCls: 'batras',
				disabled: true,
				handler: this.antEstado,
				tooltip: '<b>Pasar al Anterior Estado</b>'
		});

		this.addButton('sig_estado',{
			grupo:[0],
			text:'Siguiente',
			iconCls: 'badelante',
			disabled:true,
			handler:this.sigEstado,
			tooltip: '<b>Pasar al Siguiente Estado</b>'
		});

		this.addButton('btnChequeoDocumentosWf',{
				text: 'Documentos',
				grupo: [0],
				iconCls: 'bchecklist',
				disabled: true,
				handler: this.loadCheckDocumentosSolWf,
				tooltip: '<b>Documentos de la Solicitud</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
		});

		this.addButton('diagrama_gantt',{
				grupo:[0],
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
		}
	},

	Atributos: [
		{
			//configuracion del componente
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_reclamo'
			},
			type: 'Field',
			form: true,
			id_grupo:1
		}/*,
		{
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_proceso_wf'
			},
			type: 'Field',
			form: true
		},
		{
			config: {
				labelSeparator: '',
				inputType: 'hidden',
				name: 'id_estado_wf'
			},
			type: 'Field',
			form: true
		}*/,
		{
			config:{
				name: 'nro_tramite',
				fieldLabel: 'No. Tramite',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
			type:'TextField',
			filters:{pfiltro:'rec.nro_tramite',type:'string'},
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
				gwidth: 100,
				maxLength: 100
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.estado', type: 'string'},
			/*id_grupo: 1,*/
			grid: true,
			form: false
		},
		{
			config: {
				name: 'id_oficina_registro_incidente',
				fieldLabel: 'Oficina Reclamo',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Oficina/listarOficina',
					id: 'id_oficina',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_oficina', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'ofi.nombre'}
				}),
				valueField: 'id_oficina',
				displayField: 'nombre',
				gdisplayField: 'desc_oficina_registro_incidente',
				hiddenName: 'id_oficina_registro_incidente',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_oficina_registro_incidente']);
				}
			},
			type: 'ComboBox',
			id_grupo: 1,
			filters: {pfiltro: 'ofi.nombre', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_medio_reclamo',
				fieldLabel: 'Medio Reclamo',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/MedioReclamo/listarMedioReclamo',
					id: 'id_medio_reclamo',
					root: 'datos',
					sortInfo: {
						field: 'nombre_medio',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_medio_reclamo', 'nombre_medio'],
					remoteSort: true,
					baseParams: {par_filtro: 'mera.nombre_medio'}
				}),
				valueField: 'id_medio_reclamo',
				displayField: 'nombre_medio',
				gdisplayField: 'desc_nombre_medio',
				hiddenName: 'id_medio_reclamo',
				forceSelection: true,
				typeAhead: false,
				editable: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_medio']);
				}
			},
			type: 'ComboBox',
			id_grupo: 1,
			filters: {pfiltro: 'mera.nombre_medio', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_hora_recepcion',
				fieldLabel: 'Fecha Recepcion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i:s') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_hora_recepcion', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_funcionario_recepcion',
				fieldLabel: 'Funcionario que recibe Reclamo',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo: {
						field: 'desc_person',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','desc_person','ci'],
					remoteSort: true,
					baseParams: {par_filtro: 'PERSON.nombre_completo1'}
				}),
				valueField: 'id_funcionario',
				displayField: 'desc_person',
				gdisplayField: 'desc_nombre_funcionario',
				hiddenName: 'id_funcionario_recepcion',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				width: 260,
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_funcionario']);
				}
			},
			type: 'ComboBox',
			bottom_filter:true,
			id_grupo: 1,
			filters:{
				pfiltro:'PERSON.nombre_completo1',
				type:'string'
			},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_vuelo',
				fieldLabel: 'Nro. Vuelo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 10
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.nro_vuelo', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'origen',
				fieldLabel: 'Origen',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 10
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.origen', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'destino',
				fieldLabel: 'Destino',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 10
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.destino', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'hora_vuelo',
				fieldLabel: 'Hora Vuelo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 8
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.hora_vuelo', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_hora_incidente',
				fieldLabel: 'Fecha del Incidente',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i:s') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_hora_incidente', type: 'date'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'h-type',
				height: 300,
				value: 'developer'
			},
			type: 'Hidden',
			id_grupo: 1,
			form: true
		},
		{
			config: {
				name: 'id_tipo_incidente',
				fieldLabel: 'Tipo de Incidente',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
					id: 'id_tipo_incidente',
					root: 'datos',
					sortInfo: {
						field: 'nombre_incidente',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_tipo_incidente', 'nombre_incidente','fk_tipo_incidente'],
					remoteSort: true,
					baseParams: {par_filtro: 'rti.nombre_incidente', nivel:'1'}
				}),
				valueField: 'id_tipo_incidente',
				displayField: 'nombre_incidente',
				gdisplayField: 'desc_nombre_incidente',
				hiddenName: 'id_tipo_incidente',
				forceSelection: true,
				typeAhead: false,
				editable: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				/*width: 200,*/
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_incidente']);
				}
			},
			type: 'ComboBox',
			bottom_filter:true,
			id_grupo: 1,
			filters: {pfiltro: 'tri.nombre_incidente', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_subtipo_incidente',
				fieldLabel: 'Subtipo de Incidente',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
					id: 'id_tipo_incidente',
					root: 'datos',
					sortInfo: {
						field: 'nombre_incidente',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_tipo_incidente', 'nombre_incidente'],
					remoteSort: true/*,
					baseParams: {par_filtro: 'rti.nombre_incidente',  fk_tipo_incidente:'id_tipo_incidente'}*/

				}),
				valueField: 'id_tipo_incidente',
				displayField: 'nombre_incidente',
				gdisplayField: 'desc_sudnom_incidente',
				hiddenName: 'id_subtipo_incidente',
				forceSelection: true,
				typeAhead: false,
				editable: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_sudnom_incidente']);
				}
			},
			type: 'ComboBox',
			bottom_filter:true,
			id_grupo: 1,
			filters: {pfiltro: 'rti.fk_tipo_incidente', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_oficina_incidente',
				fieldLabel: 'Oficina Incidente',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Oficina/listarOficina',
					id: 'id_oficina',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_oficina', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'ofi.nombre'}
				}),
				valueField: 'id_oficina',
				displayField: 'nombre',
				gdisplayField: 'desc_nombre_oficina',
				hiddenName: 'id_oficina_incidente',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_oficina']);
				}
			},
			type: 'ComboBox',
			id_grupo: 1,
			filters: {pfiltro: 'ofi.nombre', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'detalle_incidente',
				fieldLabel: 'Detalle Incidente',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 100
			},
			type: 'TextArea',
			bottom_filter:true,
			filters: {pfiltro: 'rec.detalle_incidente', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'observaciones_incidente',
				fieldLabel: 'Observaciones Incidente',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 100
			},
			type: 'TextArea',
			bottom_filter:true,
			filters: {pfiltro: 'rec.observaciones_incidente', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: true
		},
		{
			config:{
				name:'id_cliente',
				fieldLabel:'Cliente',
				allowBlank:false,
				emptyText:'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/Cliente/listarCliente',
					id: 'id_cliente',
					root: 'datos',
					sortInfo:{
						field: 'nombre_completo1',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_cliente','nombre_completo1','ci'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'c.nombre_completo1'}
				}),
				valueField: 'id_cliente',
				displayField: 'nombre_completo1',
				gdisplayField:'desc_nom_cliente',//mapea al store del grid
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre_completo1}</p><p>CI:{ci}</p> </div></tpl>',
				hiddenName: 'id_cliente',
				forceSelection:true,
				typeAhead: true,
				triggerAction: 'all',
				lazyRender:true,
				mode:'remote',
				pageSize:10,
				queryDelay:1000,
				width:280,
				gwidth:280,
				minChars:2,
				turl:'../../../sis_reclamo/vista/cliente/Cliente.php',
				ttitle:'Clientes',
				// tconfig:{width:1800,height:500},
				tdata:{},
				tcls:'Cliente',
				pid:this.idContenedor,

				renderer:function (value, p, record){return String.format('{0}', record.data['desc_nom_cliente']);}
			},
			type:'TrigguerCombo',
			bottom_filter:true,
			id_grupo:2,
			filters:{
				pfiltro:'c.nombre_completo1',
				type:'string'
			},

			grid:true,
			form:true
		},
		{
			config: {
				name: 'id_funcionario_denunciado',
				fieldLabel: 'Funcionario Denunciado',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Funcionario/listarFuncionario',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo: {
						field: 'desc_person',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','desc_person','ci'],
					remoteSort: true,
					baseParams: {par_filtro: 'PERSON.nombre_completo1'}
				}),
				valueField: 'id_funcionario',
				displayField: 'desc_person',
				gdisplayField: 'desc_nombre_fun_denun',
				hiddenName: 'id_funcionario_denunciado',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				width: 260,
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'260',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_fun_denun']);
				}
			},
			type: 'ComboBox',
			id_grupo:2,
			filters:{
				pfiltro:'PERSON.nombre_completo1',
				type:'string'
			},
			bottom_filter:true,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'pnr',
				fieldLabel: 'P.N.R.',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.pnr', type: 'numeric'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_frd',
				fieldLabel: 'Nro. FRD',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_frd', type: 'numeric'},
			id_grupo: 3,
			bottom_filter:true,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_frsa',
				fieldLabel: 'Nro. FTSA',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_frsa', type: 'numeric'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_pir',
				fieldLabel: 'Nro. PIR',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_pir', type: 'numeric'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_att_canalizado',
				fieldLabel: 'Nro. Att Canalizado',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_att_canalizado', type: 'numeric'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_ripat_att',
				fieldLabel: 'Nro. RIPAT Att',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_ripat_att', type: 'numeric'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_hoja_ruta',
				fieldLabel: 'Nro. Hoja Ruta',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_hoja_ruta', type: 'numeric'},
			id_grupo: 3,
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
			filters: {pfiltro: 'rec.estado_reg', type: 'string'},
			id_grupo: 1,
			grid: true,
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
			filters: {pfiltro: 'rec.fecha_reg', type: 'date'},
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
			filters: {pfiltro: 'rec.usuario_ai', type: 'string'},
			id_grupo: 1,
			grid: true,
			form: false
		},
		{
			config: {
				name: 'id_usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'Field',
			filters: {pfiltro: 'rec.id_usuario_ai', type: 'numeric'},
			id_grupo: 1,
			grid: false,
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
			filters: {pfiltro: 'rec.fecha_mod', type: 'date'},
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
	title: 'Reclamo',
	ActSave: '../../sis_reclamo/control/Reclamo/insertarReclamo',
	ActDel: '../../sis_reclamo/control/Reclamo/eliminarReclamo',
	ActList: '../../sis_reclamo/control/Reclamo/listarReclamo',
	id_store: 'id_reclamo',
	fields: [
		{name: 'id_reclamo', type: 'numeric'},
		{name: 'id_tipo_incidente', type: 'numeric'},
		{name: 'id_subtipo_incidente', type: 'numeric'},
		{name: 'id_medio_reclamo', type: 'numeric'},
		{name: 'id_funcionario_recepcion', type: 'numeric'},
		{name: 'id_funcionario_denunciado', type: 'numeric'},
		{name: 'id_oficina_incidente', type: 'numeric'},
		{name: 'id_oficina_registro_incidente', type: 'numeric'},
		{name: 'id_proceso_wf', type: 'numeric'},
		{name: 'id_estado_wf', type: 'numeric'},
		{name: 'id_cliente', type: 'string'},
		{name: 'estado', type: 'string'},
		{name: 'fecha_hora_incidente', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'nro_ripat_att', type: 'numeric'},
		{name: 'nro_hoja_ruta', type: 'numeric'},
		{name: 'fecha_hora_recepcion', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'estado_reg', type: 'string'},
		{name: 'hora_vuelo', type: 'string'},
		{name: 'origen', type: 'string'},
		{name: 'nro_frd', type: 'numeric'},
		{name: 'observaciones_incidente', type: 'string'},
		{name: 'destino', type: 'string'},
		{name: 'nro_pir', type: 'numeric'},
		{name: 'nro_frsa', type: 'numeric'},
		{name: 'nro_att_canalizado', type: 'numeric'},
		{name: 'nro_tramite', type: 'numeric'},
		{name: 'detalle_incidente', type: 'string'},
		{name: 'pnr', type: 'numeric'},
		{name: 'nro_vuelo', type: 'string'},
		{name: 'id_usuario_reg', type: 'numeric'},
		{name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'usuario_ai', type: 'string'},
		{name: 'id_usuario_ai', type: 'numeric'},
		{name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'id_usuario_mod', type: 'numeric'},
		{name: 'usr_reg', type: 'string'},
		{name: 'usr_mod', type: 'string'},
		
		{name: 'desc_nom_cliente', type: 'string'},
		{name: 'desc_nombre_incidente', type: 'string'},
		{name: 'desc_sudnom_incidente', type: 'string'},
		{name: 'desc_nombre_medio', type: 'string'},
		{name: 'desc_nombre_funcionario', type: 'string'},
		{name: 'desc_nombre_fun_denun', type: 'string'},
		{name: 'desc_nombre_oficina', type: 'string'},
		{name: 'desc_oficina_registro_incidente', type: 'string'}
	],
	sortInfo: {
		field: 'id_reclamo',
		direction: 'DESC'
	},
	bdel: true,
	bsave: true,
	fwidth: '65%',

	Grupos: [
		{
			layout: 'column',
			border: false,
			defaults: {
				border: false
			},
			items: [
				{
					bodyStyle: 'padding-right:5px;',
					items: [
						{
							xtype: 'fieldset',
							title: 'DATOS GENERALES DEL SERVICIO QUE ORIGINA EL RECLAMO',
							autoHeight: true,
							items: [],
							id_grupo: 1
						}
					]
				},
				{
					bodyStyle: 'padding-right:5px;',
					items: [
						{
							xtype: 'fieldset',
							title: 'DATOS DEL RECLAMANTE',
							autoHeight: true,
							items: [],
							id_grupo: 2
						}
					]
				},
				{
					bodyStyle: 'padding-left:5px;',
					items: [
						{
							xtype: 'fieldset',
							title: 'DATOS TECNICOS',
							autoHeight: true,
							items: [],
							id_grupo: 3
						}
					]
				}
			]
		}
	],
	tabsouth :[{
		url:'../../../sis_reclamo/vista/respuesta/Respuesta.php',
		title:'Respuesta',
		height:'50%',
		cls:'Respuesta'
	},
		{
			url:'../../../sis_reclamo/vista/informe/Informe.php',
			title:'Informe',
			height:'50%',
			cls:'Informe'
		}
	],

	preparaMenu:function()
	{	var rec = this.sm.getSelected();
		this.desactivarMenu();
		Phx.vista.Reclamo.superclass.preparaMenu.call(this);

		//MANEJO DEL BOTON DE GESTION DE HORAS
		/*if (rec.data.calculo_horas == 'si') {
			this.getBoton('btnHoras').enable();
		}
		this.getBoton('btnColumnas').enable();
		if (rec.data.estado== 'calculo_columnas') {
			this.getBoton('btnColumnas').menu.items.items[0].enable();
			this.getBoton('btnColumnas').menu.items.items[1].enable();
			this.getBoton('btnColumnas').menu.items.items[2].enable();
		} else {
			this.getBoton('btnColumnas').menu.items.items[0].enable();
			this.getBoton('btnColumnas').menu.items.items[1].disable();
			this.getBoton('btnColumnas').menu.items.items[2].disable();
		}*/

		if (rec.data.estado == 'borrador') {
			this.getBoton('ant_estado').disable();
			this.getBoton('sig_estado').enable();

		} /*else if (rec.data.estado == 'comprobante_generado' ||
			rec.data.estado == 'planilla_finalizada') {
			this.getBoton('ant_estado').disable();
			this.getBoton('sig_estado').disable();
			this.getBoton('del').disable();

		} else if (rec.data.estado == 'obligaciones_generadas' ||
			rec.data.estado == 'comprobante_presupuestario_validado' ||
			rec.data.estado == 'comprobante_obligaciones') {
			this.getBoton('ant_estado').enable();
			this.getBoton('sig_estado').disable();
			this.getBoton('del').disable();
		} else {
			this.getBoton('ant_estado').enable();
			this.getBoton('sig_estado').enable();
		}*/


		//this.getBoton('btnChequeoDocumentosWf').enable();

		//MANEJO DEL BOTON DE GESTION DE PRESUPUESTOS


		//this.getBoton('btnPresupuestos').enable();
		//this.getBoton('btnObligaciones').enable();
		this.getBoton('diagrama_gantt').enable();

	},

	liberaMenu:function()
	{
		this.desactivarMenu();
		Phx.vista.Reclamo.superclass.liberaMenu.call(this);
	},

	desactivarMenu:function() {

		this.getBoton('del').disable();
		/*this.getBoton('btnHoras').disable();
		this.getBoton('btnColumnas').disable();
		this.getBoton('btnPresupuestos').disable();
		this.getBoton('btnObligaciones').disable();*/
		this.getBoton('diagrama_gantt').disable();
		this.getBoton('ant_estado').disable();
		this.getBoton('sig_estado').disable();
		this.getBoton('btnChequeoDocumentosWf').disable();
		//this.getBoton('btnPresupuestos').disable();

	},
	loadCheckDocumentosPlanWf:function() {
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

	sigEstado: function(){
		var rec = this.sm.getSelected();
		this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
			'Estado de Wf',
			{
				modal:true,
				width:700,
				height:450
			}, {data:{
				id_estado_wf:rec.data.id_estado_wf,
				id_proceso_wf:rec.data.id_proceso_wf/*,
				fecha_ini:rec.data.fecha_tentativa*/
				//url_verificacion:'../../sis_tesoreria/control/PlanPago/siguienteEstadoPlanPago'
				
			}}, this.idContenedor,'FormEstadoWf',
			{
				config:[{
					event:'beforesave',
					delegate: this.onSaveWizard,

				}],

				scope:this
			});

	},

	onSaveWizard:function(wizard,resp){
		Phx.CP.loadingShow();

		Ext.Ajax.request({
			url:'../../sis_reclamo/control/Reclamo/siguienteEstadoReclamo',
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
        Phx.vista.Reclamo.superclass.onButtonNew.call(this);
        this.Cmp.id_subtipo_incidente.disable();

    },
	iniciarEvento:function() {
		this.Cmp.id_tipo_incidente.on('select', function (cmb, record, index) {
			//console.log('ver rec', record.data.id_tipo_incidente);
			//console.log(record.data);
			this.Cmp.id_subtipo_incidente.reset();
			this.Cmp.id_subtipo_incidente.modificado = true;
			this.Cmp.id_subtipo_incidente.setDisabled(false);
			this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', record.data.id_tipo_incidente);
			//this.Cmp.id_subtipo_incidente.store.setBaseParam('nivel', '2');

		}, this);
	},


	onButtonEdit: function() {
		Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
		var rec = this.sm.getSelected();
		this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', rec.data.id_tipo_incidente);
		console.log('puriskiri: '+rec.data);
		//console.log('ver rec',rec.data.id_tipo_incidente);
		//this.Cmp.id_subtipo_incidente.store.setBaseParam('nivel', '2');
	}
	});
</script>

