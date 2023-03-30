system.clients = system.clients || {
	
	init: function(){
		$("#tbl-mantenedor tbody tr button").tooltip();
	},

	alEditar: function(event){
		var $tr = $(event.target).closest("tr");
		var cltNombre = $tr.find("td:eq(0)").text();
		var cltCodigo = $tr.find("td:eq(1)").text();
		var empID = Number($tr.find("td:eq(2)").data("sid"));
		var indID = Number($tr.find("td:eq(3)").data("sid"));

		$tr.html(newRow());
		$tr.find("input.ipt-nombre").val(cltNombre);
		$tr.find("input.ipt-codigo").val(cltCodigo);
		$tr.find("select.slt-empresas").val(empID).select2();
		$tr.find("select.slt-industrias").val(indID).select2();
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		
		var $ipt_nombre = $tr.find("input.ipt-nombre");
		var $ipt_codigo = $tr.find("input.ipt-codigo");
		var $empresas = Number($tr.find("select.slt-empresas").val());
		var $industrias = Number($tr.find("select.slt-industrias").val());
		
		if ($ipt_nombre[0].checkValidity() &&
		    $ipt_codigo[0].checkValidity()) {
			
			var cltID = Number($tr.data("sid"));
			var existe = !isNaN(cltID);
			
			var promise = $.post(
				"ajax/" + (existe? "update" : "add") + "Cliente.php",
				{
					'cltID': cltID,
					'cltNombre': $ipt_nombre.val(),
					'cltCodigo': $ipt_codigo.val(),
					'empID': $empresas,
					'indID': $industrias
				},
				(function (data){

					console.log("la data del response de admCliente es : ",data);
					console.log(" data[0] :  ", data[0]);

					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						console.log("aca",data);
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						
						}
						var $empresas = $tr.find("select.slt-empresas option:selected");
						var $industrias = $tr.find("select.slt-industrias option:selected");

						
						$tr.find("td:eq(0)").removeClass("no-padding").addClass("p-b-0 p-t-0").html($ipt_nombre.val());
						$tr.find("td:eq(1)").removeClass("no-padding").addClass("p-b-0 p-t-0 text-center").html($ipt_codigo.val());
						$tr.find("td:eq(2)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $empresas.val()).html($empresas.text());
						$tr.find("td:eq(3)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $industrias.val()).html($industrias.text());
						
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.clients.alEditar(event)")
							.find("span").removeClass("fa-save").addClass("fa-pencil");
					}
					console.log("aca",data);
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
		var cltID = Number($tr.data("sid"));
		var existe = !isNaN(cltID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar un cliente", 
				"¿Confirmar la eliminación de un cliente?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.clients.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteCliente.php",
			{ 'cltID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	}
	
	
};

$(document).ready(function(){ system.clients.init(); });