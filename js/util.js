var system = system || { 
	
	alAgregar: function(){
		var $tbody = $("#tbl-mantenedor tbody");
		$tbody.append('<tr>'+newRow()+'</tr>');
		$("#content").scrollTop($("#content")[0].scrollHeight);
		
		var $tr = $tbody.find("tr:last");
		$tr.find("select").select2();
		$tr.find("button").tooltip();
	},

	modal: function(htmlTitulo, htmlCuerpo, estatica){
		var $modal = $("#modal");
		if ($modal.length > 0 ) { 
			$modal.modal("hide");
			$modal.remove(); 
		}
		$("body").append(
			'<div id="modal" class="modal"' + (estatica? ' data-backdrop="static">' : '>') +
				'<div class="modal-dialog">' +
					'<div class="modal-content">' +
						(htmlTitulo !== '' ?
							('<div class="modal-header">' + htmlTitulo + '</div>') : ''
						) +
						(htmlCuerpo !== '' ?
							('<div class="modal-body m-t-20 m-b-0">' + htmlCuerpo + '</div>') : ''
						) +
					'</div>' +
				'</div>' +
			'</div>'
		);
		
		$modal = $("#modal");
		$modal.modal("show");
		
		return $modal;
	},


	modalInformacion: function(titulo, texto){

		return system.modal(
			'<h4 class="modal-title bold">' + titulo + '</h4>',
			(
				'<p class="semi-bold text-center">' + texto + '</p>' +
				'<div class="flex-container" style="height: 2rem !important">' +
					'<button class="btn btn-complete m-auto" data-dismiss="modal">OK</button>' +
				'</div>'
			),
			false
		);
	},

	modalConfirmacion: function(titulo, texto, clase_color, callbackConfirmar, callbackCancelar){

		$modal = system.modal(
			'<h4 class="modal-title bold">' + titulo + '</h4>',
			(
				'<div class="text-center">' + texto + '</div>'+
				'<div class="flex-container full-width m-t-10" style="height: 2rem">' +
					'<button id="btn-modal_confirmar" type="button" class="btn btn-'+clase_color+' m-r-auto full-height">Confirmar</button>' +
					'<button id="btn-modal_cancelar" type="button" class="btn btn-default m-l-auto full-height" data-dismiss="modal">Cancelar</button>' +
				'</div>'
			),
			true
		);
		
		if (typeof callbackConfirmar === "function"){
			$("#btn-modal_confirmar").click(callbackConfirmar);
		}
		if (typeof callbackCancelar === "function"){
			$("#btn-modal_cancelar").click(callbackCancelar);
		}

		return $modal;
	},

	modalCarga: function(titulo){

		return system.modal(
			'<h4 class="modal-title bold">' + titulo + '</h4>',
			'<div class="progress-circle-indeterminate"></div>',
			true
		);
	},

	modalError: function(event){
		
		return system.modal(
			'<h4 class="modal-title bold">Error Inesperado</h4>',
			(
				'<p class="semi-bold">Si este error persiste, favor de reportar al administrador.<br/>Detalles del error: <br/><pre class="pre-scrollable">'+event.responseText+'</pre></p>'+
				'<button class="btn btn-complete" data-dismiss="modal">OK</button>'
			)
		);
	},
	
	select2_clientes: function($input) {

		return $input.select2({
			placeholder: "Buscar un cliente",
			minimumInputLength: 3,
			ajax: {
				url: "ajax/getClientes.php",
				dataType: 'json',
				quietMillis: 250,
				data: ( function (text, page) { return { filtro: text, page: page }; } ),
				results: ( function (data, page) { return { results: data }; } ),
				formatResult: function(clt) {
					return ('<div ="row-fluid"><b>'+clt.code+'</b><p>'+clt.text+'</p></div>' );
				}, 
				formatSelection: function(clt){ return clt.text +" ("+clt.code+")" },
				cache: true
			}
		});
	},
	
	select2_proyectos: function($input) {
		
		return $input.select2({
			placeholder: "Buscar un proyecto",
			minimumInputLength: 3,
			ajax: {
				url: "ajax/getProyectos.php",
				dataType: 'json',
				quietMillis: 250,
				data: ( function (text, page) { return { filtro: text }; } ),
				results: ( function (data, page) { if (data===false) { return []; } else { return data; }; } ),
				cache: true
			},
			formatResult: function (proyecto) {
			    var markup = '<option value="'+proyecto.prjID+'">'+proyecto.prjNombre+'</option>';
			    return markup;
		    },
			formatSelection: function(repo) { return repo.prjNombre; },
			//dropdownCssClass: "bigdrop",
			escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
		});
	},

	select2: function(object, minimumInputLength){
		if (typeof minimumInputLength==="undefined") { minimumInputLength = 2; }
		$(object).val( $(object).parent().data("sid") );
		$(object).parent().removeData("sid");
		$(object).select2({
			minimumInputLength: minimumInputLength,
			minimumResultsForSearch: 12,
			width: "copy"
		});
	}
};

//$(document).ready(function() { system.init(); });