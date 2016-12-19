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
        gruposBarraTareas:[

            {name:'pendiente_asignacion',title:'<H1 align="center"><i class="fa fa-list-ol"></i> Pendientes Asig.</h1>',grupo:0,height:0},
            {name:'pendiente_respuesta',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Pendientes Resp.</h1>',grupo:1,height:0},
            {name:'archivo_con_respuesta',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Archivo con Resp.</h1>',grupo:2,height:0},
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
        bactGroups:  [0,1,2,3],
        btestGroups: [0,1],
        bexcelGroups: [0,1,2,3],

        constructor: function(config) {
            this.maestro=config.maestro;
           

            this.Atributos.splice(5,1);
            //this.Atributos.splice(3,0,);

            this.Atributos.unshift({
                config:{
                    name: 'revisado',
                    fieldLabel: 'Con Respuesta',
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
            this.load({params:{start:0, limit:50}});
            this.finCons = true;

            /*Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/siguienteEstadoReclamo',
                params: { f_actual : new Date(), nombreVista: 'PendienteRespuesta'},
                success: this.successDias,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });*/
            this.addButton('reportes',{
                grupo: [0,1,2,3],
                argument: {estado: 'reportes'},
                text: 'Reportes',
                iconCls: 'blist',
                disabled: true,
                hidden: true,
                handler: this.reportes,
                tooltip: '<b>Generar Reporte</b>'
            });

            /*this.addButton('btnRev', {
                grupo: [0],
                text : 'Revisado',
                iconCls : 'bball_green',
                disabled : true,
                handler : this.cambiarRev,
                tooltip : '<b>Revisado</b><br/>Sirve como un indicador de que la documentacion fue revisada por el asistente'
            });*/
        },
        /*cambiarRev:function(){
            Phx.CP.loadingShow();
            var d = this.sm.getSelected().data;
            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/marcarRevisado',
                params:{id_reclamo:d.id_reclamo},
                success:this.successRev,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

        },
        successRev:function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(!reg.ROOT.error){
                this.reload();
            }
        },*/
        
        reportes: function(){
            Phx.CP.loadingShow();
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
            });
        },

        guardarReporte: function(resp){
            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        },

        successDias:function(resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log('Transaccion Exitosa...'+reg.ROOT.datos);
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
            }else if(data.estado =='pendiente_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.enableTabRespuesta();
            }
            else if(data.estado =='archivo_con_respuesta' ){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.enableTabRespuesta();
            }else if(data.estado == 'archivado_concluido'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
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

            }
            //this.getBoton('btnRev').disable();
            this.disableTabRespuesta();
            return tb
        },
        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
        }

    };
</script>
