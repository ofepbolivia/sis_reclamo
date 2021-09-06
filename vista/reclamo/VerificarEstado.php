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
    Phx.vista.VerificarEstado = {
        require:'../../../sis_reclamo/vista/reclamo/RevisionReclamo.php',
        requireclase:'Phx.vista.RevisionReclamo',
        title:'VerificarEstado',
        nombreVista: 'VerificarEstado',
        ActList: '../../sis_reclamo/control/Reclamo/listarRegRipat',
        bnew:false,
        bdel:false,
        bedit:false,
        gruposBarraTareas:[
            {name:'registrado_ripat',title:'<H1 align="center"><i class="fa fa-file"></i> Registros Ripat</h1>',grupo:0,height:0}
        ],/*
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },

        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0],
        btestGroups: [0],
        bexcelGroups: [0],*/
        constructor: function(config) {
            //this.maestro = config.maestro;
            Phx.vista.VerificarEstado.superclass.constructor.call(this,config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'registrado_ripat';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.VerificarEstado.superclass.preparaMenu.call(this,n);

            //this.getBoton('sig_estado').enable();
            //this.getBoton('ant_estado').enable();

            if(data.estado =='registrado_ripat'){
                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').disable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('btnObs').disable();
                this.getBoton('btnChequeoDocumentosWf').disable();
            }

            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.VerificarEstado.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('btnObs').disable();
                this.getBoton('btnChequeoDocumentosWf').disable();

            }

            return tb
        },
        /*onButtonEdit: function() {
            Phx.vista.Respuesta.superclass.onButtonEdit.call(this);
        },*/

        tabsouth : [{}]

    };
</script>
