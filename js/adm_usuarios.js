 system.users = system.users || { 

	init: function(){	
		
		var $filas = $("#tbl-mantenedor tbody tr"); 
		$filas.find("button").tooltip();
	},

	alEditar: function(event){

		console.log("aqui1");
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");

		var usrID = Number($tr.data("sid"));
		var nombre = $tr.find("td:eq(0)").text();
		var mail = $tr.find("td:eq(1)").text();
		var tipo = Number($tr.find("td:eq(2)").data("sid"));
		var cargo_contractual = $tr.find("td:eq(3)").text();
		var cargo_generico = Number($tr.find("td:eq(4)").data("sid"));
		var nivelHAY = Number($tr.find("td:eq(5)").text());
		var tipo_contratos = Number($tr.find("td:eq(6)").data("sid"));
		var rut = $tr.find("td:eq(7)").text(); 
		var dv = "";
		var fecha_ingreso = $tr.find("td:eq(8)").text();
		var fecha_desvinculacion = $tr.find("td:eq(9)").text();
		var empresa = $tr.find("td:eq(10)").text();
		/*var area2 = $tr.find("td:eq(7)").text();*/
		var estado_facturacion = $tr.find("td:eq(11)").text();
		var estado = Number($tr.find("td:eq(12)").data("sid"));
		var vprID = Number($tr.find("td:eq(13)").data("sid"));
		var gerID = Number($tr.find("td:eq(14)").data("sid"));
		var sgrID = Number($tr.find("td:eq(15)").data("sid"));
		var buttons = $tr.find("td:eq(16)");
		
		if (rut.includes("-")){
			var fullRut = rut.split("-");
			rut = fullRut[0];
			dv = fullRut[1];
		}	
		
		$tr.html(newRow());
		$tr.data("sid", usrID);
		$tr.find("input.ipt-nombre").val(nombre);
		$tr.find("input.ipt-mail").val(mail);
		$tr.find("select.slt-tipo").val(tipo).select2();
		$tr.find("input.ipt-cargo_contractual").val(cargo_contractual);
		$tr.find("select.slt-cargo_generico").val(cargo_generico).select2();
		$tr.find("input.ipt-nivelHAY").val(nivelHAY);
		$tr.find("select.slt-tipo_contratos").val(tipo_contratos).select2();
		$tr.find("input.ipt-rut").val(rut);
		$tr.find("input.ipt-dv").val(dv);
		$tr.find("input.ipt-fecha-ingreso").val(fecha_ingreso);
		$tr.find("input.ipt-fecha-desvinculacion").val(fecha_desvinculacion);
		$tr.find("select.slt-empresa").val(empresa).select2();
		$tr.find("select.slt-area").val(null).select2();
		$tr.find("select.slt-estado-facturacion").val(estado_facturacion).select2();
		$tr.find("select.slt-estado").val(estado).select2();
		$tr.find("select.slt-vicepresidencias").val(vprID).select2();
		$tr.find("select.slt-gerencias").val(gerID).select2();
		$tr.find("select.slt-subgerencias").val(sgrID).select2();
		
		

		buttons.find("button").tooltip();
		
		
		console.log(buttons);
		console.log("aqui2");
	},
	
	alGuardar: function(event){
		
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		
		var usrID = Number($tr.data("sid"));
		var existe = !isNaN(usrID);
		var nombre = $tr.find("input.ipt-nombre").val().trim();
		var mail = $tr.find("input.ipt-mail").val().trim();
		var tipo = Number($tr.find("select.slt-tipo").val());
		var cargo_contractual = $tr.find("input.ipt-cargo_contractual").val().trim();
		var cargo_generico = Number($tr.find("select.slt-cargo_generico").val());
		var nivelHAY = $tr.find("input.ipt-nivelHAY").val();
		var tipo_contratos = Number($tr.find("select.slt-tipo_contratos").val());
		var rut = Number($tr.find("input.ipt-rut").val().trim());
		var dv = $tr.find("input.ipt-dv").val().trim();
		var fecha_ingreso = $tr.find("input.ipt-fecha-ingreso").val().trim();
		var fecha_desvinculacion = $tr.find("input.ipt-fecha-desvinculacion").val().trim();
		var empresa = $tr.find("select.slt-empresa").val();
		var area2 = $tr.find("select.slt-area").val(null);
		var estado_facturacion = $tr.find("select.slt-estado-facturacion").val();
		var estado = Number($tr.find("select.slt-estado").val());
		var vicepresidencias = Number($tr.find("select.slt-vicepresidencias").val());
		var gerencias = Number($tr.find("select.slt-gerencias").val());
		var subgerencias = Number($tr.find("select.slt-subgerencias").val());

		
		var promise = $.post(
			"ajax/" + (existe? "update" : "add") + "Usuario.php",
			{
				'usrID': usrID,
				'usrNombre': nombre,
				'usrMail': mail,
				'tpUsrID': tipo,
				'usrRut': rut,
				'usrDv': dv,
				'usrFechaIngreso': fecha_ingreso,
				'usrFechaDesvinculacion': fecha_desvinculacion,
				'usrEmpresa': empresa,
				'usrArea': null,
				'usrFlagFacturable': estado_facturacion,
				'usrEstID': estado,
				'vprID': vicepresidencias, 
				'gerID': gerencias,
				'sgrID': subgerencias,
				'cgcID': cargo_generico,
				'tctID': tipo_contratos,
				'usrCargoContractual': cargo_contractual,
				'usrNivelHAY':nivelHAY
			},
			function (data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				
				if (data[0]) {
					if (data.length === 3){
						$tr.data("sid", data[2]);
					}
					
					var $tipo = $tr.find("select.slt-tipo option:selected");
					var $cargo_generico = $tr.find("select.slt-cargo_generico option:selected");
					var $tipo_contratos = $tr.find("select.slt-tipo_contratos option:selected");
					var $empresa = $tr.find("select.slt-empresa option:selected");
					var $area = '';
					var $estado_facturacion = $tr.find("select.slt-estado-facturacion option:selected");
					var $estado = $tr.find("select.slt-estado option:selected");
					var $vicepresidencias = $tr.find("select.slt-vicepresidencias option:selected");
					var $gerencias = $tr.find("select.slt-gerencias option:selected");
					var $subgerencias = $tr.find("select.slt-subgerencias option:selected");
					var $buttons = $tr.find("td:eq(16)");
					
					$tr.find("td:eq(0)").removeClass("no-padding").addClass("b-r b-grey").html(nombre);
					$tr.find("td:eq(1)").removeClass("no-padding").addClass("text-center b-r b-grey").html(mail);
					$tr.find("td:eq(2)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $tipo.val()).html($tipo.text());
					$tr.find("td:eq(3)").removeClass("no-padding").addClass("b-r b-grey").html(cargo_contractual);
					$tr.find("td:eq(4)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $cargo_generico.val()).html($cargo_generico.text());
					$tr.find("td:eq(5)").removeClass("no-padding").addClass("b-r b-grey").html(nivelHAY);
					$tr.find("td:eq(6)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $tipo_contratos.val()).html($tipo_contratos.text());
					$tr.find("td:eq(7)").removeClass("no-padding").addClass("text-center b-r b-grey").html(rut+ (dv!==""? "-"+dv : ""));
					$tr.find("td:eq(8)").removeClass("no-padding").addClass("text-center b-r b-grey").html(fecha_ingreso);
					$tr.find("td:eq(9)").removeClass("no-padding").addClass("text-center b-r b-grey").html(fecha_desvinculacion);
					$tr.find("td:eq(10)").removeClass("no-padding").addClass("text-center b-r b-grey").html(empresa);
					/*$tr.find("td:eq(7)").removeClass("no-padding").addClass("text-center b-r b-grey").html(area2);*/
					$tr.find("td:eq(11)").removeClass("no-padding").addClass("text-center b-r b-grey").html(estado_facturacion);
					$tr.find("td:eq(12)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $estado.val()).html($estado.text());
					$tr.find("td:eq(13)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $vicepresidencias.val()).html($vicepresidencias.text());
					$tr.find("td:eq(14)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $gerencias.val()).html($gerencias.text());
					$tr.find("td:eq(15)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $subgerencias.val()).html($subgerencias.text());
					$buttons.html(updatedTd());
					
					$buttons.find("button").tooltip();
					
				}
			},
			"json"
		);
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},

	alFiltrar: function(event, columna_filtro){
		system.modalCarga("Cargando...");

		var promise = $.get(
			"ajax/getModal.php",
			{ 
				'id': "filtro_usuarios_"+columna_filtro
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
		var ajaxData = { 'tabla': 'usuarios' };
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

	alVerDetalles: function(event){
		var $tr = $(event.target).closest("tr");
		var usrID = Number($tr.data("sid"));
		var $pagina = $("#content");
		var $tabla_usuarios = $("#tbl-mantenedor");
		
		var $modal = system.modalCarga("Cargando...");
		$tabla_usuarios.hide();
		$("#btn-agregar").hide();
		
		var promise = $.get(
			"ver_usuario",
			{ 'usrID': usrID },
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
			$tabla_usuarios.show();
		});
	},

	alVolverDetalles: function(event){
		location.reload();
		$div_detalles = $("#div-detalles_usuario");
		$div_detalles.hide(); 
		$("#tbl-mantenedor").show();
		$("#btn-agregar").show();
		$div_detalles.remove();
		$(event.target).hide();
	},

	alBorrar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		var usrID = Number($tr.data("sid"));
		var existe = !isNaN(usrID); 
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"¿Eliminar este usuario?",
				"Está a punto de eliminar una cuenta de usuario, junto a sus credenciales y su acceso a la aplicación.<br/>Esta acción no se puede deshacer.",
				"danger",
				function(){ system.users.alConfirmarEliminacion($tr); }
			);
		}
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteUsuario.php",
			{ 'usrID': Number($tr.data("sid")) },
			function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			},
			"json"
		);
		
		promise.fail(system.modalError);
	}
	
};

$(document).ready(function(){ system.users.init() });