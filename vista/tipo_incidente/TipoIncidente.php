<?php
/**
*@package pXP
*@file gen-TipoIncidente.php
*@author  (admin)
*@date 10-08-2016 13:52:38
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");



?>
<script>

/*Phx.vista.TipoIncidente= Ext.extend(Phx.arbInterfaz,{
	constructor:function (config) {
		this.maestro = config.maestro;

		Phx.vista.TipoIncidente.superclass.constructor.call(this, config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}});
	},
	title:"bob",
	bdel:true,
	bsave:true
});*/


Phx.vista.TipoIncidente=Ext.extend(Phx.arbGridInterfaz,{
		constructor:function(config){
			this.maestro=config.maestro;
			this.initButtons=[this.cmbGestion];

			//llama al constructor de la clase padre
			Phx.vista.TipoIncidente.superclass.constructor.call(this,config);
			this.init();


			this.addButton('btnImprimir',
				{
					text: 'Imprimir',
					iconCls: 'bprint',
					disabled: false,
					handler: this.imprimirCbte,
					tooltip: '<b>Imprimir Clasificador</b><br/>Imprime el clasificador en el formato oficial.'
				}
			);

			this.loaderTree.baseParams={id_tipo_incidente: 1};

			Ext.define('Task', {
				extend: 'Ext.data.Model',
				fields: [
					{name: 'task',     type: 'string'},
					{name: 'user',     type: 'string'},
					{name: 'duration', type: 'string'}
				]
			});

			this.store = Ext.create('Ext.data.TreeStore', {
				model: 'Task',
				proxy: {
					type: 'ajax',
					//the store will get the content from the .json file
					url: 'http://http://192.168.17.112/kerp/sis_reclamo/vista/treegrid.json'
				},
				folderSort: true
			});

			//Ext.ux.tree.TreeGrid is no longer a Ux. You can simply use a tree.TreePanel
			this.tree = Ext.create('Ext.tree.Panel', {
				title: 'Equipo giniu',
				width: 500,
				height: 300,
				renderTo: Ext.getBody(),
				collapsible: true,
				useArrows: true,
				rootVisible: false,
				store: store,
				multiSelect: true,
				singleExpand: true,
				//the 'columns' property is now 'headers'
				columns: [{
					xtype: 'treecolumn', //this is so we know which column will show the tree
					text: 'Task',
					flex: 2,
					sortable: true,
					dataIndex: 'task'
				},{
					//we must use the templateheader component so we can use a custom tpl
					xtype: 'templatecolumn',
					text: 'Duration',
					flex: 1,
					sortable: true,
					dataIndex: 'duration',
					align: 'center',
					//add in the custom tpl for the rows
					tpl: Ext.create('Ext.XTemplate', '{duration:this.formatHours}', {
						formatHours: function(v) {
							if (v < 1) {
								return Math.round(v * 60) + ' mins';
							} else if (Math.floor(v) !== v) {
								var min = v - Math.floor(v);
								return Math.floor(v) + 'h ' + Math.round(min * 60) + 'm';
							} else {
								return v + ' hour' + (v === 1 ? '' : 's');
							}
						}
					})
				},{
					text: 'Assigned To',
					flex: 1,
					dataIndex: 'user',
					sortable: true
				}]
			});
		},
		rootVisible: true,

		Atributos:[
			{
				//configuracion del componente
				config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_tipo_incidente'
				},
				type:'Field',
				form:true
			},
			{
				config:{
					name: 'id_partida_fk',
					inputType:'hidden'
				},
				type:'Field',
				form:true
			},
			{
				config:{
					name: 'nombre_incidente',
					fieldLabel: 'Nombres',
					allowBlank: false,
					anchor: '80%',
					gwidth: 100,
					maxLength:50
				},
				type:'TextField',
				filters:{pfiltro:'inc.nombre_incidente',type:'string'},
				id_grupo:1,
				bottom_filter:true,
				grid:true,
				form:true
			},
			{
				config:{
					name: 'tiempo_respuesta',
					fieldLabel: 'Tiempo Respuesta',
					allowBlank: true,
					anchor: '80%',
					gwidth: 100,
					maxLength:4
				},
				type:'NumberField',
				filters:{pfiltro:'inc.tiempo_respuesta',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
			}

		],
		title:'Incidentes',
		ActSave:'../../sis_reclamo/control/TipoIncidente/insertarTipoIncidente',
		ActDel:'../../sis_reclamo/control/TipoIncidente/eliminarTipoIncidente',
		ActList:'../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
		id_store:'id_tipo_incidente',
		textRoot:'INCIDENTES',
		id_nodo:'id_tipo_incidente',
		id_nodo_p:'fk_tipo_incidente',
		fields: [
			'id',
			'tipo_meta',
			{name:'id_tipo_incidente', type: 'numeric'},
			{name:'estado_reg', type: 'string'},
			{name:'nombre_incidente', type: 'string'},
			{name:'nivel', type: 'numeric'},
			{name:'fk_tipo_incidente', type: 'numeric'},
			{name:'tiempo_respuesta', type: 'numeric'},
			{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
			{name:'id_usuario_reg', type: 'numeric'},
			{name:'usuario_ai', type: 'string'},
			{name:'id_usuario_ai', type: 'numeric'},
			{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
			{name:'id_usuario_mod', type: 'numeric'},
			{name:'usr_reg', type: 'string'},
			{name:'usr_mod', type: 'string'},

		],


		cmbGestion:new Ext.form.ComboBox({
			fieldLabel: 'Incidentes',
			allowBlank: true,
			emptyText:'Incidentes...',
			store: ['Equipaje','Vuelo','Atencion Al Cliente','Carga Encomienda'],
			valueField: 'id_tipo_incidente',
			triggerAction: 'all',
			displayField: 'incidente',
			hiddenName: 'id_tipo_incidente',
			mode:'local',
			editable:false,

			width:80
		}),
		sortInfo:{
			field: 'id_tipo_incidente',
			direction: 'ASC'
		},
		bdel:true,
		bsave:true,
		rootVisible:true,
		expanded:false,

		onButtonNew:function(){
			var win = new Ext.Window({
				title: 'Registro de Incidente',
				width: 500,
				height:300,
				maximizable:true,
				modal:true,

			});

			win.show();
		},

	south:{

			url:'../../sis_reclamo/vista/cliente/Cliente.php',
			title:'Lista de Clientes',
			height:'50%',
			cls:'Cliente'
	},
	preparaMenu:function(n){
			if(n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id'){
				this.tbar.items.get('b-new-'+this.idContenedor).enable()
			}
			else {
				this.tbar.items.get('b-new-'+this.idContenedor).disable()
			}
			// llamada funcion clase padre
			Phx.vista.TipoIncidente.superclass.preparaMenu.call(this,n);
		},

		EnableSelect:function(n){
			var nivel = n.getDepth();
			var direc = this.getNombrePadre(n)
			if(direc){
				Phx.vista.Partida.superclass.EnableSelect.call(this,n)
			}
		},

		getNombrePadre:function(n){
			var direc
			var padre = n.parentNode;
			if(padre){
				if(padre.attributes.id!='id'){
					direc = n.attributes.nombre +' - '+ this.getNombrePadre(padre)
					return direc;
				}else{

					return n.attributes.nombre;
				}
			}
			else{
				return undefined;
			}
		}
	}
)

/*var main = new Ext.Panel({
	title: 'My first panel', //el título del panel
	width:250, //la anchura del panel
	height:300, //la altura que tendrá
	renderTo: 'frame', //el elemento donde será insertado
	html: 'Nothing important just dummy text' //el contenido del panel
});

this.add(main);*/
</script>

