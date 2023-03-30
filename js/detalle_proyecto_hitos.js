system.project_details.hitos = system.project_details.hitos || {
	

	alAgregar: function(event){
		$("#tbl-hitos tbody").append('<tr>'+newHitoRow()+'</td>');
		$container = $("#tbl-hitos").closest(".auto-y-overflow")
		$container.scrollTop($container[0].scrollHeight);
		
	},

	alQuitar: function(event){
		var $this = $(event.target);
		$this.closest("tr").remove();
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var $tbody_hitos = $("#tbl-hitos tbody");
		
		$this.prop("disabled", true);
		system.modalCarga("Procesando...");

	
		
		var $filas_hitos = $tbody_hitos.find("tr");
		var hitos = [];
		var hitos_malos = [];
		$filas_hitos.each(function(i){
			var $ipt_desc = $(this).find("input.ipt-hito_desc");
			var $ipt_fch_trm = $(this).find("input.ipt-hito_fch_trm");
			var $ipt_fch_prov = $(this).find("input.ipt-hito_fch_prov");
			var $ipt_fch_conf = $(this).find("input.ipt-hito_fch_conf");
			var $ipt_pct = $(this).find("input.ipt-hito_pct");
			var $ipt_uf = $(this).find("input.ipt-hito_uf");
			var $ipt_mon = $(this).find("select.slt-moneda");
			var $ipt_est_hit = $(this).find("select.slt-est_hit");
			var $ipt_fact = $(this).find("input.ipt-hito_fact");
			var $ipt_fch_fact = $(this).find("input.ipt-hito_fch_fact")

			
			
			if ($ipt_desc[0].checkValidity() &&
				$ipt_fch_trm[0].checkValidity() &&
				$ipt_fch_prov[0].checkValidity() &&
				$ipt_fch_conf[0].checkValidity() &&
				$ipt_uf[0].checkValidity() &&
				$ipt_est_hit[0].checkValidity() &&
				$ipt_fact[0].checkValidity() &&
				$ipt_fch_fact[0].checkValidity()
				) {
					
				var htID = Number($(this).data("sid"));
				hitos.push({
					'htID': isNaN(htID)? null : htID, 
					'htDescripcion': $ipt_desc.val().trim(), 
					'htFechaTermino': $ipt_fch_trm.val(),
					'htFechaProvision': $ipt_fch_prov.val(),
					'htFechaConformidad': $ipt_fch_conf.val(),
					'htUF': Number($ipt_uf.val()),
					'monID': Number($ipt_mon.val()),
					'htEstID': Number($ipt_est_hit.val()),
					'htFactura': $ipt_fact.val().trim(),
					'htFechaFacturacion' : $ipt_fch_fact.val().trim()

				});
			}
		});

		if ($filas_hitos.length > hitos.length){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Hay hitos con información incompletao incorrecta, y serán ignorados. ¿Desea continuar?",
				"complete",
				function() { system.project_details.hitos.alConfirmar(hitos); }
			);
		}
		if (hitos.htEstID === 6 && hitos.htFactura===''){
			$this.prop("disabled", false);
			system.modalConfirmacion(
				"Confirmar acción",
				"Ingrese el valor de la Factura. ¿Desea continuar?",
				"complete",
				function() { system.project_details.hitos.alConfirmar(hitos); }
			);
		}
		else {
			system.project_details.hitos.alConfirmar(hitos, $this);
		}
	},

	alConfirmar: function(hitos, $trigger){
		system.modalCarga("Guardando...");

		var promise = $.post(
			"ajax/updateProyectoHitos.php",
			{
				'prjID': Number($("#div-detalles_proyecto").data("sid")),
				'hitos': hitos
			},
			(function(data){ 
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); 
				if (data[0]) {
					setTimeout(system.project_details.hitos.cargarTabla, 1500);
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