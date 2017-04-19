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

        constructor:function(config){
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.CorreosFail.superclass.constructor.call(this,config);

            this.init();
            //this.iniciarEventos();

        },
        Atributos:[
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
                filters:{pfiltro:'rec.nro_tramite',type:'string'},
                id_grupo:1,
                grid:true/*,
                form:true*/
            },
            {
                config:{
                    name: 'id_cliente',
                    fieldLabel: 'Nombre Cliente',
                    allowBlank: false,
                    anchor: '40%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value, p, record){return String.format('{0}', record.data['desc_nom_cliente']);}
                },
                type:'TextField',
                filters:{pfiltro:'c.nombre_completo2',type:'string'},
                id_grupo:0,
                grid:true/*,
                form:true*/
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
                filters:{pfiltro:'param.antecedentes_informe',type:'string'},
                id_grupo:1,
                grid:true/*,
                form:true*/
            }
        ],
        tam_pag:50,
        title:'CorreosFail',
        ActList:'../../sis_reclamo/control/Reclamo/listarFails',
        id_store:'id_cliente',
        fields: [

            {name:'nro_tramite', type: 'string'},
            {name:'id_cliente', type: 'numeric'},
            {name:'conclusion_recomendacion', type: 'string'},
            {name:'fecha_informe', type: 'date',dateFormat:'Y-m-d'},


            //{name:'desc_nombre_compensacion', type: 'string'},
            /*{name:'desc_fun', type: 'string'},
            {name:'lista', type: 'string'}*/
        ],
        sortInfo:{
            field: 'id_cliente',
            direction: 'ASC'
        },
        bdel:true,
        bsave:false,
        btest: false

    });
</script>
