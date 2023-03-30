system.project_details.advances = system.project_details.advances || { 

	init: function() {
		$("#tbl-avances tbody button.btn-remove_avance").tooltip();
	},

	alAgregar: function(event){
		$("#tbl-avances tbody").append( '<tr>' + newAvanceRow() + '</tr>');
		$container = $("#tbl-avances").closest(".auto-y-overflow")
		$container.scrollTop($container[0].scrollHeight);
	},
	
	alQuitar: function(event){
		$(event.target).closest("tr").remove();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tbody_avances = $("#tbl-avances tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Procesando...");
		
		var $filas_avances = $tbody_avances.find("tr");
		var avances = [];
		var avances_malos = [];
		$filas_avances.each(function(i){
			var $ipt_fch_adv = $(this).find("input.ipt-fecha_avance");
			var $ipt_desc = $(this).find("input.ipt-desc_avance");
			var $ipt_pct_real = $(this).find("input.ipt-pct_avance_real");
			var $ipt_pct_esp = $(this).find("input.ipt-pct_avance_esp");
			
			if ($ipt_fch_adv[0].checkValidity() &&
				$ipt_desc[0].checkValidity() &&
				$ipt_pct_real[0].checkValidity() &&
				$ipt_pct_esp[0].checkValidity()) {
					
				var advID = Number($(this).data("sid"));
				avances.push({
					'advID': isNaN(advID)? null : advID, 
					'advFecha': $ipt_fch_adv.val(),
					'advDescripcion': $ipt_desc.val().trim(), 
					'advPorcEsperado': Number($ipt_pct_esp.val()),
					'advPorcReal': Number($ipt_pct_real.val())
				});
			}
		});

		if ($filas_avances.length > avances.length){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Hay avances con información incompleta o incorrecta, los cuales serán ignorados. ¿Desea continuar?",
				"complete",
				function() { system.project_details.advances.alConfirmar(avances); }
			);
		}
		else {
			system.project_details.advances.alConfirmar(avances, $this);
		}
	},

	alConfirmar: function(avances, $trigger){
		system.modalCarga("Guardando...");

		var promise = $.post(
			"ajax/updateProyectoAvances.php",
			{
				'prjID': Number($("#div-detalles_proyecto").data("sid")),
				'avances': avances
			},
			(function(data){ 
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
				if (data[0]) {
					setTimeout(system.project_details.advances.cargarTabla, 1500);
				}
			}),
			"json"
		);
		
		promise.fail(system.modalError);
		if (!!$trigger) {
			promise.always(function() { $trigger.prop("disabled", false); });
		}
	}
};