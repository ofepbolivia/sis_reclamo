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

        gruposBarraTareas:[
            {name:'borrador',title:'<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Borradores</h1>',grupo:0,height:0, width: 100},
            {name:'pendiente_informacion',title:'<H1 align="center"><i class="fa fa-eye"></i> Pendientes Inf.</h1>',grupo:1,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1],
        bexcelGroups: [0,1],

        constructor: function(config) {
            
            Phx.vista.RegistroReclamos.superclass.constructor.call(this,config);

            
        },
        fin_registro:function(paneldoc)
        {
            var d= this.sm.getSelected().data;

            Phx.CP.loadingShow();
            this.cmbRPC.reset();

            this.cmbRPC.store.baseParams.id_uo=d.id_uo;
            this.cmbRPC.store.baseParams.fecha=d.fecha_soli;
            this.cmbRPC.store.baseParams.id_proceso_macro=d.id_proceso_macro;
            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url:'../../sis_adquisiciones/control/Solicitud/finalizarSolicitud',
                params: { id_solicitud: d.id_solicitud, operacion:'verificar', id_estado_wf: d.id_estado_wf },
                argument: { paneldoc: paneldoc},
                success: this.successSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        
        successSinc:function(resp){

            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(!reg.ROOT.error){

                if(resp.argument.paneldoc.panel){
                    resp.argument.paneldoc.panel.destroy();
                }
                this.reload();
            }else{

                alert('ocurrio un error durante el proceso')
            }


        },
        
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            console.log('valor: '+n.data);
            Phx.vista.RegistroReclamos.superclass.preparaMenu.call(this,n);
            //habilitar reporte de colicitud de comrpa y preorden de compra

            if(data['estado']==  'borrador'){
                this.getBoton('sig_estado').enable();

            }
            else {
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();

            }
            
            return tb
        },
        liberaMenu:function(){
            var tb = Phx.vista.RegistroReclamos.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();

            }

            return tb
        }

    };
</script>
