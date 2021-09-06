<?php
/**
*@package pXP
*@file gen-Reporte.php
*@author  (admin)
*@date 12-10-2016 19:21:51
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.LibroReclamo= Ext.extend(Phx.frmInterfaz, {
		Atributos : [

			{
				config: {
					name: 'id_oficina_registro_incidente',
					fieldLabel: 'Oficina Reclamo',
					allowBlank: true,
					emptyText: 'Elija una opción...',
					store: new Ext.data.JsonStore({
						url: '../../sis_organigrama/control/Oficina/listarOficina',
						id: 'id_oficina',
						root: 'datos',
						sortInfo: {
							field: 'nombre',
							direction: 'ASC'
						},
						totalProperty: 'total',
						fields: ['id_oficina', 'nombre', 'codigo','nombre_lugar'],
						remoteSort: true,
						baseParams: {par_filtro: 'ofi.nombre#ofi.codigo#lug.nombre'}
					}),
					valueField: 'id_oficina',
					displayField: 'nombre',
					gdisplayField: 'desc_oficina_registro_incidente',
					hiddenName: 'id_oficina',
					forceSelection: true,
					typeAhead: false,
					triggerAction: 'all',
					lazyRender: true,
					mode: 'remote',
					pageSize: 10,
					queryDelay: 1000,
					anchor: '30%',
					gwidth: 150,
					minChars: 2,
					resizable:true,
					listWidth:'175',
					renderer: function (value, p, record) {
						return String.format('{0}', record.data['desc_oficina_registro_incidente']);
					}
				},
				type: 'ComboBox',
				id_grupo: 1,
				filters: {pfiltro: 'ofi.nombre#ofi.codigo#lug.nombre', type: 'string'},
				grid: true,
				form: true
			},
			{
				config:{
					name: 'fecha_ini',
					fieldLabel: 'Fecha Inicio',
					allowBlank: false,
					anchor: '30%',
					gwidth: 100,
					format: 'd/m/Y',
					renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
				},
				type:'DateField',
				filters:{pfiltro:'fecha_ini',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
			},
			{
				config:{
					name: 'fecha_fin',
					fieldLabel: 'Fecha Fin',
					allowBlank: false,
					anchor: '30%',
					gwidth: 100,
					format: 'd/m/Y',
					renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
				},
				type:'DateField',
				filters:{pfiltro:'fecha_fin',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
			}
		],
		title : 'Generar Reporte',
		ActSave : '../../sis_reclamo/control/Cliente/libroReclamo',
		topBar : true,
		botones : false,
		labelSubmit : 'Imprimir',
		tooltipSubmit : '<b>Generar PDF</b>',
		constructor : function(config) {
			Phx.vista.LibroReclamo.superclass.constructor.call(this, config);
			this.init();
		},


		iniciarEventos:function(){
			this.cmpFechaIni = this.getComponente('fecha_ini');
			this.cmpFechaFin = this.getComponente('fecha_fin');
		},
		tipo : 'reporte',
		clsSubmit : 'bprint',

		agregarArgsExtraSubmit: function() {

			this.argumentExtraSubmit.oficina = this.Cmp.id_oficina_registro_incidente.getRawValue();
		}

	})
</script>
