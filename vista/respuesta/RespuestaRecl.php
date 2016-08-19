<?php
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RespuestaRecl={
        bedit:false,
        bnew:false,
        bsave:false,
        bdel:false,
        require: '../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase: 'Phx.vista.Reclamo',
        title: 'Reclamo',
        nombreVista: 'RespuestaRecl',

        constructor: function (config) {
            this.maestro=config.maestro;

            this.Atributos[this.getIndAtributo('id_reclamo')].form=false;
            this.Atributos[this.getIndAtributo('id_tipo_incidente')].form=false;
            this.Atributos[this.getIndAtributo('id_funcionario_recepcion')].form=false;
            this.Atributos[this.getIndAtributo('id_cliente')].form=false;
            this.Atributos[this.getIndAtributo('fecha_hora_recepcion')].form=false;
            this.Atributos[this.getIndAtributo('nro_tramite')].form=false;

            //funcionalidad para listado de historicos
            this.historico = "no";
            this.tbarItems = ['-',{
                text: 'Histórico',
                enableToggle: true,
                pressed: false,
                toggleHandler: function(btn, pressed) {

                    if(pressed){
                        this.historico = 'si';
                        this.desBotoneshistorico();
                    }
                    else{
                        this.historico = 'no'
                    }

                    this.store.baseParams.historico = this.historico;
                    this.reload();
                },
                scope: this
            }];
            Phx.vista.RespuestaRecl.superclass.constructor.call(this,config);
            this.addButton('ini_estado',{  argument: {estado: 'inicio'},text:'Dev. al Solicitante',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Retorna la Solcitud al estado borrador</b>'});
            this.addButton('ant_estado',{ argument: {estado: 'anterior'},text:'Rechazar',iconCls: 'batras',disabled:true,handler:this.antEstado,tooltip: '<b>Pasar al Anterior Estado</b>'});
            this.addButton('sig_estado',{ text:'Aprobar', iconCls: 'badelante', disabled: true, handler: this.sigEstado, tooltip: '<b>Pasar al Siguiente Estado</b>'});


            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //coloca filtros para acceso directo si existen
            if(config.filtro_directo){
                this.store.baseParams.filtro_valor = config.filtro_directo.valor;
                this.store.baseParams.filtro_campo = config.filtro_directo.campo;
            }
            //carga inicial de la pagina
            this.load({params:{start:0, limit:this.tam_pag}});



            if(this.nombreVista == 'solicitudvbpoa') {
                this.addButton('obs_poa',{ text:'Datos POA', disabled:true, handler: this.initObs, tooltip: '<b>Código de actividad POA</b>'});
                this.crearFormObs();
            }
            if(this.nombreVista == 'solicitudvbpresupuestos') {
                this.addButton('obs_presu',{text:'Obs. Presupuestos', disabled:true, handler: this.initObs, tooltip: '<b>Observacioens del área de presupuesto</b>'});
                this.crearFormObs();
            }

            console.log('configuracion',config, this.nombreVista)



        },











    };
</script>
