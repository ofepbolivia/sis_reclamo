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
        //layoutType: 'wizard',
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
                 gwidth: 300,
                 maxLength: 100,
                 renderer: function(value, p, record) {
                 var dias = record.data.dias_informe;
                 console.log('dias: '+record.data.dias_informe);
                 //console.log('dias_informe: '+JSON.stringify(record.data));
                 if (dias == 2) {
                 return String.format('<div ext:qtip="Bueno"><b><font color="green">Le Quedan 48 Horas</font></b><br></div>', value);
                 }
                 else if(dias>=0 && dias<=1){
                 return String.format('<div ext:qtip="Malo"><b><font color="orange">Le Quedan 24 Horas</font></b><br></div>', value);
                 }else if(dias = -1){
                 return String.format('<div ext:qtip="Vencido"><b><font color="blue">Vencido</font></b><br></div>', value);
                 }
                 }
                 },
                 type: 'TextField',

                 grid: true,
                 form: false
             });
            Phx.vista.RegistroReclamos.superclass.constructor.call(this,config);
            //this.store.baseParams.func_estado = 'oficina';
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
        }

    };
</script>
