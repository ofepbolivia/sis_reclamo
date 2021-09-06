<?php
/**
*@package pXP
*@file gen-MedioReclamo.php
*@author  (admin)
*@date 11-08-2016 01:21:34
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.MedioReclamo=Ext.extend(Phx.gridInterfaz,{
    bsave:false,
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
				name: 'codigo',
				fieldLabel: 'Código',
				allowBlank: false,
				anchor: '50%',
				gwidth: 100,
				maxLength:5
			},
			type:'TextField',
			filters:{pfiltro:'mera.codigo',type:'string'},
			id_grupo:1,
			grid:true,
			egrid: true,
			form:true
		},
		{
			config:{
				name: 'nombre_medio',
				fieldLabel: 'Nombre Medio de Reclamo',
				allowBlank: false,
				anchor: '100%',
				gwidth: 200,
				maxLength:255
			},
			type:'TextField',
			filters:{pfiltro:'mera.nombre_medio',type:'string'},
			id_grupo:1,
			egrid: true,
			grid:true,
			form:true
		},

		{
			config:{
				name: 'orden',
				fieldLabel: 'Orden',
				qtip: 'Posición en la Ordenación ',
				allowBlank: false,
				allowDecimals: true,
				anchor: '80%',
				gwidth: 70
			},
			type:'NumberField',
			filters: { pfiltro:'tipdw.ordenacion', type:'numeric' },
			valorInicial: 1.00,
			id_grupo:0,
			egrid: true,
			grid:true,
			form:true
		},

		/*{
			config:{
				name:'nombre_medio',
				fieldLabel:'Nombre',
				allowBlank:false,
				emptyText:'Medio de Reclamo',

				typeAhead: true,
				triggerAction: 'all',
				lazyRender:true,
				mode: 'local',
				store:['Telefono','Email','Personal','Terceros']

			},
			type:'ComboBox',
			id_grupo:0,
			filters:{
				type: 'list',
				options: ['Telefono','Email','Personal','Terceros']
			},
			grid:true,
			form:true
		},*/
		
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'mera.estado_reg',type:'string'},
				id_grupo:0,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'mera.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'mera.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'mera.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'mera.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Medio Reclamo',
	ActSave:'../../sis_reclamo/control/MedioReclamo/insertarMedioReclamo',
	ActDel:'../../sis_reclamo/control/MedioReclamo/eliminarMedioReclamo',
	ActList:'../../sis_reclamo/control/MedioReclamo/listarMedioReclamo',
	id_store:'id_medio_reclamo',
	fields: [
		{name:'id_medio_reclamo', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'nombre_medio', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},'orden',
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'orden',
		direction: 'ASC'
	},
	bdel:true
	}
)
</script>
		
		