<?php 
/**
 *@package pXP
 *@file PendienteRespuesta.php
 *@author  (Franklin Espinoza)
 *@date 17-10-2016 14:45
 *@Interface para el proceso de Respuesta a un Reclamo.
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.PendienteRespuesta = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'PendienteRespuesta',
        //layoutType: 'wizard',
        bnew:false,
        bdel:false,
        tam_pag:50,
        gruposBarraTareas:[

            {name:'pendiente_asignacion',title:'<H1 align="center"><i class="fa fa-list-ol"></i> Pendientes Asig.</h1>',grupo:0,height:0},
            {name:'pendiente_respuesta',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Pendientes Resp.</h1>',grupo:1,height:0},
            {name:'archivo_con_respuesta',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Archivo con Resp.</h1>',grupo:2,height:0},
            {name:'respuesta_registrado_ripat',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Registrado Ripatt</h1>',grupo:4,height:0},
            {name:'archivado_concluido',title:'<H1 align="center"><i class="fa fa-folder"></i> Archivado/Concl.</h1>',grupo:3,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0,1],
        bdelGroups:  [0,1],
        bactGroups:  [0,1,2,3,4],
        btestGroups: [0,1],
        bexcelGroups: [0,1,2,3,4],

        constructor: function(config) {
            this.tbarItems = ['-',
                this.cmbGestion

            ];
            this.maestro=config.maestro;
           

            this.Atributos.splice(5,1);
            //this.Atributos.splice(3,0,);

            this.Atributos.unshift({
                config:{
                    name: 'revisado',
                    fieldLabel: 'Registrado Ripatt',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer:function (value, p, record){
                        if(record.data['revisado'] == 'si')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Revisado / Permite ver si el reclamo fue revisado'  src = '../../../lib/imagenes/ball_green.png' align='center' width='24' height='24'/></div>");
                        else
                            return  String.format('{0}',"<div style='text-align:center'><img title='No revisado / Permite ver si el reclamo fue revisado'  src = '../../../lib/imagenes/ball_white.png' align='center' width='24' height='24'/></div>");
                    }
                },
                type:'Checkbox',
                filters:{pfiltro:'rec.revisado',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            });

            Phx.vista.PendienteRespuesta.superclass.constructor.call(this,config);
            this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'pendiente_asignacion';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    console.log(reg);
                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    this.store.baseParams.id_gestion=this.cmbGestion.getValue();
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            this.addButton('reportes',{
                grupo: [0,1,2,3,4],
                argument: {estado: 'reportes'},
                text: 'Reportes',
                iconCls: 'blist',
                disabled: true,
                handler: this.reportes,
                tooltip: '<b>Generar Reporte</b>',
                scope:this
            });

            this.cmbGestion.on('select',this.capturarEventos, this);


            /*this.addButton('btnRev', {
                grupo: [0],
                text : 'Revisado',
                iconCls : 'bball_green',
                disabled : true,
                handler : this.cambiarRev,
                tooltip : '<b>Revisado</b><br/>Sirve como un indicador de que la documentacion fue revisada por el asistente'
            });*/
        }/*,
        grupo: new Ext.grid.GridPanel({

            height: 350,

            width: 700,

            store: new Ext.data.GroupingStore({
                data: new Ext.data.JsonStore({
                    url: '../../sis_reclamo/control/MotivoAnulado/listarMotivoAnulado',
                    totalProperty: 'total',
                    root: 'datos',
                    idProperty: 'id_motivo_anulado',
                    fields: ['id_motivo_anulado', 'motivo', 'orden']
                }),
                groupField:'orden'
            }),

            columns: [
                {header: "id_motivo_anulado", width: 60, sortable: true, dataIndex: 'id_motivo_anulado'},
                {header: "motivo", width: 60, sortable: true, dataIndex: 'motivo'},
                {header: "orden", width: 60, sortable: true, dataIndex: 'orden'}

            ],

            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
            }),

            loadMask: true,

            stripeRows: true


        })*/
        ,
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'Año',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            hidden:false,
            width:80
        }),

        capturarEventos: function () {
            if(this.validarFiltros()){
                this.capturaFiltros();
            }
        },

        capturaFiltros:function(combo, record, index){
            this.desbloquearOrdenamientoGrid();
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
            //this.load();
        },

        validarFiltros:function(){
            if(this.cmbGestion.isValid()){
                return true;
            }
            else{
                return false;
            }

        },

        onButtonAct:function(){
            if(!this.validarFiltros()){
                Ext.Msg.alert('ATENCION!!!','Especifique los filtros antes')
            }
            else{
                this.store.baseParams.id_gestion=this.cmbGestion.getValue();
                Phx.vista.PendienteRespuesta.superclass.onButtonAct.call(this);
            }
        },

        reportes: function(){

            /*Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/generarReporte',
                params:{
                    codigo_proceso:  'REC',
                    proceso_macro:   'REC'
                },
                success:this.guardarReporte,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });*/
            this.vista.show();
            //Ext.Msg.alert('Titulo','Good');
        },

        guardarReporte: function(resp){
            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        },


        /*fin_registro:function(paneldoc)
        {
            var d= this.sm.getSelected().data;

            Phx.CP.loadingShow();
            this.cmbRPC.reset();

            this.cmbRPC.store.baseParams.id_uo=d.id_uo;
            this.cmbRPC.store.baseParams.fecha=d.fecha_soli;
            this.cmbRPC.store.baseParams.id_proceso_macro=d.id_proceso_macro;
            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url:'../../sis_adquisiciones/control/Solicitud/finalizarSolicitud',
                params: { id_solicitud: d.id_solicitud, operacion:'verificar', id_estado_wf: d.id_estado_wf },
                argument: { paneldoc: paneldoc},
                success: this.successSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        successSinc:function(resp){

            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(!reg.ROOT.error){

                if(resp.argument.paneldoc.panel){
                    resp.argument.paneldoc.panel.destroy();
                }
                this.reload();
            }else{

                alert('ocurrio un error durante el proceso')
            }


        },*/

        enableTabRespuesta:function(){
            if(this.TabPanelSouth.get(1)){
                this.TabPanelSouth.get(1).enable();
                this.TabPanelSouth.setActiveTab(1)
            }
        },

        disableTabRespuesta:function(){
            if(this.TabPanelSouth.get(1)){
                this.TabPanelSouth.get(1).disable();
                this.TabPanelSouth.setActiveTab(0)
            }
        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.PendienteRespuesta.superclass.preparaMenu.call(this,n);

            if(data.estado =='pendiente_asignacion'){
                this.disableTabRespuesta();
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('reportes').enable();
            }else if(data.estado =='pendiente_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('reportes').enable();
                this.enableTabRespuesta();
            }
            else if(data.estado =='archivo_con_respuesta' ){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.getBoton('reportes').enable();
                this.enableTabRespuesta();
            }else if(data.estado == 'respuesta_registrado_ripat' ){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.getBoton('reportes').enable();
                this.enableTabRespuesta();
            }else if(data.estado == 'archivado_concluido'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.getBoton('reportes').enable();
                this.enableTabRespuesta();
            }
            /*if(data['revisado_asistente']== 'si'){
                this.getBoton('btnRev').setIconClass('bball_white')
            }
            else{
                this.getBoton('btnRev').setIconClass('bball_green')
            }*/
            //this.getBoton('btnRev').setVisible(true);
            //this.getBoton('btnRev').enable();

            return tb
        },
        
        liberaMenu:function(){
            var tb = Phx.vista.PendienteRespuesta.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('reportes').disable();
            }
            //this.getBoton('btnRev').disable();
            this.disableTabRespuesta();
            return tb
        },

        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
        },

        vista: new Ext.Window({
            title:'Graficos',
            width: 600,
            height:450,
            tbar: [  // <--- ToolBar
                {text:'Back'}, // <--- Buttons
                {text:'Forward'},
                {text:'Reload'},
                {text:'Stop'},
                {text:'Home'}
            ],
            /*items: [this.grupo],*/
            maximizable: true,
            maskDisabled: true,
            bodyStyle: 'background-color:#fff',
            html: '<iframe id="'+this.idContenedor+'" src="http://www.google.com" style="width:100%;height:100%;border:none"></iframe>'


        })

    };
</script>
