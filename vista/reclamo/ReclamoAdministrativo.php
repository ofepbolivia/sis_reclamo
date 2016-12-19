<?php
/**
 *@package pXP
 *@file ReclamoAdministrativo.php
 *@author  (rarteaga)
 *@date 17-10-2016 10:22:05
 *@Interface para el manejo de Reclamo Administrativo
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ReclamoAdministrativo = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'ReclamoAdministrativo',
        //layoutType: 'wizard',
        bnew:false,
        bdel:false,

        gruposBarraTareas:[
            {name:'en_avenimiento',title:'<H1 align="center"><i class="fa fa-file"></i> En Avenimiento.</h1>',grupo:0,height:0},
            {name:'formulacion_cargos',title:'<H1 align="center"><i class="fa fa-file"></i> Form. de Cargos</h1>',grupo:1,height:0},
            {name:'resolucion_administrativa',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Resolucion Adm.</h1>',grupo:2,height:0},
            {name:'recurso_revocatorio',title:'<H1 align="center"><i class="fa fa-folder-open"></i> RR. Revocatorio</h1>',grupo:3,height:0},
            {name:'recurso_jerarquico',title:'<H1 align="center"><i class="fa fa-folder-open"></i> RR. Jerarquico</h1>',grupo:4,height:0},
            {name:'contencioso_administrativo',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Contencioso Adm.</h1>',grupo:5,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3,4,5],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3,4,5],

        constructor: function(config) {

            Phx.vista.ReclamoAdministrativo.superclass.constructor.call(this,config);
            //this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'en_avenimiento';
            this.load({params:{start:0, limit:50}});
            this.finCons = true;

        },

        onCloseDocuments: function(paneldoc, data, directo){
            var newrec = this.store.getById(data.id_solicitud);
            if(newrec){
                this.sm.selectRecords([newrec]);
                if(directo === true){
                    this.fin_requerimiento(paneldoc);
                }
                else{
                    if(confirm("¿Desea mandar la solictud para aprobación?")){
                        this.fin_requerimiento(paneldoc);
                    }
                }

            }


        },

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
            Phx.vista.ReclamoAdministrativo.superclass.preparaMenu.call(this,n);

            if(data.estado == 'en_avenimiento' || data.estado == 'formulacion_cargos'
                || data.estado == 'resolucion_administrativa' || data.estado == 'recurso_revocatorio'
                || data.estado == 'recurso_jerarquico' || data.estado == 'contencioso_administrativo'){

                this.getBoton('ant_estado').enable();
                this.getBoton('sig_estado').enable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('btnObs').enable();
                this.getBoton('btnChequeoDocumentosWf').enable();
            }
            this.enableTabRespuesta();

            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.ReclamoAdministrativo.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('btnObs').disable();
                this.getBoton('btnChequeoDocumentosWf').disable();
            }
            this.disableTabRespuesta();
            return tb
        },
        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
        }


    };
</script>
