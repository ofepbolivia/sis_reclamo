<?php
/**
 *@package pXP
 *@file ConsultaReclamo.php
 *@author  (Franklin Espinoza)
 *@date 17-10-2016 14:45
 *@Interface para consultar todos los reclamos que se tiene .
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConsultaReclamo = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'ConsultaReclamo',
        bnew:false,
        bdel:false,
        bedit:false,

        constructor: function(config) {
            this.tbarItems = ['-',
                this.cmbGestion

            ];
            Phx.vista.ConsultaReclamo.superclass.constructor.call(this,config);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('btnChequeoDocumentosWf').setVisible(false);
            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = '';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.cmbGestion.on('select',this.capturarEventos, this);
        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion',
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

        capturarEventos: function () {
            this.store.baseParams.pes_estado = 'borrador';
        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ConsultaReclamo.superclass.preparaMenu.call(this,n);
            return tb
        },

        liberaMenu:function(){
            var tb = Phx.vista.ConsultaReclamo.superclass.liberaMenu.call(this);
            return tb;
        }

    };
</script>
