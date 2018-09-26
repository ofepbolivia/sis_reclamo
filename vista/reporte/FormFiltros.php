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
        constructor:function(config){
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
            this.Cmp.id_subtipo_incidente.enable();
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
                id_grupo:0
            },
            {
                config:{
                    name : 'id_gestion',
                    origen : 'GESTION',
                    fieldLabel : 'Gestion',
                    allowBlank : true,
                    anchor: '95%',
                    /*width: 242,
                    listWidth:'10%'*/
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
                    width: 176
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
                    width: 176
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            /*{
                config: {
                    name: 'nro_tramite',
                    allowBlank: true,
                    fieldLabel: 'Nro. de Reclamo',
                    width: 150
                },
                type: 'Field',
                id_grupo: 0,
                form: true
            },*/
            {
                config: {
                    name: 'id_oficina_registro_incidente',
                    fieldLabel: 'Oficina Reclamo',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Oficina/listarOficina',
                        id: 'id_oficina',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'DESC'
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
                    anchor: '95%',
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    //width: 227,
                    //listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_oficina_registro_incidente']);
                    },
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Código: {codigo}</b></p>',
                        '</div><p><b>Nombre: </b> <span style="color: green;">{nombre}</span></p>',
                        '</div></tpl>'
                    ]),
                    enableMultiSelect: true
                },
                type: 'AwesomeCombo',
                id_grupo: 0,
                filters: {pfiltro: 'ofi.nombre#ofi.codigo#lug.nombre', type: 'string'},
                grid: true,
                form: true
            },
            {
                config : {
                    name : 'oficina',
                    fieldLabel : 'Estación',
                    allowBlank : true,
                    emptyText : 'Estación...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/Lugar/listarLugar',
                        id: 'id_lugar',
                        root: 'datos',
                        fields: ['id_lugar','codigo','nombre'],
                        totalProperty: 'total',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        baseParams:{par_filtro:'lug.codigo#lug.nombre', es_regional: 'si', _adicionar:'si'}
                    }),
                    //tpl : '<tpl for="."><div class="x-combo-list-item"><p style="color: green;">Código: {codigo}</p><p>Nombre: {nombre}</p></div></tpl>',
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Código: {codigo}</b></p>',
                        '</div><p><b>Nombre: </b> <span style="color: green;">{nombre}</span></p>',
                        '</div></tpl>'
                    ]),
                    valueField: 'id_lugar',
                    displayField: 'nombre',
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    minChars: 2,
                    anchor : '95%',
                    enableMultiSelect: true
                },

                type : 'AwesomeCombo',
                id_grupo : 0,
                grid : true,
                form : true
            },
		{
			config: {
				name: 'id_oficina_incidente',
				fieldLabel: 'Ambiente del Incidente',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					//url: '../../sis_reclamo/control/Reclamo/listarOficinas',
					url: '../../sis_reclamo/control/OficinaReclamo/listarOficina',
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
				gdisplayField: 'desc_nombre_oficina',
				hiddenName: 'id_oficina_incidente',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 10,
				queryDelay: 1000,
				anchor: '95%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				//listWidth:'263',
                tpl: new Ext.XTemplate([
                    '<tpl for=".">',
                    '<div class="x-combo-list-item">',
                    '<div class="awesomecombo-item {checked}">',
                    '<p><b>Código: {codigo}</b></p>',
                    '</div><p><b>Nombre: </b> <span style="color: green;">{nombre}</span></p>',
                    '</div></tpl>'
                ]),
				renderer: function (value, p, record) {

					return String.format('{0}', record.data['desc_nombre_oficina']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'ofi.nombre#ofi.codigo#lug.nombre', type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_medio_reclamo',
				fieldLabel: 'Medio Reclamo',
				allowBlank: true,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_reclamo/control/MedioReclamo/listarMedioReclamo',
					id: 'id_medio_reclamo',
					root: 'datos',
					sortInfo: {
						field: 'orden',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_medio_reclamo', 'nombre_medio'],
					remoteSort: true,
					baseParams: {par_filtro: 'med.nombre_medio'}
				}),
                tpl: new Ext.XTemplate([
                     '<tpl for=".">',
                     '<div class="x-combo-list-item">',
                     '<div class="awesomecombo-item {checked}">',
                     '<p><b>{nombre_medio}</b></p>',
                     '</div></div></tpl>'
                ]),
                enableMultiSelect: true,				
				valueField: 'id_medio_reclamo',
				displayField: 'nombre_medio',
				gdisplayField: 'desc_nombre_medio',
				hiddenName: 'id_medio_reclamo',
				forceSelection: true,
				typeAhead: false,
				editable: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '95%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				//listWidth:'240',
				renderer: function (value, p, record) {
					return String.format('{0}', record.data['desc_nombre_medio']);
				}
			},
			type: 'AwesomeCombo',
			id_grupo: 0,
			filters: {pfiltro: 'med.nombre_medio', type: 'string'},
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
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>{nombre_incidente}</b></p>',
                        '</div></div></tpl>'
                    ]),
                    enableMultiSelect: true,                                  
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
                    anchor: '95%',
                    /*width: 200,*/
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    //listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_nombre_incidente']);
                    }
                 
                },
                type: 'AwesomeCombo',
                bottom_filter:true,
                id_grupo: 0,
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
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>{nombre_incidente}</b></p>',
                        '</div></div></tpl>'
                    ]),
                    enableMultiSelect: true,                      
                    valueField: 'id_tipo_incidente',
                    displayField: 'nombre_incidente',
                    gdisplayField: 'desc_sudnom_incidente',
                    hiddenName: 'id_subtipo_incidente',
                    forceSelection: true,
                    typeAhead: false,
                    //editable: true,
                    //msgTarget: 'side',
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '95%',
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    disabled: true,
                    //listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_sudnom_incidente']);
                    }
                },
                type: 'AwesomeCombo',
                bottom_filter:true,
                id_grupo: 0,
                filters: {pfiltro: 't.nombre_incidente', type: 'string'},
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'origen',
                    fieldLabel: 'Ciudad Origen',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,
                    maxLength: 25,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:[ 'BCN', 'BUE', 'BYC', 'CBB', 'CCA',  'LPB', 'CIJ', 'MAD', 'MIA', 'ORU', 'POI', 'RIB', 'RBQ', 'SAO', 'SLA', 'S.RE', 'SRZ', 'TDD', 'TJA', 'UYU'],
                    style:'text-transform:uppercase;'
                },
                type: 'ComboBox',
                filters: {pfiltro: 'rec.origen', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: true
            },{
                config: {
                    name: 'transito',
                    fieldLabel: 'Ciudad de Transito',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,
                    maxLength: 25,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:['BCN', 'BUE', 'BYC', 'CBB', 'CCA',  'LPB', 'CIJ', 'MAD', 'MIA', 'ORU', 'POI', 'RIB', 'RBQ', 'SAO', 'SLA', 'S.RE', 'SRZ', 'TDD', 'TJA', 'UYU'],
                    style:'text-transform:uppercase;'
                },
                type: 'ComboBox',
                filters: {pfiltro: 'rec.transito', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'destino',
                    fieldLabel: 'Ciudad Destino',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,
                    maxLength: 25,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:['BCN', 'BUE', 'BYC', 'CBB', 'CCA',  'LPB', 'CIJ', 'MAD', 'MIA', 'ORU', 'POI', 'RIB', 'RBQ', 'SAO', 'SLA', 'S.RE', 'SRZ', 'TDD', 'TJA', 'UYU'],
                    style:'text-transform:uppercase;'

                },
                type: 'ComboBox',
                filters: {pfiltro: 'rec.destino', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: true
            }
        ],
        labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
        east: {
            url: '../../../sis_reclamo/vista/reporte/CRMGlobal.php',
            title: 'Detalle Filtro',
            width: '65%',
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
            this.Cmp.id_subtipo_incidente.enable();
        },

        generarEstadisticas: function(){
            console.log('generarEstadisticas');
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
                this.Cmp.id_subtipo_incidente.store.setBaseParam('fk_tipo_incidente',record.data.id_tipo_incidente);                                                                          
            }, this);
        }
    })
</script>