<?php
/**
*@package pXP
*@file gen-MedioReclamo.php
*@author  (admin)
*@date 10-08-2016 20:59:01
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.MedioReclamo=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.MedioReclamo.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_medio_reclamo'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'llave',
				fieldLabel: 'llave',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:80
			},
				type:'TextField',
				filters:{pfiltro:'rec.llave',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nombre_medio',
				fieldLabel: 'nombre_medio',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'rec.nombre_medio',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'obs',
				fieldLabel: 'obs',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'rec.obs',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config: {
				name: 'id_forenkey',
				fieldLabel: 'id_forenkey',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_forenkey',
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
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'codigo',
				fieldLabel: 'codigo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'rec.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tabla',
				fieldLabel: 'tabla',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'rec.tabla',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		}
	],
	tam_pag:50,	
	title:'reclamo',
	ActSave:'../../sis_reclamo1/control/MedioReclamo/insertarMedioReclamo',
	ActDel:'../../sis_reclamo1/control/MedioReclamo/eliminarMedioReclamo',
	ActList:'../../sis_reclamo1/control/MedioReclamo/listarMedioReclamo',
	id_store:'id_medio_reclamo',
	fields: [
		{name:'id_medio_reclamo', type: 'string'},
		{name:'llave', type: 'string'},
		{name:'nombre_medio', type: 'string'},
		{name:'obs', type: 'string'},
		{name:'id_forenkey', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'tabla', type: 'string'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_medio_reclamo',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		