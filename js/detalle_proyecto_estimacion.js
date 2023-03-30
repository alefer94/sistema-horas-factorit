system.project_details.estimacion = system.project_details.estimacion || {
	

	alAgregar: function(event){
		$("#tbl-estimacion tbody").append('<tr>'+newEstimacionRow()+'</td>');
		$container = $("#tbl-estimacion").closest(".auto-y-overflow")
		$container.scrollTop($container[0].scrollHeight);
		
	},

	alQuitar: function(event){
		var $this = $(event.target);
		$this.closest("tr").remove();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tbody_estimacion = $("#tbl-estimacion tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Procesando...");

	
		
		var $filas_estimacion = $tbody_estimacion.find("tr");
		var estimacion = [];
		var estimacion_malas = [];
		$filas_estimacion.each(function(i){
            var $ipt_cgcID = $(this).find("select.slt-cgcID");
			var $ipt_estRol = $(this).find("input.ipt-estimacion_estRol");
			var $ipt_estSeniority = $(this).find("input.ipt-estimacion_estSeniority");
            var $ipt_bnsID = $(this).find("select.slt-bnsID");
			var $ipt_estMes = $(this).find("input.ipt-estimacion_estMes");
		    var $ipt_estSemana = 0;
			var $ipt_estfch_inicio = $(this).find("input.ipt-estimacion_estfch_inicio");
            var $ipt_estfch_fin = $(this).find("input.ipt-estimacion_estfch_fin");
            var $ipt_estHoras = $(this).find("input.ipt-estimacion_estHoras");

			
			
			if ($ipt_estRol[0].checkValidity() &&
				$ipt_estSeniority[0].checkValidity() &&
				$ipt_estMes[0].checkValidity() &&
				$ipt_estfch_inicio[0].checkValidity() &&
				$ipt_estfch_fin[0].checkValidity() &&
				$ipt_estHoras[0].checkValidity()
				) {
					
				var estID = Number($(this).data("sid"));
				estimacion.push({
					'estID': isNaN(estID)? null : estID, 
					'cgcID': Number($ipt_cgcID.val()), 
					'estRol': $ipt_estRol.val().trim(),
					'estSeniority': $ipt_estSeniority.val().trim(),
                    'bnsID': Number($ipt_bnsID.val()),
					'estMes': $ipt_estMes.val().trim(),
					'estSemana': $ipt_estSemana,
					'estFechaInicio': $ipt_estfch_inicio.val(),
					'estFechaFin': $ipt_estfch_fin.val(),
					'estHoras': Number($ipt_estHoras.val())
				});
			}
		});

		if ($filas_estimacion.length > estimacion.length){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Hay proyectos estimados con información incompletao incorrecta, y serán ignorados. ¿Desea continuar?",
				"complete",
				function() { system.project_details.estimacion.alConfirmar(estimacion); }
			);
		}
		 else{
			system.project_details.estimacion.alConfirmar(estimacion, $this);
		}
	},

	alConfirmar: function(estimacion, $trigger){
		system.modalCarga("Guardando...");

		var promise = $.post(
			"ajax/updateProyectoEstimacion.php",
			{
				'prjID': Number($("#div-detalles_proyecto").data("sid")),
				'estimacion': estimacion
			},
			(function(data){ 
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
				if (data[0]) {
					setTimeout(system.project_details.estimacion.cargarTabla, 1500);
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