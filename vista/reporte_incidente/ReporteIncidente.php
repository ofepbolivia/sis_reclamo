<?php
/**
 *@package pXP
 *@file    ItemEntRec.php
 *@author  MAM
 *@date    12/09/2016
 *@description Reporte Material Entregado/Recibido
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ReporteIncidente = Ext.extend(Phx.frmInterfaz,{

        atributos :[
            {
                config:{
                    name:'id_uo',
                    hiddenName: 'id_uo',
                    origen:'UO',
                    fieldLabel:'UO',
                    gdisplayField:'desc_uo',//mapea al store del grid
                    gwidth:200,
                    emptyText:'Dejar blanco para toda la empresa...',
                    anchor: '50%',
                    baseParams: {gerencia: 'si'},
                    allowBlank:true,
                    renderer:function (value, p, record){return String.format('{0}', record.data['desc_uo']);}
                },
                type:'ComboRec',
                id_grupo:1,
                filters:{
                    pfiltro:'uo.codigo#uo.nombre_unidad',
                    type:'string'
                },
                grid:true,
                form:false
            },
            {
                config: {
                    name: 'id_tipo_incidente',
                    fieldLabel: 'Tipo Incidente',
                    allowBlank: true,
                    emptyText: 'Dejar en blanco para todos los tipos..',
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
                        baseParams: {par_filtro: 'rti.nombre_incidente', nivel:'1'}
                    }),
                    valueField: 'id_tipo_incidente',
                    displayField: 'nombre_incidente',
                    hiddenName: 'id_tipo_incidente',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    minChars: 1
                },
                type: 'ComboBox',
                id_grupo: 1,
                form: true
            },

            {
                config:{
                    name: 'fecha',
                    allowBlank:false,
                    fieldLabel: 'Fecha Reporte',
                    allowBlank:'Fecha a la que se generara el reporte',
                    anchor: '30%',
                    gwidth: 100,
                    format: 'd/m/Y'

                },
                type:'DateField',
                id_grupo:1,
                form:true
            }

        ],
        title : 'Generar Reporte',
        ActSave : '../../sis_reclamo/control/Reporte/listarReporte',
        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Generar Reporte</b>',
        constructor : function(config) {
            Phx.vista.ReporteIncidente.superclass.constructor.call(this, config);
            this.init();
        },
        tipo : 'reporte',
        clsSubmit : 'bprint',

        agregarArgsExtraSubmit: function() {
            this.argumentExtraSubmit.uo = this.Cmp.id_uo.getRawValue();
            this.argumentExtraSubmit.tipo_contrato = this.Cmp.id_tipo_contrato.getRawValue();

        }
    })

</script>
