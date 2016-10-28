<?php
/**
 *@package pXP
 *@file RevisionReclamo.php
 *@author  (rarteaga)
 *@date 17-10-2016 10:22:05
 *@Interface para el inicio de Revision de un Reclamo
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RevisionReclamo = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'RevisionReclamo',
        //layoutType: 'wizard',
        bnew:false,
        bdel:false,

        gruposBarraTareas:[
            {name:'pendiente_revision',title:'<H1 align="center"><i class="fa fa-file"></i> Pendientes Revision.</h1>',grupo:0,height:0},
            {name:'registrado_ripat',title:'<H1 align="center"><i class="fa fa-file"></i> Registros Ripat</h1>',grupo:1,height:0},
            {name:'derivado',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Derivados</h1>',grupo:2,height:0},
            {name:'anulado',title:'<H1 align="center"><i class="fa fa-folder"></i> Anulados</h1>',grupo:3,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3],

        constructor: function(config) {

            Phx.vista.RevisionReclamo.superclass.constructor.call(this,config);
            this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'pendiente_revision';
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
            Phx.vista.RevisionReclamo.superclass.preparaMenu.call(this,n);

            if(data.estado =='pendiente_revision' || data.estado =='registrado_ripat' || data.estado =='derivado'){
                
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
            }
            else{
                this.getBoton('sig_estado').setVisible(false);
                this.getBoton('ant_estado').enable();
            }
            this.enableTabRespuesta();

            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.RevisionReclamo.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();

            }
            this.disableTabRespuesta();
            return tb
        },
        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this);
        }
        
        
    };
</script>
