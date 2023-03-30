system.project_details.bitacoras = system.project_details.bitacoras || {
	

	alAgregar: function(event){
		$("#tbl-bitacoras tbody").append('<tr>'+newBitacoraRow()+'</td>');
		$container = $("#tbl-bitacoras").closest(".auto-y-overflow")
		$container.scrollTop($container[0].scrollHeight);
		
	},

	alQuitar: function(event){
		var $this = $(event.target);
		$this.closest("tr").remove();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tbody_bitacoras = $("#tbl-bitacoras tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Procesando...");

		
		
		var $filas_bitacoras = $tbody_bitacoras.find("tr");
		var bitacoras = [];
		var bitacoras_malos = [];
		$filas_bitacoras.each(function(i){
			var $ipt_fch = $(this).find("input.ipt-bitacora_fch");
            var $ipt_desc = $(this).find("input.ipt-bitacora_desc");
			var $ipt_hora = $(this).find("input.ipt-bitacora_hora");
			

	
			
			if ($ipt_desc[0].checkValidity() &&
				$ipt_fch[0].checkValidity() &&
				$ipt_hora[0].checkValidity()
				) {
					
				var bitID = Number($(this).data("sid"));
				bitacoras.push({
					'bitID': isNaN(bitID)? null : bitID, 
					'bitFecha': $ipt_fch.val(),
                    'bitDescripcion': $ipt_desc.val().trim(),
                    'bitHora': $ipt_hora.val().trim(), 
									
				});
			}
		});

		if ($filas_bitacoras.length > bitacoras.length){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Hay bitacoras con información incompleta o  incorrecta, y serán ignorados. ¿Desea continuar?",
				"complete",
				function() { system.project_details.bitacoras.alConfirmar(bitacoras); }
			);
		}
		else {
			system.project_details.bitacoras.alConfirmar(bitacoras, $this);
		}
	},

	alConfirmar: function(bitacoras, $trigger){
		system.modalCarga("Guardando...");

		var promise = $.post(
			"ajax/updateProyectoBitacoras.php",
			{
				'prjID': Number($("#div-detalles_proyecto").data("sid")),
				'bitacoras': bitacoras
			},
			(function(data){ 
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
				if (data[0]) {
					setTimeout(system.project_details.bitacoras.cargarTabla, 1500);
				}
			}),
			"json"
		);
		
		promise.fail(system.modalError);
		if (!!$trigger) {
			promise.always(function() { $trigger.prop("disabled", false); });
		}
	},

	
	
};