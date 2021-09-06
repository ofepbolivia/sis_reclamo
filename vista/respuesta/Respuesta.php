<?php
/**
 * @package pXP
 * @file gen-Respuesta.php
 * @author  (admin)
 * @date 11-08-2016 16:01:08
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Respuesta = Ext.extend(Phx.gridInterfaz, {

        nombreVista: 'Respuesta',

        constructor: function (config) {
            this.maestro = config.maestro;

            //llama al constructor de la clase padre
            Phx.vista.Respuesta.superclass.constructor.call(this, config);
            this.momento= '';

            this.init();
            this.store.baseParams.pes_estado = 'elaboracion_respuesta';
            this.iniciarEventos();


            this.addButton('ant_estado', {
                grupo: [0, 1, 2, 3],
                argument: {estado: 'anterior'},
                text: 'Anterior',
                iconCls: 'batras',
                disabled: true,
                /*hidden: true,*/
                handler: this.antEstado,
                tooltip: '<b>Volver al Anterior Estado</b>'
            });
            this.addButton('sig_estado', {
                grupo: [0, 1, 2],
                text: 'Siguiente',
                iconCls: 'badelante',
                disabled: true,
                handler: this.sigEstado,
                tooltip: '<b>Pasar al Siguiente Estado</b>'
            });

            this.addButton('btnChequeoDocumentosWf', {
                text: 'Documentos',
                grupo: [0, 1, 2, 3],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos de la Respuesta</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
            });

            this.addButton('btnObs', {
                grupo: [0, 1, 2, 3],
                text: 'Obs Wf.',
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.onOpenObs,
                tooltip: '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
            });

            this.addButton('diagrama_gantt', {
                grupo: [0, 1, 2, 3],
                text: 'Gant',
                iconCls: 'bgantt',
                disabled: true,
                handler: diagramGantt,
                tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            });

            /*this.addButton('vista_previa',{
                grupo:[0,1,2,3],
                text:'Vista Previa',
                iconCls: 'btemplate',
                disabled:true,
                handler:this.vistaPrevia,
                tooltip: '<b>Permite visualizar la respuesta en PDF.</b>'
            });*/

            function diagramGantt() {
                var data = this.sm.getSelected().data.id_proceso_wf;
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url: '../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                    params: {'id_proceso_wf': data},
                    success: this.successExport,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            };

        },

        Grupos : [
            {
                layout: 'column',
                //bodyStyle: 'padding-right:10px;',
                labelWidth: 80,
                labelAlign: 'top',
                border: false,
                items: [
                    {
                        columnWidth: .30,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'DATOS RESPUESTA',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        //bodyStyle: 'padding-right:10px;',
                                        border: false,
                                        padding: '0 5 0 5',
                                        //bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        columnWidth: .70,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: '-',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        //bodyStyle: 'padding-right:10px;',
                                        border: false,
                                        padding: '0 5 0 5',
                                        //bodyStyle: 'padding-left:5px;',
                                        id_grupo: 2,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },

                ]
            }
        ],

        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_respuesta'
                },
                type: 'Field',
                form: true,
                id_grupo:1
            },
            {

                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_reclamo'
                },
                type: 'Field',
                form: true,
                id_grupo:1
            },
            {
                config: {
                    name: 'nro_respuesta',
                    fieldLabel: 'No. Respuesta',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength: 100,
                    renderer: function (value, p, record) {
                        return String.format('<b><font color="green">{0}</font></b>', value);
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'res.nro_respuesta', type: 'string'},
                /*id_grupo:1,*/
                grid: true,
                form: false,
                bottom_filter: true
            },
            //

            {
                config: {
                    name: 'estado',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength: 100
                },
                type: 'TextField',
                filters: {pfiltro: 'res.estado', type: 'string'},
                /*id_grupo: 1,*/
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_respuesta',
                    fieldLabel: 'Fecha Respuesta',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 100,


                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'res.fecha_respuesta', type: 'date'},
                id_grupo: 1,
                grid: false,
                form: true,
                bottom_filter: true
            },

            {
                config: {
                    name: 'nro_cite',
                    fieldLabel: 'Nro. de Cite',
                    allowBlank: false,
                    /*regex: '/[A-Z]/',
                    regexText: "<b>Error</b></br>Invalid Number entered.",*/
                    anchor: '40%',
                    gwidth: 100,
                    maxLength: 50,
                    style: 'text-transform:uppercase;'

                },
                type: 'TextField',
                filters: {pfiltro: 'res.nro_cite', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true,
                bottom_filter: true
            },
            {
                config: {
                    name: 'asunto',
                    fieldLabel: 'Referencia / Asunto',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength: 100000
                },
                type: 'TextArea',
                filters: {pfiltro: 'res.asunto', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true,
                bottom_filter: true
            }, {
                config: {
                    name: 'destinatario',
                    fieldLabel: 'Cliente',
                    allowBlank: true,
                    disabled: true,
                    anchor: '60%',
                    gwidth: 100,
                    maxLength: 50,
                    style: 'text-transform:uppercase;'
                },
                type: 'TextField',

                id_grupo: 1
            },
            {
                config: {

                    name: 'correos',
                    fieldLabel: 'Correos',
                    allowBlank: true,
                    disabled: true,
                    anchor: '95%',
                    dato: 'reclamo',
                    qtip: 'Correo del Destinatario.',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/Cliente/listarCliente',
                        id: 'id_cliente',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre_completo2',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_cliente', 'nombre_completo2', 'nombre_completo1', 'ci', 'email', 'email2'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams: {par_filtro: 'c.nombre_completo2'}
                    }),
                    valueField: 'id_cliente',
                    displayField: 'nombre_completo2',
                    gdisplayField: 'nombre_completo2',//mapea al store del grid
                    tpl: '<tpl for="."><div class="x-combo-list-item"><p>{nombre_completo2}</p><p>CI:{ci}</p><p style= "color : green;" >email:{email}</p><p>email2:{email2}</p></div></tpl>',
                    hiddenName: 'id_cliente',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    width: 315,
                    gwidth: 320,
                    minChars: 1,
                    turl: '../../../sis_reclamo/vista/cliente/ClienteFormEdit.php',
                    ttitle: 'Editar Cliente',
                    tconfig: {width: '45%', height: '90%'},
                    tdata: {},
                    tcls: 'ClienteFormEdit',
                    //pid:this.idContenedor,

                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_nom_cliente']);
                    }
                },
                type: 'TrigguerCombo',
                id_grupo: 1,
                bottom_filter: false
            },
            /*{
                config: {
                    name: 'respuesta',
                    fieldLabel: 'Contenido de la Respuesta',
                    allowBlank: false,
                    anchor: '100%',
                    qtip: 'Definimos una Respuesta Formateada',
                    gwidth: 200,
                    enableColors: true,
                    enableAlignments: true,
                    enableLists: true,
                    enableSourceEdit: true,
                    enableFontSize: false,
                    defaultFont: 'Arial'
                },
                type: 'HtmlEditor',
                filters: {pfiltro: 'res.respuesta', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: true,
                bottom_filter: true
            },*/

            {
                config: {
                    name: 'respuesta',
                    fieldLabel: 'Respuesta',
                    allowBlank: false,
                    //anchor: '100%',
                    qtip: 'Definimos una Respuesta Formateada',
                    gwidth: 200,
                    height: '600',
                    CKConfig: {

                    }

                },
                type: 'CKEditor',
                filters: {pfiltro: 'res.respuesta', type: 'string'},
                id_grupo: 2,
                grid: false,
                form: true,
                bottom_filter: true
            },
            {
                config: {
                    name: 'recomendaciones',
                    fieldLabel: 'Recomendación para Evitar Futuros Reclamos',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength: 1000000,
                    value: 'Ninguna'
                },
                type: 'TextArea',
                filters: {pfiltro: 'res.recomendaciones', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: true,
                bottom_filter: true
            },
            {
                config: {
                    name: 'procedente',
                    fieldLabel: 'Procedente',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 100,
                    maxLength: 100,
                    gdisplayField: 'procedente',
                    typeAhead: true,
                    forceSelection: true,
                    triggerAction: 'all',
                    mode: 'local',
                    store: ['NO', 'SI', 'NINGUNO']
                },
                type: 'ComboBox',
                filters: {pfiltro: 'res.procedente', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'tipo_respuesta',
                    fieldLabel: 'Tipo Respuesta',
                    allowBlank: false,
                    anchor: '40%',
                    maxLength: 300,
                    gwidth: 100,
                    typeAhead: true,
                    forceSelection: true,
                    triggerAction: 'all',
                    mode: 'local',
                    store: ['respuesta_final', 'respuesta_parcial']
                },
                type: 'ComboBox',
                filters: {pfiltro: 'res.asunto', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: true,
                bottom_filter: true
            },
            {
                config: {
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'TextField',
                filters: {pfiltro: 'res.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_notificacion',
                    fieldLabel: 'Fecha Notificacion',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 100,
                    /*inputType:'hidden',*/
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'res.fecha_notificacion', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_usuario_ai',
                    fieldLabel: '',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'res.id_usuario_ai', type: 'numeric'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 300
                },
                type: 'TextField',
                filters: {pfiltro: 'res.usuario_ai', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'res.fecha_reg', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'res.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            }/*,
		{
			config: {
				name: 'url_resp',
				fieldLabel: 'Url Resp.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 10
			},
			type: 'TextField',
			filters: {pfiltro: 'res.url_resp', type: 'string'},
			id_grupo: 1,
			grid: false,
			form: false
		},*/
        ],
        tam_pag: 50,
        title: 'Respuesta',
        ActSave: '../../sis_reclamo/control/Respuesta/insertarRespuesta',
        ActDel: '../../sis_reclamo/control/Respuesta/eliminarRespuesta',
        ActList: '../../sis_reclamo/control/Respuesta/listarRespuesta',
        id_store: 'id_respuesta',
        fields: [
            {name: 'id_respuesta', type: 'numeric'},
            {name: 'id_reclamo', type: 'numeric'},
            {name: 'recomendaciones', type: 'string'},
            {name: 'nro_cite', type: 'string'},
            {name: 'respuesta', type: 'string'},
            {name: 'fecha_respuesta', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'estado_reg', type: 'string'},
            {name: 'procedente', type: 'string'},
            {name: 'fecha_notificacion', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            'tipo_respuesta',
            'asunto',
            {name: 'id_proceso_wf', type: 'numeric'},
            {name: 'id_estado_wf', type: 'numeric'},
            {name: 'estado', type: 'string'},
            {name: 'nro_respuesta', type: 'numeric'},
            {name: 'email', type: 'string'},
            {name: 'email2', type: 'string'},
            {name: 'admin', type: 'numeric'},
            {name: 'codigo_medio', type: 'string'},
            {name: 'nro_att', type: 'numeric'},
            {name: 'correlativo_preimpreso_frd', type: 'numeric'},
            {name: 'nro_frd', type: 'string'},
            {name: 'detalle_incidente', type: 'string'},
            {name: 'nombre_completo1', type: 'string'}


            /*,
		{name: 'url_resp', type: 'string'}*/

        ],
        sortInfo: {
            field: 'id_respuesta',
            direction: 'ASC'
        },

        /*rowExpander: new Ext.ux.grid.RowExpander({
            tpl: new Ext.Template(
                //'<br>', '<h1 style="text-align: left">Respuesta Detalle </h1>',
                '<p>&nbsp;&nbsp;<b>CONTENIDO DE LA RESPUESTA:&nbsp;&nbsp;</b> {respuesta}</p>',
                '<p>&nbsp;</p>',
                '<p>&nbsp;&nbsp;<b>RECOMENDACION PARA EVITAR FUTUROS RECLAMOS:&nbsp;&nbsp;</b> {recomendaciones}</p>',
            )
        }),*/


        bdel: true,
        bsave: false,
        btest: false,
        fwidth: '95%',
        fheight: '95%',
        collapsible: true,
        iniciarEventos: function () {
            this.Cmp.nro_cite.on('blur', function (field) {
                this.generarCite();
            }, this);

        },
        onOpenObs: function () {
            var rec = this.sm.getSelected();
            var data = {
                id_proceso_wf: rec.data.id_proceso_wf,
                id_estado_wf: rec.data.id_estado_wf,
                num_tramite: rec.data.nro_tramite
            }

            Phx.CP.loadWindows('../../../sis_workflow/vista/obs/Obs.php',
                'Observaciones del WF',
                {
                    width: '80%',
                    height: '70%'
                },
                data,
                this.idContenedor,
                'Obs'
            )
        },

        loadCheckDocumentosRecWf: function () {
            var rec = this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width: '90%',
                    height: 500
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },

        antEstado: function (res) {
            var rec = this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal: true,
                    width: 450,
                    height: 250
                }, {data: rec.data, estado_destino: res.argument.estado}, this.idContenedor, 'AntFormEstadoWf',
                {
                    config: [{
                        event: 'beforesave',
                        delegate: this.onAntEstado,
                    }
                    ],
                    scope: this
                })
        },

        onAntEstado: function (wizard, resp) {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_reclamo/control/Respuesta/anteriorEstadoRespuesta',
                params: {
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf: resp.id_estado_wf,
                    obs: resp.obs,
                    estado_destino: resp.estado_destino
                },
                argument: {wizard: wizard},
                success: this.successEstadoSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },

        successEstadoSinc: function (resp) {
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy()
            this.reload();
        },

        sigEstado: function () {

            var rec = this.sm.getSelected();
            if (rec.data.codigo_medio == 'CAU' && (rec.data.nro_att == null || rec.data.nro_att == '')) {
                Ext.Msg.alert(
                    'Alerta', 'El Reclamo es CENATIN - Mi Reclamo, necesita llenar el campo Nro. Att Canalizado.'
                );
            } else {
                this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                    'Estado de Wf',
                    {
                        modal: true,
                        width: 700,
                        height: 450
                    },
                    {
                        data: {
                            id_estado_wf: rec.data.id_estado_wf,
                            id_proceso_wf: rec.data.id_proceso_wf
                        }
                    }, this.idContenedor, 'FormEstadoWf',
                    {
                        config: [{
                            event: 'beforesave',
                            delegate: this.onSaveWizard,
                        }],
                        scope: this
                    }
                );
            }

        },

        onSaveWizard: function (wizard, resp) {
            Phx.CP.loadingShow();
            console.log(resp);
            Ext.Ajax.request({
                url: '../../sis_reclamo/control/Respuesta/siguienteEstadoRespuesta',
                params: {

                    id_proceso_wf_act: resp.id_proceso_wf_act,
                    id_estado_wf_act: resp.id_estado_wf_act,
                    id_tipo_estado: resp.id_tipo_estado,
                    id_funcionario_wf: resp.id_funcionario_wf,
                    id_depto_wf: resp.id_depto_wf,
                    obs: resp.obs,
                    json_procesos: Ext.util.JSON.encode(resp.procesos)
                },
                success: this.successWizard,
                failure: this.conexionFailure,
                argument: {wizard: wizard},
                timeout: this.timeout,
                scope: this
            });
        },

        successWizard: function (resp) {
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            var estado = reg.ROOT.datos.v_codigo_estado_siguiente;
            if (estado == 'respuesta_enviada') {
                Phx.CP.getPagina(this.idContenedorPadre).reload();
            }
            if (estado == 'vobo_respuesta' || estado == 'respuesta_aprobada') {
                this.reload();
            }

            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },

        onButtonNew: function () {

            Phx.vista.Respuesta.superclass.onButtonNew.call(this);
            this.momento = 'new';
            this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
            //mandamos al componente los datos para cargar al cliente.
            this.Cmp.correos.tdata = {maestro: this.maestro};

            var fecha = this.sumarDias(new Date(), parseInt(this.maestro.tiempo_respuesta));
            this.Cmp.fecha_respuesta.setValue(new Date());
            this.Cmp.asunto.setValue('Respuesta a Reclamo');
            this.Cmp.recomendaciones.setValue('Ninguna.');

            this.Cmp.correos.setValue(this.maestro.email+','+this.maestro.email2);

            this.Cmp.destinatario.setValue(this.maestro.desc_nom_cliente);
            //this.Cmp.correo_cli.setValue(this.maestro.email);
            this.generarCite();

        },



        onButtonEdit: function () {
            Phx.vista.Respuesta.superclass.onButtonEdit.call(this);

            this.Cmp.correos.setValue(this.maestro.email+','+this.maestro.email2);

            this.Cmp.destinatario.setValue(this.maestro.desc_nom_cliente);
            this.momento = 'edit';
            this.Cmp.id_reclamo.setValue(this.maestro.id_reclamo);
            //mandamos al componente los datos para cargar al cliente.
            this.Cmp.correos.tdata = {maestro: this.maestro};
        },

        generarCite: function () {
            ///^([0-9])+(\/)+(\-)?(\.)?([0-9a-zA-Z])+$/
            if (/^([0-9])+[(\/)(\-)(\.)]*([0-9a-zA-Z])+$/.test(this.Cmp.nro_cite.getValue())) {
                this.Cmp.nro_cite.setValue(this.Cmp.nro_cite.getValue());
            } else {
                Ext.Ajax.request({
                    url: '../../sis_reclamo/control/Respuesta/getCite',
                    params: {num_cite: this.Cmp.nro_cite.getValue()},
                    success: function (resp) {
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        this.Cmp.nro_cite.setValue(reg.ROOT.datos.v_cite);
                    },
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }
        },

        onButtonDel: function () {
            Phx.vista.Respuesta.superclass.onButtonDel.call(this);
            this.argumentExtraSubmit.id_reclamo = this.maestro.id_reclamo;
        },
        dia: ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'],
        sumarDias: function (fecha, dias) {
            console.log(dias + ' DIA ' + this.dia[fecha.getDay()]);
            if (this.maestro.tiempo_respuesta == 10) {
                fecha.setDate(fecha.getDate() + dias + 4);
            } else if ((this.dia[fecha.getDay()] == 'lunes' || this.dia[fecha.getDay()] == 'martes' || this.dia[fecha.getDay()] == 'miercoles') && this.maestro.tiempo_respuesta == 7) {
                fecha.setDate(fecha.getDate() + dias + 2);
            } else if ((this.dia[fecha.getDay()] == 'jueves' || this.dia[fecha.getDay()] == 'viernes') && this.maestro.tiempo_respuesta == 7) {
                fecha.setDate(fecha.getDate() + dias + 4);

            }
            return fecha;
        }

    });
</script>
