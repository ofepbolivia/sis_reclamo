<?php
/**
 *@package pXP
 *@file gen-Reclamo.php
 *@author  (admin)
 *@date 10-08-2016 18:32:59
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.Reclamo=Ext.extend(Phx.gridInterfaz, {

		constructor: function (config) {
			this.maestro = config.maestro;
			//llama al constructor de la clase padre
			Phx.vista.Reclamo.superclass.constructor.call(this, config);
			this.init();
			this.store.baseParams.pes_estado = 'otro';
			this.iniciarEvento();
			this.load({params: {start: 0, limit: this.tam_pag}});
			this.finCons = true;
			this.addButton('ant_estado',{grupo:[0],argument: {estado: 'anterior'},text:'Anterior',iconCls: 'batras',disabled:false,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
			this.addButton('sig_estado',{grupo:[0],text:'Siguiente',iconCls: 'badelante',disabled:false,handler:this.sigEstado,tooltip: '<b>Pasar al Siguiente Estado</b>'});

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
					width:250,
					gwidth:280,
					minChars:2,
					turl:'../../../sis_reclamo/vista/cliente/Cliente.php',
					ttitle:'Clientes',
					// tconfig:{width:1800,height:500},
					tdata:{},
					tcls:'Cliente',
					pid:this.idContenedor,

<<<<<<< HEAD
			grid:true,
			form:true
		},
		{
			config: {
				name: 'id_tipo_incidente',
				fieldLabel: 'Tipo Incidente',
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
					remoteSort: true,
					baseParams: {par_filtro: 'rti.nombre_incidente', fk_tipo_incidente:'1'}
				}),
				valueField: 'id_tipo_incidente',
				displayField: 'nombre_incidente',
				gdisplayField: 'desc_nombre_incidente',
				hiddenName: 'id_tipo_incidente',
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
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_incidente']);
				}
			},
			type: 'ComboBox',
			id_grupo: 2,
			filters: {pfiltro: 'movtip.nombre', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_subtipo_incidente',
				id:'si',
				fieldLabel: 'subtipo de Incidente',
				allowBlank: true,
				emptyText: 'Elija SubIncidente...',
				disabled: true,
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/Reclamo/listarIncidentes',
					id: 'fk_tipo_incidente',
					root: 'datos',
					sortInfo: {
						field: 'nombre_incidente',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: [ 'fk_tipo_incidente', 'nombre_incidente'],
					remoteSort: true,
					baseParams: {par_filtro: 'rti.nombre_incidente'}
=======
					renderer:function (value, p, record){return String.format('{0}', record.data['desc_nom_cliente']);}
				},
				type:'TrigguerCombo',
				bottom_filter:true,
				id_grupo:0,
				filters:{
					pfiltro:'c.nombre_completo1',
					type:'string'
				},

				grid:true,
				form:true
			},
			{
				config: {
					name: 'id_tipo_incidente',
					fieldLabel: 'Tipo Incidente',
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
						remoteSort: true,
						baseParams: {par_filtro: 'rti.nombre_incidente', nivel:'1'}
					}),
					valueField: 'id_tipo_incidente',
					displayField: 'nombre_incidente',
					gdisplayField: 'desc_nombre_incidente',
					hiddenName: 'id_tipo_incidente',
					forceSelection: true,
					typeAhead: false,
>>>>>>> 98724af44b63fe09a7ac61ba902f58648a907cfd

					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 15,
					queryDelay: 1000,
					anchor: '100%',
					gwidth: 150,
					minChars: 2,
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_nombre_incidente']);
					}
				},
				type: 'ComboBox',
				id_grupo: 2,
				filters: {pfiltro: 'movtip.nombre', type: 'string'},
				grid: true,
				form: true
			},
			{
				config: {
					name: 'id_subtipo_incidente',
					fieldLabel: 'subtipo de Incidente',
					allowBlank: true,
					emptyText: 'Elija una opción...',
					store: new Ext.data.JsonStore({
						url: '../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
						id: 'fk_tipo_incidente',
						root: 'datos',
						sortInfo: {
							field: 'nombre_incidente',
							direction: 'ASC'
						},
						totalProperty: 'total',
						fields: ['id_tipo_incidente', 'nombre_incidente'],
						remoteSort: true,
						baseParams: {par_filtro: 'rti.nombre_incidente' }

					}),
					valueField: 'id_tipo_incidente',
					displayField: 'nombre_incidente',
					gdisplayField: 'desc_sudnom_incidente',
					hiddenName: 'id_subtipo_incidente',
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
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_sudnom_incidente']);
					}
				},
				type: 'ComboBox',
				id_grupo: 2,
				filters: {pfiltro: 'rti.fk_tipo_incidente', type: 'string'},
				grid: true,
				form: true
			},
			{
				config: {
					name: 'Fecha Incidente',
					fieldLabel: 'Fecha Incidente',
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
					labelSeparator: '',
					inputType: 'hidden',
					name: 'nro_tramite'
				},
				type: 'Field',
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
			},{
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
					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 15,
					queryDelay: 1000,
					anchor: '100%',
					gwidth: 150,
					minChars: 2,
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_nombre_medio']);
					}
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters: {pfiltro: 'mera.nombre_medio', type: 'string'},
				grid: true,
				form: true
			},

			{
				config: {
					name: 'id_funcionario_recepcion',
					fieldLabel: 'Funcionario Recepcion',
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
					gwidth: 150,
					minChars: 2,
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_nombre_funcionario']);
					}
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters:{
					pfiltro:'PERSON.nombre_completo1',
					type:'string'
				},
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
				id_grupo: 0,
				grid: true,
				form: true
			},
			{
				config: {
					name: 'pnr',
					fieldLabel: 'P.N.R.',
					allowBlank: true,
					anchor: '80%',
					gwidth: 100,
					maxLength: 4
				},
				type: 'NumberField',
				filters: {pfiltro: 'rec.pnr', type: 'numeric'},
				id_grupo: 0,
				grid: true,
				form: true
			},
			{
				config: {
					name: 'id_oficina_incidente',
					fieldLabel: 'Oficina Incidente',
					allowBlank: true,
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
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_nombre_oficina']);
					}
				},
				type: 'ComboBox',
				id_grupo: 2,
				filters: {pfiltro: 'ofi.nombre', type: 'string'},
				grid: true,
				form: true
			},
			{
				config: {
					name: 'id_oficina_registro_incidente',
					fieldLabel: 'Oficina Registro Incidente',
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
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_nombre_oficina']);
					}
				},
				type: 'ComboBox',
				id_grupo: 0,
				filters: {pfiltro: 'ofi.nombre', type: 'string'},
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
					maxLength: 4
				},
				type: 'NumberField',
				filters: {pfiltro: 'rec.nro_frd', type: 'numeric'},
				id_grupo: 0,
				grid: true,
				form: true
			},
			{
				config: {
					name: 'nro_frsa',
					fieldLabel: 'Nro. FTSA',
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
					gwidth: 150,
					minChars: 2,
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
				grid: true,
				form: true
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
				id_grupo: 2,
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
				filters: {pfiltro: 'rec.detalle_incidente', type: 'string'},
				id_grupo: 2,
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
				filters: {pfiltro: 'rec.observaciones_incidente', type: 'string'},
				id_grupo: 2,
				grid: true,
				form: true
			},
			{
				config: {
					labelSeparator: 'id_proceso_wf',
					inputType: 'hidden',
					name: 'id_reclamo'
				},
				type: 'Field',
				form: true
			},
			{
				config: {
					labelSeparator: 'id_estado_wf',
					inputType: 'hidden',
					name: 'id_reclamo'
				},
				type: 'Field',
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
			{name: 'id_cliente', type: 'numeric'},
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

		],
		sortInfo: {
			field: 'id_reclamo',
			direction: 'ASC'
		},
		bdel: true,
		bsave: true,
		fheight: '80%',
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
								title: 'DATOS RECEPCION',
								autoHeight: true,
								items: [],
								id_grupo: 0
							}
						]
					},
					{
						bodyStyle: 'padding-right:5px;',
						items: [
							{
								xtype: 'fieldset',
								title: 'DATOS DEL SERVICIO QUE ORIGINA EL RECLAMOS',
								autoHeight: true,
								items: [],
								id_grupo: 1
							}
						]
					},
					{
						bodyStyle: 'padding-left:5px;',
						items: [
							{
								xtype: 'fieldset',
								title: 'TIPO DE INCEDENTE',
								autoHeight: true,
								items: [],
								id_grupo: 2
							}
						]
					}



<<<<<<< HEAD
			]
		}
	],
	iniciarEvento:function(){
		this.Cmp.id_tipo_incidente.on('select', function(cmb,record,index){
			console.log('ver rec',this.record.data.id_tipo_incidente);
			
			this.Cmp.si.enable();
			this.Cmp.id_subtipo_incidente.reset();
			//this.Cmp.id_subtipo_incidente.modificado = true;
			this.Cmp.id_subtipo_incidente.store.setBaseParam('tipo.fk_tipo_incidente', record.data.id_tipo_incidente);
		}, this);
=======
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
		onButtonNew : function () {
			Phx.vista.Reclamo.superclass.onButtonNew.call(this);
			this.Cmp.id_subtipo_incidente.disable();

		},
		iniciarEvento:function(){
			this.Cmp.id_tipo_incidente.on('select', function(cmb,record,index){
				console.log('ver rec',record.data.id_tipo_incidente);
				this.Cmp.id_subtipo_incidente.reset();
				this.Cmp.id_subtipo_incidente.modificado = true;
				this.Cmp.id_subtipo_incidente.setDisabled(false);
				this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', record.data.id_tipo_incidente);
			}, this);
>>>>>>> 98724af44b63fe09a7ac61ba902f58648a907cfd

		},
		antEstado:function(){
			var rec=this.sm.getSelected();
			Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
				'Estado de Wf',
				{
					modal:true,
					width:450,
					height:250
				}, {data:rec.data}, this.idContenedor,'AntFormEstadoWf',
				{
					config:[{
						event:'beforesave',
						delegate: this.onAntEstado,
					}
					],
					scope:this
				})
		},

		sigEstado:function(){
		var rec=this.sm.getSelected();
		this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
			'Estado de Wf',
			{
				modal:true,
				width:700,
				height:450
			}, {data:{
				id_estado_wf:rec.data.id_estado_wf,
				id_proceso_wf:rec.data.id_proceso_wf,
				fecha_ini:rec.data.fecha_tentativa




			}}, this.idContenedor,'FormEstadoWf',
			{
				config:[{
					event:'beforesave',
					delegate: this.onSaveWizard,

				}],

				scope:this
			});

	}

	});



</script>

