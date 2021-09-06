<?php 
/**
 *@package pXP
 *@file PendienteRespuesta.php
 *@author  (Franklin Espinoza)
 *@date 17-10-2016 14:45
 *@Interface para el proceso de Respuesta a un Reclamo.
 * */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.PendienteRespuesta = {
        require:'../../../sis_reclamo/vista/reclamo/Reclamo.php',
        requireclase:'Phx.vista.Reclamo',
        title:'Reclamo',
        nombreVista: 'PendienteRespuesta',
        bnew:false,
        bdel:false,
        tam_pag:50,
        gruposBarraTareas:[

            {name:'pendiente_asignacion',title:'<H1 align="center"><i class="fa fa-list-ol"></i> Pendientes Asig.</h1>',grupo:0,height:0},
            {name:'pendiente_respuesta',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Pendientes Resp.</h1>',grupo:1,height:0},
            {name:'respuesta_parcial',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Respuesta Parcial.</h1>',grupo:5,height:0},
            {name:'archivo_con_respuesta',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Archivo con Resp.</h1>',grupo:2,height:0},
            {name:'respuesta_registrado_ripat',title:'<H1 align="center"><i class="fa fa-sitemap"></i>Registrado Ripatt</h1>',grupo:4,height:0},
            {name:'archivado_concluido',title:'<H1 align="center"><i class="fa fa-folder"></i> Archivado/Concl.</h1>',grupo:3,height:0}
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.plazo.setText('');
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0,1,5],
        bdelGroups:  [0,1],
        bactGroups:  [0,1,2,3,4,5],
        btestGroups: [0,1],
        bexcelGroups: [0,1,2,3,4,5],

        constructor: function(config) {
            this.tbarItems = ['-',
                this.cmbGestion,'-'

            ];
            this.maestro=config.maestro;
            this.Atributos.splice(5,0,
             {
                config: {
                    name: 'dias_respuesta',
                    fieldLabel: 'Dias Para Responder',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 150,
                    maxLength: 100,
                    renderer: function(value, p, record) {
                        var dias = record.data.dias_respuesta;
                        var ids = new Array(4, 6, 37, 38, 48, 50);
                        var id_tipo = parseInt(record.data.id_tipo_incidente);


                        if(ids.indexOf(id_tipo) >= 0) {
                            if(record.data.revisado == 'res_ripat' || record.data.revisado == 'con_respuesta'  || record.data.revisado == 'concluido')
                                return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/respondido.png' align='center' width='24' height='24'/></div>");
                            else {
                                switch (dias) {
                                    case '10':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/ten.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '9':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/nine.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '8':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/eight.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '7':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/seven.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '6':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/six.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '5':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/five.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '4':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/four.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '3':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/three.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '2':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/two.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '1':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/one.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '0':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/cero.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '-1':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/vencido.png' align='center' width='24' height='24'/></div>");
                                        break;
                                }
                            }
                        }else if(record.data.id_tipo_incidente==36){
                            if(record.data.revisado == 'res_ripat' || record.data.revisado == 'con_respuesta' || record.data.revisado == 'concluido')
                                return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/respondido.png' align='center' width='24' height='24'/></div>");
                            else {
                                switch (dias) {
                                    case '7':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/seven.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '6':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/six.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '5':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/five.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '4':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/four.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '3':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/three.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '2':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/two.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '1':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/one.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '0':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/cero.png' align='center' width='24' height='24'/></div>");
                                        break;
                                    case '-1':
                                        return String.format('{0}', "<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/media/vencido.png' align='center' width='24' height='24'/></div>");
                                        break;
                                }
                            }
                        }
                    }
             },
             type: 'Checkbox',
             id_grupo:1,
             grid: true,
             form: false
            });

            this.Atributos.unshift({
                config:{
                    name: 'revisado',
                    fieldLabel: 'Registrado Ripatt',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer:function (value, p, record){
                        var revisado = record.data['revisado'];
                        var estado = record.data['estado'];

                        if(revisado == 'asignacion')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Asignacion'  src = '../../../sis_reclamo/reportes/24-hours.png' align='center' width='24' height='24'/></div>");
                        else if (revisado == 'respuesta')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Pendiente de Respuesta'  src = '../../../lib/imagenes/warning.png' align='center' width='24' height='24'/></div>");
                        else if (revisado == 'proceso')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Procesando Respuesta'  src = '../../../lib/imagenes/a_form_edit.png' align='center' width='24' height='24'/></div>");
                        else if (revisado == 'con_respuesta')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Con Respuesta'  src = '../../../lib/imagenes/icono_dibu/dibu_send_mail.png' align='center' width='24' height='24'/></div>");
                        else if (revisado == 'res_ripat')
                            return  String.format('{0}',"<div style='text-align:center'><img title='Reclamo Con Respuesta Registro Ripatt'  src = '../../../lib/imagenes/icono_dibu/dibu_documents.png' align='center' width='24' height='24'/></div>");
                        else if (revisado == 'falla_envio') {
                                p.style = "background-color:#EAA8A8";
                            return String.format('{0}', "<div style='text-align:center'><img title='El envio de la Respuesta Fallo.'  src = '../../../sis_reclamo/media/envio_128.png' align='center' width='24' height='24'/></div>");
                        }
                        else
                            return  String.format('{0}',"<div style='text-align:center'><img title='Respuesta Registrado Ripatt'  src = '../../../sis_reclamo/reportes/service.png' align='center' width='24' height='24'/></div>");
                    }
                },
                type:'Checkbox',
                filters:{pfiltro:'rec.revisado',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            });

            Phx.vista.PendienteRespuesta.superclass.constructor.call(this,config);
            this.getBoton('ant_estado').setVisible(true);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            //primera carga
            this.store.baseParams.pes_estado = 'pendiente_asignacion';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

            Ext.Ajax.request({
                url:'../../sis_reclamo/control/Reclamo/getDatosOficina',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));

                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    console.log(reg.ROOT.datos.id_gestion);
                    this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                    this.load({params:{start:0, limit:this.tam_pag}});

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            this.addButton('verificar_estado',{
                grupo: [0,1,2,3,4],
                text: 'Verificar Estado',
                iconCls: 'bfolder',
                disabled: false,
                handler: this.verificarEstado,
                tooltip: '<b>Permite revisar los reclamos en Registrado Rippat</b>',
                scope:this
            });

            this.addButton('fails',{
                grupo: [0,1,2,3,4],
                text: 'Falla Correo',
                iconCls: 'binfo',
                disabled: false,
                handler: this.verificarFails,
                tooltip: '<b>Permite ver los reclamos que fallaron, al enviar correo de Respuesta.</b>',
                scope:this
            });

            this.cmbGestion.on('select',this.capturarEventos, this);

            this.plazo = new Ext.form.Label({
                name: 'fecha_limite_resp',
                grupo: [0,1,2,3,4,5],
                fieldLabel: 'Fecha',
                allowBlank: false,
                anchor: '60%',
                gwidth: 100,
                format: 'd/m/Y',
                hidden : false,
                readOnly:true,
                style: 'font-size: 25pt; font-weight: bold; background-image: none; color: #ff4040;'
            });
            
            this.tbar.addField(this.plazo);

        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion_pr',
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
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
        },

        verificarEstado: function () {
            var rec=this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_reclamo/vista/reclamo/VerificarEstado.php',
                'Verificar Reclamos',
                {
                    width:'80%',
                    height:'80%'
                },
                rec.data,
                this.idContenedor,
                'VerificarEstado'
            )
        },

        enableTabRespuesta:function(){
            if(this.TabPanelSouth.get(1)){
                this.TabPanelSouth.get(1).enable();
                this.TabPanelSouth.setActiveTab(1)
            }
        },

        disableTabRespuesta:function(){
            if(this.TabPanelSouth.get(1)){
                this.TabPanelSouth.get(1).disable();
                this.TabPanelSouth.setActiveTab(0)
            }
        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.PendienteRespuesta.superclass.preparaMenu.call(this,n);

            if(data.estado =='pendiente_asignacion'){
                this.disableTabRespuesta();
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();

            }else if(data.estado =='pendiente_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();
                this.enableTabRespuesta();
            }else if(data.estado =='respuesta_parcial'){
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();
                this.enableTabRespuesta();
            }else if(data.estado == 'respuesta_parcial' ){
                this.getBoton('sig_estado').enable();
                if(data.administrador ==1){
                    this.getBoton('ant_estado').enable();
                }
                else{
                    this.getBoton('ant_estado').disable();
                }
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();
                this.enableTabRespuesta();
            }else if(data.estado =='archivo_con_respuesta' ){
                if (this.nombreVista == 'PendienteRespuesta' && data.administrador ==1){
                    this.getBoton('sig_estado').enable();
                    this.getBoton('ant_estado').enable();
                    this.getBoton('verificar_estado').enable();
                    this.getBoton('fails').enable();
                    this.enableTabRespuesta();
                }
                else{
                    this.getBoton('sig_estado').enable();
                    this.getBoton('ant_estado').disable();
                    this.getBoton('verificar_estado').enable();
                    this.getBoton('fails').enable();
                    this.enableTabRespuesta();
                }
            }else if(data.estado == 'respuesta_registrado_ripat' ){
                this.getBoton('sig_estado').enable();
                if(data.administrador ==1){
                    this.getBoton('ant_estado').enable();
                }
                else{
                    this.getBoton('ant_estado').disable();
                }
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();
                //this.getBoton('reportes').enable();
                this.enableTabRespuesta();
            }else if(data.estado == 'archivado_concluido'){
                this.getBoton('sig_estado').enable();
                if(data.administrador ==1){
                    this.getBoton('ant_estado').enable();
                }
                else{
                    this.getBoton('ant_estado').disable();
                }
                this.getBoton('verificar_estado').enable();
                this.getBoton('fails').enable();
                this.enableTabRespuesta();
            }
            return tb
        },
        
        liberaMenu:function(){
            var tb = Phx.vista.PendienteRespuesta.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('verificar_estado').disable();
                this.getBoton('fails').disable();
            }
            this.disableTabRespuesta();
            return tb
        },

        onButtonEdit: function() {
            Phx.vista.Reclamo.superclass.onButtonEdit.call(this)
            this.momento = 'edit';
            console.log(this.momento);
        },

        verificarFails: function () {
            var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_reclamo/vista/reclamo/CorreosFail.php',
                'Correos No Enviados ',
                {
                    width:'80%',
                    height:'80%'
                },
                rec.data,
                this.idContenedor,
                'CorreosFail'
            );
        }

    };
</script>
