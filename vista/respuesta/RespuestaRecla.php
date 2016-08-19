<?php
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RespuestaRecla = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Respuesta del Reclamo',
        tipo: 'RespuestaRecla',
        constructor: function (config) {
            this.maestro = config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.RespuestaRecla.superclass.constructor.call(this, config);
            this.init();
            this.load({params: {start: 0, limit: this.tam_pag}})
        },

        tabsouth :[{
            url:'../../../sis_reclamo/vista/respuesta/Respuesta.php',
            title:'Respuesta',
            height:'50%',
            cls:'Respuesta'
        }],
        bedit:false,
        bnew:false,
        bdel:false,
        bsave:false
    };
</script>