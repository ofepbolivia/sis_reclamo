<?php
/**
 *@package pXP
 *@file ConsultaReclamo.php
 *@author  (Franklin Espinoza)
 *@date 17-10-2016 14:45
 *@Interface para consultar todos los reclamos que se tiene .
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RespuestaDetalle = {
        require:'../../../sis_reclamo/vista/respuesta/Respuesta.php',
        requireclase:'Phx.vista.Respuesta',
        title:'Respuesta',
        nombreVista: 'RespuestaDetalle',
        bnew:true,
        bdel:true,
        bedit:true,

        constructor: function(config) {
            this.maestro = config.maestro;
            /*this.tbarItems = ['-',
                this.cmbGestion

            ];*/

            Phx.vista.RespuestaDetalle.superclass.constructor.call(this,config);

            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').disable();

            //this.getBoton('sig_estado').setVisible(false);
            //this.getCmp('gestion').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'elaboracion_respuesta';
            //this.load({params:{start:0, limit: 50}});
            this.finCons = true;
            //this.cmbGestion.on('select',this.capturarEventos, this);

            var dataPadre = Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();
            if(dataPadre){
                this.onEnablePanel(this, dataPadre);
            }
            else
            {
                this.bloquearMenus();
            }
        },/*

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
        }),*/
    onReloadPage: function (m) {
        this.maestro = m;
        this.store.baseParams = {id_reclamo: this.maestro.id_reclamo ,tipo_interfaz:this.nombreVista};
        this.load({params: {start: 0, limit: 50}});
    },
        
    loadValoresIniciales: function () {
        this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
        Phx.vista.RespuestaDetalle.superclass.loadValoresIniciales.call(this);

    },

    preparaMenu: function(n){

        var data = this.getSelectedData();
        var tb =this.tbar;

        Phx.vista.RespuestaDetalle.superclass.preparaMenu.call(this,n);
        this.getBoton('sig_estado').disable();
        this.getBoton('ant_estado').disable();
        if (data['estado'] == 'elaboracion_respuesta'){
            this.getBoton('ant_estado').disable();
            this.getBoton('sig_estado').enable();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
            this.getBoton('btnChequeoDocumentosWf').enable();
        }else if(data['estado'] == 'revision_legal' || data['estado'] == 'vobo_respuesta' || data['estado'] == 'respuesta_aprobada'){
            this.getBoton('sig_estado').enable();
            this.getBoton('ant_estado').enable();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
            this.getBoton('btnChequeoDocumentosWf').enable();
        }else if(data['estado'] == 'respuesta_enviada'){
            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').enable();
        }

        return tb;
    },

    liberaMenu: function(){
        var tb = Phx.vista.RespuestaDetalle.superclass.liberaMenu.call(this);
        if(tb){
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('sig_estado').disable();
            
            this.getBoton('btnObs').disable();
            this.getBoton('btnChequeoDocumentosWf').disable();
        }
        return tb;
    }

    };
</script>
