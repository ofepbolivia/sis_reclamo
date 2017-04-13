<?php
/**
 *@package pXP
 *@file    FormFiltro.php
 *@author  Grover Velasquez Colque
 *@date    30-10-2016
 *@description permite filtrar varios campos antes de mostrar el contenido de una grilla
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.FormFiltros=Ext.extend(Phx.frmInterfaz,{

        nombreVista: 'FormGlobal',
        constructor:function(config)
        {
            this.panelResumen = new Ext.Panel({html:''});
            this.Grupos = [{

                xtype: 'fieldset',
                border: false,
                autoScroll: true,
                layout: 'form',
                items: [],
                id_grupo: 0
                /*width: 500,
                height:1000*/

            }/*,
                this.panelResumen*/
            ];

            Phx.vista.FormFiltros.superclass.constructor.call(this,config);
            //this.store.baseParams={tipo_interfaz:'FormGlobal'};
            this.init();
            this.Cmp.id_subtipo_incidente.disable();
            this.iniciarEventos();



        },

        Atributos:[
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'tipo_interfaz',
                    value:'filtros'
                },
                type: 'Field',
                form: true,
                id_grupo:1
            },
            {
                config:{
                    name : 'id_gestion',
                    origen : 'GESTION',
                    fieldLabel : 'Gestion',
                    allowBlank : true,
                    width: 150
                },
                type : 'ComboRec',
                id_grupo : 0,
                form : true
            },
            {
                config:{
                    name: 'desde',
                    fieldLabel: 'Desde',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            {
                config:{
                    name: 'hasta',
                    fieldLabel: 'Hasta',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'nro_tramite',
                    allowBlank: true,
                    fieldLabel: 'Nro. de Reclamo',
                    width: 150
                },
                type: 'Field',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'id_oficina_registro_incidente',
                    fieldLabel: 'Oficina',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Oficina/listarOficina',
                        id: 'id_oficina',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_oficina', 'nombre', 'codigo','nombre_lugar'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'ofi.nombre#ofi.codigo#lug.nombre'}
                    }),
                    valueField: 'id_oficina',
                    displayField: 'nombre',
                    gdisplayField: 'desc_oficina_registro_incidente',
                    hiddenName: 'id_oficina',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    anchor: '70%',
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_oficina_registro_incidente']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 4,
                filters: {pfiltro: 'ofi.nombre#ofi.codigo#lug.nombre', type: 'string'},
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_tipo_incidente',
                    fieldLabel: 'Tipo de Incidente',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
                        id: 'id_tipo_incidente',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre_incidente',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_incidente', 'nombre_incidente','fk_tipo_incidente'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'tip.nombre_incidente', nivel:'1', fk_tipo_incidente:'1'}
                    }),
                    valueField: 'id_tipo_incidente',
                    displayField: 'nombre_incidente',
                    gdisplayField: 'desc_nombre_incidente',
                    hiddenName: 'id_tipo_incidente',
                    forceSelection: true,
                    typeAhead: false,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '70%',
                    /*width: 200,*/
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_nombre_incidente']);
                    }
                },
                type: 'ComboBox',
                bottom_filter:true,
                id_grupo: 3,
                filters: {pfiltro: 'tip.nombre_incidente', type: 'string'},
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_subtipo_incidente',
                    fieldLabel: 'Subtipo de Incidente',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/TipoIncidente/listarTipoIncidente',
                        id: 'id_tipo_incidente',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre_incidente',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_incidente', 'nombre_incidente'],
                        remoteSort: true/*,
                         baseParams: {par_filtro: 'rti.nombre_incidente',  fk_tipo_incidente:'id_tipo_incidente'}*/

                    }),
                    valueField: 'id_tipo_incidente',
                    displayField: 'nombre_incidente',
                    gdisplayField: 'desc_sudnom_incidente',
                    hiddenName: 'id_subtipo_incidente',
                    forceSelection: true,
                    typeAhead: false,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '70%',
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_sudnom_incidente']);
                    }
                },
                type: 'ComboBox',
                bottom_filter:true,
                id_grupo: 3,
                filters: {pfiltro: 't.nombre_incidente', type: 'string'},
                grid: true,
                form: true
            }
        ],
        labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
        east: {
            url: '../../../sis_reclamo/vista/reporte/CRMGlobal.php',
            title: 'Detalle Filtro',
            width: '70%',
            cls: 'CRMGlobal'
        },



        title: 'Filtros Para el Reporte de Reclamos',
        // Funcion guardar del formulario
        onSubmit: function(o) {
            var me = this;
            if (me.form.getForm().isValid()) {

                var parametros = me.getValForm()

                console.log('parametros ....', parametros);

                this.onEnablePanel(this.idContenedor + '-east', parametros)
            }
            this.Cmp.id_subtipo_incidente.disable();
        },
        
        iniciarEventos:function(){
            this.Cmp.id_gestion.on('select', function(cmb, rec, ind){

                //Ext.apply(this.Cmp.id_cuenta.store.baseParams,{id_gestion: rec.data.id_gestion})
                //Ext.apply(this.Cmp.id_partida.store.baseParams,{id_gestion: rec.data.id_gestion})
                //Ext.apply(this.Cmp.id_centro_costo.store.baseParams,{id_gestion: rec.data.id_gestion})
                console.log('cargando Filtrado...');

            },this);

            this.Cmp.id_tipo_incidente.on('select', function (cmb, record, index) {
                this.Cmp.id_subtipo_incidente.reset();
                this.Cmp.id_subtipo_incidente.modificado = true;
                this.Cmp.id_subtipo_incidente.setDisabled(false);
                this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente', record.data.id_tipo_incidente);

            }, this);
        }
    })
</script>