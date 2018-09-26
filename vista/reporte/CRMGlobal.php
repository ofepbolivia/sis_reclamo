<?php
/**
 *@package pXP
 *@file gReclamo.php
 *@author  Franklin Espinoza Alvarez
 *@date 10-08-2016 17:32:59
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema Reclamos
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CRMGlobal=Ext.extend(Phx.gridInterfaz, {

        nombreVista: 'CRMGlobal',
        constructor: function (config) {
            this.idContenedor = config.idContenedor;
            this.maestro = config.maestro;
            console.log('this.maestro', config);
            //llama al constructor de la clase padre
            Phx.vista.CRMGlobal.superclass.constructor.call(this, config);
            this.grid.getTopToolbar().disable();
            this.grid.getBottomToolbar().disable();
            this.store.baseParams={tipo_interfaz:this.nombreVista};

            this.addButton('rep_estadistico', {
                text: 'Rep. Estadistico',
                iconCls: 'bprint_good',
                disabled: false,
                handler: this.repEstadistico,
                tooltip: '<b>Imprimir Reporte</b><br>Genera reporte estadístico del Filtro aplicado.'
            });

            this.init();
        },

        repEstadistico : function () {
            //console.log(Phx.CP.getPagina(this.idContenedorPadre).generarEstadisticas());

            console.log('estadisticas', this.parametros);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/reporteEstadistico',
                params:{
                    desde: this.parametros.desde,
                    destino: this.parametros.destino,
                    hasta: this.parametros.hasta,
                    id_gestion: this.parametros.id_gestion,
                    id_medio_reclamo: this.parametros.id_medio_reclamo,
                    id_oficina_incidente: this.parametros.id_oficina_incidente,
                    id_oficina_registro_incidente: this.parametros.id_oficina_registro_incidente,
                    id_subtipo_incidente: this.parametros.id_subtipo_incidente,
                    id_tipo_incidente: this.parametros.id_tipo_incidente,
                    oficina: this.parametros.oficina,
                    origen: this.parametros.origen,
                    tipo: "reporte",
                    transito: this.parametros.transito
                },
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },

        Atributos: [
            {
                config:{
                    name:'id_cliente',
                    fieldLabel:'Cliente',
                    allowBlank:false,
                    emptyText:'Elija una opción...',
                    dato: 'reclamo',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/Cliente/listarCliente',
                        id: 'id_cliente',
                        root: 'datos',
                        sortInfo:{
                            field: 'nombre_completo2',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_cliente','nombre_completo2','nombre_completo1','ci','email'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams:{par_filtro:'c.nombre_completo2'}
                    }),
                    valueField: 'id_cliente',
                    displayField: 'nombre_completo2',
                    gdisplayField:'desc_nom_cliente',//mapea al store del grid
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{nombre_completo2}</p><p>CI:{ci}</p><p style= "color : green;" >email:{email}</p></div></tpl>',
                    hiddenName: 'id_cliente',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:10,
                    queryDelay:1000,
                    width:250,
                    gwidth:280,
                    minChars:1,
                    turl:'../../../sis_reclamo/vista/cliente/FormCliente.php',
                    ttitle:'Clientes',
                    tconfig:{width: '35%' ,height:'95%'},
                    tdata:{},
                    tcls:'FormCliente',
                    pid:this.idContenedor,

                    renderer:function (value, p, record){return String.format('{0}', record.data['desc_nom_cliente']);}
                },
                type:'TrigguerCombo',
                bottom_filter:true,
                id_grupo:1,
                filters:{
                    pfiltro:'c.nombre_completo2',
                    type:'string'
                },

                grid:true,
                form:false
            },
            {
                config:{
                    name: 'genero',
                    fieldLabel: 'Genero',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:50,
                    style:'text-transform:uppercase;'
                },
                type:'TextField',
                filters:{pfiltro:'c.genero',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'ci',
                    fieldLabel: 'Carnet de Identidad',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:15
                },
                type:'TextField',
                filters:{pfiltro:'c.ci',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:false
            },
            {
                config: {
                    name: 'pnr',
                    fieldLabel: 'P.N.R.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 50,
                    maxLength: 4
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.pnr', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'telefono',
                    fieldLabel: 'Telefono',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:20
                },
                type:'TextField',
                filters:{pfiltro:'c.telefono',type:'string'},
                id_grupo:2,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'email',
                    fieldLabel: 'Email',
                    vtype:'email',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:50
                },
                type:'TextField',
                filters:{pfiltro:'c.email',type:'string'},
                id_grupo:2,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'ciudad_residencia',
                    fieldLabel: 'Ciudad de Residencia',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 110,
                    maxLength:30,
                    style:'text-transform:uppercase;'
                },
                type:'TextField',
                filters:{pfiltro:'c.ciudad_residencia',type:'string'},
                id_grupo:2,
                grid:true,
                form:false
            },
            {
                config: {
                    name: 'fecha_recepcion_sac',
                    fieldLabel: 'Fecha Recepcion SAC',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 150,
                    gdisplayField: 'fecha_recepcion_sac',
                    format: 'd/m/Y H:i',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i A') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'rec.fecha_recepcion_sac', type: 'date'},
                id_grupo: 4,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_hora_recepcion',
                    fieldLabel: 'Fecha de Recepción',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 120,
                    gdisplayField: 'fecha_hora_recepcion',
                    format: 'd/m/Y H:i',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i A') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'rec.fecha_hora_recepcion', type: 'date'},
                id_grupo: 4,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_hora_incidente',
                    fieldLabel: 'Fecha de Incidente',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 120,
                    format: 'd/m/Y H:i',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i A') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'rec.fecha_hora_incidente', type: 'date'},
                id_grupo: 2,
                grid: true,
                form: false
            },
		{
			config: {
				name: 'id_medio_reclamo',
				fieldLabel: 'Medio Reclamo',
				allowBlank: false,
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
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				resizable:true,
				listWidth:'240',
				renderer: function (value, p, record) {
					console.log('datosss',record.data['desc_nombre_medio']);
					return String.format('{0}', record.data['desc_nombre_medio']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'med.nombre_medio', type: 'string'},
			grid: true,
			form: true
		},            
            {
                config: {
                    name: 'id_oficina_registro_incidente',
                    fieldLabel: 'Ciudad Reclamo',
                    allowBlank: false,
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
                    anchor: '100%',
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
                form: false
            },
            {
                config: {
                    name: 'id_oficina_registro_incidente',
                    fieldLabel: 'Lugar del Reclamo',
                    allowBlank: false,
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
                    anchor: '100%',
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
                form: false
            },
            {
                config: {
                    name: 'id_oficina_incidente',
                    fieldLabel: 'Incidente Sucedio en...',
                    allowBlank: false,
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
                    gdisplayField: 'desc_nombre_oficina',
                    hiddenName: 'id_oficina_incidente',
                    forceSelection: true,
                    typeAhead: false,
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

                        return String.format('{0}', record.data['desc_nombre_oficina']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 3,
                filters: {pfiltro: 'ofi.nombre#ofi.codigo#lug.nombre', type: 'string'},
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_frd',
                    fieldLabel: 'Nro. FRD',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 20
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.nro_frd', type: 'string'},
                id_grupo: 0,
                bottom_filter:true,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'correlativo_preimpreso_frd',
                    fieldLabel: 'Nro. Preimpreso FRD',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25
                },
                type: 'NumberField',
                filters: {pfiltro: 'rec.correlativo_preimpreso_frd', type: 'numeric'},
                id_grupo: 0,
                bottom_filter:true,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_ripat_att',
                    fieldLabel: 'Nro. RIPAT Att',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 6
                },
                type: 'NumberField',
                filters: {pfiltro: 'rec.nro_ripat_att', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_hoja_ruta',
                    fieldLabel: 'Nro. Hoja Ruta',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'rec.nro_hoja_ruta', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_tipo_incidente',
                    fieldLabel: 'Tipo de Incidente',
                    allowBlank: false,
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
                    anchor: '100%',
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
                form: false
            },
            {
                config: {
                    name: 'id_subtipo_incidente',
                    fieldLabel: 'Subtipo de Incidente',
                    allowBlank: false,
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
                    anchor: '100%',
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
                form: false
            },
            {
                config: {
                    name: 'nro_vuelo',
                    fieldLabel: 'Nro. Vuelo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25,
                    style:'text-transform:uppercase;'
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.nro_vuelo', type: 'string'},
                bottom_filter: true,
                id_grupo: 2,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'origen',
                    fieldLabel: 'Origen',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25,
                    style:'text-transform:uppercase;'
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.origen', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: false
            },{
                config: {
                    name: 'transito',
                    fieldLabel: 'TTO',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25,
                    style:'text-transform:uppercase;'
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.transito', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'destino',
                    fieldLabel: 'Destino',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 25,
                    style:'text-transform:uppercase;'
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.destino', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_funcionario_recepcion',
                    fieldLabel: 'Funcionario que recibe Reclamo',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1#FUNCAR.nombre_cargo'}
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    gdisplayField: 'desc_nombre_funcionario',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    hiddenName: 'id_funcionario_recepcion',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    anchor: '100%',
                    width: 260,
                    gwidth: 200,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_nombre_funcionario']);
                    }
                },
                type: 'ComboBox',
                bottom_filter:true,
                id_grupo: 4,
                filters:{
                    pfiltro:'fun.desc_funcionario1#FUNCAR.nombre_cargo',
                    type:'string'
                },
                grid: true,
                form: false
            },
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
                filters: {pfiltro: 'rec.estado', type: 'string'},
                /*id_grupo: 1,*/
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_cite',
                    fieldLabel: 'Nro. de Cite',
                    allowBlank: false,
                    /*regex: '/[A-Z]/',
                     regexText: "<b>Error</b></br>Invalid Number entered.",*/
                    anchor: '40%',
                    gwidth: 150,
                    maxLength: 50,
                    style:'text-transform:uppercase;'

                },
                type: 'TextField',
                filters: {pfiltro: 'res.nro_cite', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_funcionario_denunciado',
                    fieldLabel: 'Lista Negra',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1'}
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    gdisplayField: 'desc_nombre_fun_denun',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    hiddenName: 'id_funcionario_denunciado',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    width: 260,
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'260',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_nombre_fun_denun']);
                    }
                },
                type: 'TrigguerCombo',
                id_grupo:3,
                filters:{
                    pfiltro:'fu.desc_funcionario1',
                    type:'string'
                },
                bottom_filter:true,
                grid: true,
                form: false
            },
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
                filters: {pfiltro: 'rec.estado', type: 'string'},
                /*id_grupo: 1,*/
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'detalle_incidente',
                    fieldLabel: 'Detalle Incidente',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength: 1000
                },
                type: 'TextArea',
                bottom_filter:true,
                filters: {pfiltro: 'rec.detalle_incidente', type: 'string'},
                id_grupo: 3,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'observaciones_incidente',
                    fieldLabel: 'Observaciones Incidente',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength: 1000
                },
                type: 'TextArea',
                bottom_filter:true,
                filters: {pfiltro: 'rec.observaciones_incidente', type: 'string'},
                id_grupo: 3,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_att_canalizado',
                    fieldLabel: 'Nro. FRD Att Canalizado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    maxLength: 20
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.nro_att_canalizado', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_pir',
                    fieldLabel: 'Nro. PIR',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'rec.nro_pir', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'nro_frsa',
                    fieldLabel: 'Nro. FRSA',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'rec.nro_frsa', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'conclusion_recomendacion',
                    fieldLabel: 'Recomendaciones Ralizadas por Personal',
                    allowBlank: true,
                    anchor: '80%',
                    height: 80,
                    gwidth: 200,
                    maxLength:1000
                },
                type:'TextArea',
                filters:{pfiltro:'infor.conclusion_recomendacion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config: {
                    name: 'recomendaciones',
                    fieldLabel: 'Acciones Tomadas',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength: 1000000
                },
                type: 'TextArea',
                filters: {pfiltro: 'res.recomendaciones', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'nro_guia_aerea',
                    fieldLabel: 'Guia Aerea',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'TextField',
                filters: {pfiltro: 'rec.nro_guia_aerea', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            }
        ],
        tam_pag: 50,
        title: 'CRMGlobal',
        ActList: '../../sis_reclamo/control/Reclamo/listarReclamo',
        id_store: 'id_reclamo',
        fields: [
            {name: 'desc_nom_cliente', type: 'string'},
            {name: 'genero', type: 'string'},
            {name: 'ci', type: 'string'},
            {name: 'pnr', type: 'string'},
            {name: 'telefono', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'ciudad_residencia', type: 'string'},
            {name: 'fecha_recepcion_sac', type: 'date', dateFormat: 'Y-m-d H:i:s'},
            {name: 'fecha_hora_recepcion', type: 'date', dateFormat: 'Y-m-d H:i:s'},
            {name: 'fecha_hora_incidente', type: 'date', dateFormat: 'Y-m-d H:i:s'},
            {name: 'desc_oficina_registro_incidente', type: 'string'},
            {name: 'desc_nombre_oficina', type: 'string'},
            {name: 'nro_frd', type: 'string'},
            {name: 'correlativo_preimpreso_frd', type: 'numeric'},
            {name: 'nro_ripat_att', type: 'numeric'},
            {name: 'nro_hoja_ruta', type: 'numeric'},
            {name: 'desc_nombre_incidente', type: 'string'},
            {name: 'desc_sudnom_incidente', type: 'string'},
            {name: 'nro_vuelo', type: 'string'},
            {name: 'origen', type: 'string'},
            {name: 'transito', type: 'string'},
            {name: 'destino', type: 'string'},
            {name: 'desc_nombre_funcionario', type: 'string'},
            {name: 'estado', type: 'string'},
            {name: 'nro_cite', type: 'string'},
            {name: 'desc_nombre_fun_denun', type: 'string'},
            {name: 'detalle_incidente', type: 'string'},
            {name: 'observaciones_incidente', type: 'string'},
            {name: 'nro_att_canalizado', type: 'numeric'},
            {name: 'nro_pir', type: 'numeric'},
            {name: 'nro_frsa', type: 'numeric'},
            {name: 'conclusion_recomendacion', type: 'string'},
            {name: 'recomendaciones', type: 'string'},
            {name: 'nro_guia_aerea', type: 'string'},



            {name: 'id_reclamo', type: 'numeric'},
            {name: 'id_tipo_incidente', type: 'numeric'},
            {name: 'id_subtipo_incidente', type: 'numeric'},
            {name: 'id_medio_reclamo', type: 'numeric'},
            {name: 'id_funcionario_recepcion', type: 'numeric'},
            {name: 'id_oficina_incidente', type: 'numeric'},
            {name: 'id_oficina_registro_incidente', type: 'numeric'},
            {name: 'id_proceso_wf', type: 'numeric'},
            {name: 'id_estado_wf', type: 'numeric'},
            {name: 'id_cliente', type: 'string'},
            {name: 'id_funcionario_denunciado', type: 'numeric'},
            {name: 'estado_reg', type: 'string'},


            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            {name: 'id_gestion', type: 'int4'},
            {name: 'nombre_completo2', type: 'string'},
            {name: 'desc_nombre_medio', tyep: 'string'}
        ],
        sortInfo: {
            field: 'fecha_reg',
            direction: 'DESC'
        },
        btest: false,
        bdel:false,
        bsave:false,
        bedit:false,
        bnew:false,
        bodyStyle: 'padding:0 10px 0;',
        loadValoresIniciales:function(){
            Phx.vista.CRMGlobal.superclass.loadValoresIniciales.call(this);
            //this.getComponente('id_int_comprobante').setValue(this.maestro.id_int_comprobante);
        },
        onReloadPage:function(param){
            //Se obtiene la gestión de los Reclamos.
            var me = this;
            me.parametros = param;
            this.initFiltro(param);
        },

        initFiltro: function(param){
            this.store.baseParams=param;
            this.load( { params: { start:0, limit: this.tam_pag } });
        }
    });
</script>

