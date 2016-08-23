<?php
/**
 *@package pXP
 *@file gen-Informe.php
 *@author  (admin)
 *@date 11-08-2016 01:52:07
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.Informe=Ext.extend(Phx.gridInterfaz,{

			constructor:function(config){
				this.maestro=config.maestro;
				//llama al constructor de la clase padre
				Phx.vista.Informe.superclass.constructor.call(this,config);
				this.init();
				this.load({params:{start:0, limit:this.tam_pag}})
			},

			Atributos:[
				{
					//configuracion del componente
					config:{
						labelSeparator:'',
						inputType:'hidden',
						name: 'id_informe'
					},
					type:'Field',
					form:true
				},
				{
					config:{
						name: 'fecha_informe',
						fieldLabel: 'Fecha Informe',
						allowBlank: false,
						anchor: '80%',
						gwidth: 100,
						format: 'd/m/Y',
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
					},
					type:'DateField',
					filters:{pfiltro:'infor.fecha_informe',type:'date'},
					id_grupo:1,
					grid:true,
					form:true
				},
				//Inicia combo uno
				{
					config:{
						name:'id_persona',
						fieldLabel:'Funcionario',
						allowBlank:false,
						emptyText:'Persona...',
						store: new Ext.data.JsonStore({
							url: '../../sis_seguridad/control/Persona/listarPersona',
							id: 'id_persona',
							root: 'datos',
							sortInfo:{
								field: 'nombre_completo1',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_persona','nombre_completo1','ci'],
							// turn on remote sorting
							remoteSort: true,
							baseParams:{par_filtro:'p.nombre_completo1#p.ci'}
						}),
						valueField: 'id_persona',
						displayField: 'nombre_completo1',
						gdisplayField:'desc_person',//mapea al store del grid
						tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre_completo1}</p><p>CI:{ci}</p> </div></tpl>',
						hiddenName: 'id_persona',
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
						turl:'../../../sis_seguridad/vista/persona/Persona.php',
						ttitle:'Personas',
						// tconfig:{width:1800,height:500},
						tdata:{},
						tcls:'persona',
						pid:this.idContenedor,

						renderer:function (value, p, record){return String.format('{0}', record.data['desc_person']);}
					},
					type:'TrigguerCombo',
					bottom_filter:true,
					id_grupo:0,
					filters:{
						pfiltro:'nombre_completo1',
						type:'string'
					},

					grid:true,
					form:true
				},
				//Finaliza combo uno
				/*
				{
					config:{
						name: 'id_usuario_ai',
						fieldLabel: '',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:4
					},
					type:'Field',
					filters:{pfiltro:'infor.id_usuario_ai',type:'numeric'},
					id_grupo:1,
					grid:false,
					form:false
				},*/
				{
					config: {
						name: 'id_reclamo',
						fieldLabel: 'Id Reclamo',
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
						hiddenName: 'id_reclamo',
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
						name: 'nro_informe',
						fieldLabel: 'Nro Informe',
						allowBlank: false,
						anchor: '80%',
						gwidth: 100,
						maxLength:20
					},
					type:'TextField',
					filters:{pfiltro:'infor.nro_informe',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'antecedentes_informe',
						fieldLabel: 'Antecedentes Informe',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextArea',
					filters:{pfiltro:'infor.antecedentes_informe',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'analisis_tecnico',
						fieldLabel: 'Analisis Tecnico',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextArea',
					filters:{pfiltro:'infor.analisis_tecnico',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'sugerencia_respuesta',
						fieldLabel: 'Sugerencia Respuesta',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextArea',
					filters:{pfiltro:'infor.sugerencia_respuesta',type:'string'},
					id_grupo:1,
					grid:true,
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
					filters:{pfiltro:'infor.estado_reg',type:'string'},
					id_grupo:1,
					grid:true,
					form:false
				},
				{
					config:{
						name: 'lista_compensacion',
						fieldLabel: 'Lista Compensacion',
						allowBlank: false,
						emptyText: 'Elija una opción...',
						store: new Ext.data.JsonStore({
							url: '../../sis_reclamo/control/Compensacion/listarCompensacion',
							id: 'id_compensacion',
							root: 'datos',
							sortInfo: {
								field: 'nombre',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_compensacion', 'nombre'],
							remoteSort: true,
							baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
						}),
						valueField: 'id_compensacion',
						displayField: 'nombre',
						gdisplayField: 'desc_nombre_compensacion',
						hiddenName: 'id_reclamo',
						forceSelection: true,
						enableMultiSelect:true,
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
							return String.format('{0}', record.data['desc_nombre_compensacion']);
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
						name: 'conclusion_recomendacion',
						fieldLabel: 'Conclusion Recomendacion',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextArea',
					filters:{pfiltro:'infor.conclusion_recomendacion',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
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
					filters:{pfiltro:'infor.usuario_ai',type:'string'},
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
					filters:{pfiltro:'infor.fecha_reg',type:'date'},
					id_grupo:1,
					grid:true,
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
					filters:{pfiltro:'infor.fecha_mod',type:'date'},
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
			title:'informe',
			ActSave:'../../sis_reclamo/control/Informe/insertarInforme',
			ActDel:'../../sis_reclamo/control/Informe/eliminarInforme',
			ActList:'../../sis_reclamo/control/Informe/listarInforme',
			id_store:'id_informe',
			fields: [
				{name:'id_informe', type: 'numeric'},
				{name:'sugerencia_respuesta', type: 'string'},
				{name:'id_reclamo', type: 'numeric'},
				{name:'antecedentes_informe', type: 'string'},
				{name:'nro_informe', type: 'string'},
				{name:'id_funcionario', type: 'numeric'},
				{name:'conclusion_recomendacion', type: 'string'},
				{name:'fecha_informe', type: 'date',dateFormat:'Y-m-d'},
				{name:'estado_reg', type: 'string'},
				{name:'lista_compensacion', type: 'string'},
				{name:'analisis_tecnico', type: 'string'},
				{name:'id_usuario_ai', type: 'numeric'},
				{name:'id_usuario_reg', type: 'numeric'},
				{name:'usuario_ai', type: 'string'},
				{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
				{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
				{name:'id_usuario_mod', type: 'numeric'},
				{name:'usr_reg', type: 'string'},
				{name:'usr_mod', type: 'string'},

			],
			sortInfo:{
				field: 'id_informe',
				direction: 'ASC'
			},
			bdel:true,
			bsave:true
		}
	)
</script>

		