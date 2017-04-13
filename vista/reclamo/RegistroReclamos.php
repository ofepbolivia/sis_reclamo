<?php
/**
 *@package pXP
 *@file RegistroReclamos.php
 *@author  (Franklin Espinoza)
 *@date 13-10-2016 14:45
 *@Interface para el inicio de solicitudes de compra
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RegistroReclamos = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'RegistroReclamos',
        fwidth: '60%',
        fheight : '75%',
    constructor: function(config){
        this.maestro=config.maestro;
        this.mycls = config.mycls;
        //this.Atributos.splice(3,1);
        this.Atributos.splice(5,0,{
             config: {
             name: 'dias_informe',
             fieldLabel: 'Dias Para Adjuntar Inf.',
             allowBlank: true,
             anchor: '100%',
             gwidth: 150,
             maxLength: 100,
             renderer: function(value, p, record) {
             var dias = record.data.dias_informe;
             console.log('dias: '+record.data.dias_informe);
             //console.log('dias_informe: '+JSON.stringify(record.data));
             if(record.data.revisado == 'con_informe')
                 return  String.format('{0}',"<div style='text-align:center'><img title='El Reclamo ya tiene Informe'  src = '../../../sis_reclamo/media/respondido.png' align='center' width='24' height='24'/></div>");
             else if (dias == 2) {
                 return  String.format('{0}',"<div style='text-align:center'><img title='Tiene 48 Horas Para adjuntar Informe'  src = '../../../sis_reclamo/media/two.png' align='center' width='24' height='24'/></div>");
             }
             else if(dias>=0 && dias<=1){
                 return  String.format('{0}',"<div style='text-align:center'><img title='Tiene 24 Horas Para adjuntar Informe'  src = '../../../sis_reclamo/media/cero.png' align='center' width='24' height='24'/></div>");
             }else if(dias = -1){
                 return  String.format('{0}',"<div style='text-align:center'><img title='Ha vencido el Plazo para poder adjuntar Informe'  src = '../../../sis_reclamo/media/bomb.png' align='center' width='24' height='24'/></div>");
             }
             }
             },
             type: 'Checkbox',
             grid: true,
             form: false
         });
        this.Atributos.unshift({
            //configuracion del componente
            config: {
                labelSeparator: '',
                inputType: 'hidden',
                name: 'interfaz',
                value:'registrado'
            },
            type: 'Field',
            form: true,
            id_grupo:1
        });
        Phx.vista.RegistroReclamos.superclass.constructor.call(this,config);
        //this.store.baseParams.func_estado = 'oficina';
        this.store.baseParams.tipo_interfaz=this.nombreVista;
        console.log('padre: '+this.mycls);
        console.log('maestro: '+JSON.stringify(config));
        //this.padre = Phx.CP.getPagina(this.idContenedorPadre).nombreVista;
    },
    gruposBarraTareas:[
        {name:'borrador',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Borradores</h1>',grupo:0,height:0, width: 100},
        {name:'pendiente_revision',title:'<H1 align="center"><i class="fa fa-list-ul"></i>Adjuntar Informe</h1>',grupo:2,height:0, width: 100},
        {name:'pendiente_informacion',title:'<H1 align="center"><i class="fa fa-files-o"></i> Pendientes Inf.</h1>',grupo:1,height:0}
    ],
    tam_pag:50,
    actualizarSegunTab: function(name, indice){
        if(this.finCons){
            this.store.baseParams.pes_estado = name;
            this.load({params:{start:0, limit:this.tam_pag}});
        }
    },
    beditGroups: [0],
    bdelGroups:  [0],
    bactGroups:  [0,1,2],
    bexcelGroups: [0,1,2],
    tabsouth :[
        {
            url:'../../../sis_reclamo/vista/informe/Informe.php',
            title:'Informe',
            height:'50%',
            cls:'Informe'
        }
    ],

    enableTabRespuesta:function(){
        if(this.TabPanelSouth.get(0)){
            //console.debug('uno: '+this.TabPanelSouth.get(0));
            this.TabPanelSouth.get(0).enable();
            this.TabPanelSouth.setActiveTab(0);
        }
    },

    disableTabRespuesta:function(){
        if(this.TabPanelSouth.get(0)){
            //console.log('dos: '+Ext.util.JSON.decode(this.TabPanelSouth.get(0)));
            this.TabPanelSouth.get(0).disable();
            //this.TabPanelSouth.setActiveTab(0)
        }
    },
    preparaMenu:function(n){
        var data = this.getSelectedData();
        var tb =this.tbar;
        //console.log('registro_1:'+data);
        //console.log('registro_2:'+JSON.stringify(data));
        Phx.vista.RegistroReclamos.superclass.preparaMenu.call(this,n);
        //habilitar reporte de colicitud de comrpa y preorden de compra
        //var dataPadre = Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();


        //console.log('papa: '+this.padre);
        if(data['estado']==  'borrador'){
            this.getBoton('sig_estado').enable();
            this.disableTabRespuesta();

        }else if(data['estado']==  'pendiente_revision' /*&& this.mycls == 'RegistroReclamos'*/){
            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').disable();
            this.enableTabRespuesta();
        }
        else {
            this.getBoton('sig_estado').enable();
            this.getBoton('ant_estado').enable();
            this.enableTabRespuesta();

        }

        return tb;
    },

    liberaMenu:function(){
        var tb = Phx.vista.RegistroReclamos.superclass.liberaMenu.call(this);
        var data = this.getSelectedData();
        if(tb){
            this.getBoton('sig_estado').disable();
            this.getBoton('sig_estado').disable();

            /*if(data.estado = 'pendiente_informacion'){
                this.enableTabRespuesta();
            }else {
                this.disableTabRespuesta();
            }*/
        }

        return tb;
    },

    onButtonEdit: function() {
        Phx.vista.RegistroReclamos.superclass.onButtonEdit.call(this);
    }/*,

    actualizarFRD:function () {

        Ext.Ajax.request({
            url:'../../sis_reclamo/control/Reclamo/getFRD',
            params:{
                oficina:this.Cmp.id_oficina_registro_incidente.getValue()
            },
            success: function(resp){
                var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                //this.Cmp.nro_frd.setValue(reg.ROOT.datos.v_frd);
                console.log('FRD',reg.ROOT.datos.v_frd);
            },
            failure: this.conexionFailure,
            timeout:this.timeout,
            scope:this
        });

    },

    onSubmit:function(o){
        Phx.vista.RegistroReclamos.superclass.onSubmit.call(this,o);
        this.actualizarFRD();
    }*/

    };
</script>
