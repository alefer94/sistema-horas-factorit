system.project_details.users = system.project_details.users || { 
	
	alQuitar: function(event){
		var $tr = $(event.target).closest("tr"); 
		var id 		= Number($tr.data("sid"));
		var nombre 	= $tr.find("td:eq(0)").text();
		var horas 	= $tr.find("td:eq(1)").text();
		$("#slt-users").append('<option value="'+ id +'" data-horas="'+ horas +'">'+ nombre +'</option>');
		$tr.remove();
	},

	alSeleccionar: function(event){
		var $this = $(event.target);
		var haySeleccion = (!!$this.val() === true);
		$("#btn-agregar_usuario").prop("disabled", !haySeleccion);
	},

	alAgregar: function(event){

		

	
		var $this = $(event.target).closest("button");
		var $select = $("#slt-users");
		
		if (!!$select.val() === true){
			var $opt = $select.find("option:selected");
			
			$this.prop("disabled", true);
			$("#tbl-users tbody").prepend(
				'<tr data-sid="'+($opt.val())+'">' +
				newColaboradorRow($opt.text(), $opt.data("horas")) +
				'</tr>'
			);
			$("#tbl-users").closest(".auto-y-overflow").scrollTop(0);
			

			$opt.remove();
			$select.select2();
		}
	
	},

	alGuardar: function(event){
		var $this = $(event.target).closest("button");
		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var users = [];
		$this.prop("disabled", true);
		
		$("#tbl-users tbody tr").each(function(){
			var usrID = Number($(this).data("sid"));
			if (!isNaN(usrID)) {
				users.push(usrID);
			}
		});
		
		var promise = $.post(
			"ajax/updateProyectoColaboradores.php",
			{
				prjID: prjID,
				colaboradores: ((users.length === 0)? false : users)
			},
			( function(data){
				system.modalInformacion( data[0]?'Éxito':'Error', data[1] );
				if (data[0]){
					$("#btn-agregar").prop("disabled", false);
				}
			} ),
			"json"
		);
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},

	init: function() {
		$("#slt-users").select2();
		$("#tbl-users tbody button.btn-remove_user").tooltip();
	}
};

$(document).ready(function(){ system.project_details.users.init() });