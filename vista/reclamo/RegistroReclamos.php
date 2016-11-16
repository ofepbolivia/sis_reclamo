<?php
/**
 *@package pXP
 *@file RegistroReclamos.php
 *@author  (Franklin Espinoza)
 *@date 13-10-2016 14:45
 *@Interface para el inicio de solicitudes de compra
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RegistroReclamos = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'RegistroReclamos',
        //layoutType: 'wizard',
        constructor: function(config) {
            Phx.vista.RegistroReclamos.superclass.constructor.call(this,config);
            //this.store.baseParams.func_estado = 'oficina';
        },
        gruposBarraTareas:[
            {name:'borrador',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Borradores</h1>',grupo:0,height:0, width: 100},
            {name:'pendiente_revision',title:'<H1 align="center"><i class="fa fa-list-ul"></i>Adjuntar Informe</h1>',grupo:2,height:0, width: 100},
            {name:'pendiente_informacion',title:'<H1 align="center"><i class="fa fa-files-o"></i> Pendientes Inf.</h1>',grupo:1,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2],
        bexcelGroups: [0,1,2],
        tabsouth :[
            {
                url:'../../../sis_reclamo/vista/informe/Informe.php',
                title:'Informe',
                height:'50%',
                cls:'Informe'
            }
        ],

        enableTabRespuesta:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },

        disableTabRespuesta:function(){
            if(this.TabPanelSouth.get(0)){

                this.TabPanelSouth.get(0).disable();
                //this.TabPanelSouth.setActiveTab(0)
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;

            Phx.vista.RegistroReclamos.superclass.preparaMenu.call(this,n);
            //habilitar reporte de colicitud de comrpa y preorden de compra

            if(data['estado']==  'borrador'){
                this.getBoton('sig_estado').enable();
                this.disableTabRespuesta();

            }else if(data['estado']==  'pendiente_revision'){
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.enableTabRespuesta();
            }
            else {
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.disableTabRespuesta();

            }

            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.RegistroReclamos.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();
            }
            this.disableTabRespuesta();
            return tb;
        },
        onButtonEdit: function() {
            Phx.vista.RegistroReclamos.superclass.onButtonEdit.call(this);
        }

    };
</script>
