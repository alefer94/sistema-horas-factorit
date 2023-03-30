<?php 
require_once __DIR__.'/../snippets/options_cargo_generico.php';
require_once __DIR__.'/../snippets/options_bandas_salariales.php';



function detalle_proyecto_estimacion($pdo = null, $usrID = 0, $prjID = 0){

	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();


	$esJefe = ($usuario["tpUsrID"] >= 2);
?>
<div class="panel panel-default padding-15">
	<div class="panel-title b-b b-grey">
		<h5 id="btn-collapseEstimacion" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
			data-toggle="collapse" data-target="#collapseEstimacion" aria-expanded="false" role="button" 
			aria-controls="collapseEstimacion">Estimación<span class="pg pg-arrow_down"></span></h5>
	</div>
	<div id="collapseEstimacion" class="panel-body no-padding no-margin collapse" aria-expanded="false">
		<div class="auto-y-overflow no-x-overflow m-t-15 m-b-0 no-padding b-t b-b b-l b-r b-grey" style="height: 200px !important">
			<div class="table-responsive">
				<table id="tbl-estimacion" class="table table-condensed table-bordered no-margin full-width">
					<thead class="bg-info-light">
						<tr>
							<th style="width: 6rem !important" class="no-border text-center text-white">Rol Fit</th>
							<th style="width: 9rem !important" class="no-border text-center text-white">Rol(Propuesto)</th>
							<th style="width: 7rem !important" class="no-border text-center text-white">Seniority</th>
							<th style="width: 9rem !important" class="no-border text-center text-white">Codigo Banda</th>
							<!-- <th style="width: 8rem !important" class="no-border text-center text-white">Distribución</th> -->
						<?php if ($esJefe) { ?>
							<th style="width: 6rem !important" class="no-border text-center text-white">Mes</th>
							<!-- <th style="width: 6rem !important" class="no-border text-center text-white">Semana</th> -->
						<?php } ?>
							<th style="width: 8rem !important" class="no-border text-center text-white">Fecha Inicio</th>
							<th style="width: 8rem !important" class="no-border text-center text-white">Fecha Fin</th>
							<th style="width: 6rem !important" class="no-border text-center text-white">Horas</th>
							
						<?php if ($esJefe) { ?>
							<th style="width: 6rem !important; max-width: 8rem !important;" class="no-border text-center text-white">Quitar</th>
						<?php } ?>
						</tr>
					</thead>
					<tbody><?php 
					foreach (PDOProyectos::select_estimacion($pdo, intval($proyecto["prjID"])) as $estimacion) {
						if ($esJefe) { 
					  ?><tr data-sid="<?=$estimacion["estID"];?>"><?php
                          ?><td class="no-border no-padding">
                          <select class="slt-cgcID full-width"><option></option><?php options_cargo_generico(new InterfazPDO(),$estimacion["cgcID"]); ?></select> 
                          </td><?php
						  ?><td class="no-padding"><input class="form-control ipt-estimacion_estRol b-rad-0 no-border" type="text" placeholder="..." value="<?=$estimacion["estRol"];?>" required/></td><?php
                          ?><td class="no-padding"><input class="form-control ipt-estimacion_estSeniority b-rad-0 no-border" type="text" placeholder="..." value="<?=$estimacion["estSeniority"];?>" required/></td><?php
                          ?><td class="no-border no-padding">
						  <select class="slt-bnsID full-width"><option></option><?php options_bandas_salariales(new InterfazPDO(),$estimacion["bnsID"]); ?></select> 
						  </td><?php
                          ?><td class="no-padding"><input class="form-control ipt-estimacion_estMes b-rad-0 no-border" type="text" placeholder="..." value="<?=$estimacion["estMes"];?>" required/></td><?php
                          ?><td class="no-padding"><input class="form-control ipt-estimacion_estfch_inicio b-rad-0 no-border text-center full-width btn" type="date" value="<?=$estimacion["estFechaInicio"];?>" /></td><?php
                          ?><td class="no-padding"><input class="form-control ipt-estimacion_estfch_fin b-rad-0 no-border text-center full-width btn" type="date" value="<?=$estimacion["estFechaFin"];?>" /></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-estimacion_estHoras b-rad-0 no-border text-center full-width" type="number" placeholder="0" min="0" step="0.01" value="<?=$estimacion["estHoras"];?>" /></td><?php
						  ?><td class="no-padding no-border full-height"><button class="btn btn-danger btn-remove_estimacion no-border b-rad-0 full-height full-width" type="button" onclick="system.project_details.estimacion.alQuitar(event)"><i class="fa fa-remove" ></i></button></td><?php
						 
						} 
						else {
						?><tr><?php
						
						  ?><td class="p-t-0 p-b-0"><?=$estimacion["estRol"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$estimacion["estSeniority"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$estimacion["estMes"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$estimacion["estSemana"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$estimacion["estFechaFin"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$estimacion["estHoras"];?></td><?php
						 
						}
						
					  ?></tr><?php 
					} 
				  ?></tbody>
				</table>
			</div>
		</div><?php 
		if ($esJefe) { 
	  ?><div class="text-right m-t-15 m-b-0">
	        <button id="btn-agregar_estimacion" type="button" class="btn btn-primary" onclick="system.project_details.estimacion.alAgregar()">Agregar Estimación <span class="fa fa-plus"></span></button>
		    <button id="btn-guardar_estimacion" class="btn btn-complete" onclick="system.project_details.estimacion.alGuardar(event)">Guardar Estimación <span class="fa fa-check"></span></button>
	    </div><?php
		}
  ?></div>
</div>
<script type="text/javascript">
	function newEstimacionRow(){
		return (
            '<td class="no-border no-padding">' +
				'<select class="slt-cgcID full-width"><option></option><?php options_cargo_generico(new InterfazPDO()); ?></select>' +
			'</td>'+
			'<td class="no-padding"><input class="form-control ipt-estimacion_estRol b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
            '<td class="no-padding"><input class="form-control ipt-estimacion_estSeniority  b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
            '<td class="no-border no-padding">' +
				'<select class="slt-bnsID full-width"><option></option><?php options_bandas_salariales(new InterfazPDO()); ?></select>' +
			'</td>'+
            '<td class="no-padding"><input class="form-control ipt-estimacion_estMes  b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
            // '<td class="no-padding"><input class="form-control ipt-estimacion_estSemana  b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
            '<td class="no-padding"><input class="form-control ipt-estimacion_estfch_inicio b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
            '<td class="no-padding"><input class="form-control ipt-estimacion_estfch_fin b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
			'<td class="no-padding"><input class="form-control ipt-estimacion_estHoras b-rad-0 no-border text-center full-width" type="number" min="0" step="0.01" placeholder="0"/></td>'+
			'<td class="no-border no-padding full-height"><div class="no-padding full-width full-height"><button class="btn btn-danger btn-remove_estimacion b-rad-0 no-border full-height full-width" type="button" onclick="system.project_details.estimacion.alQuitar(event)"><i class="fa fa-remove" ></i></button></td>'
		);
	}
</script>
<script src="js/detalle_proyecto_estimacion.js" type="text/javascript"></script>
<?php
}