system.project_details.plan = system.project_details.plan || { 

	cargarTabla: function(){
		var $modal = system.modalCarga("Cargando planeación...");
		var promise = $.get(
			"ajax/getTabla.php",
			{
				'tabla': 'proyecto_planeacion',
				'prjID': Number($("#div-detalles_proyecto").data("sid"))
			},
			function(data){
				$modal.modal("hide");
				$modal.remove();
				$("#div-proj_det_planeacion").html(data);
			},
			"html"
		);

		promise.fail(system.modalError);
	},

	alCambiarHorasPlaneacion: function(event){
		var $tr = $(event.target).closest("tr");
		var $td_horas = $tr.find("td:eq(2)");
		var $td_horasConPorcentaje = $tr.find("td:eq(4)");
		
		var ndias = Number($tr.find("input.ipt-dias_etapa").val());
		var horas_etapa = !!ndias===false? "" : ndias * 8;
		$td_horas.text( horas_etapa );
		
		var nPctPart = Number($tr.find("input.ipt-pct_etapa").val());
		var horas_total_etapa =  !!ndias===false || !!nPctPart===false? "" : Number(((ndias * 8 * nPctPart) / 100).toFixed(1));
		$td_horasConPorcentaje.text( horas_total_etapa );
		
		var total = 0;
		$("#tbl-planeacion_inicial tbody tr").each(function(){
			if ($(this).attr("id") !== "tr-planeacion-inicial-total") {
				var horas = Number($(this).find("td:last").text());
				if (!isNaN(horas)) {
					total += horas;
				}
			}
			else {
				total = Math.round(total);
				$(this).find("td:last").text(total);
			}
		});
		
		$("#btn-guardar_planeacion").prop("disabled", (total === 0));
	},

	alGuardarPlaneacionInicial: function(event){
		var $this = $(event.target).closest("button");
		var $tbl_planeacion = $("#tbl-planeacion_inicial tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		
		var planeacion = [];
		var $filas = $tbl_planeacion.find("tr");
		var $ultima = $filas.last();
		
		$filas.each(function(){
			if (!$(this).is($ultima)){
				var horasFase = Number($(this).find("td:last").text());
				if (isNaN(horasFase) || horasFase < 0) { horasFase = 0; }
				planeacion.push(horasFase);
			}
			else {
				console.log(planeacion);
				var promise = $.post(
					"ajax/updateProyectoPlaneacion.php",
					{
						'prjID': Number($("#div-detalles_proyecto").data("sid")),
						'planeacion[]': planeacion
					},
					function(data){ 
						system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
						if (data[0]) {
							setTimeout(system.project_details.plan.cargarTabla, 1500);
						}
					}, 
					"json"
				);
				
				promise.fail(system.modalError);
				promise.always(function() { $this.prop("disabled", false); });
			}
		});
	},
	
	alConfirmarControlesCambios: function(cambios, $trigger){
		system.modalCarga("Guardando...");

		var promise = $.post(
			"ajax/updateProyectoCambios.php",
			{
				'prjID': Number($("#div-detalles_proyecto").data("sid")),
				'cambios': cambios
			},
			function(data){ 
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
				if (data[0]) {
					setInterval(system.project_details.plan.cargarTabla, 1500);
				}
			},
			"json"
		);
		
		promise.fail(system.modalError);
		if (!!$trigger) {
			promise.always(function() { $trigger.prop("disabled", false); });
		}
	},
	
	alAgregarCambio: function(){
		$("#tbl-control_cambios tbody").append('<tr class="tr-cambio">'+newCambioRow()+'</td>');
		$container = $("#tbl-control_cambios").closest(".auto-y-overflow")
		$container.scrollTop($container[0].scrollHeight);
	},
	
	alQuitarCambio: function(event){ 
		$(event.target).closest("tr").remove(); 
	},

	alGuardarControlesCambios: function(event){
		var $this = $(event.target).closest("button");
		var $tbody_planeacion = $("#tbl-control_cambios tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Procesando...");
		
		var $filas_cambios = $tbody_planeacion.find("tr.tr-cambio");
		var cambios = [];
		$filas_cambios.each(function(i){
			var $ipt_nombre = $(this).find("input.ipt-nombre_cambio");
			var $ipt_horas = $(this).find("input.ipt-horas_cambio");
			
			if ($ipt_nombre[0].checkValidity() &&
				$ipt_horas[0].checkValidity()) {
					
				var cmbID = Number($(this).data("sid"));
				cambios.push({
					'cmbID': isNaN(cmbID)? null : cmbID, 
					'cmbNombre': $ipt_nombre.val().trim(), 
					'cmbHoras': Number($ipt_horas.val())
				});
			}
		});
		
		if ($filas_cambios.length > cambios.length){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Hay cambios sin descripción u horas de duración válidos. Éstos serán ignorados. ¿Desea continuar?",
				"complete",
				function() { system.project_details.plan.alConfirmarControlesCambios(cambios); }
			);
		}
		else {
			system.project_details.plan.alConfirmarControlesCambios(cambios, $this);
		}
	}

};