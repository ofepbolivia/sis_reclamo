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
    Phx.vista.VoBoRespuesta = {
        require:'../../../sis_reclamo/vista/respuesta/Respuesta.php',
        requireclase:'Phx.vista.Respuesta',
        title:'Respuesta',
        nombreVista: 'VoBoRespuesta',
        //layoutType: 'wizard',
        bnew:false,
        bdel:false,
        /*gruposBarraTareas:[

            {name:'revision_legal',title:'<H1 align="center"><i class="fa fa-legal"></i> Revision Legal</h1>',grupo:0,height:0},
            {name:'vobo_respuesta',title:'<H1 align="center"><i class="fa fa-thumbs-o-up"></i>VoBo. Respuesta</h1>',grupo:1,height:0}

        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0,1],
        bdelGroups:  [0,1],
        bactGroups:  [0,1],
        btestGroups: [],
        bexcelGroups: [0,1],*/
        tam_pag: 50,
        constructor: function(config) {
            this.maestro = config.maestro;
            Phx.vista.VoBoRespuesta.superclass.constructor.call(this,config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'vobo_respuesta';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;


        },
        fin_registro:function(paneldoc)
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


        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.VoBoRespuesta.superclass.preparaMenu.call(this,n);

            //this.getBoton('sig_estado').enable();
            //this.getBoton('ant_estado').enable();

            if(data.estado =='vobo_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('btnObs').enable();
                this.getBoton('btnChequeoDocumentosWf').enable();
                console.log('DOS');
            }

            /*Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario: 0},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    console.log('datos: '+JSON.stringify(reg.ROOT.datos));
                    //&& reg.ROOT.datos.nombre_cargo == 'Responsable Atención al Cliente'
                    if(data.estado =='vobo_respuesta'){
                        this.getBoton('sig_estado').enable();
                        this.getBoton('ant_estado').enable();
                        this.getBoton('diagrama_gantt').enable();
                        this.getBoton('btnObs').enable();
                        this.getBoton('btnChequeoDocumentosWf').enable();
                        console.log('DOS');
                    }else{
                        this.getBoton('sig_estado').disable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('diagrama_gantt').disable();
                        this.getBoton('btnObs').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        console.log('TRES');
                    }
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });*/
            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.VoBoRespuesta.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('btnObs').disable();
                this.getBoton('btnChequeoDocumentosWf').disable();

            }
            
            return tb
        },
        onButtonEdit: function() {
            Phx.vista.Respuesta.superclass.onButtonEdit.call(this);
        }

    };
</script>
