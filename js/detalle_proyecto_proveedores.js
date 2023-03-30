system.project_details.proveedores = system.project_details.proveedores || {
	

	init: function(){
		$("#tbl-proveedores tbody tr button").tooltip();
	},

	alAgregar2: function(){
		var $hitos = $('.ipt-hito_desc').val();
		var $fechas = $('#ipt-fecha_inicio').val();

		if( $hitos!=null && $fechas!=''){
		var $tbody = $("#tbl-proveedores tbody");
		$tbody.append('<tr>'+newProveedorRow()+'</tr>');
		$("#content").scrollTop($("#content")[0].scrollHeight);
		
		var $tr = $tbody.find("tr:last");
		$tr.find("select").select2();
		$tr.find("button").tooltip();
		}
		else{
			system.modalInformacion('Alerta ','Debe agregar fechas y hitos, para poder crear una Asignación');

		}
		console.log($hitos);
		console.log($fechas);
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var prjProID = Number($tr.data("sid"));
		var existe = !isNaN(prjProID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un colaborador externo", 
				"¿Confirmar la eliminación de un colaborador?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.project_details.proveedores.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteProyectoProveedores.php",
			{ 'prjProID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	},


	alSeleccionar: function(event){
		var $this = $(event.target);
		var haySeleccion = (!!$this.val() === true);
		$("#btn-agregar_proveedor").prop("disabled", !haySeleccion);
	},

	alAgregarProveedor: function(event){
		var $this = $(event.target).closest("button");
		var $select = $("#slt-users");
		
		if (!!$select.val() === true){
			var $opt = $select.find("option:selected");
			
			$this.prop("disabled", true);
			$("#tbl-users tbody").prepend(
				'<tr data-sid="">' +
				newProveedorRow($opt.text(),$opt.val() ) +
				'</tr>'
			);
			$("#tbl-users").closest(".auto-y-overflow").scrollTop(0);
			

			$opt.remove();
			$select.select2();
		}		
	},

	alEditar: function(event){
		
		var $tr = $(event.target).closest("tr");
		var prjProID = Number($tr.data("sid"));
		var usrID = Number($tr.find("td:eq(0)").data("sid"));
        var prjProFechaInicioAsignacion = $tr.find("td:eq(1)").text();
        var prjProFechaFinAsignacion = $tr.find("td:eq(2)").text();
	    var prjProPorcentajeAsignacion = Number($tr.find("td:eq(3)").text().slice(0,-1));
		var prjProCosto = Number($tr.find("td:eq(4)").text());
		var monID = Number($tr.find("td:eq(5)").data("sid"));


		

		$tr.html(newProveedorRow());
		$tr.data("sid", prjProID);
		$tr.find("select.slt-proveedores").val(usrID).select2();
		$tr.find("input.ipt-prjProFechaInicioAsignacion").val(prjProFechaInicioAsignacion);
		$tr.find("input.ipt-prjProFechaFinAsignacion").val(prjProFechaFinAsignacion);
		$tr.find("input.ipt-prjProPorcentajeAsignacion").val(prjProPorcentajeAsignacion);
		$tr.find("input.ipt-prjProCosto").val(prjProCosto);
		$tr.find("select.slt-moneda").val(monID);
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");

		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		var prjProID = Number($tr.data("sid"));
		var prjID = ($tr.find("input.slt-prj").val());
		var usrID = Number($tr.find("select.slt-proveedores").val());	
		var prjProFechaInicioAsignacion = $tr.find("input.ipt-prjProFechaInicioAsignacion").val().trim();
		var prjProFechaFinAsignacion = $tr.find("input.ipt-prjProFechaFinAsignacion").val().trim();
		var prjProPorcentajeAsignacion = $tr.find("input.ipt-prjProPorcentajeAsignacion").val().trim();
		var prjProCosto= $tr.find("input.ipt-prjProCosto").val().trim();
		var monID = Number($tr.find("select.slt-moneda").val());
        var existe = !isNaN(prjProID);
		$this.prop("disabled", true);
			
			var promise = $.post(
				"ajax/" + (existe ? "update" : "add") + "ProyectoProveedores.php",
				{
					'prjProID': prjProID,
					'prjID': prjID,
					'usrID': usrID,
					'prjProFechaInicioAsignacion': prjProFechaInicioAsignacion,
					'prjProFechaFinAsignacion': prjProFechaFinAsignacion,
					'prjProPorcentajeAsignacion': prjProPorcentajeAsignacion,
					'prjProCosto': prjProCosto,
					'monID': monID
                    
				
				},
				function (data){
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						}
						
						var $usrID = $tr.find("select.slt-proveedores option:selected");
						var $monID = $tr.find("select.slt-moneda option:selected");
						
						
						$tr.find("td:eq(1)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $usrID.val()).html($usrID.text());
						$tr.find("td:eq(2)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjProFechaInicioAsignacion);
						$tr.find("td:eq(3)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjProFechaFinAsignacion);
						$tr.find("td:eq(4)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjProPorcentajeAsignacion);
						$tr.find("td:eq(5)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjProCosto);
						$tr.find("td:eq(6)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $monID.val()).html($monID.text());
                       
					
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.project_details.proveedores.alEditar(event)")
							.find("span").removeClass("fa-save").addClass("fa-pencil");
					}
				},
				"json"
			);
			
			promise.fail(system.modalError);
			promise.always(function() { $this.prop("disabled", false); });
		
	},

	

	
};

$(document).ready(function(){ system.project_details.proveedores.init(); });