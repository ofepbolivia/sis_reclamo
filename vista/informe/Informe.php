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

                var fecha = new Date();
                Ext.Ajax.request({
                    url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                    params:{fecha:fecha.getDate()+'/'+(fecha.getMonth()+1)+'/'+fecha.getFullYear()},
                    success:function(resp){
                        var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                        this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                        this.cmbGestion.setRawValue(fecha.getFullYear());
                        this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
                        this.load({params:{start:0, limit:this.tam_pag}});
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });

				this.maestro=config.maestro;
				console.log('informe: '+config);
				//llama al constructor de la clase padre
				Phx.vista.Informe.superclass.constructor.call(this,config);
				//this.grid.getTopToolbar().disable();
				//this.grid.getBottomToolbar().disable();
				this.init();
				//this.load({params:{start:0, limit: 0}});
				//this.bloquearMenus();
				//this.iniciarEventos();
                this.addButton('copiar',{
                    grupo:[0,1,2,3,4,5],
                    text :'Copiar Informe.',
                    iconCls : 'bfolder',
                    /*disabled: true,*/
                    handler : this.copiarInf,
                    tooltip : '<b>Copiar</b><br/><b>Nos permite copiar, un Informe similar para varios Reclamos.</b>'
                });

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
						labelSeparator: '',
						name: 'id_reclamo',
						inputType:'hidden',

					},
					type:'Field',
					form:true
				},
				{
					config:{
						name: 'nro_informe',
						fieldLabel: 'Nro. de Informe',
						allowBlank: true,
						anchor: '50%',
						gwidth: 150,
						maxLength:20,
						readOnly:true,
						renderer: function(value,p,record) {
							return String.format('<b><font color="green">{0}</font></b>', value);
						}
					},
					type:'TextField',
					filters:{pfiltro:'infor.nro_informe',type:'string'},
					bottom_filter:true,
					id_grupo:1,
					grid:true,
					form:true
				},
				{
					config:{
						name: 'fecha_informe',
						fieldLabel: 'Fecha Informe',
						allowBlank: false,
						anchor: '40%',
						gwidth: 100,
						format: 'd/m/Y',
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
					},
					type:'DateField',
					filters:{pfiltro:'infor.fecha_informe',type:'date'},
					id_grupo:0,
					grid:true,
					form:true,
                    bottom_filter : true
				},
				{
					config: {

						name: 'id_funcionario',
						fieldLabel: 'Funcionario Informe',
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
						gdisplayField: 'desc_fun',
						hiddenName: 'id_funcionario',
						forceSelection: true,
						typeAhead: false,
						triggerAction: 'all',
						lazyRender: true,
						mode: 'remote',
						pageSize: 15,
						queryDelay: 1000,
						anchor: '50%',
						gwidth: 200,
						minChars: 2,
						renderer: function (value, p, record) {
							return String.format('{0}', record.data['desc_fun']);
						}
					},
					type: 'ComboBox',
					id_grupo: 1,
					filters: {pfiltro: 'PERSON.nombre_completo1', type: 'string'},
					grid: true,
					form: true,
                    bottom_filter : true
				},
				{
					config: {
						name: 'lista_compensacion',
						fieldLabel: 'Lista de Compensaciones',
						allowBlank: true,
						emptyText: 'Seleccion...',
						store: new Ext.data.JsonStore({
							url: '../../sis_reclamo/control/Compensacion/listarCompensacion',
							id: 'id_compensacion',
							root: 'datos',
							sortInfo: {
								field: 'orden',
								direction: 'ASC'
							},
							totalProperty: 'total',
							fields: ['id_compensacion', 'nombre'],
							remoteSort: true,
							baseParams: {par_filtro: 'com.nombre'}
						}),
						valueField: 'id_compensacion',
						displayField: 'nombre',
						gdisplayField: 'lista_compensacion',//mapea al store del grid
						hiddenName: 'id_compensacion',
						forceSelection: true,
						typeAhead: true,
						triggerAction: 'all',
						lazyRender: true,
						mode: 'remote',
						pageSize: 15,
						queryDelay: 1000,
						anchor: '50%',
						gwidth: 270,
						minChars: 2,
						enableMultiSelect: true,
						renderer: function (value, p, record) {
							return String.format('{0}', record.data['lista']);
						}
					},
					type: 'AwesomeCombo',
					id_grupo: 0,
					grid: true,
					form: true,
                    bottom_filter : true
				},
				{
					config:{
						name: 'antecedentes_informe',
						fieldLabel: 'Antecedentes',
						allowBlank: true,
						anchor: '80%',
						height: 80,
						gwidth: 100,
						maxLength:100000
					},
					type:'TextArea',
					filters:{pfiltro:'infor.antecedentes_informe',type:'string'},
					id_grupo:1,
					grid:false,
					form:true,
                    bottom_filter : true
				},
				{
					config:{
						name: 'analisis_tecnico',
						fieldLabel: 'Analisis Tecnico',
						allowBlank: true,
						anchor: '80%',
						height: 80,
						gwidth: 200,
						maxLength:100000
					},
					type:'TextArea',
					filters:{pfiltro:'infor.analisis_tecnico',type:'string'},
					bottom_filter:true,
					id_grupo:1,
					grid:false,
					form:true
				},
				{
					config:{
						name: 'conclusion_recomendacion',
						fieldLabel: 'Conclusiones y Recomendaciones',
						allowBlank: true,
						anchor: '80%',
						height: 80,
						gwidth: 200,
						maxLength:100000
					},
					type:'TextArea',
					filters:{pfiltro:'infor.conclusion_recomendacion',type:'string'},
					id_grupo:1,
					grid:false,
					form:true,
                    bottom_filter : true
				},
				{
					config:{
						name: 'sugerencia_respuesta',
						fieldLabel: 'Sugerencia de Respuesta',
						allowBlank: true,
						anchor: '80%',
						height: 80,
						gwidth: 200,
						maxLength:100000
					},
					type:'TextArea',
					filters:{pfiltro:'infor.sugerencia_respuesta',type:'string'},
					id_grupo:1,
					grid:false,
					form:true,
                    bottom_filter : true
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
			title:'Informe',
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
				
				//{name:'desc_nombre_compensacion', type: 'string'},
				{name:'desc_fun', type: 'string'},
				{name:'lista', type: 'string'}
			],
			sortInfo:{
				field: 'id_informe',
				direction: 'ASC'
			},
        
        rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                //'<br>','<h1 style="text-align: center">DATOS DE CONTACTO</h1>',

                '<p>&nbsp;&nbsp;&nbsp;<b>ANTECEDENTES:&nbsp;</b> {antecedentes_informe}</p>',
                '<p>&nbsp;&nbsp;&nbsp;<b>ANALISIS TECNICO:&nbsp;</b> {analisis_tecnico}</p>',
                '<p>&nbsp;&nbsp;&nbsp;<b>CONCLUSIONES Y RECOMENDACIONES:&nbsp;</b> {conclusion_recomendacion}</p>',
                '<p>&nbsp;&nbsp;&nbsp;<b>SUGERENCIA DE RESPUESTA:&nbsp;</b> {sugerencia_respuesta}</p>',
                )
        }),
        capturarEventos: function () {

            this.store.baseParams.id_gestion = this.cmbGestion.getValue();
            this.load({params: {start: 0, limit: this.tam_pag}});
        },
			bdel:true,
			bsave:false,
			btest: false,
			fwidth: '55%',
			fheight: '90%',
			requireclase: 'Phx.vista.Respuesta',

			onButtonNew: function () {
				Phx.vista.Informe.superclass.onButtonNew.call(this);
				this.Cmp.nro_informe.setValue(this.maestro.nro_tramite);
				this.Cmp.fecha_informe.setValue(new Date());

				Ext.Ajax.request({
					url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
					params:{id_usuario: 0},
					success:function(resp){
						var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

						this.Cmp.id_funcionario.setValue(reg.ROOT.datos.id_funcionario);;
						this.Cmp.id_funcionario.setRawValue(reg.ROOT.datos.desc_funcionario1);

                        this.Cmp.id_gestion.setValue(this.cmbGestion.getValue());
                        this.Cmp.id_gestion.setRawValue(this.cmbGestion.getRawValue());
					},
					failure: this.conexionFailure,
					timeout:this.timeout,
					scope:this
				});
				//this.Cmp.id_funcionario.setValue(this.maestro.id_funcionario_recepcion);
			},

			/*onReloadPage: function(m){
				this.maestro = m;
				console.log('bolivia',this.maestro);
                //this.store.baseParams = {id_informe: this.maestro.id_informe};
                this.store.baseParams = {id_reclamo: this.maestro.id_reclamo};
				this.load({params:{start: 0, limit: 50}});

			},*/

            onReloadPage:function(param){
                //Se obtiene la gestión de los Reclamos.
                this.maestro = param;
                this.initFiltro(param);
            },

            initFiltro: function(param){
                console.log('parametros',param);
                this.store.baseParams=param;
                this.load( { params: { start:0, limit: this.tam_pag } });
            },

			loadValoresIniciales: function(){
				this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
				Phx.vista.Informe.superclass.loadValoresIniciales.call(this);
			},

            preparaMenu:function(n){
                //console.log('preparaMenu');
                Phx.vista.Informe.superclass.preparaMenu.call(this,n);
                this.getBoton('copiar').enable();
                if(this.maestro.estado ==  'pendiente_revision'||this.maestro.estado == 'registrado_ripat'||this.maestro.estado ==  'derivado'){
                    this.getBoton('del').disable();
                }else if(this.maestro.estado ==  'anulado'){
                    this.getBoton('new').disable();
                    this.getBoton('edit').disable();
                    this.getBoton('del').disable();
                }

                /*var padre = Phx.CP.getPagina(this.idContenedorPadre).nombreVista;

                if(this.maestro.estado ==  'borrador' || (padre == 'ObligacionPagoVb' && this.maestro.estado ==  'vbpresupuestos')){
                    alert(padre);
                    this.getBoton('edit').enable();
                    this.getBoton('new').enable();
                    this.getBoton('del').enable();

                    this.getBoton('btnProrrateo').enable();
                    /usr/lib64/libtdsodbc.so


                 }
                else{
                    alert(padre);
                    this.getBoton('edit').disable();
                    this.getBoton('new').disable();
                    this.getBoton('del').disable();
                    this.getBoton('btnProrrateo').disable();


                }
                if(this.maestro&&(this.maestro.estado ==  'borrador' && this.maestro.tipo_obligacion=='adquisiciones' )){

                    this.getBoton('edit').enable();
                    this.getBoton('new').disable();
                    this.getBoton('del').disable();
                    this.getBoton('btnProrrateo').disable();
                }*/
            },

            liberaMenu: function() {
                Phx.vista.Informe.superclass.liberaMenu.call(this);
                this.getBoton('copiar').disable();


            },

            copiarInf: function () {
                var rec = this.sm.getSelected();
                //console.log(rec.data.id_informe);
                this.objWizard = Phx.CP.loadWindows('../../../sis_reclamo/vista/informe/Copiar.php',
                    'Copiar Informe',
                    {
                        modal: true,
                        width: 450,
                        height: 150
                    },
                    {
                        data: {
                            id_informe: rec.data.id_informe
                        }
                    }, this.idContenedor, 'Copiar'/*,
                    {
                        config: [{
                            event: 'beforesave',
                            delegate: this.onExito,
                        }],
                        scope: this
                    }*/
                );
                /*this.crearFormCopiarRec();
                this.wReclamos.show();*/
            },

            /*onExito : function () {
                Phx.CP.loadingShow();
                Phx.CP.loadingHide();
                this.reload();
            },*/

            crearFormCopiarRec: function () {
                console.log('itemSelector');
                this.formReclamos = new Ext.form.FormPanel({
                    baseCls: 'x-plain',
                    autoDestroy: true,
                    layout: 'form',
                    items: [
                        {
                            xtype: 'itemselector',
                            name: 'itemselector',
                            fieldLabel: '',
                            imagePath: '../../../lib/ext3/examples/ux/images/',
                            multiselects:
                            [
                                {
                                    legend: 'Lista de Reclamos',
                                    width: 280,
                                    height: 320,
                                    store: new Ext.data.JsonStore({
                                        url: '../../sis_reclamo/control/Reclamo/listarConsulta',
                                        id: 'id_reclamo',
                                        root: 'datos',
                                        sortInfo: {
                                            field: 'nro_tramite',
                                            direction: 'ASC'
                                        },
                                        totalProperty: 'total',
                                        fields: ['id_reclamo', 'nro_tramite'],
                                        remoteSort: true/*,
                                        baseParams: {par_filtro: 'rec.id_reclamo'}*/
                                    }),
                                    valueField: 'id_reclamo',
                                    displayField: 'nro_tramite',
                                    title: 'Izquierda'

                                }
                                ,{
                                    legend: 'Informe Copiado a Reclamos',
                                    width: 280,
                                    height: 320,
                                    store: [],
                                    title: 'Derecha'/*,
                                    tbar:[{
                                        text: 'clear',
                                        handler:function(){
                                            this.formReclamos.getForm().findField('itemselector').reset();
                                        }
                                    }]*/
                                }
                            ]
                        }
                    ]
                });

                console.log('pasa');
                this.wReclamos = new Ext.Window({
                    title: 'Reclamos a Seleccionar',
                    collapsible: true,
                    maximizable: true,
                    autoDestroy: true,
                    width: 800,
                    height: 400,
                    layout: 'fit',
                    plain: true,
                    bodyStyle: 'padding:5px;',
                    buttonAlign: 'center',
                    items: this.formReclamos,
                    modal:true,
                    closeAction: 'hide',
                    buttons: [{
                        text: 'Guardar',
                        handler:this.onSubmitReclamos,
                        scope:this

                    },{
                        text: 'Cancelar',
                        handler:function(){this.wReclamos.hide()},
                        scope:this
                    }]
                });
                console.log('fallas');
            },

            onSubmitReclamos : function(){
                Ext.Msg.alert('funciona');
                this.wReclamos.hide();
                this.reload();
                /*var d= this.sm.getSelected().data;

                this.DataSelected = d

                Phx.CP.loadingShow();

                Ext.Ajax.request({
                    url:'../../sis_adquisiciones/control/Cotizacion/siguienteEstadoCotizacion',
                    params:{id_cotizacion:d.id_cotizacion,
                        fecha_oc: this.cmpFechaOC.getValue().dateFormat('d/m/Y'),
                        operacion:'verificar'},

                    //params:{id_cotizacion:d.id_cotizacion,operacion:'sol_apro'},
                    success:this.successSinc,
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });*/
            }


	});
</script>

		