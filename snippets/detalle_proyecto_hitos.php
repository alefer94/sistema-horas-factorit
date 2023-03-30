<?php 
require_once __DIR__.'/../snippets/options_monedas.php';
require_once __DIR__.'/../snippets/options_estados_hitos.php';


function detalle_proyecto_hitos($pdo = null, $usrID = 0, $prjID = 0){

	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();


	$esJefe = ($usuario["tpUsrID"] >= 2);
?>
<div class="panel panel-default padding-15">
	<div class="panel-title b-b b-grey">
		<h5 id="btn-collapseHitos" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
			data-toggle="collapse" data-target="#collapseHitos" aria-expanded="false" role="button" 
			aria-controls="collapseHitos">Hitos <span class="pg pg-arrow_down"></span></h5>
	</div>
	<div id="collapseHitos" class="panel-body no-padding no-margin collapse" aria-expanded="false">
		<div class="auto-y-overflow no-x-overflow m-t-15 m-b-0 no-padding b-t b-b b-l b-r b-grey" style="height: 300px !important">
			<div class="table-responsive">
				<table id="tbl-hitos" class="table table-condensed table-bordered no-margin full-width">
					<thead class="bg-info-light">
						<tr>
							<th style="width: 10rem !important" class="no-border text-center text-white">Descripción</th>
							<th style="width: 10rem !important" class="no-border text-center text-white">Fecha Término</th>
							<th style="width: 12rem !important" class="no-border text-center text-white">Fecha Provisión</th>
							<th style="width: 12rem !important" class="no-border text-center text-white">Fecha Conformidad</th>
							<!-- <th style="width: 8rem !important" class="no-border text-center text-white">Distribución</th> -->
						<?php if ($esJefe) { ?>
							<th style="width: 8rem !important" class="no-border text-center text-white">Valor</th>
							<th style="width: 7rem !important" class="no-border text-center text-white">Moneda</th>
						<?php } ?>
							<th style="width: 9rem !important" class="no-border text-center text-white">Estado Hito</th>
							<th style="width: 8rem !important" class="no-border text-center text-white">Factura N°</th>
							<th style="width: 9rem !important" class="no-border text-center text-white">Fecha Factura</th>
							
						<?php if ($esJefe) { ?>
							<th style="width: 7rem !important; max-width: 8rem !important;" class="no-border text-center text-white">Quitar</th>
						<?php } ?>
						</tr>
					</thead>
					<tbody><?php 
					foreach (PDOProyectos::select_hitos($pdo, intval($proyecto["prjID"])) as $hito) {
						if ($esJefe) { 
					  ?><tr data-sid="<?=$hito["htID"];?>"><?php
						  ?><td class="no-padding"><input class="form-control ipt-hito_desc b-rad-0 no-border" type="text" placeholder="..." value="<?=$hito["htDescripcion"];?>" required/></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-hito_fch_trm b-rad-0 no-border text-center full-width btn" type="date" value="<?=$hito["htFechaTermino"];?>" /></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-hito_fch_prov b-rad-0 no-border text-center full-width btn" type="date" value="<?=$hito["htFechaProvision"];?>" /></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-hito_fch_conf b-rad-0 no-border text-center full-width btn" type="date" value="<?=$hito["htFechaConformidad"];?>" /></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-hito_uf b-rad-0 no-border text-center full-width" type="number" placeholder="0" min="0" step="0.01" value="<?=$hito["htUF"];?>" /></td><?php
						  ?><td class="no-border no-padding">
						  <select class="slt-moneda full-width"><option></option><?php options_monedas(new InterfazPDO(),$hito["monID"]); ?></select> 
					  	  </td><?php
						  ?><td class="no-border no-padding">
						  <select class="slt-est_hit full-width"><option></option><?php options_estados_hitos(new InterfazPDO(),$hito["htEstID"]); ?></select> 
						  </td><?php
						  ?><td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-hito_fact b-rad-0 no-border text-center" type="text" placeholder="..."  value="<?=$hito["htFactura"];?>"/></div></td><?php
						  ?><td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-hito_fch_fact b-rad-0 no-border text-center" type="text" placeholder="..."  value="<?=$hito["htFechaFacturacion"];?>"/></div></td><?php
						  ?><td class="no-padding no-border full-height"><button class="btn btn-danger btn-remove_hito no-border b-rad-0 full-height full-width" type="button" onclick="system.project_details.hitos.alQuitar(event)"><i class="fa fa-remove" ></i></button></td><?php
						 
						} 
						else {
						?><tr><?php
						
						  ?><td class="p-t-0 p-b-0"><?=$hito["htDescripcion"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htFechaTermino"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htFechaProvision"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htFechaConformidad"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htEstDescripcion"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htFactura"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$hito["htFechaFacturacion"];?></td><?php
						}
						
					  ?></tr><?php 
					} 
				  ?></tbody>
				</table>
			</div>
		</div><?php 
		if ($esJefe) { 
	  ?><div class="text-right m-t-15 m-b-0">
	        <button id="btn-agregar_hito" type="button" class="btn btn-primary" onclick="system.project_details.hitos.alAgregar()">Agregar Hito <span class="fa fa-plus"></span></button>
		    <button id="btn-guardar_hitos" class="btn btn-complete" onclick="system.project_details.hitos.alGuardar(event)">Guardar Hitos <span class="fa fa-check"></span></button>
	    </div><?php
		}
  ?></div>
</div>
<script type="text/javascript">
	function newHitoRow(){
		return (
			'<td class="no-padding"><input class="form-control ipt-hito_desc b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
			'<td class="no-padding"><input class="form-control ipt-hito_fch_trm b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
			'<td class="no-padding"><input class="form-control ipt-hito_fch_prov b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
			'<td class="no-padding"><input class="form-control ipt-hito_fch_conf b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
			// '<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-hito_pct b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100"/><span class="input-group-addon no-border b-rad-0">%</span></div></td>'+
			'<td class="no-padding"><input class="form-control ipt-hito_uf b-rad-0 no-border text-center full-width" type="number" min="0" step="0.01" placeholder="0"/></td>'+
			'<td class="no-border no-padding">' +
				'<select class="slt-moneda full-width"><option></option><?php options_monedas(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-est_hit full-width"><option></option><?php options_estados_hitos(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-hito_fact b-rad-0 no-border text-center" type="text" placeholder="..."/></div></td>'+
			'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-hito_fch_fact b-rad-0 no-border text-center" type="text" placeholder="..."/></div></td>'+
			'<td class="no-border no-padding full-height"><div class="no-padding full-width full-height"><button class="btn btn-danger btn-remove_hito b-rad-0 no-border full-height full-width" type="button" onclick="system.project_details.hitos.alQuitar(event)"><i class="fa fa-remove" ></i></button></td>'
		);
	}
</script>
<script src="js/detalle_proyecto_hitos.js" type="text/javascript"></script>
<?php
}