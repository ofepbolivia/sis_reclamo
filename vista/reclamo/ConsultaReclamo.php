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
    Phx.vista.ConsultaReclamo = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'ConsultaReclamo',
        bnew:false,
        bdel:false,
        //bedit:false,
        bedit:false,
        ActList: '../../sis_reclamo/control/Reclamo/listarConsulta',
        /*tipoStore: 'GroupingStore',
        groupField: 'estado',
        remoteGroup: true,
        viewGrid: new Ext.grid.GroupingView({
            forceFit: true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        }),
        stripeRowsGrid: true,*/
        constructor: function(config) {
            this.tbarItems = ['-',
                this.cmbGestion,'-'

            ];

            /*var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(config));

            console.log('configuracion: '+objRes.idContenedorPadre);
            console.log('padre:'+Phx.CP.getPagina(this.idContenedorPadre));*/

            Phx.vista.ConsultaReclamo.superclass.constructor.call(this,config);

            //this.getBoton('btnObs').setVisible(false);
            //this.getBoton('btnChequeoDocumentosWf').setVisible(false);
            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));

                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    console.log(reg.ROOT.datos.id_gestion);
                    this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                    this.load({params:{start:0, limit:this.tam_pag}});

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.store.baseParams.pes_estado = null;
            //this.load({params:{start:0, limit:this.tam_pag}});
            //this.finCons = true;

            this.addButton('rep_archivo', {
                text: 'Rep. Estadistico',
                iconCls: 'bprint_good',
                disabled: false,
                handler: this.repArchivo,
                tooltip: '<b>Imprimir Reporte</b><br>Genera reporte de los documentos de un funcionario.'
            });


            this.cmbGestion.on('select',this.capturarEventos, this);

            this.plazo = new Ext.form.Label({
                name: 'fecha_limite_sel',
                grupo: [0,1,2,3,4],
                fieldLabel: 'Fecha',
                allowBlank: false,
                anchor: '60%',
                gwidth: 100,
                format: 'd/m/Y',
                hidden : false,
                readOnly:true,
                style: 'font-size: 25pt; font-weight: bold; background-image: none; color: #ff4040;'
            });

            this.tbar.addField(this.plazo);
        },
        repArchivo : function () {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/reporteEstadistico',
                params:{'id_usuario':0},
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion_sel',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'A??o',
            //tabIndex : 0,
            /*listeners: {
                afterrender: function(combo) {
                    Ext.Ajax.request({
                        url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                        params:{id_usuario:0},
                        success:function(resp){
                            var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));

                            combo.setValue(reg.ROOT.datos.id_gestion);
                            combo.setRawValue(reg.ROOT.datos.gestion);

                            this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                            console.log(resp);

                        },
                        failure: this.conexionFailure,
                        timeout:this.timeout,
                        scope:this
                    });

                }
            },*/
            //selectOnFocus:true,
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

        tabsouth :null,

        capturarEventos: function () {
            //if(this.validarFiltros()){
                //this.capturaFiltros();
            //}
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
        },

        /*capturaFiltros:function(combo, record, index){
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
                Phx.vista.ConsultaReclamo.superclass.onButtonAct.call(this);
            }
        },*/

        /*onButtonEdit:function () {
            //lert('edit');
            Phx.vista.ConsultaReclamo.superclass.onButtonEdit.call(this);
        },*/
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ConsultaReclamo.superclass.preparaMenu.call(this,n);
            return tb
        },

        liberaMenu:function(){
            var tb = Phx.vista.ConsultaReclamo.superclass.liberaMenu.call(this);
            return tb;
        },
        enableTabRespuesta:function(){

        },

        disableTabRespuesta:function(){

        }

    };
</script>
                                                                                                                                                                                                                                                                                                                                        