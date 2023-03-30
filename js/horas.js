/*
 * Sistema: Gestión de Horas de Colaboradores
 * Cliente: FactorIT
 * Archivo fuente: js/horas.js
 * Autor: Benjamin La Madrid
*/
system.hours = system.hours || { 
	
	alDesplazarVista: function(event){ 
		$this = $(event.target);
		var scrollpos = $this.scrollTop();
		if ($this.attr("id")==="scroll_horas") {
			var scrollpos_left = $this.scrollLeft();
			$("#scroll_columnas").scrollTop(scrollpos);
			$("#scroll_head_horas").scrollLeft(scrollpos_left);
		}
		else {
			$("#scroll_horas").scrollTop(scrollpos);
		}
	},

	init: function(){
		var $tbl = $("#tbl-columnas");
		$tbl.find("tbody tr").each(function(i){
			var $tr = $(this);
			$tr.find("button").tooltip();
			var hrMes = $tr.data("mes");
			var usrID = Number($tr.find("input.slt-usr").val());
			var $tr_horas = $("#tbl-horas tbody tr").eq(i);
			
			var promise = system.hours.validarDiasHabiles(hrMes, usrID, $tr_horas);
			promise.fail(system.modalError);
		});

		$("div.thead-warning-light .btn-filtrar").tooltip();

		$("div.scroll-sibling").scroll(system.hours.alDesplazarVista);
		
		$("#footer div.row select").each(function(){ system.select2(this, 0);});
	},

	alAgregar: function(){
		if ($("#slt-proyecto")[0].checkValidity() && 
			$("#slt-etapa")[0].checkValidity() && 
			$("#slt-periodo")[0].checkValidity()) {

				
			var nuevaFila = newRow();
			var $tbody_data = $("#tbl-columnas tbody");
			$tbody_data.prepend('<tr>' +nuevaFila[0] + '</tr>');

			var $tbody_horas = $("#tbl-horas tbody");
			$tbody_horas.prepend('<tr>' +nuevaFila[1] + '</tr>');
			
			$("div.scroll-sibling").scrollTop(0);
			
			var $new_tr = $tbody_horas.find("tr:first");
			var hrMes = $("#slt-periodo").val();
			var usrID = Number($tbody_data.find("input.slt-usr").val());

			$tbody_data.find("tr:first button").tooltip();
			
			var promise = system.hours.validarDiasHabiles(hrMes, usrID, $new_tr);
			promise.fail(system.modalError);
		}
	},

	alGuardar: function(event){ 
		var $this = $(event.target).closest("button");
		var $tr = $this.closest("tr");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		
		var hrID = Number($tr.data("sid"));
		var existe = !isNaN(hrID);
		var prjID   = Number( $tr.find("td:eq(2)").data("sid") );
		var etapaID = Number( $tr.find("td:eq(3)").data("sid") );
		var hrMes   = $tr.find("td:eq(4)").data("sid");

		var fila_index = $tr.index("#tbl-columnas tbody tr");
		var $tr_horas = $("#tbl-horas tbody tr").eq(fila_index);
		
		var ajaxData = {
			'hrID':	existe? hrID : 0
		};
		
		$tr_horas.find("input.ipt-dia").each(function(i){
			var hrsDia = Number($(this).val());
			if (isNaN(hrsDia)) { hrsDia = 0; }
			ajaxData['hrDia'+String(i+1)] = hrsDia;
		});
		
		if (!existe){
			ajaxData['prjID'] = prjID;
			ajaxData['etapaID'] = etapaID;
			ajaxData['hrMes'] = hrMes;
		}
		
		//POST de AJAX
		var promise = $.post(
			"ajax/"+ (existe? "update" : "add") +"Horas.php",
			ajaxData,
			( function(data) {
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );

				if (data[2]> 0) {
					$tr.data("sid", Number(data[2])); //para actualizar este nuevo registro a partir de aquí
				}
			} ), 
			'json'
		);
		
		promise.fail(system.modalError);
		promise.always(function() { $this.prop("disabled", false); });
	},

	alBorrar: function(event){
		var $tr = $(event.target).closest("tr");
		var hrID = Number($tr.data("sid"));
		var existe = !isNaN(hrID);

		var fila_index = $tr.index("#tbl-columnas tbody tr");
		$tr = $tr.add( $("#tbl-horas tbody tr").eq(fila_index) );
		
		if (!existe) {
			$tr.remove();
		}
		else {
			system.modalConfirmacion(
				"¿Eliminar este registro de horas?", 
				"Esta acción no se puede deshacer.",
				"danger",
				function() { system.hours.alConfirmarEliminacion($tr); }
			);
		}
	},

	alConfirmarEliminacion: function($tr){
		var promise = $.post(
			"ajax/deleteHora.php",
			{ 'hrID': Number($tr.data("sid")) },
			( function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]){
					$tr.remove();
					system.hours.reflowData();
				}
			} ),
			"json"
		);
		
		promise.fail(system.modalError);
	},

	alFiltrar: function(event){
		system.modalConfirmacion(
			'Filtrar Registros',
			'<div class="form-group">' +
				'<label for="slt-filtro_periodo">Periodo</label>' +
				'<select id="slt-filtro_periodo" class="full-width" placeholder="Seleccione un periodo..." required>' +
					($("#slt-periodo").html()) +
				'</select>' +
			'</div>',
			'complete',
			function(){ system.hours.alConfirmarFiltro($(event.target)); }
		);

		$("#slt-filtro_periodo").val("").select2();
	},

	alConfirmarFiltro: function($source){
		var $slt_periodo = $("#slt-filtro_periodo");
		if ($slt_periodo[0].checkValidity()){
			var $modal = system.modalCarga("Cargando...");
			var $option = $slt_periodo.find("option:selected");

			var promise = $.get(
				"ajax/getTabla.php",
				{ 
					'tabla': "horas",
					'periodo': $option.val()
				},
				function(array_html){
					$("#tbl-columnas tbody").html(array_html[0]);
					$("#tbl-horas tbody").html(array_html[1]);
					$source.text("("+$option.text()+")"  );
					$modal.modal("hide");
					$modal.remove();
					system.hours.init();
				},
				"json"
			)

			promise.fail(system.modalError);
		}
	},

	validarDiasHabiles: function(hrMes, usrID, $tr){
		return $.get(
			"ajax/getDiasNoHabiles.php",
			//{ 'hrMes': hrMes},
			{ 'hrMes': hrMes,
			  'usrID': usrID },
			function(diasHabiles){
				var dia = 1;
				$tr.find("input.form-control").each(function(){
					if ($.inArray(dia, diasHabiles) <= -1){
						if (!!$(this).val().trim()===false){ $(this).attr("placeholder", "0"); }
						$(this).prop("disabled", false);
					}
					else {
						$(this).attr("placeholder", "");
					}
					dia++;
				});
			},
			"json"
		);
	}
		
};

$(document).ready(function(){ 
	system.alAgregar = system.hours.alAgregar;
	system.hours.init(); 
});