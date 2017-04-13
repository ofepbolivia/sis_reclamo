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
            {name:'anulado',title:'<H1 align="center"><i class="fa fa-folder"></i> Anulados</h1>',grupo:3,height:0}/*,
            {name:'edicion',title:'<H1 align="center"><i class="fa fa-folder"></i> Ediciones</h1>',grupo:4,height:0}*/
        ],
        tam_pag: 50,
        
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
            this.tbarItems = ['-',
                this.cmbGestion,'-'

            ];
            //this.Atributos.splice(5,1);
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
            });*/
            /*this.fields.push({name: 'motivo', type: 'string'});
            this.fields.push({name: 'motivo_anulado', type: 'string'});
            this.fields.push({name: 'id_motivo_anulado', type: 'numeric'});*/
            this.Atributos.splice(5,0,
                {
                    config: {
                        name: 'dias_respuesta',
                        fieldLabel: 'Dias Para Responder',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 150,
                        maxLength: 100,
                        renderer: function(value, p, record) {
                            var dias = record.data.dias_respuesta;
                            var ids = new Array(4, 6, 37, 38, 48, 50);
                            var id_tipo = parseInt(record.data.id_tipo_incidente);

                            if(ids.indexOf(id_tipo) >= 0) {
                                if(record.data.revisado == 'res_ripat' || record.data.revisado == 'con_respuesta'  || record.data.revisado == 'concluido')
                                    return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/respondido.png' align='center' width='24' height='24'/></div>");
                                else {
                                    switch (dias) {
                                        case '10':
                                            console.log('10');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/ten.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '9':
                                            console.log('9');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/nine.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '8':
                                            console.log('8');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/eight.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '7':
                                            console.log('7');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/seven.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '6':
                                            console.log('6');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/six.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '5':
                                            console.log('5');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/five.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '4':
                                            console.log('4');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/four.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '3':
                                            console.log('3');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/three.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '2':
                                            console.log('2');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/two.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '1':
                                            console.log('1');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/one.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '0':
                                            console.log('0');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/cero.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '-1':
                                            console.log('-1');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/vencido.png' align='center' width='24' height='24'/></div>");
                                            break;
                                    }
                                }

                                /*if (dias >= 1 && dias <= 10) {
                                 return String.format('<div ext:qtip="Optimo"><b><font color="green">Faltan {0} Días</font></b><br></div>', value);
                                 }
                                 else if(dias == 0){
                                 return String.format('<div ext:qtip="Critico"><b><font color="orange">Faltan {0} Días</font></b><br></div>', value);
                                 }else if(dias = -1){
                                 return String.format('<div ext:qtip="Con Respuesta"><b><font color="red">Con Respuesta o Vencido</font></b><br></div>', value);
                                 }*/
                            }else if(record.data.id_tipo_incidente==36){
                                /*if (dias >=1  && dias <= 7) {
                                 return String.format('<div ext:qtip="Optimo"><b><font color="green">Faltan {0} Días</font></b><br></div>', value);
                                 }
                                 else if(dias == 0){
                                 return String.format('<div ext:qtip="Critico"><b><font color="orange">Faltan {0} Días</font></b><br></div>', value);
                                 }else if(dias = -1){
                                 return String.format('<div ext:qtip="Con Respuesta"><b><font color="red">Con Respuesta o Vencido</font></b><br></div>', value);
                                 }*/
                                if(record.data.revisado == 'res_ripat' || record.data.revisado == 'con_respuesta' || record.data.revisado == 'concluido')
                                    return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/respondido.png' align='center' width='24' height='24'/></div>");
                                else {
                                    switch (dias) {
                                        case '7':
                                            console.log('7');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/seven.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '6':
                                            console.log('6');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/six.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '5':
                                            console.log('5');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/five.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '4':
                                            console.log('4');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/four.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '3':
                                            console.log('3');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/three.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '2':
                                            console.log('2');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/two.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '1':
                                            console.log('1');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/one.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '0':
                                            console.log('0');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/cero.png' align='center' width='24' height='24'/></div>");
                                            break;
                                        case '-1':
                                            console.log('-1');
                                            return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/vencido.png' align='center' width='24' height='24'/></div>");
                                            break;
                                    }
                                }
                            }
                        }
                    },
                    type: 'Checkbox',
                    id_grupo:1,
                    grid: true,
                    form: false
                });
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
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    console.log('rEG2: '+reg);
                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    this.store.baseParams.id_gestion=this.cmbGestion.getValue();
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.cmbGestion.on('select',this.capturarEventos, this);

        },
        
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion_rev',
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
        }),

        capturarEventos: function () {
            if(this.validarFiltros()){
                this.capturaFiltros();
            }
        },

        capturaFiltros:function(combo, record, index){
            this.desbloquearOrdenamientoGrid();
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
            //this.load();
        },

        validarFiltros:function(){
            if(this.cmbGestion.isValid()){
                return true;
            }
            else{
                return false;
            }

        },

        onButtonAct:function(){
            if(!this.validarFiltros()){
                Ext.Msg.alert('ATENCION!!!','Especifique los filtros antes')
            }
            else{
                this.store.baseParams.id_gestion=this.cmbGestion.getValue();
                Phx.vista.RevisionReclamo.superclass.onButtonAct.call(this);
            }
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
        }/*,
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
        }*/
    };
</script>
