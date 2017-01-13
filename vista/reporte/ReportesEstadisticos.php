<?php
/**
 *@package pXP
 *@file gen-Depto.php
 *@author  )
 *@date 24-11-2011 15:52:20
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    Ext.namespace('Phx','Phx.vista.widget');
    Ext.define('Phx.vista.ReportesEstadisticos',{
        extend: 'Ext.util.Observable',
        hombres:876,
        mujeres:654,
        constructor: function(config) {
            var me = this;
            Ext.apply(this, config);
            var me = this;
            this.callParent(arguments);


            this.panel = Ext.getCmp(this.idContenedor);

            var newIndex = 3;



            this.menu = new Ext.FormPanel({
                labelWidth: 75, // label settings here cascade unless overridden
                url:'save-form.php',
                region:'west',
                /*frame:true,*/
                split:true,
                title: 'Elija Opcion de Reporte',
                bodyStyle:'padding:5px 5px 0',
                width: 250,
                defaults: {width: 200},
                margins: '5 0 5 5',

                items: [
                    {
                        xtype:'combo',
                        name: 'reportes',
                        id: 'reportes',
                        fieldLabel: 'Reporte de',
                        allowBlank:true,

                        width: 150,
                        maxLength:25,
                        typeAhead:true,
                        forceSelection: true,
                        triggerAction:'all',
                        mode:'local',
                        store:['Tipo Incidente','Ciudad de Reclamo','Lugar de Reclamo','Genero','Ambiente del Incidente','Estado del Reclamo']


                    /*filters:{pfiltro:'cli.genero',type:'string'},
                    id_grupo:0,
                    grid:true,
                    form:true*/
                }
                ],

                buttons: [{
                    iconCls: 'album-btn',
                    handler: this.guardar,
                    text: 'Generar',
                    scope: this
                }]
            });


            var banco = new Ext.data.JsonStore({
                url: '../../sis_reclamo/control/MotivoAnulado/listarMotivoAnulado',
                id:'id_motivo_anulado',
                totalProperty: 'total',
                root: 'datos',
                remoteSort: true,
                fields: ['id_motivo_anulado', 'motivo', 'orden']
            });
            console.log('banco',banco);

            this.reportPanel = new Ext.Panel({
                id: 'reportPanel',
                width: '100%',
                height: '100%',
                /*renderTo: Ext.get('principal'),*/
                region:'center',
                margins: '5 0 5 5',
                layout: 'vbox',
                items: [/*grafico, grid*/]
            });

            this.iniciarEventos();






            this.Border = new Ext.Container({
                layout:'border',
                id:'principal',
                items:[ this.menu, this.reportPanel]
            });

            this.panel.add(this.Border);
            this.panel.doLayout();
            this.addEvents('init');





            //this.iniciarEventos();
        },

        iniciarEventos: function(){

            Ext.getCmp('reportes').on('select', function(cmb, rec, ind){
                this.cargarTipo(rec.data.field1);
            },this);
        },

        cargarTipo: function(tipoGrafico){
            this.reportPanel.removeAll();
            if(tipoGrafico=='Tipo Incidente'){
                Ext.Ajax.request({
                    url:'../../sis_reclamo/control/Reclamo/stadistica',
                    params:{tipo:'tipo_incidente'},
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        console.log(parseInt(reg.ROOT.datos.v_boleto),parseInt(reg.ROOT.datos.v_vuelo));
                        boleto = parseInt(reg.ROOT.datos.v_boleto);
                        vuelo = parseInt(reg.ROOT.datos.v_vuelo);
                        equipaje = parseInt(reg.ROOT.datos.v_equipaje);
                        carga = parseInt(reg.ROOT.datos.v_carga);
                        catering = parseInt(reg.ROOT.datos.v_catering);
                        sac = parseInt(reg.ROOT.datos.v_sac);
                        otros = parseInt(reg.ROOT.datos.v_otros);

                        var myData = [
                            ['BOLETOS', boleto],
                            ['VUELO', vuelo],
                            ['EQUIPAJE', equipaje],
                            ['CARGA/ENCOMIENDA', carga],
                            ['CATERING', catering],
                            ['ATENCION AL USUARIO', sac],
                            ['OTROS', otros],
                            ['TOTAL', boleto+vuelo+equipaje+carga+catering+sac+otros]
                        ];
                        var store = new Ext.data.ArrayStore({
                            fields: [
                                {name: 'tipo'},
                                {name: 'cantidad', type: 'integer'}

                            ]
                        });
                        store.loadData(myData);

                        var grid = new Ext.grid.GridPanel({
                            store: store,
                            columns: [
                                {
                                    header   : 'Tipo de Incidente',
                                    width    : 120,
                                    sortable : true,
                                    dataIndex: 'tipo'
                                },
                                {
                                    header   : 'N°. Casos',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'cantidad'
                                },
                                {
                                    header   : 'Porcentaje',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'porcentaje'
                                }
                            ],
                            stripeRows: true,
                            width: '100%',
                            title: 'Detalle',
                            // config options for stateful behavior
                            stateful: true,
                            stateId: 'grid',
                            collapsible:true,
                            flex: 2
                        });
                        var grafico = new Ext.Panel({
                            title: 'Grafico',
                            id: 'grafico',
                            bodyPadding: 5,
                            width: '100%',
                            items: [{
                                store: new Ext.data.JsonStore({
                                    fields: ['season', 'total'],
                                    data: [{
                                        season: 'Boletos',
                                        total: boleto
                                    },{
                                        season: 'Vuelo',
                                        total: vuelo
                                    },{
                                        season: 'Equipaje',
                                        total: equipaje
                                    },{
                                        season: 'Carga/Encomienda',
                                        total: carga
                                    },{
                                        season: 'Catering',
                                        total: catering
                                    },{
                                        season: 'Atencion al Usuario',
                                        total: sac
                                    },{
                                        season: 'Otros',
                                        total: otros
                                    }]
                                }),
                                xtype: 'piechart',
                                dataField: 'total',
                                categoryField: 'season',
                                //extra styles get applied to the chart defaults
                                extraStyle:
                                {
                                    legend:
                                    {
                                        display: 'bottom',
                                        padding: 5,
                                        font:
                                        {
                                            family: 'Tahoma',
                                            size: 13
                                        }
                                    }
                                }
                            }], // An array of form fields
                            flex: 2,
                            collapsible: true
                        });
                        this.reportPanel.add(grafico);
                        this.reportPanel.add(grid);
                        this.reportPanel.render(Ext.get('principal'));
                        this.reportPanel.doLayout();
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }else if(tipoGrafico=='Ciudad de Reclamo'){
                Ext.Ajax.request({
                    url:'../../sis_reclamo/control/Reclamo/stadistica',
                    params:{tipo:'ciudad'},
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        console.log(reg.ROOT.datos);
                        //console.log(parseInt(reg.ROOT.datos.v_boleto),parseInt(reg.ROOT.datos.v_vuelo));

                        v_lim = parseInt(reg.ROOT.datos.v_lim);
                        v_bue = parseInt(reg.ROOT.datos.v_bue);
                        v_sla = parseInt(reg.ROOT.datos.v_sla);
                        v_sao = parseInt(reg.ROOT.datos.v_sao);
                        v_mad = parseInt(reg.ROOT.datos.v_mad);
                        v_viru = parseInt(reg.ROOT.datos.v_viru);
                        v_uyu = parseInt(reg.ROOT.datos.v_uyu);
                        v_oru = parseInt(reg.ROOT.datos.v_oru);
                        v_poi = parseInt(reg.ROOT.datos.v_poi);
                        v_cij = parseInt(reg.ROOT.datos.v_cij);
                        v_tdd = parseInt(reg.ROOT.datos.v_tdd);
                        v_tja = parseInt(reg.ROOT.datos.v_tja);
                        v_sre = parseInt(reg.ROOT.datos.v_sre);
                        v_srz = parseInt(reg.ROOT.datos.v_srz);
                        v_lpb = parseInt(reg.ROOT.datos.v_lpb);
                        v_cbb = parseInt(reg.ROOT.datos.v_cbb);
                        v_acft = parseInt(reg.ROOT.datos.v_acft);
                        v_otros = parseInt(reg.ROOT.datos.v_otros);
                        v_mia = parseInt(reg.ROOT.datos.v_mia);


                        var myData = [
                            ['CBB', v_cbb],
                            ['LPB', v_lpb],
                            ['SRZ', v_srz],
                            ['SRE', v_sre],
                            ['TJA', v_tja],
                            ['TDD', v_tdd],
                            ['CIJ', v_cij],
                            ['POI', v_poi],
                            ['ORU', v_oru],
                            ['UYU', v_uyu],
                            ['VIRU', v_viru],
                            ['OTROS', v_otros],
                            ['ACFT', v_acft],
                            ['MAD', v_mad],
                            ['SAO', v_sao],
                            ['SLA', v_sla],
                            ['MIA', v_mia],
                            ['BUE', v_bue],
                            ['TOTAL', v_cbb+v_lpb+v_srz+v_sre+v_tja+v_tdd+v_cij+v_poi+v_oru+v_uyu+v_viru+v_otros+v_acft+v_mad+v_sao+v_sla+v_mia+v_bue]
                        ];
                        var store = new Ext.data.ArrayStore({
                            fields: [
                                {name: 'tipo'},
                                {name: 'cantidad', type: 'integer'}

                            ]
                        });
                        store.loadData(myData);

                        var grid = new Ext.grid.GridPanel({
                            store: store,
                            columns: [
                                {
                                    header   : 'Ciudad de Reclamo',
                                    width    : 120,
                                    sortable : true,
                                    dataIndex: 'tipo'
                                },
                                {
                                    header   : 'N°. Casos',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'cantidad'
                                },
                                {
                                    header   : 'Porcentaje',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'porcentaje'
                                }
                            ],
                            stripeRows: true,
                            width: '100%',
                            title: 'Detalle',
                            // config options for stateful behavior
                            stateful: true,
                            stateId: 'grid',
                            collapsible:true,
                            flex: 2
                        });
                        var grafico = new Ext.Panel({
                            title: 'Grafico',
                            id: 'grafico',
                            bodyPadding: 5,
                            width: '100%',
                            items: [{
                                store: new Ext.data.JsonStore({
                                    fields: ['season', 'total'],
                                    data: [{
                                        season: 'Cochabamba',
                                        total: v_cbb
                                    },{
                                        season: 'La Paz',
                                        total: v_lpb
                                    },{
                                        season: 'Santa Cruz',
                                        total: v_srz
                                    },{
                                        season: 'Sucre',
                                        total: v_sre
                                    },{
                                        season: 'Tarija',
                                        total: v_tja
                                    },{
                                        season: 'Trinidad',
                                        total: v_tdd
                                    },{
                                        season: 'Cobija',
                                        total: v_cij
                                    },{
                                        season: 'Potosi',
                                        total: v_poi
                                    },{
                                        season: 'Oruro',
                                        total: v_oru
                                    },{
                                        season: 'Uyuni',
                                        total: v_uyu
                                    },{
                                        season: 'Viru Viru',
                                        total: v_viru
                                    },{
                                        season: 'Otros',
                                        total: v_otros
                                    },{
                                        season: 'ACFT',
                                        total: v_acft
                                    },{
                                        season: 'Madrid',
                                        total: v_mad
                                    },{
                                        season: 'Sao Paulo',
                                        total: v_sao
                                    },{
                                        season: 'Salta',
                                        total: v_sla
                                    },{
                                        season: 'Miami',
                                        total: v_mia
                                    },{
                                        season: 'Buenos Aires',
                                        total: v_bue
                                    }]
                                }),
                                xtype: 'piechart',
                                dataField: 'total',
                                categoryField: 'season',
                                //extra styles get applied to the chart defaults
                                extraStyle:
                                {
                                    legend:
                                    {
                                        display: 'bottom',
                                        padding: 5,
                                        font:
                                        {
                                            family: 'Tahoma',
                                            size: 13
                                        }
                                    }
                                }
                            }], // An array of form fields
                            flex: 2,
                            collapsible: true
                        });
                        this.reportPanel.add(grafico);
                        this.reportPanel.add(grid);
                        this.reportPanel.render(Ext.get('principal'));
                        this.reportPanel.doLayout();
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }else if(tipoGrafico=='Lugar de Reclamo'){
                Ext.Ajax.request({
                    url:'../../sis_reclamo/control/Reclamo/stadistica',
                    params:{tipo:'lugar'},
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

                        v_ato = parseInt(reg.ROOT.datos.v_ato);
                        v_cto = parseInt(reg.ROOT.datos.v_cto);
                        v_cga = parseInt(reg.ROOT.datos.v_cga);
                        v_canalizado = parseInt(reg.ROOT.datos.v_canalizado);
                        v_web = parseInt(reg.ROOT.datos.v_web);
                        v_acft = parseInt(reg.ROOT.datos.v_acft);
                        v_call = parseInt(reg.ROOT.datos.v_call);
                        v_att = parseInt(reg.ROOT.datos.v_att);

                        var myData = [
                            ['ATO', v_ato],
                            ['CTO', v_cto],
                            ['CGA', v_cga],
                            ['CANALIZADO', v_canalizado],
                            ['WEB', v_web],
                            ['ACFT', v_acft],
                            ['CALL CENTER', v_call],
                            ['ATT', v_att],
                            ['TOTAL', v_ato+v_cto+v_cga+v_canalizado+v_web+v_acft+v_call+v_att]
                        ];

                        var store = new Ext.data.ArrayStore({
                            fields: [
                                {name: 'tipo'},
                                {name: 'cantidad', type: 'integer'}

                            ]
                        });
                        store.loadData(myData);

                        var grid = new Ext.grid.GridPanel({
                            store: store,
                            columns: [
                                {
                                    header   : 'Lugar Reclamo',
                                    width    : 120,
                                    sortable : true,
                                    dataIndex: 'tipo'
                                },
                                {
                                    header   : 'N°. Casos',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'cantidad'
                                },
                                {
                                    header   : 'Porcentaje',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'porcentaje'
                                }
                            ],
                            stripeRows: true,
                            width: '100%',
                            title: 'Detalle',
                            // config options for stateful behavior
                            stateful: true,
                            stateId: 'grid',
                            collapsible:true,
                            flex: 2
                        });


                        var grafico = new Ext.Panel({
                            title: 'Grafico',
                            id: 'grafico',
                            bodyPadding: 5,
                            width: '100%',
                            items: [{
                                store: new Ext.data.JsonStore({
                                    fields: ['season', 'total'],
                                    data: [{
                                        season: 'Aeropuerto',
                                        total: v_ato
                                    },{
                                        season: 'Oficina Regional',
                                        total: v_cto
                                    },{
                                        season: 'Carga',
                                        total: v_cga
                                    },{
                                        season: 'Canalizado',
                                        total: v_canalizado
                                    },{
                                        season: 'Web',
                                        total: v_web
                                    },{
                                        season: 'ACFT',
                                        total: v_acft
                                    },{
                                        season: 'Call Center',
                                        total: v_call
                                    },{
                                        season: 'ATT',
                                        total: v_att
                                    }]
                                }),
                                xtype: 'piechart',
                                dataField: 'total',
                                categoryField: 'season',
                                //extra styles get applied to the chart defaults
                                extraStyle:
                                {
                                    legend:
                                    {
                                        display: 'bottom',
                                        padding: 5,
                                        font:
                                        {
                                            family: 'Tahoma',
                                            size: 13
                                        }
                                    }
                                }
                            }], // An array of form fields
                            flex: 2,
                            collapsible: true
                        });
                        this.reportPanel.add(grafico);
                        this.reportPanel.add(grid);
                        this.reportPanel.render(Ext.get('principal'));
                        this.reportPanel.doLayout();
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }
            else if(tipoGrafico=='Genero'){
                Ext.Ajax.request({
                    url:'../../sis_reclamo/control/Reclamo/stadistica',
                    params:{tipo:'genero'},
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        hombres = parseInt(reg.ROOT.datos.v_hombres);
                        mujeres = parseInt(reg.ROOT.datos.v_mujeres);
                        noEspecifica = parseInt(reg.ROOT.datos.v_noEspecifica);


                        var myData = [
                            ['VARONES', hombres],
                            ['MUJERES', mujeres],
                            ['NO ESPECIFICA', noEspecifica],
                            ['TOTAL', hombres+mujeres+noEspecifica]
                        ];
                        var store = new Ext.data.ArrayStore({
                            fields: [
                                {name: 'genero'},
                                {name: 'cantidad', type: 'integer'}

                            ]
                        });
                        store.loadData(myData);

                        var grid = new Ext.grid.GridPanel({
                            store: store,
                            columns: [
                                {
                                    header   : 'Genero',
                                    width    : 120,
                                    sortable : true,
                                    dataIndex: 'genero'
                                },
                                {
                                    header   : 'Cantidad',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'cantidad'
                                },
                                {
                                    header   : 'Porcentaje',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'porcentaje'
                                }
                            ],
                            stripeRows: true,
                            width: '100%',
                            title: 'Detalle',
                            // config options for stateful behavior
                            stateful: true,
                            stateId: 'grid',
                            collapsible:true,
                            flex: 2
                        });
                        var grafico = new Ext.Panel({
                            title: 'Grafico',
                            id: 'grafico',
                            bodyPadding: 5,
                            width: '100%',
                            items: [{
                                store: new Ext.data.JsonStore({
                                    fields: ['season', 'total'],
                                    data: [{
                                        season: 'Varones',
                                        total: hombres
                                    },{
                                        season: 'Mujeres',
                                        total: mujeres
                                    },{
                                        season: 'No Especifica',
                                        total: noEspecifica
                                    }]
                                }),
                                xtype: 'piechart',
                                dataField: 'total',
                                categoryField: 'season',
                                //extra styles get applied to the chart defaults
                                extraStyle:
                                {
                                    legend:
                                    {
                                        display: 'bottom',
                                        padding: 5,
                                        font:
                                        {
                                            family: 'Tahoma',
                                            size: 13
                                        }
                                    }
                                }
                            }], // An array of form fields
                            flex: 2,
                            collapsible: true
                        });
                        this.reportPanel.add(grafico);
                        this.reportPanel.add(grid);
                        this.reportPanel.render(Ext.get('principal'));
                        this.reportPanel.doLayout();
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }else if(tipoGrafico=='Ambiente del Incidente'){
                Ext.Msg.alert('Ambiente',tipoGrafico);

            }else if(tipoGrafico=='Estado del Reclamo'){
                Ext.Ajax.request({
                    url:'../../sis_reclamo/control/Reclamo/stadistica',
                    params:{tipo:'estado'},
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

                        borrador = parseInt(reg.ROOT.datos.borrador);
                        pen_rev = parseInt(reg.ROOT.datos.pendiente_revision);
                        reg_ripat = parseInt(reg.ROOT.datos.registrado_ripat);
                        pen_inf = parseInt(reg.ROOT.datos.pendiente_informacion);
                        anulado = parseInt(reg.ROOT.datos.anulado);
                        derivado = parseInt(reg.ROOT.datos.derivado);
                        pen_resp = parseInt(reg.ROOT.datos.pendiente_respuesta);
                        arch_con_resp = parseInt(reg.ROOT.datos.archivo_con_respuesta);
                        arch_con = parseInt(reg.ROOT.datos.archivado_concluido);
                        en_ave = parseInt(reg.ROOT.datos.en_avenimiento);
                        form_cargos = parseInt(reg.ROOT.datos.formulacion_cargos);
                        res_admin = parseInt(reg.ROOT.datos.resolucion_administrativa);
                        rec_revo = parseInt(reg.ROOT.datos.recurso_revocatorio);
                        rec_jerar = parseInt(reg.ROOT.datos.recurso_jerarquico);
                        con_admin = parseInt(reg.ROOT.datos.contencioso_administrativo);
                        pen_asig = parseInt(reg.ROOT.datos.pendiente_asignacion);
                        resp_reg_ripat = parseInt(reg.ROOT.datos.respuesta_registro_ripat);

                        var myData = [
                            ['BORRADOR', borrador],
                            ['PENDIENTE REV.', pen_rev],
                            ['REG. RIPAT', reg_ripat],
                            ['PENDIENTE INF.', pen_inf],
                            ['ANULADO', anulado],
                            ['DERIVADO', derivado],
                            ['PENDIENTE RESP.', pen_resp],
                            ['ARCHIVO CON RESP.', arch_con_resp],
                            ['ARCHIVADO-CONCLUIDO', arch_con],
                            ['EN AVENIMIENTO', en_ave],
                            ['FORMULACION CARGOS', form_cargos],
                            ['RES. ADMINISTRATIVA', res_admin],
                            ['REC. REVOCATORIO', rec_revo],
                            ['REC. JERARQUICO', rec_jerar],
                            ['CONTENCIOSO ADMIN.', con_admin],
                            ['PENDIENTE ASIG.', pen_asig],
                            ['RESP. REG. RIPAT', resp_reg_ripat],
                            ['TOTAL', borrador+pen_rev+reg_ripat+pen_inf+anulado+derivado+pen_resp+arch_con_resp+arch_con+en_ave+form_cargos+res_admin+rec_revo+rec_jerar+con_admin+pen_asig+resp_reg_ripat]
                        ];

                        var store = new Ext.data.ArrayStore({
                            fields: [
                                {name: 'tipo'},
                                {name: 'cantidad', type: 'integer'}

                            ]
                        });
                        store.loadData(myData);

                        var grid = new Ext.grid.GridPanel({
                            store: store,
                            columns: [
                                {
                                    header   : 'Tipo de Incidente',
                                    width    : 120,
                                    sortable : true,
                                    dataIndex: 'tipo'
                                },
                                {
                                    header   : 'N°. Casos',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'cantidad'
                                },
                                {
                                    header   : 'Porcentaje',
                                    width    : 75,
                                    sortable : true,
                                    dataIndex: 'porcentaje'
                                }
                            ],
                            stripeRows: true,
                            width: '100%',
                            title: 'Detalle',
                            // config options for stateful behavior
                            stateful: true,
                            stateId: 'grid',
                            collapsible:true,
                            flex: 2
                        });



                        pen_asig = parseInt(reg.ROOT.datos.pendiente_asignacion);
                        resp_reg_ripat = parseInt(reg.ROOT.datos.respuesta_registro_ripat);
                        var grafico = new Ext.Panel({
                            title: 'Grafico',
                            id: 'grafico',
                            bodyPadding: 5,
                            width: '100%',
                            items: [{
                                store: new Ext.data.JsonStore({
                                    fields: ['season', 'total'],
                                    data: [{
                                        season: 'Borrador',
                                        total: borrador
                                    },{
                                        season: 'P. Resp.',
                                        total: pen_rev
                                    },{
                                        season: 'Reg. Rip.',
                                        total: reg_ripat
                                    },{
                                        season: 'Pen. Inf.',
                                        total: pen_inf
                                    },{
                                        season: 'Anulado',
                                        total: anulado
                                    },{
                                        season: 'Derivado',
                                        total: derivado
                                    },{
                                        season: 'Pen. Resp.',
                                        total: pen_resp
                                    },{
                                        season: 'Archivo con Resp.',
                                        total: arch_con_resp
                                    },{
                                        season: 'Avenimiento',
                                        total: en_ave
                                    },{
                                        season: 'Form. Cargos',
                                        total: form_cargos
                                    },{
                                        season: 'Res. Adm.',
                                        total: res_admin
                                    },{
                                        season: 'Rec. Revo.',
                                        total: rec_revo
                                    },{
                                        season: 'Rec. Jer.',
                                        total: rec_jerar
                                    },{
                                        season: 'Cont. Adm.',
                                        total: con_admin
                                    },{
                                        season: 'Pen. Asig.',
                                        total: pen_asig
                                    },{
                                        season: 'Resp Reg. Rip.',
                                        total: resp_reg_ripat
                                    }]
                                }),
                                xtype: 'piechart',
                                dataField: 'total',
                                categoryField: 'season',
                                //extra styles get applied to the chart defaults
                                extraStyle:
                                {
                                    legend:
                                    {
                                        display: 'bottom',
                                        padding: 5,
                                        font:
                                        {
                                            family: 'Tahoma',
                                            size: 13
                                        }
                                    }
                                }
                            }], // An array of form fields
                            flex: 2,
                            collapsible: true
                        });
                        this.reportPanel.add(grafico);
                        this.reportPanel.add(grid);
                        this.reportPanel.render(Ext.get('principal'));
                        this.reportPanel.doLayout();
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }
        },

        change: function (val) {
            if (val > 0) {
                return '<span style="color:green;">' + val + '</span>';
            } else if (val < 0) {
                return '<span style="color:red;">' + val + '</span>';
            }
            return val;
        },

        pctChange: function (val) {
            if (val > 0) {
                return '<span style="color:green;">' + val + '%</span>';
            } else if (val < 0) {
                return '<span style="color:red;">' + val + '%</span>';
            }
            return val;
        },

        guardar: function(){
            Ext.Msg.alert('Guardar');
        },

        cancelar:  function(){
            Ext.Msg.alert('Cancelar');
        },

        iniciarDashboard:function(nodo){

            //es diferente del nodo actual
            if(nodo != this.nodoActual){

                //limpiar widget
                this.limpiarDashboard();

                this.nodoActual = nodo;
                //extraer datos de los widget configurados

                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashdet/listarDashdetalle',
                    success : this.cargarDashboard,
                    failure : Phx.CP.conexionFailure,
                    params : {id_dashboard: nodo.attributes.id_dashboard},
                    arguments: {nodo: nodo},
                    scope : this
                });


            }

        },
        cargarDashboard:function(response,arg,b){

            console.log('regreso', response,arg,b)

            console.log('responseText',response.responseText)


            //crear objetos
            var regreso = Ext.util.JSON.decode(Ext.util.Format.trim(response.responseText)).datos;

            var me = this;
            regreso.forEach(function(entry) {
                me.insertarWidget(entry);
            });

            me.PanelDash.doLayout();


        },

        insertarWidget:function(entry){
            var me = this;
            var wid = Ext.id()+'-Widget', item ;
            console.log('entry',entry.columna);
            var indice = entry.columna?entry.columna:0;
            var tmp = new Ext.ux.Portlet({
                id: wid,
                layout: 'fit',
                title: entry.nombre,
                closable: true,
                maximizable : true,
                autoShow: true,
                autoScroll: false,
                autoHeight : false,
                autoDestroy: true,
                widget: entry,
                forceLayout:true,
                autoLoad: {
                    url: '../../../'+entry.ruta,
                    params:{ idContenedor: wid, _tipo: 'direc', mycls: entry.clase},
                    showLoadIndicator: "Cargando...",
                    arguments: {config: entry},
                    callback: me.callbackWidget,
                    text: 'Loading...',
                    scope: me,
                    scripts :true
                }
            });

            me.PanelDash.items.items[indice].add(tmp);
            //tmp.show()


        },

        callbackWidget: function(a,o,c,d){
            var xx = new Phx.vista.widget[d.arguments.config.clase](d.params);
            xx.init();
        },

        limpiarDashboard:function(){
            var me = this;

            for(var i=0; i<=2 ;i++){
                var aux = 0;
                me.PanelDash.items.items[i].removeAll(true)
            }
            this.nodoActual = undefined;
        },

        newDasboard: function(){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url : '../../sis_parametros/control/Dashboard/insertarDashboard',
                success : this.successNewDash,
                failure : Phx.CP.conexionFailure,
                params : {foo: 'bar'},
                scope : this
            });


        },

        deleteDasboard: function(){


            this.sm = this.treeMenu.getSelectionModel();
            var node = this.sm.getSelectedNode();

            if(confirm('¿Está seguro de eliminar el Dashboard?')){

                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashboard/eliminarDashboard',
                    success : this.successDelDash,
                    failure : Phx.CP.conexionFailure,
                    params : { id_dashboard: node.attributes.id_dashboard },
                    scope : this
                });

            }
        },

        editDashboard:function(obj, value, startValue,o){
            var node =obj.editNode;
            if(value != startValue){
                Ext.Ajax.request({
                    url : '../../sis_parametros/control/Dashboard/insertarDashboard',
                    success : this.successNewDash,
                    failure : Phx.CP.conexionFailure,
                    params : {nombre: value, id_dashboard: node.attributes.id_dashboard},
                    scope : this
                });
            }

        },

        successDelDash:function(){
            Phx.CP.loadingHide();
            this.limpiarDashboard();
            this.root.reload();

        },
        successNewDash:function(){
            Phx.CP.loadingHide();
            this.root.reload();

        },

        loadWindowsWidget:function(){
            var me = this;

            if(this.nodoActual)	{
                Phx.CP.loadWindows('../../../sis_parametros/vista/widget/WidgetDash.php',
                    'Estado de Wf',
                    {   modal: true,
                        width: '70%',
                        height: '50%'
                    },
                    { foo: 'foo' },
                    me.idContenedor,'WidgetDash',
                    {  config:[{
                        event: 'selectwidget',
                        delegate: me.onSelectwidget,
                    }],
                        scope:me
                    });
            }
            else {
                alert('Primero seleccione el dashboard')
            }
        },

        onSelectwidget: function(win, rec){
            var me = this;
            console.log('selectwidget', rec)
            win.panel.close();

            me.insertarWidget(rec.data);
            me.PanelDash.doLayout();
        },


        getPosiciones: function(){

            var position = [], me = this;
            for(var i=0; i<=2 ;i++){
                var aux = 0;

                me.PanelDash.items.items[i].items.items.forEach(function(entry) {
                    position.push({  columna: i,
                        fila:aux,
                        id_widget: entry.widget.id_widget?entry.widget.id_widget:0,
                        id_dashdet: entry.widget.id_dashdet?entry.widget.id_dashdet:0,
                        id_dashboard: entry.widget.id_dashboard ?entry.widget.id_dashboard:0

                    });
                    aux++;
                })

            }

            return position

        },

        guardarPosiciones:function(){

            if(this.nodoActual)	{
                console.log(this.getPosiciones());
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url:'../../sis_parametros/control/Dashdet/guardarPosiciones',
                    params:{
                        id_dashboard_activo:  this.nodoActual.attributes.id_dashboard,
                        json_procesos:  Ext.util.JSON.encode(this.getPosiciones()),
                    },
                    success: this.successNewDash,
                    failure: Phx.CP.conexionFailure,
                    scope: this
                });
            }
            else{
                alert('Primero seleccion un dashboard');
            }
        }

    });
</script>