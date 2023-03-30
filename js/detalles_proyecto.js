system.project_details = system.project_details || { 

	alClickWidget: function(){
		var _id = $(this).attr("id"); 
		$("#btn-"+_id).find("span.pg").toggleClass("pg-arrow_down pg-arrow_up");
	},
	
	alGuardarDescripcion: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		
		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var descripcion = $("#ipt-descripcion").val();
		
		var promise = $.post(
			"ajax/updateProyectoDescripcion.php",
			{
				'prjID': prjID,
				'descripcion': (descripcion===""? false : descripcion)
			},
			( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
			"json"
		);
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},
	
	alGuardarFechas: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		
		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var $fchInicio = $("#ipt-fecha_inicio");
		var $fchTerminoDes = $("#ipt-fecha_termino_des");
		var $fchTerminoEst = $("#ipt-fecha_termino_est");
		var $fchTermino = $("#ipt-fecha_termino");

		if ($fchInicio[0].checkValidity() && 
		    $fchTerminoDes[0].checkValidity() &&
		    $fchTerminoEst[0].checkValidity() && 
		    $fchTermino[0].checkValidity() ) {

			var promise = $.post(
				"ajax/updateProyectoFechas.php",
				{
					'prjID': prjID,
					'prjFechaInicio': $fchInicio.val(),
					'prjFechaTerminoDes': $fchTerminoDes.val(),
					'prjFechaTerminoEst': $fchTerminoEst.val(),
					'prjFechaTermino': $fchTermino.val()
				},
				( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
				"json"
				
			
			);
			
			
			
		}

		
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });

		
	},	

	alGuardarProveedor: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		

		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var $prjProveedor =$("#proveedor-switch").prop('checked');
		if( $prjProveedor==true){
			$prjProveedor=1
		}
		
		
			var promise = $.post(
				"ajax/updateProyectoProveedor.php",
				{	 'prjID': prjID,
				     'prjProveedor': $prjProveedor
					
					
				},
				( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
				"json"
				
			);
	
			
		
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},	

	alGuardarFreelance: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		

		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var $prjFreelance =$("#freelance-switch").prop('checked');
		if( $prjFreelance==true){
			$prjFreelance=1
		}
		
		
			var promise = $.post(
				"ajax/updateProyectoFreelance.php",
				{	 'prjID': prjID,
				     'prjFreelance': $prjFreelance
					
					
				},
				( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
				"json"
				
			);
		
			
		
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},	

	alGuardarBonos: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		

		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var $prjBonos =$("#bonos-switch").prop('checked');
		if( $prjBonos==true){
			$prjBonos=1
		}
		
		
			var promise = $.post(
				"ajax/updateProyectoBonos.php",
				{	 'prjID': prjID,
				     'prjBonos': $prjBonos
					
					
				},
				( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
				"json"
				
			);
		
			
		
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},	

	alGuardarInterno: function(event){
		var $this = $(event.target).closest("button");
		
		$this.prop("disabled", true);
		system.modalCarga("Guardando...");
		

		var prjID = Number($("#div-detalles_proyecto").data("sid"));
		var $prjInterno =$("#interno-switch").prop('checked');
		if( $prjInterno==true){
			$prjInterno=1
		}
		
		
			var promise = $.post(
				"ajax/updateProyectoInterno.php",
				{	 'prjID': prjID,
				     'prjInterno': $prjInterno
					
					
				},
				( function(data){ system.modalInformacion( data[0]?'Éxito':'Error', data[1] ); } ),
				"json"
				
			);
		
			
		
		
		promise.fail(system.modalError);
		promise.always(function(){ $this.prop("disabled", false); });
	},	

	init: function(){
		$collapses = $("#collapseFechas")
				  .add("#collapseDescripcion")
				  .add("#collapseColaboradores")
				  .add("#collapsePlaneacion")
				  .add("#collapseHitos")
				  .add("#collapseEstimacion")
				  .add("#collapseBitacoras")
				  .add("#collapseProveedores");
		
		$collapses.on("show.bs.collapse", system.project_details.alClickWidget);
		// $collapses.on("hide.bs.collapse", system.project_details.alClickWidget);
	}
	
};

$(document).ready(function(){ system.project_details.init(); });