system.usr_skill = system.usr_skill || {
	
	init: function(){
		$("#tbl-mantenedor tbody tr button").tooltip();
	},

	alEditar: function(event){
		
		var $tr = $(event.target).closest("tr");
		var sklID = Number($tr.find("td:eq(0)").data("sid"));
		var lskID = Number($tr.find("td:eq(1)").data("sid"));
		

		$tr.html(newRow());
		$tr.find("select.slt-skl").val(sklID).select2();
		$tr.find("select.slt-lsk").val(lskID).select2();
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		var sklID = Number($tr.find("select.slt-skl").val());
		var lskID = Number($tr.find("select.slt-lsk").val());
		var usrID = Number($tr.find("input.slt-usr").val());

		
			
			var uslID = Number($tr.data("sid"));
			var existe = !isNaN(uslID);
			
			var promise = $.post(
				"ajax/" + (existe? "update" : "add") + "PerfilSkill.php",
				{
					'uslID': uslID,
					'usrID': usrID,
					'sklID': sklID,
					'lskID': lskID
				},
				function (data){
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						}
						var $sklID = $tr.find("select.slt-skl option:selected");
						var $lskID = $tr.find("select.slt-lsk option:selected");

						
						$tr.find("td:eq(1)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $sklID.val()).html($sklID.text());
						$tr.find("td:eq(2)").removeClass("no-padding").addClass("text-center b-r b-grey").data("sid", $lskID.val()).html($lskID.text());
					
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.usr_skill.alEditar(event)")
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
		var uslID = Number($tr.data("sid"));
		var existe = !isNaN(uslID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un skill-nivel", 
				"¿Confirmar la eliminación de un cliente?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.usr_skill.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deletePerfilSkill.php",
			{ 'uslID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	}
	
	
};

$(document).ready(function(){ system.usr_skill.init(); });