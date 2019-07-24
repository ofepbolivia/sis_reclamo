<?php
/**
*@package pXP
*@file gen-Feriados.php
*@author  (breydi.vasquez)
*@date 09-05-2018 20:44:22
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Feriados=Ext.extend(Phx.gridInterfaz,{


	constructor:function(config){
		this.maestro=config.maestro;
        //this.initButtons=this.cmbGestion;
        //this.ar1 = this.combo_gestion.store.data.items[0].data;
        //llama al constructor de la clase padre
		Phx.vista.Feriados.superclass.constructor.call(this,config);
        this.init();
        this.iniciarEventos();

        /*Ext.Ajax.request({
            url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
            params:{fecha:new Date()},
            success:function(resp){
                var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                this.cmbGestion.setRawValue(reg.ROOT.datos.anho);
                this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
                this.load({params:{start:0, limit:this.tam_pag}});
            },
            failure: this.conexionFailure,
            timeout:this.timeout,
            scope:this
        });*/

        //this.cmbGestion.on('select',this.capturarEventos, this);

        this.load({params:{start:0, limit:this.tam_pag}});

	},

    capturarEventos: function () {
        this.store.baseParams.id_gestion = this.cmbGestion.getValue();
        this.load({params: {start: 0, limit: this.tam_pag}});
    },
    /*combo_gestion : new Ext.form.ComboBox({
        name:'gestion',
        store:['2018','2017','2016','2015','2014','2013','2012','2011','2010'],
        typeAhead: true,
        value: '2018',
        mode: 'local',
        triggerAction: 'all',
        emptyText:'Géstion...',
        selectOnFocus:true,
        width:135,

    }),*/
    cmbGestion: new Ext.form.ComboBox({
        name: 'gestion',
        id: 'gestion_reg',
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
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_feriado'
			},
			type:'Field',
			form:true 
		},

        {
            config:{
                name: 'fecha',
                fieldLabel: 'Feriado',
                allowBlank: false,
                //disabled:true,
                anchor: '40%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'tfdos.fecha',type:'date'},
            id_grupo:1,
            grid:true,
            form:true,
            bottom_filter:true
        },
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripción',
                allowBlank: true,
                //disabled:true,
                anchor: '80%',
                gwidth: 230,
                maxLength:250
            },
            type:'TextField',
            filters:{pfiltro:'tfdos.descripcion',type:'string'},
            id_grupo:1,
            grid:true,
            form:true,
            bottom_filter:true

        },
        {
            config:{
                inputType: 'hidden',
                name: 'tipo',
                fieldLabel: 'Tipo',
                allowBlank: true,
                anchor: '40%',
                gwidth: 100,
                maxLength:4,
                //store:['Nacional','Departamental'],
                renderer:function(value,p,record){
                    if(record.data['tipo'] == 1){
                        return String.format('{0}','Departamental');
                    }else{
                        return String.format('{0}','Nacional');
                    }
                }
            },
            //type:'NumberField',
            type:'ComboBox',
            filters:{pfiltro:'tfdos.tipo',type:'numeric'},
            id_grupo:1,
            grid:true,
            form:true
        },

		{
			config:{
				name: 'id_lugar',
				fieldLabel: ' Lugar',
				allowBlank: false,
				emptyText:'Lugar...',
				store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Lugar/listarLugar',
					id: 'id_lugar',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_lugar','id_lugar_fk','codigo','nombre','tipo','sw_municipio','sw_impuesto','codigo_largo'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{tipos:"''departamento'',''pais'',''localidad''",par_filtro:'nombre'}
				}),
				valueField: 'id_lugar',
				displayField: 'nombre',
				gdisplayField:'lugar',
				hiddenName: 'id_lugar',
    			triggerAction: 'all',
    			lazyRender:true,
				mode:'remote',
				pageSize:50,
				queryDelay:500,
				anchor:"60%",
				gwidth:150,
				forceSelection:true,
				minChars:2,
				renderer:function (value, p, record){return String.format('{0}', record.data['lugar']);}
			},
			type:'ComboBox',
			filters:{pfiltro:'lug.nombre',type:'string'},
			id_grupo:0,
			grid:true,
			form:true,
            bottom_filter:true
		},

		/*{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'tfdos.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},*/
		{
			config:{
				name: 'estado',
				fieldLabel: 'Estado',
				allowBlank: false,
				anchor: '30%',
				gwidth: 70,
				maxLength:2,
                //disabled:true,
                store:['A','I'],
                renderer:function (value,p,record) {
                    if(record.data['estado'] == 'A'){
                        return String.format('{0}','<span style="color: darkgreen">Activo</span>');
                    }else{
                        return String.format('{0}','<span style="color: brown;">Inactivo');
                    }
                }
			},
				//type:'TextField',ComboBox
                type:'ComboBox',
				filters:{pfiltro:'tfdos.estado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config: {
				inputType:'hidden',
				name: 'id_origen',				
				fieldLabel: 'Origen',
				/*
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_',
				displayField: 'nombre',
				gdisplayField: 'desc_',
				hiddenName: 'id_origen',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_']);
				}*/
			},
			type: 'Field',
			//type: 'ComboBox',
			id_grupo: 1,
			//filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: false,
			form: true
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'tfdos.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},

		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'tfdos.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'tfdos.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Feriados',
	ActSave:'../../sis_reclamo/control/Feriados/insertarFeriados',
	ActDel:'../../sis_reclamo/control/Feriados/eliminarFeriados',
	ActList:'../../sis_reclamo/control/Feriados/listarFeriados',
	id_store:'id_feriado',
	fields: [
		{name:'id_feriado', type: 'numeric'},
		{name:'tipo', type: 'numeric'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_lugar', type: 'string'},
        {name:'lugar', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'id_origen', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		
	],
	sortInfo:{
		field: 'fecha',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
    bdel:false,
    bnew:false,

    onButtonNew: function() {
        Phx.vista.Feriados.superclass.onButtonNew.call(this);
            this.Cmp.fecha.enable();
            this.Cmp.descripcion.enable();
            this.Cmp.estado.enable();
    },
    onButtonEdit:function () {
        Phx.vista.Feriados.superclass.onButtonEdit.call(this);
        this.Cmp.fecha.disable();
        this.Cmp.descripcion.disable();
        this.Cmp.estado.disable();

    },
    /*iniciarEventos : function () {

        this.store.baseParams.gestion = '';

        if(this.combo_gestion.getValue()===''){
            this.load({params: {start: 0, limit: this.tam_pag}});
        }
        this.combo_gestion.on('select', function(c,r,i) {
            this.store.baseParams.gestion = this.combo_gestion.getValue();
            if (this.combo_gestion.getValue()) {
                //console.log('gestion',this.store.baseParams.gestion);
                this.load({params: {start: 0, limit: this.tam_pag}});
            }

        } , this);
    },*/

	}

)
</script>
		
		