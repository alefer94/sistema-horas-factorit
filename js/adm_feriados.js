system.feriado = system.feriado || {
	
	init: function(){
		$("#tbl-mantenedor tbody tr button").tooltip();
		
	},

	alEditar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");

		var frdDescripcion = $tr.find("td:eq(0)").text();
		var frdDia = Number($tr.find("td:eq(1)").data("sid"));
		var frdMes = Number($tr.find("td:eq(2)").data("sid"));
		var frdAnio = $tr.find("td:eq(3)").text();
		var empID = Number($tr.find("td:eq(4)").data("sid"));

		
		$tr.html(newRow());
		$tr.find("input.ipt-descripcion").val(frdDescripcion);
		$tr.find("select.slt-dia").val(frdDia).select2();
		$tr.find("select.slt-mes").val(frdMes).select2();
		$tr.find("input.ipt-anio").val(frdAnio);
		$tr.find("select.slt-empresas").val(empID).select2();
		$tr.find("button.btn-save").tooltip();
		$tr.find("button.btn-remove").tooltip();

		$tr.find("button").tooltip();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");

		
		var $ipt_descripcion = $tr.find("input.ipt-descripcion");
		var $ipt_dia = $tr.find("select.slt-dia");
		var $ipt_mes = $tr.find("select.slt-mes");
		var $ipt_anio = $tr.find("input.ipt-anio");
		var $empresas = Number($tr.find("select.slt-empresas").val());
		
		
		if ($ipt_descripcion[0].checkValidity() &&
			$ipt_dia[0].checkValidity()&&
			$ipt_anio[0].checkValidity()) {
			
			var frdID = Number($tr.data("sid"));
			var existe = !isNaN(frdID);
			
			var promise = $.post(
				"ajax/" + (existe? "update" : "add") + "Feriados.php",
				{
					'frdID': frdID,
					'frdDescripcion':$ipt_descripcion.val(),
					'frdDia':$ipt_dia.val(),
					'frdMes':$ipt_mes.val(),
					'frdanio':$ipt_anio.val(),
					'empID': $empresas,

					
				},
				(function (data){
					system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
					
					if (data[0]) {
						if (data.length === 3) {
							$tr.data("sid", data[2]);
						}
						var $ipt_dia = $tr.find("select.slt-dia option:selected");
						var $ipt_mes= $tr.find("select.slt-mes option:selected");
						var $empresas = $tr.find("select.slt-empresas option:selected");
						
						$tr.find("td:eq(0)").removeClass("no-padding").addClass("p-b-0 p-t-0").html($ipt_descripcion.val());
						$tr.find("td:eq(1)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $ipt_dia.val()).html($ipt_dia.text());
						$tr.find("td:eq(2)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $ipt_mes.val()).html($ipt_mes.text());
						$tr.find("td:eq(3)").removeClass("no-padding").addClass("p-b-0 p-t-0 text-center").html($ipt_anio.val());
						$tr.find("td:eq(4)").removeClass("no-padding").addClass("p-t-0 p-b-0 text-center").data("sid", $empresas.val()).html($empresas.text());
						
						var $btn_edit = $tr.find("button.btn-edit");
						$btn_edit.find(".fa").toggleClass("fa-pencil fa-save");
						$tr.find("button.btn-save").removeClass("btn-save btn-complete").addClass("btn-edit btn-primary").attr("onclick", "system.feriado.alEditar(event)")
							.find("span").removeClass("fa-save").addClass("fa-pencil");
					}
				}),
				"json"
			);
			
			promise.fail(system.modalError);
			promise.always(function() { $this.prop("disabled", false); });
		}
	},


	alFiltrar: function(event, columna_filtro){
		system.modalCarga("Cargando...");

		var promise = $.get(
			"ajax/getModal.php",
			{ 
				'id': "filtro_feriados_"+columna_filtro
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
		var ajaxData = { 'tabla': 'feriados' };
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
					$modalTrigger.text("(Filtrado)").append('<i class="fa fa-filter" aria-hidden="true"></i>'); ; 
				}

				$("#tbl-mantenedor tbody").html(data);
				$modal.modal("hide");
				$modal.remove();
			},
			"html"
		);

		promise.fail(system.modalError);
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var frdID = Number($tr.data("sid"));
		var existe = !isNaN(frdID);
		
		if (!existe) {
			$tr.remove();
		}
		else { 
			system.modalConfirmacion(
				"Eliminar una fecha", 
				"¿Confirmar la eliminación de una fecha ?<br/>Esta acción no se puede deshacer.",
				"danger",
				function() { system.feriado.alConfirmarEliminacion($tr); }
			);
		}
		
	},

	alConfirmarEliminacion: function($tr){
		system.modalCarga("Eliminando...");
		
		var promise = $.post(
			"ajax/deleteFeriados.php",
			{ 'frdID': Number($tr.data("sid")) },
			(function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]) { $tr.remove(); }
			}),
			"json"
		);
		
		promise.fail(system.modalError);
	}

	
	
	
};

$(document).ready(function(){ system.feriado.init(); });