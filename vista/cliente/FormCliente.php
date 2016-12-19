
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
    Phx.vista.FormCliente=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_reclamo/control/Cliente/insertarCliente',
        layout: 'fit',
        breset: false,
        bcancel: true,
        dedo: 'dedo',
        autoScroll: false,
        labelSubmit: '<i class="fa fa-check"></i> Guardar',
        constructor:function(config){
            console.log(config);
            Phx.vista.FormCliente.superclass.constructor.call(this,config);
            this.init();
            this.loadValoresIniciales();
            
        },
        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_cliente'
                },
                type:'Field',
                id_grupo:1,
                form:true
            },
            {
                config:{
                    name: 'nombre',
                    fieldLabel: 'Nombres',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:50,
                    style:'text-transform:uppercase; white-space: pre-line;',
                    /*regex:/^\s+|\s+$/g,
                    maskRe: /\s/g*/
                },
                type:'TextField',
                filters:{pfiltro:'cli.nombre',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:true,
            },
            {
                config:{
                    name: 'apellido_paterno',
                    fieldLabel: 'Primer Apellido',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength:30,
                    style:'text-transform:uppercase; white-space: pre-line;',
                    /*regex:/^\s+|\s+$/g,
                    maskRe: /\s/g*/
                },
                type:'TextField',
                filters:{pfiltro:'cli.apellido_paterno',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:true
            }
            ,{
                config:{
                    name: 'apellido_materno',
                    fieldLabel: 'Segundo Apellido',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:30,
                    style:'text-transform:uppercase; white-space: pre-line;',
                    /*regex:/^\s+|\s+$/g,
                    maskRe: /\s/g*/
                },
                type:'TextField',
                filters:{pfiltro:'cli.apellido_materno',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'genero',
                    fieldLabel: 'Genero',
                    allowBlank:false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:['VARON','MUJER']
                },
                type:'ComboBox',
                filters:{pfiltro:'cli.genero',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'ci',
                    fieldLabel: 'Nro. Doc. Identificación',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:15
                },
                type:'TextField',
                filters:{pfiltro:'cli.ci',type:'string'},
                id_grupo:1,
                bottom_filter:true,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'lugar_expedicion',
                    fieldLabel: 'Lugar de Expedición',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode: 'local',
                    store:['CB','SC','LP','BN','CJ','TJ','OR','PT','CH', 'OTRO']
                },
                type:'ComboBox',
                filters:{pfiltro:'cli.lugar_expedicion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'nacionalidad',
                    fieldLabel: 'Nacionalidad',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:30,
                    style:'text-transform:uppercase;'
                },
                type:'TextField',
                filters:{pfiltro:'cli.nacionalidad',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'celular',
                    fieldLabel: 'Celular',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:20
                },
                type:'NumberField',
                filters:{pfiltro:'cli.celular',type:'numeric'},
                id_grupo:2,
                grid:true,
                form:true
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
                filters:{pfiltro:'cli.telefono',type:'numeric'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'email',
                    fieldLabel: 'Email',
                    vtype:'email',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:50
                },
                type:'TextField',
                filters:{pfiltro:'cli.email',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'direccion',
                    fieldLabel: 'Direccion,   (Calle/Av./No.)',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:200
                },
                type:'TextArea',
                filters:{pfiltro:'cli.direccion',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },
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
                    turl:'../../../sis_parametros/vista/lugar/Lugar.php',
                    ttitle:'Lugar',
                    // tconfig:{width:1800,height:500},
                    tdata:{},
                    tcls:'Lugar',
                    renderer: function(value, p, record){
                        return String.format('{0}', record.data['pais_residencia']);
                    }
                },
                type:'TrigguerCombo',
                bottom_filter:false,
                filters:{pfiltro:'cli.id_pais_residencia',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'ciudad_residencia',
                    fieldLabel: 'Ciudad de Residencia',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:30,
                    style:'text-transform:uppercase;'
                },
                type:'TextField',
                filters:{pfiltro:'cli.ciudad_residencia',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'barrio_zona',
                    fieldLabel: 'Zona/Barrio',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:200,
                    style:'text-transform:uppercase;'
                },
                type:'TextField',
                filters:{pfiltro:'cli.barrio_zona',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'cli.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'id_usuario_ai',
                    fieldLabel: '',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'cli.id_usuario_ai',type:'numeric'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cli.fecha_reg',type:'date'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionario AI',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:300
                },
                type:'TextField',
                filters:{pfiltro:'cli.usuario_ai',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'cli.fecha_mod',type:'date'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
            }
        ],
        title:'Clientes',

        /*loadValoresIniciales:function()
        {
            Phx.vista.FormCliente.superclass.loadValoresIniciales.call(this);
        },*/
        onSubmit:function(o){
            //TODO passar los datos obtenidos del wizard y pasar  el evento save
            
            
            /*if (this.form.getForm().isValid()) {
                this.fireEvent('beforesave', this, this.getValues());
            }*/

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
            /*Ext.Ajax.request({
                url:'../../sis_reclamo/control/Cliente/getNombreCliente',
                params:{id_cliente: reg.ROOT.datos.id_cliente},
                success:this.successName,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });*/


            Phx.CP.loadingHide();
            //Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.close();
            this.onDestroy();


        },

        successName: function(resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log('nombre: '+reg.ROOT.datos.nombre_completo1);

            //Ext.getCmp('id_cliente').setRawValue(reg.ROOT.datos.nombre_completo1);
        },

        getValues:function(){
            var resp = {
                nombre: this.Cmp.nombre.getValue(),
                apellido_paterno: this.Cmp.apellido_paterno.getValue(),
                apellido_materno: this.Cmp.apellido_materno.getValue(),
                genero: this.Cmp.genero.getValue(),
                ci: this.Cmp.ci.getValue(),
                lugar_expedicion: this.Cmp.lugar_expedicion.getValue(),
                nacionalidad: this.Cmp.nacionalidad.getValue(),
                celular: this.Cmp.celular.getValue(),
                telefono: this.Cmp.telefono.getValue(),
                email: this.Cmp.email.getValue(),
                direccion: this.Cmp.direccion.getValue(),
                id_pais_residencia: this.Cmp.id_pais_residencia.getValue(),
                ciudad_residencia: this.Cmp.ciudad_residencia.getValue(),
                barrio_zona: this.Cmp.barrio_zona.getValue()
            };
            return resp;
        }
    });
</script>
