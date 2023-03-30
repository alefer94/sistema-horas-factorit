system.projects = system.projects || { 

	init: function(){
		$("#tbl-mantenedor tbody tr button").tooltip();
		$("#tbl-mantenedor thead span.btn-filtrar").tooltip();
		//system.select2_clientes( $tbody.find("input.slt-clientes") );
	},

	alAgregar: function(){
		var $tbody = $("#tbl-mantenedor tbody");
		$tbody.prepend('<tr>'+newRow()+'</tr>');
		$("#content").scrollTop(0);
		
		var $tr = $tbody.find("tr:first");
		$tr.find("select").select2();
		$tr.find("button").tooltip();
	},

	alFiltrar: function(event, columna_filtro){
		system.modalCarga("Cargando...");

		var promise = $.get(
			"ajax/getModal.php",
			{ 
				'id': "filtro_proyectos_"+columna_filtro
			},
			function(data){
				system.modal(
					'<h4 class="modal-title bold">Filtrar por '+columna_filtro+'</h4>',
					data,
					"complete"
				);
			},
			"html"
			
		);

		promise.fail(system.modalError);
	},

	alConfirmarFiltro: function(event){
		var $this = $(event.target).closest("form");
		var ajaxData = { 'tabla': 'proyectos' };
		var filtrado = false;

		$this.serializeArray().forEach(element => { 
			ajaxData[element.name] = element.value;
			if (!filtrado && !!element.value) { filtrado = true; } 
		});

		var $modal = system.modalCarga("Cargando...");
		
		var promise = $.get(
			"ajax/getTabla.php",
			ajaxData,
			function(data){
				$("#tbl-mantenedor thead span.btn-filtrar").text("(Filtrado)").append('<i class="fa fa-filter" aria-hidden="true"></i>');
				if (filtrado) { 
					var $modalTrigger = $("#"+ $this.attr("for") );
					$modalTrigger.text("(Filtrado)").append('<i class="fa fa-filter" aria-hidden="true"></i>'); 
				}

				$("#tbl-mantenedor tbody").html(data);
				$modal.modal("hide");
				$modal.remove();
			},
			"html"
		);

		promise.fail(system.modalError);
	},

	alEditar: function(event){
		var $tr = $(event.target).closest("tr");
		var prjID 		= Number($tr.data("sid"));
		var codigo 		= $tr.find("td:eq(0)").text();
		var nombre 		= $tr.find("td:eq(1)").text();
		var cltID 		= Number($tr.find("td:eq(2)").data("sid"));
		var usrIDJefe 	= Number($tr.find("td:eq(3)").data("sid"));
		var usrIDComercial 	= Number($tr.find("td:eq(4)").data("sid"));
		var estado_facturacion_pry = $tr.find("td:eq(5)").text();
		var estado_pry = $tr.find("td:eq(6)").text();
		var ccoID = Number($tr.find("td:eq(7)").data("sid"));
		var codigoPadre = $tr.find("td:eq(8)").text();
		var costoVenta	= Number(parseFloat($tr.find("td:eq(9)").text()));
		var margenEsperado = Number($tr.find("td:eq(10)").text().slice(0,-1));
		var vprID = Number($tr.find("td:eq(11)").data("sid"));
		var gerID = Number($tr.find("td:eq(12)").data("sid"));
		var sgrID = Number($tr.find("td:eq(13)").data("sid"));
		
		
		$tr.html(newRow());
		$tr.data("sid", prjID);
		$tr.find("input.ipt-codigo").val(codigo);
		$tr.find("input.ipt-nombre").val(nombre);
		$tr.find("select.slt-clientes").val(cltID).select2();
		$tr.find("select.slt-jefes").val(usrIDJefe).select2();
		$tr.find("select.slt-comercial").val(usrIDComercial).select2();
		$tr.find("select.slt-estado-facturacion-pry").val(estado_facturacion_pry).select2();
		$tr.find("select.slt-estado-pry").val(estado_pry).select2();
		$tr.find("select.slt-centro-cos").val(ccoID).select2();
		$tr.find("input.ipt-codigo-padre").val(codigoPadre);
		$tr.find("input.ipt-costo-venta").val(costoVenta);
		$tr.find("input.ipt-margen-esperado").val(margenEsperado);
		$tr.find("select.slt-vicepresidencias").val(vprID).select2();
		$tr.find("select.slt-gerencias").val(gerID).select2();
		$tr.find("select.slt-subgerencias").val(sgrID).select2();
		$tr.find("button.btn-submit").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");		
		
		$this.prop("disabled", true);

		system.modalCarga("Guardando...");
		var codigo_iniciales = "";
		var codigo_identificador = "";
		var codigo_identificador_2 = "";
		var valoresAceptados = "/^[0-9]+$/";

		var $ipt_codigo = $tr.find("input.ipt-codigo");
		var $ipt_nombre = $tr.find("input.ipt-nombre");
		var $slt_cliente = $tr.find("select.slt-clientes");
		var $slt_jefe = $tr.find("select.slt-jefes");
		var $slt_comercial = $tr.find("select.slt-comercial");
		var $slt_estado_facturacion_pry = $tr.find("select.slt-estado-facturacion-pry");
		var $slt_estado_pry = $tr.find("select.slt-estado-pry");
		var $slt_centro_cos = $tr.find("select.slt-centro-cos");
		var $ipt_codigo_padre = $tr.find("input.ipt-codigo-padre");
		var $ipt_costo_venta = $tr.find("input.ipt-costo-venta");
		var $ipt_margen_esperado = $tr.find("input.ipt-margen-esperado");
		var $slt_vicepresidencias = $tr.find("select.slt-vicepresidencias");
		var $slt_gerencias = $tr.find("select.slt-gerencias");
		var $slt_subgerencias = $tr.find("select.slt-subgerencias");
		
		
		if ($ipt_codigo[0].checkValidity() &&
			$ipt_nombre[0].checkValidity() &&
			$slt_cliente[0].checkValidity()){	

			var prjID = Number($tr.data("sid"));
			var existe = !isNaN(prjID);
			var usrIDJefe = Number($slt_jefe.val());
			var usrIDComercial = Number($slt_comercial.val());

			if($ipt_codigo.val().length != 7){
				system.modalInformacion('Alerta ','El codigo de proyecto debe tener 7 caracteres.');
				$this.prop("disabled", false);
			}
			else{
				codigo_iniciales = $ipt_codigo.val().substring(0,3).toUpperCase();			
				
				if (codigo_iniciales == "COP" || codigo_iniciales == "FIT") {
					codigo_identificador = $ipt_codigo.val().substring(3,7);
					console.log(codigo_identificador);	

					codigo_identificador_2 = parseInt(codigo_identificador);
					console.log(codigo_identificador_2);

				/*	if (codigo_identificador.match(valoresAceptados)){*/
				/*	if (Number.isInteger(codigo_identificador)) {*/
					if (codigo_identificador_2 != null
						&& codigo_identificador_2 != undefined 
						&& !(isNaN(codigo_identificador_2))
						&& (codigo_identificador_2 >=0 && codigo_identificador_2 <=9999 )){	
							
						system.modalInformacion('Alerta ','codigo correcto');
						var promise = $.post(
							"ajax/" + (existe? "update" : "add") + "Proyecto.php",
							{
								'prjID': prjID,
								'prjCodigo': $ipt_codigo.val(),
								'prjNombre': $ipt_nombre.val(),
								'cltID': Number($slt_cliente.val()),
								'usrIDJefe': isNaN(usrIDJefe)? 0: usrIDJefe,
								'usrIDComercial': isNaN(usrIDComercial)? 0: usrIDComercial,
								'prjFlagFacturacion': $slt_estado_facturacion_pry.val(),
								'prjEstadoProyecto': $slt_estado_pry.val(),
								'ccoID': Number($slt_centro_cos.val()),
								'prjCodigoPadre': $ipt_codigo_padre.val(),
								'prjCostoVenta': Number($ipt_costo_venta.val()),
								'prjMargenEsperado': Number($ipt_margen_esperado.val()),
								'vprID': Number($slt_vicepresidencias.val()),
								'gerID': Number($slt_gerencias.val()),
								'sgrID': Number($slt_subgerencias.val()),
								
							},
							(function (data) {
								system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
								
								if (data[0]) {
									if (data.length === 3) {
										$tr.data("sid", data[2]);
									
									}
									
									
									var $opt_cliente = $slt_cliente.find("option:selected");
									var $opt_jefe = $slt_jefe.find("option:selected");
									var $opt_comercial = $slt_comercial.find("option:selected");
									var $opt_estado_facturacion_pry = $slt_estado_facturacion_pry.find("option:selected");
									var $opt_estado_pry = $slt_estado_pry.find("option:selected");
									var $opt_centro_cos = $slt_centro_cos.find("option:selected");
									var $opt_vicepresidencias = $slt_vicepresidencias.find("option:selected");
									var $opt_gerencias = $slt_gerencias.find("option:selected");
									var $opt_subgerencias = $slt_subgerencias.find("option:selected");
									
									var $td_buttons = $tr.find("td:eq(14)");
									
									$tr.find("td:eq(0)").removeClass("no-padding").html($ipt_codigo.val());
									$tr.find("td:eq(1)").removeClass("no-padding").html($ipt_nombre.val());
									$tr.find("td:eq(2)").removeClass("no-padding no-border").data("sid", $opt_cliente.val()).html($opt_cliente.text());
									$tr.find("td:eq(3)").removeClass("no-padding no-border").data("sid", $opt_jefe.val()).html($opt_jefe.text());
									$tr.find("td:eq(4)").removeClass("no-padding no-border").data("sid", $opt_comercial.val()).html($opt_comercial.text());
									$tr.find("td:eq(5)").removeClass("no-padding no-border").data("sid", $opt_estado_facturacion_pry.val()).html($opt_estado_facturacion_pry.text());
									$tr.find("td:eq(6)").removeClass("no-padding no-border").data("sid", $opt_estado_pry.val()).html($opt_estado_pry.text());
									$tr.find("td:eq(7)").removeClass("no-padding no-border").data("sid", $opt_centro_cos.val()).html($opt_centro_cos.text());
									$tr.find("td:eq(8)").removeClass("no-padding").html($ipt_codigo_padre.val());
									$tr.find("td:eq(9)").removeClass("no-padding").addClass("text-center b-r b-grey").html($ipt_costo_venta.val());
									$tr.find("td:eq(10)").removeClass("no-padding").addClass("text-center b-r b-grey").html($ipt_margen_esperado.val());
									$tr.find("td:eq(11)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $opt_vicepresidencias.val()).html($opt_vicepresidencias.text());
					                $tr.find("td:eq(12)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $opt_gerencias.val()).html($opt_gerencias.text());
					                $tr.find("td:eq(13)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $opt_subgerencias.val()).html($opt_subgerencias.text());
									
									$td_buttons.html(updatedTd());
									
									$td_buttons.find("button").tooltip();
			
									
								}
							}),
							"json"
							
						);
						promise.fail(system.modalError);
						promise.always(function(){ $this.prop("disabled", false); });
					}
					else {
						system.modalInformacion('Alerta ','El Formato de codigo de proyecto debe ser el siguiente "COP9999" o "FIT9999" , error en la parte final del codigo.');
						$this.prop("disabled", false);
					}
				} 
				else {
					system.modalInformacion('Alerta ','El Formato de codigo de proyecto debe ser el siguiente "COP9999" o "FIT9999" , error en la parte inicial del codigo.');	
					$this.prop("disabled", false);
				}
				
			}
		}
	},

	alVerDetalles: function(event){
		var $tr = $(event.target).closest("tr");
		var prjID = Number($tr.data("sid"));
		var $pagina = $("#content");
		var $tabla_proyectos = $("#tbl-mantenedor");
		
		var $modal = system.modalCarga("Cargando...");
		$tabla_proyectos.hide();
		$("#btn-agregar").hide();
		
		var promise = $.get(
			"ver_proyecto",
			{ 'prjID': prjID },
			function(data){
				$modal.modal("hide");
				$modal.remove();
				$("#btn-salir_detalles").show();
				$pagina.append(data);
			},
			"html"
		);
		promise.fail(function(event) {
			system.modalError(event);
			$("#btn-agregar").show();
			$tabla_proyectos.show();
		});
	},

	alVolverDetalles: function(event){
		$div_detalles = $("#div-detalles_proyecto");
		$div_detalles.hide(); 
		$("#tbl-mantenedor").show();
		$("#btn-agregar").show();
		$div_detalles.remove();
		$(event.target).hide();
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var prjID = Number($tr.data("sid"));
		var existe = !isNaN(prjID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un proyecto",
				"Si realiza esta acción, se borrarán el proyecto y todas las horas de usuarios registradas a nombre de éste.<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.projects.alConfirmarEliminacion($tr); }
			);
		}
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteProyecto.php",
			{ 'prjID': Number($tr.data("sid")) },
			function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }	
			},
			"json"
		);
		
		promise.fail(system.modalError);
	}
	
};

$(document).ready(function(){ 
	system.alAgregar = system.projects.alAgregar;
	system.projects.init(); 
});