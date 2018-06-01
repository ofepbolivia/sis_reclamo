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

            Phx.vista.RespuestaDetalle.superclass.constructor.call(this,config);

            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').disable();
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'elaboracion_respuesta';
            //this.load({params:{start:0, limit: 50}});
            this.finCons = true;
            var dataPadre = Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();
            if(dataPadre){
                this.onEnablePanel(this, dataPadre);
            }
            else
            {
                this.bloquearMenus();
            }


        },

        onReloadPage: function (m) {
            this.maestro = m;
            this.store.baseParams = {id_reclamo: this.maestro.id_reclamo ,tipo_interfaz:this.nombreVista};
            this.load({params: {start: 0, limit: 50}});
        },

        loadValoresIniciales: function () {
            this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
            Phx.vista.RespuestaDetalle.superclass.loadValoresIniciales.call(this);

        },
        successSave:function(resp){
            Phx.vista.RespuestaDetalle.superclass.successSave.call(this,resp);
            Phx.CP.getPagina(this.idContenedorPadre).reload();
        },

        onSubmit: function (o,x, force) {
            if(this.momento == 'edit'){
                Phx.vista.RespuestaDetalle.superclass.onSubmit.call(this, o);
            }else if(this.momento == 'new') {
                Ext.Ajax.request({
                    url: '../../sis_reclamo/control/Respuesta/validarCite',
                    params: {
                        nro_cite: this.Cmp.nro_cite.getValue()
                    },
                    argument: {},
                    success: function (resp) {
                        var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
                        if (reg.ROOT.datos.v_valid == 'true')
                            Ext.Msg.alert('Alerta','El Nro. de Cite ' + this.Cmp.nro_cite.getValue() +' ya fue asignado a la Respuesta Nro. ' + reg.ROOT.datos.v_nro_respuesta);
                        else
                            Phx.vista.RespuestaDetalle.superclass.onSubmit.call(this, o);

                    },
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }
        },
        preparaMenu: function(n){

            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.RespuestaDetalle.superclass.preparaMenu.call(this,n);
            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').disable();
this.getBoton('btnChequeoDocumentosWf').enable();
            if (data['estado'] == 'elaboracion_respuesta'){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').enable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('btnObs').enable();
                this.getBoton('btnChequeoDocumentosWf').enable();
                this.getBoton('del').enable();
            }else if(data['estado'] == 'revision_legal' || data['estado'] == 'vobo_respuesta' || data['estado'] == 'respuesta_aprobada'){
                if(this.nombreVista == 'RespuestaDetalle' && (data['estado'] == 'revision_legal' || data['estado'] == 'vobo_respuesta') && data.admin !=1){
                    this.getBoton('sig_estado').disable();
                    this.getBoton('ant_estado').disable();
                    this.getBoton('diagrama_gantt').enable();
                    this.getBoton('btnObs').enable();
                    this.getBoton('btnChequeoDocumentosWf').enable();
                    this.getBoton('del').disable();
                    this.getBoton('edit').enable();
                    this.getBoton('new').disable();
                }else{
                    this.getBoton('sig_estado').enable();
                    this.getBoton('ant_estado').enable();
                    this.getBoton('diagrama_gantt').enable();
                    this.getBoton('btnObs').enable();
                    this.getBoton('btnChequeoDocumentosWf').enable();
                    this.getBoton('del').disable();
                    this.getBoton('edit').enable();
                }
            }else if(data['estado'] == 'respuesta_enviada' ){
                this.getBoton('sig_estado').disable();
                if(this.maestro.estado == 'archivo_con_respuesta') {
                    this.getBoton('ant_estado').disable();
                    this.getBoton('edit').disable();
                    this.getBoton('new').disable();
                }else{
                    this.getBoton('ant_estado').enable();
                }
                this.getBoton('del').disable();
            }

            return tb;
        },

        liberaMenu: function(){
            var tb = Phx.vista.RespuestaDetalle.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                if(this.maestro.estado == 'archivo_con_respuesta') {
                    this.getBoton('new').disable();
                }
                this.getBoton('btnObs').disable();
                this.getBoton('btnChequeoDocumentosWf').disable();
            }
            return tb;
        }

    };
</script>
