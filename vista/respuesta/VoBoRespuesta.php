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
        bnew:false,
        bdel:false,
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

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.VoBoRespuesta.superclass.preparaMenu.call(this,n);

            if(data.estado =='vobo_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('btnObs').enable();
                this.getBoton('btnChequeoDocumentosWf').enable();
                console.log('DOS');
            }

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
