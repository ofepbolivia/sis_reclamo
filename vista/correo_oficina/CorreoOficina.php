<?php
/**
*@package pXP
*@file gen-CorreoOficina.php
*@author  (franklin.espinoza)
*@date 11-05-2018 22:27:57
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CorreoOficina=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config;
    	//llama al constructor de la clase padre
		Phx.vista.CorreoOficina.superclass.constructor.call(this,config);

		console.log('maestro',config.id_oficina);
		this.init();
        this.store.baseParams.id_oficina = config.id_oficina;
        console.log('master',this.maestro.id_oficina);

		this.load({params:{start:0, limit:this.tam_pag}});

	},

    /*onReloadPage:function(param){
        //Se obtiene la gestión de los Reclamos.
        this.maestro = param;
        this.initFiltro(param);
    },

    initFiltro: function(param){
        console.log('parametros',param);
        this.store.baseParams=param;
        this.load( { params: { start:0, limit: this.tam_pag } });
    },*/

    onButtonNew:function(){
        Phx.vista.CorreoOficina.superclass.onButtonNew.call(this);
        this.Cmp.id_oficina.setValue(this.maestro.id_oficina);
        console.log('onButtonNew', this.Cmp.id_oficina.getValue(),this.maestro.id_oficina);

    },

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_correo_oficina'
			},
			type:'Field',
			form:true
		},

        /*{
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_oficina'
            },
            type:'Field',
            form:true
        },*/

		{
			config:{
				name: 'id_oficina',
				fieldLabel: 'Oficina',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
				maxLength:70,
                inputType:'hidden',
                renderer:function (value, p, record){
				    return String.format('<b style="color:green;">{0}</b>', record.data['desc_oficina']);
				}
			},
				type:'TextField',
				filters:{pfiltro:'cof.id_oficina',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},

        {
            config:{
                name: 'id_funcionario',
                fieldLabel: 'Funcionario',
                allowBlank: false,
                anchor: '80%',
                gwidth: 250,
                maxLength:70,
                style:'text-transform:uppercase;'
            },
            type:'TextField',
            filters:{pfiltro:'cof.id_funcionario',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },

        {
            config:{
                name: 'correo',
                fieldLabel: 'Correo',
                allowBlank: false,
                anchor: '80%',
                gwidth: 150,
                maxLength:70,
                vtype: 'email'
            },
            type:'TextField',
            filters:{pfiltro:'cof.correo',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },

        {
            config:{
                name: 'fecha_ini',
                fieldLabel: 'Fecha Inicio',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'cof.fecha_ini',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },

        {
            config:{
                name: 'fecha_fin',
                fieldLabel: 'Fecha Fin',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'cof.fecha_fin',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },

		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'cof.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
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
				filters:{pfiltro:'cof.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'cof.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'cof.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
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
				filters:{pfiltro:'cof.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'CorreoOficina',
	ActSave:'../../sis_reclamo/control/CorreoOficina/insertarCorreoOficina',
	ActDel:'../../sis_reclamo/control/CorreoOficina/eliminarCorreoOficina',
	ActList:'../../sis_reclamo/control/CorreoOficina/listarCorreoOficina',
	id_store:'id_correo_att',
	fields: [
		{name:'id_correo_oficina', type: 'numeric'},
		{name:'correo', type: 'string'},
		{name:'id_oficina', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_oficina', type: 'string'},
		{name:'fecha_ini', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_fin', type: 'date',dateFormat:'Y-m-d'}

	],
	sortInfo:{
		field: 'id_correo_oficina',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		