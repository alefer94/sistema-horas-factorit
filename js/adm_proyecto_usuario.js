system.proyect_usr_col = system.proyect_usr_col || {
	
	init: function(){
		$("#tbl-users tbody tr button").tooltip();
	},

	alAgregar: function(){
		
		
		var $hitos = $('.ipt-hito_desc').val();
		var $fechas = $('#ipt-fecha_inicio').val();

		if( $hitos!=null && $fechas!=''){
		var $tbody = $("#tbl-users tbody");
		$tbody.append('<tr>'+newColaboradorRow()+'</tr>');
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

	alSeleccionar: function(event){
		var $this = $(event.target);
		var haySeleccion = (!!$this.val() === true);
		$("#btn-agregar_usuario").prop("disabled", !haySeleccion);
	},

	alAgregarColaborador: function(event){
		var $this = $(event.target).closest("button");
		var $select = $("#slt-users");
		
		if (!!$select.val() === true){
			var $opt = $select.find("option:selected");
			
			$this.prop("disabled", true);
			$("#tbl-users tbody").prepend(
				'<tr data-sid="">' +
				newColaboradorRow($opt.text(),$opt.val() ) +
				'</tr>'
			);
			$("#tbl-users").closest(".auto-y-overflow").scrollTop(0);
			

			$opt.remove();
			$select.select2();
		}		
	},

	alEditar: function(event){
		
		var $tr = $(event.target).closest("tr");
		var prjUsrID = Number($tr.data("sid"));
		var usrID = Number($tr.find("td:eq(0)").data("sid"));
        var prjUsrFechaInicioAsignacion = $tr.find("td:eq(1)").text();
        var prjUsrFechaFinAsignacion = $tr.find("td:eq(2)").text();
	    var prjUsrPorcentajeAsignacion = Number($tr.find("td:eq(3)").text().slice(0,-1));
		

		$tr.html(newColaboradorRow());
		$tr.data("sid", prjUsrID);
		$tr.find("select.slt-usuarios").val(usrID).select2();
		$tr.find("input.ipt-prjUsrFechaInicioAsignacion").val(prjUsrFechaInicioAsignacion);
		$tr.find("input.ipt-prjUsrFechaFinAsignacion").val(prjUsrFechaFinAsignacion);
		$tr.find("input.ipt-prjUsrPorcentajeAsignacion").val(prjUsrPorcentajeAsignacion);
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");

		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		var prjUsrID = Number($tr.data("sid"));
		var prjID = ($tr.find("input.slt-prj").val());
		var usrID = Number($tr.find("select.slt-usuarios").val());	
		var prjUsrFechaInicioAsignacion = $tr.find("input.ipt-prjUsrFechaInicioAsignacion").val().trim();
		var prjUsrFechaFinAsignacion = $tr.find("input.ipt-prjUsrFechaFinAsignacion").val().trim();
		var prjUsrPorcentajeAsignacion = $tr.find("input.ipt-prjUsrPorcentajeAsignacion").val().trim();
		var existe = !isNaN(prjUsrID);
		$this.prop("disabled", true);
			
			var promise = $.post(
				"ajax/" + (existe ? "update" : "add") + "ProyectoUsuario.php",
				{
					'prjUsrID': prjUsrID,
					'prjID': prjID,
					'usrID': usrID,
					'prjUsrFechaInicioAsignacion': prjUsrFechaInicioAsignacion,
					'prjUsrFechaFinAsignacion': prjUsrFechaFinAsignacion,
					'prjUsrPorcentajeAsignacion': prjUsrPorcentajeAsignacion,
				
				},
				function (data){
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						}
						
						var $usrID = $tr.find("select.slt-usuarios option:selected");
						
						$tr.find("td:eq(1)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $usrID.val()).html($usrID.text());
						$tr.find("td:eq(2)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjUsrFechaInicioAsignacion);
						$tr.find("td:eq(3)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjUsrFechaFinAsignacion);
						$tr.find("td:eq(4)").removeClass("no-padding").addClass("text-center b-r b-grey").html(prjUsrPorcentajeAsignacion);
					
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.proyect_usr_col.alEditar(event)")
							.find("span").removeClass("fa-save").addClass("fa-pencil");
					}
				},
				"json"
			);
			
			promise.fail(system.modalError);
			promise.always(function() { $this.prop("disabled", false); });
		
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var prjUsrID = Number($tr.data("sid"));
		var existe = !isNaN(prjUsrID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un colaborador", 
				"¿Confirmar la eliminación de un colaborador?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.proyect_usr_col.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteProyectoUsuario.php",
			{ 'prjUsrID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	},

	init: function() {
		$("#slt-users").select2();
		$("#tbl-users tbody button.btn-remove_user").tooltip();
	}
	
	
};

$(document).ready(function(){ system.proyect_usr_col.init(); });