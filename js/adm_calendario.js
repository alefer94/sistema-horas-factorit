system.calendar = system.calendar || { 
	
	alSeleccionarAño: function (event){
		var $this = $(event.target);
		var $tabla = $("#tbl-mantenedor");
		
		$this.prop("disabled", true);
		system.modalCarga("Cargando...");
		$tabla.addClass("invisible");
		
		var promise = $.get(
			"ajax/getTabla.php",
			{ 
				tabla: 'feriados', 
				anio: $this.val() 
			},
			( function(data){ 
				$("#tbl-mantenedor tbody").html(data);
				$("#modal").modal("hide");
				$("#modal").remove(); 
			}),
			"html"
		);
		
		promise.fail(system.modalError);
		
		promise.always(function() { 
			system.calendar.init();
			$tabla.removeClass("invisible");
			$this.prop("disabled", false); 
		});
	},

	alBorrar: function(event) { $(event.target).closest("tr").remove(); },

	alConfirmarGuardar: function(año, feriados){
		system.modalCarga("Guardando...");
		console.log({
			'anio': año,
			'feriados': feriados
		});
		var promise = $.post(
			"ajax/updateDiasNoHabiles.php",
			{
				'anio': año,
				'feriados': feriados
			},
			( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); }),
			"json"
		);
		
		promise.fail(system.modalError);
		
		promise.always(function(){
			$("#btn-guardar").prop("disabled", false);
		});
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		
		var $slt_anio = $("#slt-anio");
		var $filas = $("#tbl-mantenedor tbody tr");
		var feriados = [];
		
		$filas.each(function(i){
			var $tr = $(this);
			var $ipt_dia = $tr.find("select.slt-dia");
			var $ipt_mes = $tr.find("select.slt-mes");
			var $ipt_desc = $tr.find("input.ipt-desc");
			
			if ($ipt_dia[0].checkValidity() &&
				$ipt_mes[0].checkValidity() &&
				$ipt_desc[0].checkValidity() ) {
					
				feriados.push ([
					Number($ipt_mes.val()),
					Number($ipt_dia.val()),
					$ipt_desc.val()
				]);
			}
		});
		
		if (feriados.length === $filas.length){
			system.calendar.alConfirmarGuardar(
				Number($slt_anio.val()),
				feriados
			);
			$this.prop("disabled", false);
		}
		else {
			system.modalConfirmacion(
				'Confirmar guardado',
				'Algunos feriados no poseen una descripción o una fecha correcta, y por ello no serán guardados.<br/>¿Desea ignorarlos y continuar?',
				'complete',
				(function(){ 
					system.calendar.alConfirmarGuardar(
						$slt_anio.val(),
						feriados
					); 
					$this.prop("disabled", false);
				})
			).on("hidden.bs.modal", function() { $this.prop("disabled", false) } );
		}
	},

	alCambiarMes: function(event){
		var $this = $(event.target).closest("button");
		var $tr = $this.parent().parent();
		var $select_dia = $tr.find("select.slt-dia");
		
		var dias = Number($this.find("option:selected").data("dias"));
		if (Number($select_dia.val()) > dias){
			$select_dia.val(dias).select2();
		}
		
		$select_dia.find("option").each(function(){
			var $opt = $(this);
			var dia = Number($opt.val());
			$opt.prop("disabled", dia>dias);
		});
	},

	init: function(){
		$("#slt-anio").select2();
		$tbody = $("#tbl-mantenedor tbody");
		$tbody.find("button.btn-remove").tooltip();
		$tbody.find("select").select2();
	}
};

$(document).ready(function() { system.calendar.init(); } );