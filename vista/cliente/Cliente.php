<?php
/**
 * @package pXP
 * @file gen-Cliente.php
 * @author  (admin)
 * @date 12-08-2016 14:29:16
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Cliente = Ext.extend(Phx.gridInterfaz, {
            momento: '',// permite saber si se preciona en el boton Nuevo, Editar.
            constructor: function (config) {
                this.maestro = config.maestro;
                //llama al constructor de la clase padre
                this.fwidth = '55%';
                this.fheight = '65%';
                Phx.vista.Cliente.superclass.constructor.call(this, config);
                this.init();
                this.load({params: {start: 0, limit: this.tam_pag}})
            },

            Atributos: [
                {
                    //configuracion del componente
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'id_cliente'
                    },
                    type: 'Field',
                    id_grupo: 1,
                    form: true
                },

                {
                    config: {
                        name: 'nombre_completo2',
                        fieldLabel: 'Nombre Completo',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 300,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'c.nombre_completo2', type: 'string'},
                    id_grupo: 1,
                    bottom_filter: true,
                    grid: true,
                    form: false
                },
                {
                    config: {
                        name: 'nombre',
                        fieldLabel: 'Nombre',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 150,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.nombre', type: 'string'},
                    id_grupo: 1,
                    bottom_filter: true,
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'apellido_paterno',
                        fieldLabel: 'Primer Apellido',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 150,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.apellido_paterno', type: 'string'},
                    id_grupo: 1,
                    bottom_filter: true,
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'apellido_materno',
                        fieldLabel: 'Segundo Apellido',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.apellido_materno', type: 'string'},
                    id_grupo: 1,
                    bottom_filter: true,
                    grid: false,
                    form: true
                },
                {
                    config: {
                        name: 'genero',
                        fieldLabel: 'Genero',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 10,
                        typeAhead: true,
                        forceSelection: true,
                        triggerAction: 'all',
                        mode: 'local',
                        store: ['VARON', 'MUJER', 'OTROS']
                    },
                    type: 'ComboBox',
                    filters: {pfiltro: 'cli.genero', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'ci',
                        fieldLabel: 'Nro. Doc. Identificación',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 15
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.ci', type: 'string'},
                    id_grupo: 1,
                    bottom_filter: true,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'lugar_expedicion',
                        fieldLabel: 'Lugar de Expedición',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 10,
                        typeAhead: true,
                        forceSelection: true,
                        triggerAction: 'all',
                        mode: 'local',
                        store: ['CB', 'SC', 'LP', 'BN', 'CJ', 'TJ', 'OR', 'PT', 'CH', 'OTRO']
                    },
                    type: 'ComboBox',
                    filters: {pfiltro: 'cli.lugar_expedicion', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'nacionalidad',
                        fieldLabel: 'Nacionalidad',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.nacionalidad', type: 'string'},
                    id_grupo: 1,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'celular',
                        fieldLabel: 'Celular',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 70,
                        qtip: 'Puede ingresar mas de un No. de Celular separado por -, o / Ej: xxxxxxxxxx/xxxxxxxxx, yyyyyyyy-yyyyyyyy'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.celular', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'telefono',
                        fieldLabel: 'Telefono',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 70,
                        qtip: 'Puede ingresar mas de un No. de Telefono separado por -, o / Ej: xxxxxxxxxx/xxxxxxxxx, yyyyyyyy-yyyyyyyy'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.telefono', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'email',
                        fieldLabel: 'Email',
                        vtype: 'email',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 50
                    },
                    type: 'TextField',
                    bottom_filter: true,
                    filters: {pfiltro: 'cli.email', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'email2',
                        fieldLabel: 'Email 2',
                        vtype: 'email',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 50
                    },
                    type: 'TextField',
                    bottom_filter: true,
                    filters: {pfiltro: 'cli.email2', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'direccion',
                        fieldLabel: 'Direccion,   (Calle/Av./No.)',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 200
                    },
                    type: 'TextArea',
                    filters: {pfiltro: 'cli.direccion', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'id_pais_residencia',
                        fieldLabel: 'Pais de Residencia',
                        allowBlank: false,
                        emptyText: 'Elija una opcion...',
                        resizable: true,
                        store: new Ext.data.JsonStore({
                            url: '../../sis_parametros/control/Lugar/listarLugar',//../../sis_reclamo/control/Cliente/listarPais
                            id: 'id_lugar',
                            root: 'datos',
                            sortInfo: {
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_lugar', 'nombre'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'lug.nombre', tipo: 'pais'}
                        }),
                        valueField: 'id_lugar',
                        displayField: 'nombre',
                        gdisplayField: 'pais_residencia',
                        hiddenName: 'id_pais_residencia',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        lazyRender: true,
                        queryMode: 'remote',
                        pageSize: 20,
                        queryDelay: 250,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 30,
                        style: 'text-transform:uppercase;',
                        /*turl:'../../../sis_parametros/vista/lugar/Lugar.php',
                        ttitle:'Lugar',
                        // tconfig:{width:1800,height:500},
                        tdata:{},
                        tcls:'Lugar',*/
                        renderer: function (value, p, record) {
                            return String.format('{0}', record.data['pais_residencia']);
                        }
                    },
                    //type:'TrigguerCombo',
                    type: 'ComboBox',
                    bottom_filter: false,
                    filters: {pfiltro: 'lug.nombre', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'ciudad_residencia',
                        fieldLabel: 'Ciudad de Residencia',
                        allowBlank: false,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 30,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.ciudad_residencia', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
                },
                {
                    config: {
                        name: 'barrio_zona',
                        fieldLabel: 'Zona/Barrio',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        maxLength: 200,
                        style: 'text-transform:uppercase;'
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.barrio_zona', type: 'string'},
                    id_grupo: 2,
                    grid: true,
                    form: true
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
                    filters: {pfiltro: 'cli.estado_reg', type: 'string'},
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
                    filters: {pfiltro: 'cli.id_usuario_ai', type: 'numeric'},
                    id_grupo: 1,
                    grid: false,
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
                    filters: {pfiltro: 'cli.fecha_reg', type: 'date'},
                    id_grupo: 1,
                    grid: false,
                    form: false
                },
                {
                    config: {
                        name: 'usuario_ai',
                        fieldLabel: 'Funcionario AI',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100,
                        maxLength: 300
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'cli.usuario_ai', type: 'string'},
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
                    grid: false,
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
                    filters: {pfiltro: 'cli.fecha_mod', type: 'date'},
                    id_grupo: 1,
                    grid: false,
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
                    grid: false,
                    form: false
                }
            ],

            rowExpander: new Ext.ux.grid.RowExpander({
                tpl: new Ext.Template(
                    '<br>', '<h1 style="text-align: left">DATOS DE CONTACTO</h1>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Telefono Celular:&nbsp;&nbsp;</b> {celular}, <b>Telefono Fijo:</b> {telefono}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Correo:&nbsp;&nbsp;</b> {email}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Correo2:&nbsp;&nbsp;</b> {email2}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Direccion:&nbsp;&nbsp;</b> {direccion}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Ciudad de Residencia:&nbsp;&nbsp;</b> {ciudad_residencia}, <b>Pais de Residencia:</b> {pais_residencia}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Barrio/Zona:&nbsp;&nbsp;</b> {barrio_zona}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha de Registro:&nbsp;&nbsp;</b> {fecha_reg:date("d/m/Y")}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha Ult. Modificación:&nbsp;&nbsp;</b> {fecha_mod:date("d/m/Y")}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Creado por:&nbsp;&nbsp;</b> {usr_reg}</p>',
                    '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Modificado por:&nbsp;&nbsp;</b> {usr_mod}</p><br>'
                )
            }),
            Grupos: [
                {
                    layout: 'column',
                    border: false,
                    defaults: {
                        border: false
                    },
                    items: [{
                        bodyStyle: 'padding-right:5px;',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Personales',
                            autoHeight: true,
                            items: [],
                            id_grupo: 1
                        }]
                    }, {
                        bodyStyle: 'padding-left:5px;',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos de Contacto',
                            autoHeight: true,
                            items: [],
                            id_grupo: 2
                        }]
                    }]
                }
            ],
            arrayDefaultColumHidden: [
                'celular', 'telefono', 'email', 'email2', 'direccion',
                'id_pais_residencia', 'ciudad_residencia', 'barrio_zona'
            ],
            tam_pag: 50,
            title: 'cliente',
            ActSave: '../../sis_reclamo/control/Cliente/insertarCliente',
            ActDel: '../../sis_reclamo/control/Cliente/eliminarCliente',
            ActList: '../../sis_reclamo/control/Cliente/listarCliente',
            id_store: 'id_cliente',
            fields: [
                {name: 'id_cliente', type: 'numeric'},
                {name: 'genero', type: 'string'},
                {name: 'ci', type: 'string'},
                {name: 'email', type: 'string'},
                {name: 'email2', type: 'string'},
                {name: 'direccion', type: 'string'},
                {name: 'celular', type: 'string'},
                {name: 'nombre', type: 'string'},
                {name: 'lugar_expedicion', type: 'string'},
                {name: 'apellido_paterno', type: 'string'},
                {name: 'telefono', type: 'string'},
                {name: 'ciudad_residencia', type: 'string'},
                {name: 'id_pais_residencia', type: 'numeric'},
                {name: 'nacionalidad', type: 'string'},
                {name: 'barrio_zona', type: 'string'},
                {name: 'estado_reg', type: 'string'},
                {name: 'apellido_materno', type: 'string'},
                {name: 'id_usuario_ai', type: 'numeric'},
                {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'usuario_ai', type: 'string'},
                {name: 'id_usuario_reg', type: 'numeric'},
                {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'id_usuario_mod', type: 'numeric'},
                {name: 'usr_reg', type: 'string'},
                {name: 'usr_mod', type: 'string'},

                {name: 'pais_residencia', type: 'string'},
                {name: 'nombre_completo2', type: 'string'},

                {name: 'nombre', type: 'string'}


            ],
            sortInfo: {
                field: 'id_cliente',
                direction: 'ASC'
            },
            bdel: true,
            bsave: false,

            onButtonNew: function () {
                Phx.vista.Cliente.superclass.onButtonNew.call(this);
                this.momento = 'new';
            },

            onButtonEdit: function () {
                Phx.vista.Cliente.superclass.onButtonEdit.call(this);
                this.momento = 'edit';
            },
            /*
            successSave:function (resp) {

                Phx.vista.Cliente.superclass.successSave.call(this,resp);
                var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                console.log(objRes);
                Phx.CP.getPagina('docs-PENRES').reload();
                if(objRes.ROOT.datos.v_momento == 'new'){
                    this.sigEstado2(objRes.ROOT.datos.v_id_estado_wf, objRes.ROOT.datos.v_id_proceso_wf);
                }else{
                    console.log('momento: '+objRes.ROOT.datos.v_momento);
                }
            },*/
            onSubmit: function (o, x, force) {
                //Phx.vista.Cliente.superclass.onSubmit.call(this, o);
                if (this.momento == 'edit') {
                    console.log(this.momento);
                    Phx.vista.Cliente.superclass.onSubmit.call(this, o);
                } else if (this.momento == 'new') {
                    console.log(this.momento);
                    Ext.Ajax.request({
                        url: '../../sis_reclamo/control/Cliente/validarCliente',
                        params: {
                            nombre: this.Cmp.nombre.getValue(),
                            apellido: this.Cmp.apellido_paterno.getValue(),
                            genero: this.Cmp.genero.getValue(),
                            ci: this.Cmp.ci.getValue()
                        },
                        argument: {},
                        success: function (resp) {
                            var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
                            //console.log('EXISTE:',reg.ROOT.datos.v_valid);
                            if (reg.ROOT.datos.v_valid == 'true') {
                                Ext.Msg.alert('Alerta', 'El cliente <b>' + (this.Cmp.nombre.getValue()).toUpperCase() + ' ' + (this.Cmp.apellido_paterno.getValue()).toUpperCase() + '</b> con Documento N° <b>' + this.Cmp.ci.getValue() + '</b> anteriormente ya fue registrado en la BD del ERP por el funcionari@ <b>' + reg.ROOT.datos.v_desc_func) + '</b>';
                            }
                            else {
                                this.Cmp.nombre.setValue((this.Cmp.nombre.getValue()).trim());
                                this.Cmp.apellido_paterno.setValue((this.Cmp.apellido_paterno.getValue()).trim());
                                this.Cmp.apellido_materno.setValue((this.Cmp.apellido_materno.getValue()).trim());
                                Phx.vista.Cliente.superclass.onSubmit.call(this, o);
                            }

                        },
                        failure: this.conexionFailure,
                        timeout: this.timeout,
                        scope: this
                    });
                }

            }


        }
    )
</script>

		