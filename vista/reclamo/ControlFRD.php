<?php
/**
 *@package pXP
 *@file gen-Informe.php
 *@author  (admin)
 *@date 11-08-2016 01:52:07
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ControlFRD=Ext.extend(Phx.gridInterfaz,{

        bnew : false,
        bedit : false,
        bdel : false,
        ActList:'../../sis_reclamo/control/Reclamo/listarControlFRD',
        constructor:function(config){
            this.tbarItems = ['-',
                this.cmbGestion,'-',this.cmbOficina,'-'

            ];
            this.maestro=config;

            if(config.nombreVista == 'RegistroReclamos'){
                this.cmbOficina.disabled =  true;
            }
            //llama al constructor de la clase padre
            Phx.vista.ControlFRD.superclass.constructor.call(this,config);
            this.maestro=config;
            //console.log('maestro de hoy: '+JSON.stringify(this.maestro));
            this.init();
            //this.load({params:{start:0, limit: this.tam_pag}});

            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    this.cmbOficina.setValue(this.maestro.id_oficina_registro_incidente);
                    this.cmbOficina.setRawValue(this.maestro.desc_oficina_registro_incidente);
                    this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
                    this.store.baseParams.id_oficina=this.maestro.id_oficina_registro_incidente;
                    this.load({params:{start:0, limit: this.tam_pag}});
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            this.addButton('frds_faltante',{
                grupo:[0,1,2,3,4,5],
                text :'Reporte FRDS Faltantes',
                iconCls : 'bfolder',
                disabled: false,
                handler : this.frdFaltantes,
                tooltip : '<b>Control FRDS,</b><br/><b>Se generar un reporte en formato PDF de los FRDS que faltan en una oficina en Especifico.</b>'
            });

            this.cmbGestion.on('select',this.capturarEventos1, this);
            this.cmbOficina.on('select',this.capturarEventos2, this);

        },

        capturarEventos1: function () {
            /*if(this.validarFiltros()){
             this.capturaFiltros();
             }*/
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            //this.store.baseParams.id_oficina=this.cmbOficina.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
        },

        capturarEventos2: function () {
            this.store.baseParams.id_oficina=this.cmbOficina.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
        },

        frdFaltantes: function () {
            //Phx.CP.loadingShow();
            Ext.Ajax.request({
             url:'../../sis_reclamo/control/Reclamo/reporteFRDFaltantes',
             params:{
                 'id_oficina': this.cmbOficina.getValue(),
                 'id_gestion': this.cmbGestion.getValue()
             },
             success:/*function(resp){
                 var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                 console.log(reg);
             }*/this.successExport,
             failure: this.conexionFailure,
             timeout:this.timeout,
             scope:this
             });
        },

        /*preparaMenu: function (n) {
            var rec = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ControlFRD.superclass.preparaMenu.call(this,n);
            this.getBoton('frds_faltante').enable();
        },

        liberaMenu: function () {
            var tb = Phx.vista.ControlFRD.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('frds_faltante').disable();
            }
            return tb
        },*/

        cmbGestion: new Ext.form.ComboBox({
            name: 'id_gestion',
            id: 'id_gestion',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'Año',
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

        cmbOficina: new Ext.form.ComboBox({
            name: 'id_oficina',
            id: 'id_oficina',
            fieldLabel: 'Oficina',
            allowBlank: true,
            emptyText:'Oficina...',
            blankText: 'Oficina',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_reclamo/control/OficinaReclamo/listarOficina',
                    id: 'id_oficina',
                    root: 'datos',
                    sortInfo:{
                        field: 'nombre',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_oficina', 'nombre', 'codigo','nombre_lugar'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'nombre'}
                }),
            valueField: 'id_oficina',
            displayField: 'nombre',
            hiddenName: 'id_oficina',
            triggerAction: 'all',
            mode:'remote',
            queryDelay:500,
            pageSize:10,
            listWidth:'280',
            hidden:false,
            width:150
        }),

        Atributos:[
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_reclamo'
                },
                type:'Field',
                form:false
            },
            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'Nro. Tramite',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 150,
                    maxLength:20,
                    readOnly:true,
                    renderer: function(value,p,record) {
                        return String.format('<b><font color="green">{0}</font></b>', value);
                    }
                },
                type:'TextField',
                filters:{pfiltro:'tr.nro_tramite',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'nro_frd',
                    fieldLabel: 'Nro. FRD ',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 120,
                    renderer:function (value, p, record){return String.format('<b><font color="green">{0}</font></b>', record.data['nro_frd']);}
                },
                type:'TextField',
                filters:{pfiltro:'tr.nro_frd',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'nro_correlativo',
                    fieldLabel: 'Preimpreso FRD',
                    allowBlank: true,
                    anchor: '80%',
                    height: 80,
                    gwidth: 120,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'tr.correlativo_preimpreso_frd',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'nombre_cliente',
                    fieldLabel: 'Cliente',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 230,
                    renderer:function (value, p, record){return String.format('<font color="green">{0}</font>', record.data['nombre_cliente']);}
                },
                type:'TextField',
                filters:{pfiltro:'vc.nombre_completo1',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'nombre_funcionario',
                    fieldLabel: 'Funcionario que Registra',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 230,
                    renderer:function (value, p, record){return String.format('<b><font color="green">{0}</font></b>', record.data['nombre_funcionario']);}
                },
                type:'TextField',
                filters:{pfiltro:'vf.desc_funcionario1',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    name: 'oficina',
                    fieldLabel: 'Oficina FRD',
                    allowBlank: true,
                    anchor: '80%',
                    height: 80,
                    gwidth: 200,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'tof.nombre',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            }

        ],
        tam_pag:50,
        title:'ControlFRD',
        id_store:'id_reclamo',
        fields: [

            {name:'id_reclamo', type: 'numeric'},
            {name:'nro_tramite', type: 'string'},
            {name:'nro_frd', type: 'string'},
            {name:'nro_correlativo', type: 'numeric'},
            {name:'oficina', type: 'string'},
            {name:'id_oficina', type: 'numeric'},
            {name:'id_gestion', type: 'numeric'},
            {name:'nombre_cliente', type: 'string'},
            {name:'nombre_funcionario', type: 'string'}
        ],
        sortInfo:{
            field: 'nro_frd',
            direction: 'DESC'
        },
        bsave:false,
        btest: false

    });
</script>
