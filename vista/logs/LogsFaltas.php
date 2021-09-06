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
    Phx.vista.LogsFaltas=Ext.extend(Phx.gridInterfaz,{

        bnew : false,
        bedit : false,
        bdel : false,
        constructor:function(config){
            //llama al constructor de la clase padre
            Phx.vista.LogsFaltas.superclass.constructor.call(this,config);
            this.maestro=config;
            //console.log('maestro de hoy: '+JSON.stringify(this.maestro));
            this.init();
            this.load({params:{start:0, limit: this.tam_pag}});
        },

        Atributos:[
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_logs_reclamo'
                },
                type:'Field',
                form:false
            },
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
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_funcionario'
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
                    name: 'nombre_funcionario',
                    fieldLabel: 'Funcionario Observado',
                    allowBlank: true,
                    anchor: '80%',
                    height: 80,
                    gwidth: 220,
                    maxLength:100,
                    renderer:function (value, p, record)
                    {return String.format('<b><font color="green">{0}</font></b>', record.data['nombre_funcionario']);}
                },
                type:'TextField',
                filters:{pfiltro:'vf.desc_funcionario1',type:'string'},
                id_grupo:0,
                grid:true,
                form:false,
                bottom_filter : true
            },
            {
                config:{
                    name: 'descripcion',
                    fieldLabel: 'Descripcion Falta',
                    allowBlank: false,
                    anchor: '100%',
                    qtip:'Descripción de la falta del Funcionario.',
                    gwidth: 700,
                    enableColors: true,
                    enableAlignments: true,
                    enableLists: true,
                    enableSourceEdit: true,
                    enableFontSize: false,
                    defaultFont:'Arial'
                    //renderer:function (value, p, record){return String.format('<b><font color="green">{0}</font></b>', record.data['descripcion']);}
                },
                type:'HtmlEditor',
                filters: {pfiltro: 'tlr.descripcion', type: 'string'},
                id_grupo:0,
                grid:true,
                form:false
            }

        ],
        //ActList:'../../sis_reclamo/control/Reclamo/listarControlFRD',
        //ActSave: '../../sis_reclamo/control/Reclamo/insertarReclamo',
        //ActDel: '../../sis_reclamo/control/Reclamo/eliminarReclamo',
        ActList: '../../sis_reclamo/control/Reclamo/listarLogsFaltas',
        tam_pag:50,
        title:'Logs Faltas',
        id_store:'id_logs_reclamo',
        fields: [

            {name:'id_logs_reclamo', type: 'numeric'},
            {name:'descripcion', type: 'string'},
            {name:'id_reclamo', type: 'numeric'},
            {name:'id_funcionario', type: 'numeric'},
            {name:'nombre_funcionario', type: 'string'},
            {name:'nro_tramite', type: 'string'}


        ],
        sortInfo:{
            field: 'id_logs_reclamo',
            direction: 'DESC'
        },
        bsave:false,
        btest: false

    });
</script>
