<?php
/**
 *@package pXP
 *@file gen-Depto.php
 *@author  )
 *@date 24-11-2011 15:52:20
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    Ext.namespace('Phx','Phx.vista.widget');
    Ext.define('Phx.vista.ReportesEstadisticos',{
        extend: 'Ext.util.Observable',
        hombres:876,
        mujeres:654,
        constructor: function(config) {

            Ext.apply(this, config);
            var me = this;
            this.callParent(arguments);


            this.panel = Ext.getCmp(this.idContenedor);

            var newIndex = 3;

            this.loaderTree = new Ext.tree.TreeLoader({
                url : '../../sis_parametros/control/Dashboard/listarDashboard',
                baseParams : {foo:'bar'},
                clearOnLoad : true
            });


            // set up the Album tree
            this.treeMenu = new Ext.tree.TreePanel({
                // tree
                animate:true,
                //maskDisabled:false,
                containerScroll: true,
                rootVisible:false,
                region:'west',
                width:200,
                split:true,
                autoScroll:true,
                tbar: this.tb,
                loader : this.loaderTree,
                margins: '5 0 5 5'
            });

            this.menu = new Ext.FormPanel({
                labelWidth: 75, // label settings here cascade unless overridden
                url:'save-form.php',
                region:'west',
                /*frame:true,*/
                split:true,
                title: 'Elija Opcion de Reporte',
                bodyStyle:'padding:5px 5px 0',
                width: 250,
                defaults: {width: 200},
                margins: '5 0 5 5',

                items: [
                    {
                        xtype:'combo',
                        name: 'reportes',
                        id: 'reportes',
                        fieldLabel: 'Reporte de',
                        allowBlank:true,

                        width: 150,
                        maxLength:25,
                        typeAhead:true,
                        forceSelection: true,
                        triggerAction:'all',
                        mode:'local',
                        store:['Tipo Incidente','Ciudad de Reclamo','Lugar de Reclamo','Genero','Ambiente del Incidente','Estado del Reclamo']


                    /*filters:{pfiltro:'cli.genero',type:'string'},
                    id_grupo:0,
                    grid:true,
                    form:true*/
                }
                ],

                buttons: [{
                    iconCls: 'album-btn',
                    handler: this.guardar,
                    text: 'Generar',
                    scope: this
                }]
            });



            var myData = [
                ['3m Co',                               71.72, 0.02,  0.03,  '9/1 12:00am'],
                ['Alcoa Inc',                           29.01, 0.42,  1.47,  '9/1 12:00am'],
                ['Altria Group Inc',                    83.81, 0.28,  0.34,  '9/1 12:00am'],
                ['American Express Company',            52.55, 0.01,  0.02,  '9/1 12:00am'],
                ['American International Group, Inc.',  64.13, 0.31,  0.49,  '9/1 12:00am'],
                ['AT&T Inc.',                           31.61, -0.48, -1.54, '9/1 12:00am'],
                ['Boeing Co.',                          75.43, 0.53,  0.71,  '9/1 12:00am'],
                ['Caterpillar Inc.',                    67.27, 0.92,  1.39,  '9/1 12:00am'],
                ['Citigroup, Inc.',                     49.37, 0.02,  0.04,  '9/1 12:00am'],
                ['E.I. du Pont de Nemours and Company', 40.48, 0.51,  1.28,  '9/1 12:00am'],
                ['Exxon Mobil Corp',                    68.1,  -0.43, -0.64, '9/1 12:00am'],
                ['General Electric Company',            34.14, -0.08, -0.23, '9/1 12:00am'],
                ['General Motors Corporation',          30.27, 1.09,  3.74,  '9/1 12:00am'],
                ['Hewlett-Packard Co.',                 36.53, -0.03, -0.08, '9/1 12:00am'],
                ['Honeywell Intl Inc',                  38.77, 0.05,  0.13,  '9/1 12:00am'],
                ['Intel Corporation',                   19.88, 0.31,  1.58,  '9/1 12:00am'],
                ['International Business Machines',     81.41, 0.44,  0.54,  '9/1 12:00am'],
                ['Johnson & Johnson',                   64.72, 0.06,  0.09,  '9/1 12:00am'],
                ['JP Morgan & Chase & Co',              45.73, 0.07,  0.15,  '9/1 12:00am'],
                ['McDonald\'s Corporation',             36.76, 0.86,  2.40,  '9/1 12:00am'],
                ['Merck & Co., Inc.',                   40.96, 0.41,  1.01,  '9/1 12:00am'],
                ['Microsoft Corporation',               25.84, 0.14,  0.54,  '9/1 12:00am'],
                ['Pfizer Inc',                          27.96, 0.4,   1.45,  '9/1 12:00am'],
                ['The Coca-Cola Company',               45.07, 0.26,  0.58,  '9/1 12:00am'],
                ['The Home Depot, Inc.',                34.64, 0.35,  1.02,  '9/1 12:00am'],
                ['The Procter & Gamble Company',        61.91, 0.01,  0.02,  '9/1 12:00am'],
                ['United Technologies Corporation',     63.26, 0.55,  0.88,  '9/1 12:00am'],
                ['Verizon Communications',              35.57, 0.39,  1.11,  '9/1 12:00am'],
                ['Wal-Mart Stores, Inc.',               45.45, 0.73,  1.63,  '9/1 12:00am']
            ];

            var store = new Ext.data.ArrayStore({
                fields: [
                    {name: 'company'},
                    {name: 'price',      type: 'float'},
                    {name: 'change',     type: 'float'},
                    {name: 'pctChange',  type: 'float'},
                    {name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'}
                ]
            });
            store.loadData(myData);

            var banco = new Ext.data.JsonStore({
                url: '../../sis_reclamo/control/MotivoAnulado/listarMotivoAnulado',
                id:'id_motivo_anulado',
                totalProperty: 'total',
                root: 'datos',
                remoteSort: true,
                fields: ['id_motivo_anulado', 'motivo', 'orden']
            });
            console.log('banco',banco);






            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/stadistica',
                params:{id_usuario:0},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    hombres=parseInt(reg.ROOT.datos.v_hombres);
                    mujeres=parseInt(reg.ROOT.datos.v_mujeres);
                    //console.log(hombres, mujeres);

                    console.log('estadistica:',reg);
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            storeGraf = new Ext.data.JsonStore({
                fields: ['season', 'total'],
                data: [{
                    season: 'Hombres',
                    total: this.hombres
                },{
                    season: 'Mujeres',
                    total: this.mujeres
                }]
            });

            var grid = new Ext.grid.GridPanel({
                store: store,
                columns: [
                    {
                        id       :'company',
                        header   : 'Company',
                        width    : 160,
                        sortable : true,
                        dataIndex: 'company'
                    },
                    {
                        header   : 'Price',
                        width    : 75,
                        sortable : true,
                        renderer : 'usMoney',
                        dataIndex: 'price'
                    },
                    {
                        header   : 'Change',
                        width    : 75,
                        sortable : true,
                        renderer : this.change,
                        dataIndex: 'change'
                    },
                    {
                        header   : '% Change',
                        width    : 75,
                        sortable : true,
                        renderer : this.pctChange,
                        dataIndex: 'pctChange'
                    },
                    {
                        header   : 'Last Updated',
                        width    : 85,
                        sortable : true,
                        renderer : Ext.util.Format.dateRenderer('m/d/Y'),
                        dataIndex: 'lastChange'
                    },
                    {
                        xtype: 'actioncolumn',
                        width: 50,
                        items: [{
                            icon   : '../shared/icons/fam/delete.gif',  // Use a URL in the icon config
                            tooltip: 'Sell stock',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = store.getAt(rowIndex);
                                alert("Sell " + rec.get('company'));
                            }
                        }, {
                            getClass: function(v, meta, rec) {          // Or return a class from a function
                                if (rec.get('change') < 0) {
                                    this.items[1].tooltip = 'Do not buy!';
                                    return 'alert-col';
                                } else {
                                    this.items[1].tooltip = 'Buy stock';
                                    return 'buy-col';
                                }
                            },
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = store.getAt(rowIndex);
                                alert("Buy " + rec.get('company'));
                            }
                        }]
                    }
                ],
                stripeRows: true,
                //height: '50%',
                //height: 288,
                //width: 798,
                width: '100%',
                //anchor: '100%',
                title: 'Detalle',
                // config options for stateful behavior
                stateful: true,
                stateId: 'grid',
                collapsible:true,
                flex: 2
            });

            var grafico = new Ext.Panel({
                title: 'Grafico',
                bodyPadding: 5,
                //width: 798,
                width: '100%',
                //height:'50%',
                //anchor: '100%',
                //height:288,
                items: [{
                    store: storeGraf,
                    xtype: 'piechart',
                    dataField: 'total',
                    categoryField: 'season',
                    //extra styles get applied to the chart defaults
                    extraStyle:
                    {
                        legend:
                        {
                            display: 'bottom',
                            padding: 5,
                            font:
                            {
                                family: 'Tahoma',
                                size: 13
                            }
                        }
                    }
                }], // An array of form fields
                flex: 2,
                collapsible: true
            });

            this.reportPanel = new Ext.Panel({

                width: '100%',
                height: '100%',
                renderTo: Ext.get('principal'),
                region:'center',
                margins: '5 0 5 5',
                layout: 'vbox',
                items: [grafico, grid]
            });
            //this.reportPanel.doLayout();

            this.root = new Ext.tree.AsyncTreeNode({
                text : this.textRoot,
                draggable : false,
                allowDelete : true,
                allowEdit : true,
                collapsed : true,
                expanded : true,
                expandable : true,
                hidden : false,
                id : 'id'
            });

            this.treeMenu.setRootNode(this.root);

            // add an inline editor for the nodes
            this.ge = new Ext.tree.TreeEditor(this.treeMenu, {/* fieldconfig here */ }, {
                allowBlank:false,
                blankText:'A name is required',
                selectOnFocus:true
            });

            this.ge.on('complete', this.editDashboard, this);



            this.Border = new Ext.Container({
                layout:'border',
                id:'principal',
                items:[ this.menu, this.reportPanel]
            });

            this.panel.add(this.Border);
            this.panel.doLayout();
            this.addEvents('init');

            this.treeMenu.on('click', function(node, e){
                if(node.isLeaf()){
                    if (e != undefined) {
                        e.stopEvent();
                    }
                    console.log('node',node)
                    this.iniciarDashboard(node);
                }
            }, this);

            // create some portlet tools using built in Ext tool ids
            this.toolsportlet = [{
                id:'gear',
                handler: function(){
                    Ext.Msg.alert('Message', 'The Settings tool was clicked.');
                }
            },{
                id:'close',
                handler: function(e, target, panel){
                    panel.ownerCt.remove(panel, true);
                }
            }];

            this.iniciarEventos();
        },
        nodoActual: null,

        iniciarEventos: function(){

            Ext.getCmp('reportes').on('select', function(cmb, rec, ind){
                Ext.Msg.alert('Nombre',rec.data.field1);
            },this);
        },

        change: function (val) {
            if (val > 0) {
                return '<span style="color:green;">' + val + '</span>';
            } else if (val < 0) {
                return '<span style="color:red;">' + val + '</span>';
            }
            return val;
        },

        pctChange: function (val) {
            if (val > 0) {
                return '<span style="color:green;">' + val + '%</span>';
            } else if (val < 0) {
                return '<span style="color:red;">' + val + '%</span>';
            }
            return val;
        },

        guardar: function(){
            Ext.Msg.alert('Guardar');
        },

        cancelar:  function(){
            Ext.Msg.alert('Cancelar');
        },

        iniciarDashboard:function(nodo){

            //es diferente del nodo actual
            if(nodo != this.nodoActual){

                //limpiar widget
                this.limpiarDashboard();

                this.nodoActual = nodo;
                //extraer datos de los widget configurados

                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashdet/listarDashdetalle',
                    success : this.cargarDashboard,
                    failure : Phx.CP.conexionFailure,
                    params : {id_dashboard: nodo.attributes.id_dashboard},
                    arguments: {nodo: nodo},
                    scope : this
                });


            }

        },
        cargarDashboard:function(response,arg,b){

            console.log('regreso', response,arg,b)

            console.log('responseText',response.responseText)


            //crear objetos
            var regreso = Ext.util.JSON.decode(Ext.util.Format.trim(response.responseText)).datos;

            var me = this;
            regreso.forEach(function(entry) {
                me.insertarWidget(entry);
            });

            me.PanelDash.doLayout();


        },

        insertarWidget:function(entry){
            var me = this;
            var wid = Ext.id()+'-Widget', item ;
            console.log('entry',entry.columna);
            var indice = entry.columna?entry.columna:0;
            var tmp = new Ext.ux.Portlet({
                id: wid,
                layout: 'fit',
                title: entry.nombre,
                closable: true,
                maximizable : true,
                autoShow: true,
                autoScroll: false,
                autoHeight : false,
                autoDestroy: true,
                widget: entry,
                forceLayout:true,
                autoLoad: {
                    url: '../../../'+entry.ruta,
                    params:{ idContenedor: wid, _tipo: 'direc', mycls: entry.clase},
                    showLoadIndicator: "Cargando...",
                    arguments: {config: entry},
                    callback: me.callbackWidget,
                    text: 'Loading...',
                    scope: me,
                    scripts :true
                }
            });

            me.PanelDash.items.items[indice].add(tmp);
            //tmp.show()


        },

        callbackWidget: function(a,o,c,d){
            var xx = new Phx.vista.widget[d.arguments.config.clase](d.params);
            xx.init();
        },

        limpiarDashboard:function(){
            var me = this;

            for(var i=0; i<=2 ;i++){
                var aux = 0;
                me.PanelDash.items.items[i].removeAll(true)
            }
            this.nodoActual = undefined;
        },

        newDasboard: function(){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url : '../../sis_parametros/control/Dashboard/insertarDashboard',
                success : this.successNewDash,
                failure : Phx.CP.conexionFailure,
                params : {foo: 'bar'},
                scope : this
            });


        },

        deleteDasboard: function(){


            this.sm = this.treeMenu.getSelectionModel();
            var node = this.sm.getSelectedNode();

            if(confirm('¿Está seguro de eliminar el Dashboard?')){

                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashboard/eliminarDashboard',
                    success : this.successDelDash,
                    failure : Phx.CP.conexionFailure,
                    params : { id_dashboard: node.attributes.id_dashboard },
                    scope : this
                });

            }
        },

        editDashboard:function(obj, value, startValue,o){
            var node =obj.editNode;
            if(value != startValue){
                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashboard/insertarDashboard',
                    success : this.successNewDash,
                    failure : Phx.CP.conexionFailure,
                    params : {nombre: value, id_dashboard: node.attributes.id_dashboard},
                    scope : this
                });
            }

        },

        successDelDash:function(){
            Phx.CP.loadingHide();
            this.limpiarDashboard();
            this.root.reload();

        },
        successNewDash:function(){
            Phx.CP.loadingHide();
            this.root.reload();

        },

        loadWindowsWidget:function(){
            var me = this;

            if(this.nodoActual)	{
                Phx.CP.loadWindows('../../../sis_parametros/vista/widget/WidgetDash.php',
                    'Estado de Wf',
                    {   modal: true,
                        width: '70%',
                        height: '50%'
                    },
                    { foo: 'foo' },
                    me.idContenedor,'WidgetDash',
                    {  config:[{
                        event: 'selectwidget',
                        delegate: me.onSelectwidget,
                    }],
                        scope:me
                    });
            }
            else {
                alert('Primero seleccione el dashboard')
            }
        },

        onSelectwidget: function(win, rec){
            var me = this;
            console.log('selectwidget', rec)
            win.panel.close();

            me.insertarWidget(rec.data);
            me.PanelDash.doLayout();
        },


        getPosiciones: function(){

            var position = [], me = this;
            for(var i=0; i<=2 ;i++){
                var aux = 0;

                me.PanelDash.items.items[i].items.items.forEach(function(entry) {
                    position.push({  columna: i,
                        fila:aux,
                        id_widget: entry.widget.id_widget?entry.widget.id_widget:0,
                        id_dashdet: entry.widget.id_dashdet?entry.widget.id_dashdet:0,
                        id_dashboard: entry.widget.id_dashboard ?entry.widget.id_dashboard:0

                    });
                    aux++;
                })

            }

            return position

        },

        guardarPosiciones:function(){

            if(this.nodoActual)	{
                console.log(this.getPosiciones());
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url:'../../sis_parametros/control/Dashdet/guardarPosiciones',
                    params:{
                        id_dashboard_activo:  this.nodoActual.attributes.id_dashboard,
                        json_procesos:  Ext.util.JSON.encode(this.getPosiciones()),
                    },
                    success: this.successNewDash,
                    failure: Phx.CP.conexionFailure,
                    scope: this
                });
            }
            else{
                alert('Primero seleccion un dashboard');
            }
        }

    });
</script>