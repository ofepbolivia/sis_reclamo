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
						name: 'sugerencia_respuesta',
						fieldLabel: 'sugerencia_respuesta',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.sugerencia_respuesta',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config: {
						name: 'id_reclamo',
						fieldLabel: 'id_reclamo',
						allowBlank: true,
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
						name: 'antecedentes_informe',
						fieldLabel: 'antecedentes_informe',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.antecedentes_informe',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'nro_informe',
						fieldLabel: 'nro_informe',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.nro_informe',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config: {
						name: 'id_funcionario',
						fieldLabel: 'id_funcionario',
						allowBlank: true,
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
						hiddenName: 'id_funcionario',
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
						name: 'conclusion_recomendacion',
						fieldLabel: 'conclusion_recomendacion',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.conclusion_recomendacion',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'fecha_informe',
						fieldLabel: 'fecha_informe',
						allowBlank: true,
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
						fieldLabel: 'lista_compensacion',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.lista_compensacion',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'analisis_tecnico',
						fieldLabel: 'analisis_tecnico',
						allowBlank: true,
						anchor: '80%',
						gwidth: 100,
						maxLength:255
					},
					type:'TextField',
					filters:{pfiltro:'infor.analisis_tecnico',type:'string'},
					id_grupo:1,
					grid:true,
					form:true
				},
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

		