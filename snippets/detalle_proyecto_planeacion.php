<?php 

function detalle_proyecto_planeacion($pdo = null, $usrID = 0, $prjID = 0){

		$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
		$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();
		$esJefe = ($usuario["tpUsrID"] >= 2);
		$planeacionHecha = ($proyecto["prjHorasEstimadas"] > 0);
		$id_tabla = $planeacionHecha? "tbl-control_cambios" : "tbl-planeacion_inicial";
?>
<div class="panel panel-default padding-15">
	<div class="panel-title b-b b-grey">
		<h5 id="btn-collapsePlaneacion" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
			data-toggle="collapse" data-target="#collapsePlaneacion" aria-expanded="false" role="button" 
			aria-controls="collapsePlaneacion">Planeación <span class="pg pg-arrow_down"></span></h5>
	</div>
	<div id="collapsePlaneacion" class="panel-body no-padding no-margin collapse"  aria-expanded="false">
	
<?php 
	if (!$esJefe && !$planeacionHecha) { 
	  ?><div class="alert alert-info bold bordered text-center" role="alert">
			<span>La planeación inicial de este proyecto aún no se ha grabado.</span>
			<span class="fa fa-exclamation-circle pull-right" style="font-size: 21px"></span>
		</div><?php 
	} 
	else { 
	  ?><div class="auto-y-overflow no-x-overflow m-t-15 m-l-0 m-r-0 m-b-15 no-padding b-t b-b b-l b-r b-grey" style="height: 250px !important">
		    <div class="table-responsive">
				<table id="<?=$id_tabla;?>" class="table table-condensed table-bordered no-margin full-width"><?php
				
				if (!$planeacionHecha) { ?>
					<thead class="bg-info-light">
					<tr>
						<th style="width:12rem !important" class="no-border text-center text-white p-l-5 p-r-5">Etapa</th>
						<th style="width:5rem !important" class="no-border text-center text-white p-l-5 p-r-5">Días</th>
						<th style="width:6rem !important" class="no-border text-center text-white p-l-5 p-r-5">Horas</th>
						<th style="width:4rem !important" class="no-border text-center text-white p-l-5 p-r-5" title="% de Participación">Participación</th>
						<th style="width:6rem !important" class="no-border text-center text-white p-l-5 p-r-5">Total</th>
					</tr>
					</thead>
					<tbody><?php
					foreach (PDOProyectos::select_etapas($pdo) as $etapa) { 
					  ?><tr>
							<td class="p-t-0 p-b-0" data-sid="<?=$etapa["etapaID"];?>"><?=$etapa["etapaNombre"];?></td>
							<td class="no-padding"><input class="form-control ipt-dias_etapa no-border text-center" type="text" maxlength="3" placeholder="..." onchange="system.project_details.plan.alCambiarHorasPlaneacion(event)" /></td>
							<td class="p-t-0 p-b-0 text-center"></td>
							<td class="no-padding"><input class="form-control ipt-pct_etapa no-border text-center" type="text" maxlength="3" placeholder="%" onchange="system.project_details.plan.alCambiarHorasPlaneacion(event)" /></td>
							<td class="p-t-0 p-b-0 text-center"></td>
						</tr><?php 
					}
					  ?><tr id="tr-planeacion-inicial-total">
							<td class="bold padding-10" colspan="4">Total</td>
							<td class="text-center"></td>
						</tr>
					</tbody><?php 
				} else { ?>
					<thead class="bg-info-light">
					<tr>
						<th style="width: 12rem !important" class="no-border text-center text-white p-l-5 p-r-5">Etapa</th>
						<th style="width: 6rem !important" class="no-border text-center text-white p-l-5 p-r-5">Horas</th>
						<th style="width: 8rem !important; max-width: 8rem !important;" class="no-border text-center text-white p-l-5 p-r-5">Quitar</th>
					</tr>
					</thead>
					<tbody><?php
					foreach (PDOProyectos::select_planeacion($pdo, intval($proyecto["prjID"])) as $fase) {
						if ($fase["prjPlnHrs"] > 0) { 
					?><tr>
							<td class="p-t-0 p-b-0"><?=$fase["etapaNombre"];?></td>
							<td class="p-t-0 p-b-0 text-center"><?=$fase["prjPlnHrs"];?> hrs</td>
							<td class="no-padding full-height no-border"><div class="no-margin full-width full-height bg-info-light"></div></td>
						</tr><?php 	
						}
					}
					
					foreach (PDOProyectos::select_cambios($pdo, intval($proyecto["prjID"])) as $i => $cmb) { 
						if ($i === 0) { 
					?><tr>
							<td class="p-b-0 p-t-0 bold">Total Planeación Inicial</td>
							<td class="p-b-0 p-t-0 text-center"><?=$cmb["cmbHoras"];?> hrs</td>
							<td class="no-padding no-border full-height"><button class="btn btn-danger no-border b-rad-0 full-height full-width" disabled><span class="fa fa-remove"></span></button></td>
						</tr><?php 
						} 
						else { 
					?><tr class="tr-cambio" data-sid="<?=$cmb["cmbID"];?>"> 
							<td class="no-padding"><input value="<?=$cmb["cmbNombre"];?>" class="form-control ipt-nombre_cambio no-border b-rad-0" type="text" maxlength="50" placeholder="..." required /></td>
							<td class="no-padding"><input value="<?=$cmb["cmbHoras"];?>" class="form-control text-center ipt-horas_cambio no-border b-rad-0" type="number" min="0" maxlength="3" placeholder="..." required /></td>
							<td class="no-padding no-border full-height"><button class="btn btn-danger btn-remove_cambio no-border b-rad-0 full-height full-width" onclick="system.project_details.plan.alQuitarCambio(event)"><span class="fa fa-remove"></span></button></td>
						</tr><?php 
						}	
					} 
			} ?></tbody>
				</table>
			</div>
		</div>
	<?php if ($esJefe) { ?>
		<div class="text-right no-margin">
		<?php if ($planeacionHecha){ ?>
		    <button id="btn-agregar_cambio" type="button" class="btn btn-primary" onclick="system.project_details.plan.alAgregarCambio()">Agregar Cambio <span class="fa fa-plus"></span></button>
		    <button id="btn-guardar_cambios" type="button" class="btn btn-complete" onclick="system.project_details.plan.alGuardarControlesCambios(event)">Guardar Cambios <span class="fa fa-check"></span></button>
		<?php } else { ?>
		    <button id="btn-guardar_planeacion" type="button" class="btn btn-complete" onclick="system.project_details.plan.alGuardarPlaneacionInicial(event)" disabled>Guardar Planeación Inicial <span class="fa fa-check"></span></button>
		<?php } ?>
	    </div>
	<?php } ?>
<?php } ?>
    </div>
</div>
<script type="text/javascript">
	function newCambioRow(){
		return (
			'<td class="no-padding"><input class="form-control ipt-nombre_cambio no-border b-rad-0" type="text" maxlength="50" placeholder="..." required/></td>'+
			'<td class="no-padding"><input class="form-control text-center ipt-horas_cambio no-border b-rad-0" type="number" min="0" maxlength="3" placeholder="..." required/></td>'+
			'<td class="no-padding no-border full-height"><button type="button" class="btn btn-danger btn-remove_cambio b-rad-0 no-border full-height full-width" onclick="system.project_details.plan.alQuitarCambio(event)"><span class="fa fa-remove"></span></button></td>'
		);
	}
</script>
<script src="js/detalle_proyecto_planeacion.js" type="text/javascript"></script>
<?php 
}