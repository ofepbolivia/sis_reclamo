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
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'VoBoRespuesta',
        //layoutType: 'wizard',
        bnew:false,
        bdel:false,
        gruposBarraTareas:[

            {name:'revision_legal',title:'<H1 align="center"><i class="fa fa-legal"></i> Revision Legal</h1>',grupo:0,height:0},
            {name:'vobo_respuesta',title:'<H1 align="center"><i class="fa fa-thumbs-o-up"></i>VoBo. Respuesta</h1>',grupo:1,height:0}

        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1],
        btestGroups: [0],
        bexcelGroups: [0,1],

        constructor: function(config) {

            Phx.vista.VoBoRespuesta.superclass.constructor.call(this,config);
            this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'revision_legal';
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
            

            if(data.estado =='revision_legal' || data.estado =='vobo_respuesta' ){

                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
            }
            this.enableTabRespuesta(1);
            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.VoBoRespuesta.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();

            }
            this.disableTabRespuesta(1);
            return tb
        },
        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
        }

    };
</script>
