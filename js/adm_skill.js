system.skill = system.skill || {
	
	init: function(){
		$("#tbl-mantenedor tbody tr button").tooltip();
	
	},

	alEditar: function(event){
		var $tr = $(event.target).closest("tr");
		var sklNombre = $tr.find("td:eq(0)").text();
		
		
		$tr.html(newRow());
		$tr.find("input.ipt-nombre").val(sklNombre);
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		
		var $ipt_nombre = $tr.find("input.ipt-nombre");
		
		if ($ipt_nombre[0].checkValidity()) {
			
			var sklID = Number($tr.data("sid"));
			var existe = !isNaN(sklID);

			
			var promise = $.post(
				"ajax/" + (existe? "update" : "add") + "Skill.php",
				{
					'sklID': sklID,
					'sklNombre': $ipt_nombre.val()
				},
				(function (data){
					console.log("AQUI DEBERIA ESTAR data de skill: ",data);
					console.log(" data[0] :  ", data[0]);
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						console.log("acaa",data);
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						}
						
						$tr.find("td:eq(0)").removeClass("no-padding").addClass("p-b-0 p-t-0").html($ipt_nombre.val());
						
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.skill.alEditar(event)")
							.find("span").removeClass("fa-save").addClass("fa-pencil");
					}
					console.log("acaa2",data);
				}),
			
				"json"
			);
			console.log("aqui es",promise);
			console.log("system.modalError",system.modalError)
			promise.fail(system.modalError);


			 
			
			promise.always(function() { $this.prop("disabled", false); });
		}
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var sklID = Number($tr.data("sid"));
		var existe = !isNaN(sklID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un skill", 
				"¿Confirmar la eliminación de un skill?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.skill.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteSkill.php",
			{ 'sklID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	}
	
	
};

$(document).ready(function(){ system.skill.init(); });