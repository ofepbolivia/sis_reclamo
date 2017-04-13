
<?php
/**
 *@package pXP
 *@file    formCliente.php
 *@author  Espinoza Alvarez
 *@date    14-11-2016
 *@description permite mostrar formulario de registro de Clientes
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Copiar=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_reclamo/control/Informe/copiarInforme',
        /*layout: 'absolute',*/
        breset: false,
        bcancel: true,
        autoScroll: false,
        labelSubmit: '<i class="fa fa-check"></i> Guardar',
        constructor:function(config){
            console.log(config);
            Phx.vista.Copiar.superclass.constructor.call(this,config);
            this.init();
            //this.loadValoresIniciales();
            this.iniciarEvento();
            //this.store.baseParams = {informe:this.data.id_informe};

            this.Cmp.id_informe.setValue(this.data.id_informe);

        },
        Atributos:[

            {
                config: {
                    name: 'gestion',
                    fieldLabel: 'Gestion',
                    allowBlank: true,
                    emptyText: 'Gestion...',
                    blankText: 'Año',
                    store: new Ext.data.JsonStore(
                        {
                            url: '../../sis_parametros/control/Gestion/listarGestion',
                            id: 'id_gestion',
                            root: 'datos',
                            sortInfo: {
                                field: 'gestion',
                                direction: 'DESC'
                            },
                            totalProperty: 'total',
                            fields: ['id_gestion', 'gestion'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams: {par_filtro: 'gestion'}
                        }),
                    valueField: 'id_gestion',
                    triggerAction: 'all',
                    displayField: 'gestion',
                    hiddenName: 'id_gestion',
                    mode: 'remote',
                    pageSize: 50,
                    queryDelay: 500,
                    /*listWidth: '280',*/
                    hidden: false,
                    width: 80,
                    anchor: '100%',
                },
                type: 'ComboBox',
                form: true

            },
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_informe',
                    value: this.informe
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    name: 'copiar_informe',
                    fieldLabel: 'Lista de Reclamos',
                    allowBlank: true,
                    emptyText: 'Seleccion...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_reclamo/control/Reclamo/listarConsulta',
                        id: 'id_reclamo',
                        root: 'datos',
                        sortInfo: {
                            field: 'nro_tramite',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_reclamo', 'nro_tramite'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'rec.id_reclamo'}
                    }),
                    valueField: 'id_reclamo',
                    displayField: 'nro_tramite',
                    gdisplayField: 'nro_tramite',//mapea al store del grid
                    hiddenName: 'id_reclamo',
                    forceSelection: true,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    gwidth: 300,
                    minChars: 2,
                    enableMultiSelect: true,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['nro_tramite']);
                    }
                },
                type: 'AwesomeCombo',
                id_grupo: 0,
                grid: true,
                form: true
            }

            /*,

            {
                config:{
                    name: 'id_pais_residencia',
                    fieldLabel: 'Pais de Residencia',
                    allowBlank: false,
                    emptyText: 'Elija una opcion...',

                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/Lugar/listarLugar',
                        id: 'id_lugar',
                        root: 'datos',
                        sortInfo:{
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_lugar','nombre'],
                        remoteSort: true,
                        baseParams:{par_filtro:'lug.nombre',tipo:'pais'}
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
                    minChars:2,
                    queryDelay: 250,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:30,
                    style:'text-transform:uppercase;',
                    /!*turl:'../../../sis_parametros/vista/lugar/Lugar.php',
                     ttitle:'Lugar',
                     // tconfig:{width:1800,height:500},
                     tdata:{},
                     tcls:'Lugar',*!/
                    renderer: function(value, p, record){
                        return String.format('{0}', record.data['pais_residencia']);
                    }
                },
                //type:'TrigguerCombo',
                type:'ComboBox',
                bottom_filter:false,
                filters:{pfiltro:'lug.nombre',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            }*/
        ],
        title:'Copiar Informe',
        iniciarEvento:function() {

            this.Cmp.gestion.on('select', function (cmb, record, index) {
                this.Cmp.copiar_informe.reset();
                this.Cmp.copiar_informe.modificado = true;
                this.Cmp.copiar_informe.setDisabled(false);
                this.Cmp.copiar_informe.store.setBaseParam('id_gestion', record.data.id_gestion);

            }, this);
        },

        successSave:function(resp)
        {
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.panel.close();
        }
        /*onSubmit:function(o){

            this.Cmp.nombre.setValue((this.Cmp.nombre.getValue()).trim());
            this.Cmp.apellido_paterno.setValue((this.Cmp.apellido_paterno.getValue()).trim());
            this.Cmp.apellido_materno.setValue((this.Cmp.apellido_materno.getValue()).trim());
            Phx.vista.FormCliente.superclass.onSubmit.call(this,o);
        },


        successSave:function(resp)
        {
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            Phx.CP.getPagina(this.idContenedorPadre).cargarCliente(reg.ROOT.datos.id_cliente, this.Cmp.apellido_paterno.getValue() +
                ' ' + this.Cmp.apellido_materno.getValue() +
                ' ' + this.Cmp.nombre.getValue());
            /!*Ext.Ajax.request({
             url:'../../sis_reclamo/control/Cliente/getNombreCliente',
             params:{id_cliente: reg.ROOT.datos.id_cliente},
             success:this.successName,
             failure: this.conexionFailure,
             timeout:this.timeout,
             scope:this
             });*!/


            Phx.CP.loadingHide();
            this.close();
            this.onDestroy();


        },

        successName: function(resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log('nombre: '+reg.ROOT.datos.nombre_completo1);

            //Ext.getCmp('id_cliente').setRawValue(reg.ROOT.datos.nombre_completo1);
        }*/
    });
</script>
