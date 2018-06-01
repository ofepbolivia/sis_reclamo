<?php
/**
 *@package pXP
 *@file    FormRespuesta.php
 *@author  Franklin Espinoza Alvarez
 *@date    7-12-2017
 *@description Permite insertar un incidente envio de equipaje.
 */
include_once ('../../media/styles.php');
header("content-type: text/javascript; charset=UTF-8");
?>



<script>
    Phx.vista.FormRespuesta=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_reclamo/control/Respuesta/insertarRespuesta',
        //layout: 'fit',
        autoScroll: false,
        breset: false,
        labelSubmit: '<i class="fa fa-check"></i> Guardar',
        bcancel: true,
        constructor:function(config){
            /*this.addEvents('beforesave');
            this.addEvents('successsave');*/
            this.maestro = config.data.objPadre;
            console.log('maestro Envio', this.maestro);
            this.loadForm();
            this.buildGrupos();
            Phx.vista.FormRespuesta.superclass.constructor.call(this,config);
            this.init();
            //this.iniciarEventos();
        },

        /*ocultarComponente:  function () {
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         this.Atributos[this.getIndAtributo('')].form=false;
         },*/

        iniciarEventos: function () {


            if(this.maestro.momento == 'new') {
                Ext.Ajax.request({
                    url: '../../sis_reclamo/control/Reclamo/getDatosOficina',
                    params: {
                        id_usuario: 0
                    },
                    success: function (resp) {
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        console.log('reg',reg);
                        this.Cmp.id_funcionario_responsable.setValue(reg.ROOT.datos.id_funcionario);
                        this.Cmp.id_funcionario_responsable.setRawValue(reg.ROOT.datos.desc_funcionario1);
                        this.Cmp.id_oficina_registro.setValue(reg.ROOT.datos.id_oficina);
                        this.Cmp.id_oficina_registro.setRawValue(reg.ROOT.datos.oficina_nombre);

                    },
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }else if(this.maestro.momento == 'edit') {

                this.cargarFormulario();
            }

            this.Cmp.id_funcionario_responsable.on('select', function (cmp, rec, index) {
                console.log('chulos', rec.data.id_funcionario);
                Ext.Ajax.request({
                    url: '../../sis_equipajes/control/ParteIrregular/listarOficinaResponsable',
                    params: {
                        id_funcionario: rec.data.id_funcionario
                    },
                    success: function (resp) {
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        this.Cmp.id_oficina_registro.setValue(reg.ROOT.datos.id_oficina);
                        this.Cmp.id_oficina_registro.setRawValue(reg.ROOT.datos.oficina_nombre);
                    },
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });

            }, this);

        },

        cargarFormulario: function () {



            this.Cmp.id_parte_irregular.setValue(this.maestro.id_parte_irregular);

            this.Cmp.id_funcionario_responsable.setValue(this.maestro.id_funcionario_responsable);
            this.Cmp.id_funcionario_responsable.setRawValue(this.maestro.desc_funcionario);

            this.Cmp.id_oficina_registro.setValue(this.maestro.id_oficina_registro);
            this.Cmp.id_oficina_registro.setRawValue(this.maestro.desc_oficina);

            this.Cmp.originador.setValue(this.maestro.originador );
            this.Cmp.fecha_originador.setValue(this.maestro.fecha_originador );

            this.Cmp.id_matricula.setValue(this.maestro.id_matricula);
            this.Cmp.id_matricula.setRawValue(this.maestro.desc_orden);

            this.Cmp.vuelo.setValue(this.maestro.vuelo);

            var  objeto_encontrado = Ext.util.JSON.decode(Ext.util.Format.trim(this.maestro.objeto_encontrado));
            this.objeto_store.loadData(objeto_encontrado);
        },

        onInitAdd: function(){

        },
        onCancelAdd: function(re,save){
            if(this.sw_init_add){
                this.mestore.remove(this.mestore.getAt(0));
            }

            this.sw_init_add = false;
            //this.evaluaGrilla();
        },
        onUpdateRegister: function(){
            this.sw_init_add = false;
        },

        onAfterEdit:function(re, o, rec, num){

            //console.log('prueba',this.descCmp.tipo.getValue());
            if(this.maestro.vista != 'OEN') {
                var cmb_rec = this.descCmp['color'].store.getById(rec.get('color'));

                if (cmb_rec) {
                    rec.set('color', cmb_rec.get('nombre'));
                }

                var cmb_rec = this.descCmp['tipo'].store.getById(rec.get('tipo'));
                if (cmb_rec) {
                    rec.set('tipo', cmb_rec.get('nombre'));
                }

                var cmb_rec = this.descCmp['descripcion'].store.getById(rec.get('descripcion'));
                if (cmb_rec) {
                    rec.set('descripcion', cmb_rec.get('nombre'));
                }
            }

        },

        guardarDireccion: function () {
            console.log('guardarDireccion');
        },
        loadForm:  function () {
            console.log('Carga Panel Envio');
            myData = {
                records : [
                    { id_parte_irregular : "0", nro_tramite : "CERO", cliente : "0", nro_ticket: "0", peso_entrega: "0" },
                    { id_parte_irregular : "1", nro_tramite : "UNO", cliente : "1", nro_ticket: "1", peso_entrega: "1" },
                    { id_parte_irregular : "2", nro_tramite : "DOS", cliente : "2", nro_ticket: "2", peso_entrega: "2" },
                    { id_parte_irregular : "3", nro_tramite : "TRES", cliente : "3", nro_ticket: "3", peso_entrega: "3" },
                    { id_parte_irregular : "4", nro_tramite : "CUATRO", cliente : "4", nro_ticket: "4", peso_entrega: "4" },
                    { id_parte_irregular : "5", nro_tramite : "CINCO", cliente : "5", nro_ticket: "5", peso_entrega: "5" },
                    { id_parte_irregular : "6", nro_tramite : "SEIS", cliente : "6", nro_ticket: "6", peso_entrega: "6" },
                    { id_parte_irregular : "7", nro_tramite : "SIETE", cliente : "7", nro_ticket: "7", peso_entrega: "7" },
                    { id_parte_irregular : "8", nro_tramite : "OCHO", cliente : "8", nro_ticket: "8", peso_entrega: "8" },
                    { id_parte_irregular : "9", nro_tramite : "NUEVE", cliente : "9", nro_ticket: "9", peso_entrega: "9" }
                ]
            };


            // Generic fields array to use in both store defs.
            fields = [
                {name: 'id_parte_irregular', mapping : 'id_parte_irregular'},
                {name: 'nro_tramite', mapping : 'nro_tramite'},
                {name: 'cliente', mapping : 'cliente'},
                {name: 'nro_ticket', mapping : 'nro_ticket'},
                {name: 'direccion_temporal', mapping : 'direccion_temporal'},
                {name: 'enviar', mapping : 'enviar'}
            ];

            // create the data store
            firstGridStore = new Ext.data.JsonStore({
                url: '../../sis_equipajes/control/ParteIrregular/listarParteIrregular',
                id: 'id_parte_irregular',
                root: 'datos',
                sortInfo: {
                    field: 'nro_tramite',
                    direction: 'DESC'
                },
                totalProperty: 'total',
                fields: ['id_parte_irregular',
                    'nro_tramite',
                    'cliente',
                    'nro_ticket',
                    'direccion_temporal',
                    'enviar',
                ],
                remoteSort: true,
                baseParams: {
                    start: 0,
                    limit: 50,
                    sort: 'nro_tramite',
                    dir: 'DESC',
                    tipo_interfaz: 'SEL',
                    estado_envio: 'enviar'
                }
            });firstGridStore.load();
            //var aux = Ext.util.JSON.decode(firstGridStore);
            //var aux = JSON.stringify(firstGridStore.data);

            firstArrayStore = new Ext.data.ArrayStore({
                fields: ['id_parte_irregular','nro_tramite','cliente'],
            });

            firstArrayStore = firstGridStore;
            var mixed = firstGridStore.datos;



            var check = new Ext.grid.CheckboxSelectionModel();
            var num = new Ext.grid.RowNumberer();
            var cm = new Ext.grid.ColumnModel({
                defaults: {
                    sortable: true
                },
                columns          : [
                    num,
                    {header: "Nro Tramite", width: 150, sortable: true, dataIndex: 'nro_tramite'},
                    {header: "cliente", width: 220, sortable: true, dataIndex: 'cliente'},
                    {
                        header: "Direccion Temporal",
                        width: 240,
                        dataIndex: 'direccion_temporal',
                        sortable: true,
                        editor: new Ext.form.TextField({
                            allowBlank: false
                        })
                    },
                    {header: "nro_ticket", width: 100, sortable: true, dataIndex: 'nro_ticket'},
                    {xtype: 'checkcolumn', header: 'Enviar', dataIndex: 'enviar', width: 55, align: 'center',listeners: {
                            checkchange: function (cmp, checked) {
                                alert('CHECK');
                                console.log('CHECK');
                            }
                        }}
                ]
            });
            // Grid Pendiente de Envio
            this.firstGrid = new Ext.grid.EditorGridPanel({
                store            : firstGridStore,
                cm: cm,
                stripeRows       : true,

                title            : 'Equipajes Pendientes de Envio',

                columnLines      : true,
                height: 380,
                width: 700,
                tbar: {
                    defaults:{scope:this},
                    items: [
                        {
                            text: 'Guardar Direcciones',
                            iconCls:'btn-save',
                            handler: function() {

                                var records = '';
                                var modified = this.firstGrid.store.getModifiedRecords();
                                var tam = modified.length;
                                var cont = 1;
                                if(tam>=1) {
                                    Ext.each(modified, function (record, index) {
                                        records += record.id + ', ' + record.data['direccion_temporal'];
                                        if (cont < tam) {
                                            records += ';';
                                        }
                                        cont++;
                                    });

                                    Ext.Ajax.request({
                                        url: '../../sis_equipajes/control/Envio/actualizarDireccion',
                                        params: {'records': records},
                                        success: function (resp) {
                                            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

                                            if(objRes.ROOT.datos.estado_envio) {
                                                Ext.Msg.show({
                                                    title: 'Información',
                                                    msg: '<b>Estimado usuario:</b> <br>Las direcciones se guardaron con Exito.</br>',
                                                    buttons: Ext.Msg.OK,
                                                    width: 512,
                                                    icon: Ext.Msg.INFO
                                                });
                                            }
                                        },
                                        failure: this.conexionFailure,
                                        timeout: this.timeout,
                                        scope: this
                                    });
                                }else{
                                    Ext.Msg.show({
                                        title: 'Información',
                                        msg: '<b>Estimado Usuario no ha modificado ningun dirección.</b>',
                                        buttons: Ext.Msg.OK,
                                        width: 512,
                                        icon: Ext.Msg.INFO
                                    });
                                }

                            }
                        }
                    ]
                },
                // paging bar on the bottom
                bbar: new Ext.PagingToolbar({
                    pageSize: 10,
                    store: firstGridStore,
                    displayInfo: true,
                    displayMsg: 'Pagina {0} - {1} de {2}',
                    emptyMsg: "No se tiene Registros",
                    items:[
                        '-',/* {
                        pressed: true,
                        enableToggle:true,
                        text: 'Show Preview',
                        cls: 'x-btn-text-icon details',
                        toggleHandler: function(btn, pressed){
                            var view = grid.getView();
                            view.showPreview = pressed;
                            view.refresh();
                        }
                    }*/]
                }),

                viewConfig: {
                    scrollOffset: 0
                },
                view: new Ext.grid.GridView({
                    getRowClass: function (rec, idx, rowParams, store){
                        var id = parseInt(rec.id);
                        //alert(id);
                    }
                }),
                sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                frame: true,

            });
            //this.firstGrid.getStore().reload();


            secondGridStore = new Ext.data.JsonStore({
                fields : fields
            });
            console.log('secondGridStore', secondGridStore);
            // create the destination Grid
            this.secondGrid = new Ext.grid.GridPanel({
                id: 'second',
                height: 400 ,
                renderTo : Ext.getCmp(this.maestro.idContenedor).body,
                ddGroup          : 'firstGridDDGroup',
                store            : secondGridStore,
                columns          : [{ id : 'nro_tramite', header: "Nro Tramite", width: 70, sortable: true, dataIndex: 'nro_tramite'},
                    {header: "cliente", width: 100, sortable: true, dataIndex: 'cliente'},
                    {header: "nro_ticket", width: 70, sortable: true, dataIndex: 'nro_ticket'}
                ],
                enableDragDrop   : true,
                stripeRows       : true,
                autoExpandColumn : 'nro_tramite',
                title            : 'Equipajes Enviados',
                loadMask         : true,
                columnLines      : true,
                viewConfig: {
                    scrollOffset: 0
                },
                view: new Ext.grid.GridView({
                    getRowClass: function (rec, idx, rowParams, store){
                        var id = parseInt(rec.id);
                        //alert(id);
                    }
                }),
                frame: true,

            });
            console.log('GRILLAS ',this.firstGrid, this.secondGrid);
            //Simple 'border layout' panel to house both grids
            this.panelPrincipal = new Ext.Panel({
                /*id:'principal',*/
                width        : 800,
                height       : 500,
                layout       : 'hbox',
                split: true,
                border: true,
                plain: true,
                anchor: '100%',
                scope:this,
                /*renderTo : Ext.getCmp(this.idContenedor).body,
                defaults     : { flex : 1 }, //auto stretch
                layoutConfig : { align : 'stretch' },*/
                items        : [
                    this.firstGrid,
                    this.secondGrid
                ],
                bbar    : [
                    '->', // Fill
                    {
                        text    : 'Reestablecer',
                        handler : function() {
                            //refresh source grid
                            this.firstGrid.getStore().reload();

                            //purge destination grid
                            this.secondGridStore.removeAll();
                        }
                    }
                ]
            });
            that = this;
            // used to add records to the destination stores
            var blankRecord =  Ext.data.Record.create(this.fields);


        },


        buildGrupos: function() {
            this.Grupos = [
                {
                    layout: 'column',
                    //bodyStyle: 'padding-right:10px;',
                    labelWidth: 80,
                    labelAlign: 'top',
                    border: false,
                    items: [
                        {
                            columnWidth: .30,
                            border: false,
                            layout: 'fit',
                            bodyStyle: 'padding-right:10px;',
                            items: [
                                {
                                    xtype: 'fieldset',
                                    title: 'DATOS RECEPCIÓN',

                                    autoHeight: true,
                                    items: [
                                        {
                                            layout: 'form',
                                            anchor: '100%',
                                            //bodyStyle: 'padding-right:10px;',
                                            border: false,
                                            padding: '0 5 0 5',
                                            //bodyStyle: 'padding-left:5px;',
                                            id_grupo: 1,
                                            items: []
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            columnWidth: .70,
                            layout: 'fit',
                            bodyStyle: 'padding-top:7px;',
                            labelWidth: 80,
                            labelAlign: 'top',
                            border: false,
                            items: [

                                this.firstGrid

                            ]
                        }

                    ]
                }
            ];
        },


        fields: [
            {name:'id_envio', type: 'numeric'},
            {name:'id_transporte', type: 'numeric'},
            {name:'dirreccion_envio', type: 'string'},
            {name:'cliente_envio', type: 'string'},
            {name:'detalle_envio', type: 'string'},
            {name:'peso_envio', type: 'numeric'}
        ],
        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_envio'
                },
                type:'Field',
                form:true
            },
            /*{
             config:{
             name: 'nro_tramite',
             fieldLabel: 'Nro. Tramite',
             allowBlank: false,
             anchor: '90%',
             gwidth: 220,
             maxLength:20,

             renderer: function(value, p, record) {
             //return String.format('<div ext:qtip="Optimo"><b><font color="green">{0}</font></b><br></div>', value);

             return   '<div class="x-combo-list-item"><p><b>Nro. Tramite: </b><b style="color:green;">'+record.data['nro_tramite']+'</b></p></div>';
             }
             },
             type:'TextField',
             filters:{pfiltro:'part_irr.nro_tramite',type:'string'},
             id_grupo:12,
             grid:true,
             form:false
             },
             {
             config: {
             name: 'id_parte_irregular',
             fieldLabel: 'id_parte_irregular',
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
             hiddenName: 'id_parte_irregular',
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
             },*/

            {
                config: {
                    name: 'id_transporte',
                    fieldLabel: 'Empresa Transporte',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_equipajes/control/Transportista/listarTransportista',
                        id: 'id_transporte',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre_trans',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_transporte', 'nombre_trans', 'descripcion_trans'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'trans.nombre_trans#trans.descripcion_trans'}
                    }),
                    valueField: 'id_transporte',
                    displayField: 'nombre_trans',
                    gdisplayField: 'desc_transporte',
                    hiddenName: 'id_transporte',
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
                        return String.format('{0}', record.data['desc_transporte']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 1,
                filters: {pfiltro: 'envio.id_transporte',type: 'string'},
                grid: true,
                form: true
            },/*
            {
                config: {
                    name: 'id_transporte',
                    fieldLabel: 'Empresa Transporte',
                    allowBlank: false,
                    anchor: '100%',
                    /!*hidden: false,*!/
                    gwidth: 100,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:[ 'Empresa 1', 'Empresa 2', 'Empresa 3'],
                    style:'text-transform:uppercase;'
                },
                type: 'ComboBox',
                filters: {pfiltro: 'envio.id_transporte', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },*/
            {
                config:{
                    name: 'dirreccion_envio',
                    fieldLabel: 'Direccion',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:250,
                    hidden: true
                },
                type:'TextField',
                filters:{pfiltro:'envio.dirreccion_envio',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'cliente_envio',
                    fieldLabel: 'Cliente',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:250,
                    hidden: true
                },
                type:'TextField',
                filters:{pfiltro:'envio.cliente_envio',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'detalle_envio',
                    fieldLabel: 'Detalle Envio',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10000
                },
                type:'TextArea',
                filters:{pfiltro:'envio.detalle_envio',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'costo_envio',
                    fieldLabel: 'Costo de Envio',
                    allowBlank: false,
                    anchor: '50%',
                    gwidth: 100,
                    maxLength:655362
                },
                type:'NumberField',
                filters:{pfiltro:'envio.costo_envio',type:'numeric'},
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
                filters:{pfiltro:'envio.estado_reg',type:'string'},
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
                filters:{pfiltro:'envio.usuario_ai',type:'string'},
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
                filters:{pfiltro:'envio.fecha_reg',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'id_usuario_ai',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'envio.id_usuario_ai',type:'numeric'},
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
                filters:{pfiltro:'envio.fecha_mod',type:'date'},
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
        title:'Tranferencia PIR',

        fields: [
            {name:'id_parte_irregular', type: 'numeric'},
            {name:'vista', type: 'string'},
            {name:'fecha_originador', type: 'date'},
            {name:'vuelo', type: 'string'},
            {name:'originador', type: 'string'},
            {name:'id_matricula', type: 'numeric'},
            {name:'id_funcionario_responsable', type: 'numeric'},
            {name:'id_oficina_registro', type: ' numeric'}
        ],

        onSubmit:function(o){
            //this.Cmp.vista.setValue(this.maestro.vista);
            /*var i, reg = [];
            for (i = 0; i < this.objeto_grid.store.getCount(); i++) {
                record = this.objeto_grid.store.getAt(i);
                reg[i] = record.data;
            }
            console.log('registros',reg);
            this.argumentExtraSubmit = {
                'objeto_grid': JSON.stringify(reg, function replacer(key, value) {
                    return value;
                }),
                'vista': this.maestro.vista

            };*/
            var records = '';
            var items = this.firstGrid.getStore().data;
            console.log('datos modificados', items);
            Ext.each(items.items, function (record, index) {
                if(record.data.enviar) {
                    if (records != '') {
                        records = records + ',' + record.id;
                    } else {
                        records = record.id;
                    }
                }
            });
            this.records = records;
            this.transporte = this.Cmp.id_transporte.getRawValue();
            this.detalle = this.Cmp.detalle_envio.getValue();
            console.log('this.records', this.records,this.transporte,this.detalle);
            this.argumentExtraSubmit = {
                'registros': records
            };


            Phx.vista.FormRespuesta.superclass.onSubmit.call(this,o);
        },

        successSave:function(resp){

            Ext.Ajax.request({
                url:'../../sis_equipajes/control/Envio/generarNotaEnvio',
                params:{
                    records: this.records,
                    transporte: this.transporte,
                    detalle: this.detalle
                },
                argument:{},
                success: function (resp) {
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    console.log('generado',reg.ROOT.detalle.archivo_generado);
                    window.open('../../../lib/lib_control/Intermediario.php?r='+reg.ROOT.detalle.archivo_generado+'&t='+new Date().toLocaleTimeString());
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.close();
        },
        records: undefined,
        transporte: undefined,
        detalle: undefined
    });
</script>