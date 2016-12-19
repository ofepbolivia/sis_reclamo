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
        beditGroups: [0,1],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3],

        constructor: function(config) {
            this.Atributos.splice(5,1);
            /*this.Atributos.splice(this.Atributos.length,0,{
                config: {
                    name: 'id_motivo_anulado',
                    fieldLabel: 'Motivo Anulado',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/MotivoAnulado/listarMotivoAnulado',
                        id: 'id_motivo_anulado',
                        root: 'datos',
                        sortInfo: {
                            field: 'motivo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_motivo_anulado','motivo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'ma.motivo'}
                    }),
                    valueField: 'id_motivo_anulado',
                    displayField: 'motivo',
                    gdisplayField: 'motivo_anulado',
                    hiddenName: 'id_motivo_anulado',
                    forceSelection: true,
                    typeAhead: false,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['motivo_anulado']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 5,
                filters: {pfiltro: 'ma.motivo', type: 'string'},
                grid: true,
                form: true
            });
            this.fields.push({name: 'motivo', type: 'string'});
            this.fields.push({name: 'motivo_anulado', type: 'string'});
            this.fields.push({name: 'id_motivo_anulado', type: 'numeric'});*/
            this.Grupos.push({
                bodyStyle: 'padding-right:10px;',
                items: [
                    {

                        xtype: 'fieldset',
                        title: 'MOTIVO DE ANULACIÓN',
                        autoHeight: true,
                        items: [],
                        id_grupo: 5
                    }
                ]
            });
            this.fwidth= '65%',
            this.fheight = '99%',
            Phx.vista.RevisionReclamo.superclass.constructor.call(this,config);
            this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'pendiente_revision';
            this.load({params:{start:0, limit:60}});
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
                this.TabPanelSouth.setActiveTab(1);
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
                this.disableTabRespuesta();
            }
            else{
                this.getBoton('sig_estado').setVisible(false);
                this.getBoton('ant_estado').enable();
                this.disableTabRespuesta();
            }
            //this.enableTabRespuesta();

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
            var rec = this.sm.getSelected();
            //this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', rec.data.id_tipo_incidente);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_workflow/control/TipoColumna/listarColumnasFormulario',
                params:{

                    id_estado_wf: rec.data['id_estado_wf']
                },
                success:this.editCampos,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            //alert(rec.data['id_estado_wf']);
            Phx.vista.RevisionReclamo.superclass.onButtonEdit.call(this);
        },

        editCampos: function(resp){
            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            //console.log('campos Edit: '+objRes);
            //Phx.vista.RevisionReclamo.superclass.onButtonEdit.call(this);
            this.armarFormularioFromArray(objRes.datos);
        }
    };
</script>
