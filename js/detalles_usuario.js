system.user_details = system.user_details || { 

	init: function(){
		$("form select").select2();
	},

	alGuardar: function(event){
		if (event) {
			event.preventDefault();

			var formData = $(event.target).serialize();
			system.modalCarga("Procesando...");
			
			var promise = $.post(
				"ajax/updateUsuario.php",
				formData,
				( function(data){ 
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					var usrID = $("#div-detalles_usuario").data("sid");
	
					if (data[0]) {
						var $tr = $("#tbl-mantenedor tbody tr[data-sid='"+usrID+"']");
						
						var nombre = $("#ipt-nombre").val().trim();
						var mail = $("#ipt-mail").val().trim();
						var rut = Number($("#ipt-rut").val().trim());
						var dv = $("#ipt-dv").val().trim();
						var $estado = $("#slt-estado option:selected");
						var $tipo = $("#slt-tipo option:selected");
						var cargo_contractual = $("#ipt-cargo_contractual").val().trim();
						var $cargo_generico = $("#slt-cargo_generico option:selected");
						var $tipo_contratos = $("#slt-tipo_contratos option:selected");
						var $empresa = $("#slt-empresa option:selected");
						var $area = $("#slt-area option:selected");
						var $facturacion = $("#slt-estado-facturacion option:selected");
						var $vicepresidencias = $("#slt-vicepresidencias option:selected")
						var $gerencias = $("#slt-gerencias option:selected")
						var $subgerencias = $("#slt-subgerencias option:selected")
						var fecha_ingreso = $("#ipt-fecha_ingreso").val().trim();
						var fecha_desvinculacion = $("#ipt-fecha_desvinculacion").val().trim();
						
						$tr.find("td:eq(0)").html(nombre);
						$tr.find("td:eq(1)").html(mail);
						$tr.find("td:eq(2)").data("sid", $tipo.val()).html($tipo.text());
						$tr.find("td:eq(3)").html(cargo_contractual);
						$tr.find("td:eq(4)").data("sid", $cargo_generico.val()).html($cargo_generico.text());
						$tr.find("td:eq(5)").data("sid", $tipo_contratos.val()).html($tipo_contratos.text());
						$tr.find("td:eq(6)").html(rut+ (dv!==""? "-"+dv : ""));
						$tr.find("td:eq(7)").html(fecha_ingreso);
						$tr.find("td:eq(8)").html(fecha_desvinculacion);
						$tr.find("td:eq(9)").data("sid", $empresa.val()).html($empresa.text());
						$tr.find("td:eq(10)").data("sid", $area.val()).html($area.text());
						$tr.find("td:eq(11)").data("sid", $facturacion.val()).html($facturacion.text());
						$tr.find("td:eq(12)").data("sid", $estado.val()).html($estado.text());
						$tr.find("td:eq(13)").data("sid", $vicepresidencias.val()).html($vicepresidencias.text());
						$tr.find("td:eq(14)").data("sid", $gerencias.val()).html($.text());
						$tr.find("td:eq(15)").data("sid", $subgerencias.val()).html($.text());
						
					}
				} ),
				"json"
			);
			
			promise.fail(system.modalError);
		}
	},

	alCambiarClave: function(event){		
		system.modalConfirmacion(
			'Cambiar Clave de Usuario',
			'<div class="form-group">' +
				'<label for="txt_password_new">Clave Nueva</label>' +
				'<input class="form-control" type="text" id="txt_password_new" required />' +
			'</div>',
			'warning text-black',
			system.user_details.alConfirmarCambioClave
		);
	},
	
	alConfirmarCambioClave: function(){
		var $usrClave = $("#txt_password_new");
		if ($usrClave[0].checkValidity()){ 

			system.modalCarga("Guardando...");
			var promise = $.post(
				"ajax/updateUsuario.php",
				{ 
					"usrID": Number($("#div-detalles_usuario").data("sid")), 
					"usrClave": $usrClave.val().trim() 
				},
				function (data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); },
				"json" 
			);
			
			promise.fail(system.modalError);
		}
	}
	
};

$(document).ready(function(){ system.user_details.init(); });