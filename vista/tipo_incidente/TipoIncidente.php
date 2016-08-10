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
Phx.vista.TipoIncidente=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.TipoIncidente.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
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
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'inc.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'nombre_incidente',
				fieldLabel: 'nombre_incidente',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'inc.nombre_incidente',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nivel',
				fieldLabel: 'nivel',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'NumberField',
				filters:{pfiltro:'inc.nivel',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fk_tipo_incidente',
				fieldLabel: 'fk_tipo_incidente',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'NumberField',
				filters:{pfiltro:'inc.fk_tipo_incidente',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tiempo_respuesta',
				fieldLabel: 'tiempo_respuesta',
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
				filters:{pfiltro:'inc.fecha_reg',type:'date'},
				id_grupo:1,
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
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'inc.usuario_ai',type:'string'},
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
				filters:{pfiltro:'inc.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'inc.fecha_mod',type:'date'},
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
	title:'incidente',
	ActSave:'../../sis_reclamos/control/TipoIncidente/insertarTipoIncidente',
	ActDel:'../../sis_reclamos/control/TipoIncidente/eliminarTipoIncidente',
	ActList:'../../sis_reclamos/control/TipoIncidente/listarTipoIncidente',
	id_store:'id_tipo_incidente',
	fields: [
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
	sortInfo:{
		field: 'id_tipo_incidente',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		