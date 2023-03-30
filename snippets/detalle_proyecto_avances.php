<?php 

function detalle_proyecto_avances($pdo = null, $usrID = 0, $prjID = 0){

	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$esJefe = ($usuario["tpUsrID"] >= 2);
?>
	<div class="panel panel-default padding-15">
		<div class="panel-title b-b b-grey">
			<h5 id="btn-collapseAvances" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
				data-toggle="collapse" data-target="#collapseAvances" aria-expanded="false" role="button" 
				aria-controls="collapseAvances">Avances <span class="pg pg-arrow_down"></span></h5>
		</div>
		<div id="collapseAvances" class="panel-body no-padding no-margin collapse"  aria-expanded="false">
			<div class="auto-y-overflow no-x-overflow m-t-15 m-l-0 m-r-0 m-b-15 no-padding b-t b-b b-l b-r b-grey" style="height: 250px !important">
				<div class="table-responsive">
					<table id="tbl-avances" class="table table-condensed table-bordered no-margin full-width">
						<thead class="bg-info-light">
						<tr>
							<th style="width:8rem !important" class="no-border text-center text-white p-l-5 p-r-5">Fecha</th>
							<th style="width:12rem !important" class="no-border text-center text-white p-l-5 p-r-5">Descripción</th>
							<th style="width:6rem !important" class="no-border text-center text-white p-l-5 p-r-5">Avance Real</th>
							<th style="width:6rem !important" class="no-border text-center text-white p-l-5 p-r-5">Avance Esperado</th>
							<th style="width:4rem !important" class="no-border text-center text-white p-l-5 p-r-5">Quitar</th>
						</tr>
						</thead>
						<tbody>
					<?php foreach (PDOProyectos::select_avances($pdo, $prjID) as $avance) { ?>
							<tr data-sid="<?=$avance["advID"];?>">
								<td class="no-padding"><input class="form-control ipt-fecha_avance no-border text-center" type="date" value="<?=$avance["advFecha"];?>" required/></td>
								<td class="no-padding"><input class="form-control ipt-desc_avance no-border" type="text" placeholder="..." value="<?=$avance["advDescripcion"];?>" /></td>
								<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-pct_avance_real b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" value="<?=$avance["advPorcReal"];?>" required/><span class="input-group-addon no-border b-rad-0">%</span></div></td>
								<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-pct_avance_esp b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" value="<?=$avance["advPorcEsperado"];?>" required/><span class="input-group-addon no-border b-rad-0">%</span></div></td>
								<td class="no-padding no-border full-height"><div class="no-padding full-width full-height"><button class="btn btn-danger btn-remove_avance no-border b-rad-0 full-width full-height" onclick="system.project_details.advances.alQuitar(event)"><span class="fa fa-remove"></span></button></div></td>
							</tr>
					<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php if ($esJefe) { ?>
			<div class="text-right no-margin">
				<button id="btn-agregar_avance" type="button" class="btn btn-primary" onclick="system.project_details.advances.alAgregar()">Agregar Avance <span class="fa fa-plus"></span></button>
				<button id="btn-guardar_avances" type="button" class="btn btn-complete" onclick="system.project_details.advances.alGuardar(event)">Guardar Avances <span class="fa fa-check"></span></button>
			</div>
		<?php } ?>
		</div>
	</div>
	<script type="text/javascript">
		function newAvanceRow(){
			return (
				'<td class="no-padding"><input class="form-control ipt-fecha_avance no-border text-center" type="date" required/></td>' +
				'<td class="no-padding"><input class="form-control ipt-desc_avance no-border" type="text" placeholder="..." /></td>' +
				'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-pct_avance_real b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" required/><span class="input-group-addon no-border b-rad-0">%</span></div></td>' +
				'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-pct_avance_esp b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" required/><span class="input-group-addon no-border b-rad-0">%</span></div></td>' +
				'<td class="no-padding no-border full-height"><div class="no-padding full-width full-height"><button class="btn btn-danger btn-remove_avance no-border b-rad-0 full-width full-height" onclick="system.project_details.advances.alQuitar(event)"><span class="fa fa-remove"></span></button></div></td>'
			);
		}
	</script>
	<script src="js/detalle_proyecto_avances.js" type="text/javascript"></script>
<?php 
}