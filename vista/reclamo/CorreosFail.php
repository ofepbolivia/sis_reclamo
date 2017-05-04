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
    Phx.vista.CorreosFail=Ext.extend(Phx.gridInterfaz,{

        bnew : false,
        bedit : false,
        bdel : false,
        ActList:'../../sis_reclamo/control/Reclamo/listarFails',
        constructor:function(config){
            this.maestro=config;
            console.log('configuracion',config);
            //llama al constructor de la clase padre
            Phx.vista.CorreosFail.superclass.constructor.call(this,config);
            this.init();
            this.load({params:{start:0, limit: 50}});

        },
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
                filters:{pfiltro:'trec.nro_tramite',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'id_cliente',
                    fieldLabel: 'Nombre Cliente',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 100,
                    renderer:function (value, p, record){return String.format('{0}', record.data['desc_funcionario']);}
                },
                type:'TextField',
                filters:{pfiltro:'trec.id_cliente',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'falla',
                    fieldLabel: 'Descripcion Falla',
                    allowBlank: true,
                    anchor: '80%',
                    height: 80,
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'falla',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            }
        ],
        tam_pag:50,
        title:'CorreosFail',
        id_store:'id_reclamo',
        fields: [

            {name:'id_reclamo', type: 'numeric'},
            {name:'nro_tramite', type: 'string'},
            {name:'id_cliente', type: 'numeric'},
            {name:'falla', type: 'string'},
            {name:'desc_funcionario', type: 'string'}
        ],
        sortInfo:{
            field: 'id_reclamo',
            direction: 'ASC'
        },
        bsave:false,
        btest: false

    });
</script>
