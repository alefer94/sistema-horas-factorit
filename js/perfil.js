system.preferences = system.preferences || {

	alCambiarVistaClave: function(){
		var $this = $(this);
		var $input = $this.parent().find(".form-control"); 
		var $icon = $this.find("span");
		if ($this.hasClass("warning")){
			$icon.addClass("fa-eye-slash").removeClass("fa-eye");
			$this.removeClass("warning").addClass("danger");
			$input.attr("type", "text");
		}
		else {
			$icon.removeClass("fa-eye-slash").addClass("fa-eye");
			$this.addClass("warning").removeClass("danger");
			$input.attr("type", "password");
		}
	},

	alCambiarImagen: function(src){ },

	alCambiarPreferencias: function(event){
		event.preventDefault();
		var formData = $(event.target).serialize();
		$("#txt_password_old").val("");
		$("#txt_password_new").val("");
		
		system.modalCarga("Guardando...");
		var promise = $.post(
			"ajax/updatePreferencias.php",
			formData,
			( function (data){
				system.modalInformacion( data[0]?"Éxito":"Error", data[1] );		
				if (data[0]) {
					setInterval(function(){
						location.reload();
					}, 2500);
				}
				else {
					setInterval(function(){
						$("#modal_aviso").remove();
						$modal.find("h4.modal-title").text('Preferencias');
						$modal.find("form").show();
					}, 2000);
				}
			} ),
			"json" 
		);
		
		promise.fail(system.modalError);
	},

	init: function(){
		//$("#btn_cambiar_imagen").click(alCambiarImagen);
		$("#btn-cambiar_vista").tooltip();
		$("#slt-theme").select2();
	}
};


$(document).ready(function(){ system.preferences.init(); });