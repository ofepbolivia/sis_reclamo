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

	nombreVista: 'Reclamo',
	constructor: function (config) {

		/*this.maestro = config.maestro;
		this.tbarItems = ['-',
			this.cmbGestion

		];*/
		//llama al constructor de la clase padre
		Phx.vista.Reclamo.superclass.constructor.call(this, config);
		this.init();
		this.iniciarEvento();
		this.store.baseParams.pes_estado = 'borrador';
		//this.store.baseParams = {tipo_interfaz: this.nombreVista, id_reclamo: this.maestro.id_reclamo};
		this.load({params: {start: 0, limit: this.tam_pag}});
		this.finCons = true;

		this.cmbGestion.on('select',this.capturarEventos, this);

		this.addButton('ant_estado',{
				grupo: [1],
				argument: {estado: 'anterior'},
				text: 'Anterior',
				iconCls: 'batras',
				disabled: true,
				hidden:true,
				handler: this.antEstado,
				tooltip: '<b>Pasar al Anterior Estado</b>'
		});

		this.addButton('reportes',{
			grupo: [0,1],
			argument: {estado: 'reportes'},
			text: 'Reportes',
			iconCls: 'blist',
			/*disabled: true,*/
			hidden:true,
			handler: this.reportes,
			tooltip: '<b>Generar Reporte</b>'
		});


		this.addButton('sig_estado',{
			grupo:[0,1],
			text:'Siguiente',
			iconCls: 'badelante',
			disabled:true,
			handler:this.sigEstado,
			tooltip: '<b>Pasar al Siguiente Estado</b>'
		});

		this.addButton('btnChequeoDocumentosWf',{
				text: 'Documentos',
				grupo: [0,1,2],
				iconCls: 'bchecklist',
				disabled: true,
				handler: this.loadCheckDocumentosRecWf,
				tooltip: '<b>Documentos de la Solicitud</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
		});

		this.addButton('btnObs',{
			grupo:[0,1,2],
			text :'Obs Wf.',
			iconCls : 'bchecklist',
			disabled: true,
			handler : this.onOpenObs,
			tooltip : '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
		});

		this.addButton('diagrama_gantt',{
				grupo:[0,1,2],
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
    /*capturarEventos:function(combo, record, index){
		this.gestion = this.cmbGestion.getValue();
		this.store.baseParams = {id_gestion:this.gestion};
		this.load({params:{start:0, limit:50}});
	},*/
	gruposBarraTareas:[{name:'borrador',title:'<H1 align="center"><i class="fa fa-eye"></i> En Borrador</h1>',grupo:0,height:0},
		{name:'proceso',title:'<H1 align="center"><i class="fa fa-eye"></i> En Proceso</h1>',grupo:1,height:0},
		{name:'finalizado',title:'<H1 align="center"><i class="fa fa-eye"></i> Finalizados</h1>',grupo:2,height:0}

	],

	actualizarSegunTab: function(name, indice){
		if(this.finCons) {
			this.store.baseParams.pes_estado = name;
			this.load({params:{start:0, limit:this.tam_pag}});
		}
	},

	beditGroups: [0],
	bdelGroups:  [0],
	bactGroups:  [0,1,2],
	btestGroups: [0],
	bexcelGroups: [0,1,2],

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
		},
		{
			config:{
				name: 'nro_tramite',
				fieldLabel: 'No. Tramite',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
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
		/*{
			config: {
				name: 'correlativo',
				fieldLabel: 'Preimpreso FRD',
				msgTarget: 'under',
				layout:'hbox',
				items: [
					{xtype: 'textfield', value: 'CBB', emptyText: 'CBB', name:'region', id: 'region', disabled:true, width:35},
					{xtype: 'textfield', name: 'correlativo', id: 'correlativo', width: 50, allowBlank: true},
					{xtype: 'textfield', value: '2016', emptyText:'2016', name: 'gestion',id: 'gestion', disabled:true, width: 40, allowBlank: true, margins: '0 5 0 0'}
				]
			},
			type: 'CompositeField',
			id_grupo: 0,
			form: true
		},*/
		{
			config: {
				name: 'correlativo_preimpreso_frd',
				fieldLabel: 'Nro. Preimpreso FRD',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 8
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.correlativo_preimpreso_frd', type: 'numeric'},
			id_grupo: 0,
			bottom_filter:true,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_frd',
				fieldLabel: 'Nro. FRD',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 20
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.nro_frd', type: 'string'},
			id_grupo: 0,
			bottom_filter:true,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_frsa',
				fieldLabel: 'Nro. FRSA',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_frsa', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_pir',
				fieldLabel: 'Nro. PIR',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_pir', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_att_canalizado',
				fieldLabel: 'Nro. Att Canalizado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_att_canalizado', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_ripat_att',
				fieldLabel: 'Nro. RIPAT Att',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_ripat_att', type: 'numeric'},
			id_grupo: 0,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'nro_hoja_ruta',
				fieldLabel: 'Nro. Hoja Ruta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 4
			},
			type: 'NumberField',
			filters: {pfiltro: 'rec.nro_hoja_ruta', type: 'numeric'},
			id_grupo: 0,
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
						field: 'nombre_completo2',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_cliente','nombre_completo2','ci','email'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'cli.nombre_completo2'}
				}),
				valueField: 'id_cliente',
				displayField: 'nombre_completo2',
				gdisplayField:'desc_nom_cliente',//mapea al store del grid
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre_completo2}</p><p>CI:{ci}</p><p style= "color : green;" >email:{email}</p></div></tpl>',
				hiddenName: 'id_cliente',
				forceSelection:true,
				typeAhead: true,
				triggerAction: 'all',
				lazyRender:true,
				mode:'remote',
				pageSize:10,
				queryDelay:1000,
				width:250,
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
			id_grupo:1,
			filters:{
				pfiltro:'cli.nombre_completo2',
				type:'string'
			},

			grid:true,
			form:true
		},
		{
			config: {
				name: 'nro_vuelo',
				fieldLabel: 'Nro. Vuelo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 25,
				style:'text-transform:uppercase;'
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.nro_vuelo', type: 'string'},
			id_grupo: 2,
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
				maxLength: 25,
				style:'text-transform:uppercase;'
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.origen', type: 'string'},
			id_grupo: 2,
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
				maxLength: 25,
				style:'text-transform:uppercase;'
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.destino', type: 'string'},
			id_grupo: 2,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_hora_vuelo',
				fieldLabel: 'Fecha, Hora de Vuelo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y H:i A',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i A') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_hora_vuelo', type: 'date'},
			id_grupo: 2,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'pnr',
				fieldLabel: 'P.N.R.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 50,
				maxLength: 4
			},
			type: 'TextField',
			filters: {pfiltro: 'rec.pnr', type: 'string'},
			id_grupo: 0,
			grid: true,
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
					baseParams: {par_filtro: 'tip.nombre_incidente', nivel:'1'}
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
			id_grupo: 3,
			filters: {pfiltro: 'tip.nombre_incidente', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_subtipo_incidente',
				fieldLabel: 'Subtipo de Incidente',
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
			id_grupo: 3,
			filters: {pfiltro: 't.nombre_incidente', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_hora_incidente',
				fieldLabel: 'Fecha, Hora del Incidente',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				format: 'd/m/Y H:i A',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_hora_incidente', type: 'date'},
			id_grupo: 3,
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
			id_grupo: 3,
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
				maxLength: 1000
			},
			type: 'TextArea',
			bottom_filter:true,
			filters: {pfiltro: 'rec.detalle_incidente', type: 'string'},
			id_grupo: 3,
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
				maxLength: 1000
			},
			type: 'TextArea',
			bottom_filter:true,
			filters: {pfiltro: 'rec.observaciones_incidente', type: 'string'},
			id_grupo: 3,
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_funcionario_denunciado',
				fieldLabel: 'Funcionario Denunciado',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo: {
						field: 'desc_funcionario1',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
					remoteSort: true,
					baseParams: {par_filtro: 'rec.id_funcionario_denunciado'}
				}),
				valueField: 'id_funcionario',
				displayField: 'desc_funcionario1',
				gdisplayField: 'desc_nombre_fun_denun',
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
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
			id_grupo:3,
			filters:{
				pfiltro:'rec.id_funcionario_denunciado',
				type:'string'
			},
			bottom_filter:true,
			grid: true,
			form: true
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
			id_grupo: 4,
			filters: {pfiltro: 'ofi.nombre', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_hora_recepcion',
				fieldLabel: 'Fecha, Hora de Recepcion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				/*disabled: true,*/
				gdisplayField: 'fecha_hora_recepcion',
				format: 'd/m/Y T H:i A',
				renderer: function (value, p, record) {
					return value ? value.dateFormat('d/m/Y H:i') : ''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_hora_recepcion', type: 'date'},
			id_grupo: 4,
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
					url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo: {
						field: 'desc_funcionario1',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
					remoteSort: true,
					baseParams: {par_filtro: 'rec.id_funcionario_recepcion'}
				}),
				valueField: 'id_funcionario',
				displayField: 'desc_funcionario1',
				gdisplayField: 'desc_nombre_funcionario',
				tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
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
				gwidth: 200,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_funcionario']);
				}
			},
			type: 'ComboBox',
			bottom_filter:true,
			id_grupo: 4,
			filters:{
				pfiltro:'rec.id_funcionario_recepcion',
				type:'string'
			},
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
						field: 'orden',
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
			id_grupo: 4,
			filters: {pfiltro: 'mera.nombre_medio', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'fecha_limite_respuesta',
				fieldLabel: 'Fecha Limite de Respuesta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 15,
				format: 'd/m/Y',
				renderer:function (value,p,record){
					return value?value.dateFormat('d/m/Y'):''
				}
			},
			type: 'DateField',
			filters: {pfiltro: 'rec.fecha_limite_respuesta', type: 'date'},
			id_grupo: 4,
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
		{name: 'fecha_hora_incidente', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'nro_ripat_att', type: 'numeric'},
		{name: 'nro_hoja_ruta', type: 'numeric'},
		{name: 'fecha_hora_recepcion', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'estado_reg', type: 'string'},
		{name: 'fecha_hora_vuelo', type: 'date', dateFormat: 'Y-m-d H:i:s V'},
		{name: 'origen', type: 'string'},
		{name: 'nro_frd', type: 'string'},
		{name: 'correlativo_preimpreso_frd', type: 'numeric'},
		{name: 'fecha_limite_respuesta', type: 'date', dateFormat: 'Y-m-d'},
		{name: 'observaciones_incidente', type: 'string'},
		{name: 'destino', type: 'string'},
		{name: 'nro_pir', type: 'numeric'},
		{name: 'nro_frsa', type: 'numeric'},
		{name: 'nro_att_canalizado', type: 'numeric'},
		{name: 'nro_tramite', type: 'numeric'},
		{name: 'detalle_incidente', type: 'string'},
		{name: 'pnr', type: 'string'},
		{name: 'nro_vuelo', type: 'string'},
		{name: 'id_usuario_reg', type: 'numeric'},
		{name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'usuario_ai', type: 'string'},
		{name: 'id_usuario_ai', type: 'numeric'},
		{name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
		{name: 'id_usuario_mod', type: 'numeric'},
		{name: 'usr_reg', type: 'string'},
		{name: 'usr_mod', type: 'string'},


		/*{name: 'correlativo', type: 'numeric'},*/

		{name: 'desc_nom_cliente', type: 'string'},
		{name: 'desc_nombre_incidente', type: 'string'},
		{name: 'desc_sudnom_incidente', type: 'string'},
		{name: 'desc_nombre_medio', type: 'string'},
		{name: 'desc_nombre_funcionario', type: 'string'},
		{name: 'desc_nombre_fun_denun', type: 'string'},
		{name: 'desc_nombre_oficina', type: 'string'},
		{name: 'desc_oficina_registro_incidente', type: 'string'},
		{name: 'id_gestion', type: 'int4'}
	],
	sortInfo: {
		field: 'id_reclamo',
		direction: 'DESC'
	},
	bdel: true,
	bedit: true,
	btest: false,
	fwidth: '65%',
	fheight : '95%',
	bodyStyle: 'padding:0 10px 0;',
	Grupos: [
		{
			layout: 'column',
			border: false,
			defaults: {
				border: false
			},

			items: [
				{
					bodyStyle: 'padding-right:10px;',
					items: [

						{
							xtype: 'fieldset',
							title: 'DATOS TECNICOS',
							autoHeight: true,
							items: [/*{
								xtype: 'compositefield',
								fieldLabel: 'F.R.D',

								msgTarget: 'under',
								items: [
									{xtype: 'textfield', value:'CBB', emptyText: 'CBB', name:'region', width: 34, disabled:true},
									{xtype: 'label', text: '/'},
									{xtype: 'textfield',    name: 'correlativo', width: 70, allowBlank: false},
									{xtype: 'label', text: '/'},
									{xtype: 'textfield', value: '2016', emptyText: '2016', name: 'gestion', width: 45, disabled:true}
								]
							}*/],
							id_grupo: 0
						},
						{
							xtype: 'fieldset',
							title: 'DATOS DEL CLIENTE',
							autoHeight: true,
							items: [],
							id_grupo: 1
						},
						{
							xtype: 'fieldset',
							title: 'DATOS DEL SERVICIO QUE ORIGINA EL RECLAMO',
							autoHeight: true,
							items: [],
							id_grupo: 2
						}
					]
				}
				,
				{
					bodyStyle: 'padding-right:10px;',
					items: [
						{
							xtype: 'fieldset',
							title: 'DATOS DEL INCIDENTE',
							autoHeight: true,
							items: [],
							id_grupo: 3
						},
						{
							bodyStyle: 'padding-left:5px;',
							xtype: 'fieldset',
							title: 'DATOS DE RECEPCION',
							autoHeight: true,
							items: [],
							id_grupo: 4
						}
					]
				}
			]
		}
	],
	arrayDefaultColumHidden:[
		'nro_frsa','nro_pir','nro_att_canalizado','nro_ripat_att',
		'nro_hoja_ruta','origen','destino'
	],
	tabsouth :[
		{
			url:'../../../sis_reclamo/vista/informe/Informe.php',
			title:'Informe',
			height:'50%',
			cls:'Informe'
		},
		{
			url:'../../../sis_reclamo/vista/respuesta/Respuesta.php',
			title:'Respuesta',
			height:'50%',
			cls:'Respuesta'
		}
	],
		cmbGestion: new Ext.form.ComboBox({
			name: 'gestion',
			id: 'gestion',
			fieldLabel: 'Gestion',
			allowBlank: true,
			emptyText:'Gestion...',
			blankText: 'Año',
			store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Gestion/listarGestion',
					id: 'id_gestion',
					root: 'datos',
					sortInfo:{
						field: 'gestion',
						direction: 'DESC'
					},
					totalProperty: 'total',
					fields: ['id_gestion','gestion'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'gestion'}
				}),
			valueField: 'id_gestion',
			triggerAction: 'all',
			displayField: 'gestion',
			hiddenName: 'id_gestion',
			mode:'remote',
			pageSize:50,
			queryDelay:500,
			listWidth:'280',
			width:80
		})
		,

	preparaMenu:function(n)
	{	var rec = this.getSelectedData();
		var tb =this.tbar;
		//this.desactivarMenu();

		//this.getBoton('btnChequeoDocumentos').setDisabled(false);
		this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
		Phx.vista.Reclamo.superclass.preparaMenu.call(this,n);
		//this.getBoton('btnReporte').setDisabled(false);
		this.getBoton('diagrama_gantt').enable();
		this.getBoton('btnObs').enable();


		/*if (rec['estado'] == 'borrador') {

			this.getBoton('sig_estado').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
			this.getBoton('btnObs').enable();


		}else if(rec['estado'] == 'pendiente_revision'){
			this.getBoton('ant_estado').setVisible(true);
			this.getBoton('ant_estado').enable();
			this.getBoton('sig_estado').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
			this.getBoton('btnObs').enable();

		}else if(rec['estado'] == 'pendiente_informacion'){
			this.getBoton('ant_estado').setVisible(true);
			this.getBoton('ant_estado').enable();
			this.getBoton('sig_estado').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
			this.getBoton('btnObs').enable();

		}else if(rec['estado'] == 'registrado_ripat'){
			this.getBoton('ant_estado').disable();
			this.getBoton('sig_estado').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
			this.getBoton('btnObs').disable();
		}else if(rec['estado'] == 'pendiente_inf'){
			this.getBoton('ant_estado').disable();
			this.getBoton('sig_estado').enable();
			this.getBoton('btnChequeoDocumentosWf').enable();
			this.getBoton('btnObs').enable();
		}*/

		//this.getBoton('btnChequeoDocumentosWf').enable();
		//this.getBoton('diagrama_gantt').enable();
		return tb;
	},
	liberaMenu:function(){
		var tb = Phx.vista.Reclamo.superclass.liberaMenu.call(this);
		if(tb){

			//this.getBoton('btnReporte').setDisabled(true);
			//this.getBoton('btnChequeoDocumentos').setDisabled(true);
			this.getBoton('ant_estado').disable();
			this.getBoton('sig_estado').disable();
			this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
			this.getBoton('diagrama_gantt').disable();
			this.getBoton('btnObs').disable();

		}
		return tb
	},
	/*liberaMenu:function()
	{
		this.desactivarMenu();
		Phx.vista.Reclamo.superclass.liberaMenu.call(this);
	},*/

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
		//alert('anterior');
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
			url:'../../sis_reclamo/control/Reclamo/anteriorEstadoReclamo',
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
					id_proceso_wf:rec.data.id_proceso_wf/*,
					fecha_ini:rec.data.fecha_tentativa*/
					//url_verificacion:'../../sis_tesoreria/control/PlanPago/siguienteEstadoPlanPago'
				}
			}, this.idContenedor,'FormEstadoWf',
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

	onOpenObs:function() {
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

	iniciarEvento:function() {
		//alert(this.Cmp.id_tipo_incidente);
		this.Cmp.id_tipo_incidente.on('select', function (cmb, record, index) {
			this.Cmp.id_subtipo_incidente.reset();
			this.Cmp.id_subtipo_incidente.modificado = true;
			this.Cmp.id_subtipo_incidente.setDisabled(false);
			this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', record.data.id_tipo_incidente);

		}, this);

		/*this.Cmp.gestion.on('select', function (cmb, record, index) {
			alert('salud');
			this.Cmp.id_subtipo_incidente.reset();
			this.Cmp.id_subtipo_incidente.modificado = true;
			this.Cmp.id_subtipo_incidente.setDisabled(false);
			this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', record.data.id_tipo_incidente);
		}, this);*/
	},

	onButtonNew : function () {
		Phx.CP.loadingShow();
		Ext.Ajax.request({
			url:'../../sis_workflow/control/TipoColumna/listarColumnasFormulario',
			params:{
				codigo_proceso:  'REC',
				proceso_macro:   'REC'
			},
			success:this.saveCampos,
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});

		//Phx.vista.Reclamo.superclass.onButtonNew.call(this);
	},

	saveCampos: function(resp){
		this.Cmp.id_subtipo_incidente.disable();
		Phx.CP.loadingHide();
		var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

		Phx.vista.Reclamo.superclass.onButtonNew.call(this);
		this.armarFormularioFromArray(objRes.datos);
	},

	onButtonEdit: function() {
		var rec = this.sm.getSelected();
		this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', rec.data.id_tipo_incidente);
		Phx.CP.loadingShow();
		Ext.Ajax.request({
			url:'../../sis_workflow/control/TipoColumna/listarColumnasFormulario',
			params:{

				id_estado_wf: rec.data['id_estado_wf']
			},
			success:this.editCampos,
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
		});
		Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
	},

	editCampos: function(resp){
		Phx.CP.loadingHide();
		var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
		console.log(objRes);
		Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
		this.armarFormularioFromArray(objRes.datos);
	}
	});
</script>

