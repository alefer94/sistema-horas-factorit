<?php
require_once __DIR__.'/../common/validateAjaxRequest.php';

$usrID = validateAjaxRequest();
$prjID = intval($_GET["prjID"]);

if ($usrID != -1){
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOUsuarios.php';
	require_once __DIR__.'/../common/PDOProyectos.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_hitos.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_estimacion.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_colaboradores.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_planeacion.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_avances.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_bitacoras.php';
	require_once __DIR__.'/../snippets/detalle_proyecto_proveedores.php';
	
	
	$pdo = new InterfazPDO();
	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();
	
	
	$proveedor = filter_var($proyecto["prjProveedor"], FILTER_VALIDATE_BOOLEAN);
	$freelance = filter_var($proyecto["prjFreelance"], FILTER_VALIDATE_BOOLEAN);
	$bonos = filter_var($proyecto["prjBonos"], FILTER_VALIDATE_BOOLEAN);
	$interno = filter_var($proyecto["prjInterno"], FILTER_VALIDATE_BOOLEAN);


	unset($pdo);
	
	$esJefe = ($usuario["tpUsrID"] >= 2);
	unset($usuario);
?>
<div id="div-detalles_proyecto" class="container-fluid p-t-20" data-sid="<?=$prjID; ?>">
	<div class="row">
		<div class="col-md-3  font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width">
			<h4 style="font-family:verdana;"><?=$proyecto["prjCodigo"];?>/<?=$proyecto["prjNombre"];?></h4>

			
		</div>
		
		<div class="row">

			<div class="col-md-4">
				<table class="table table-borderless table caption-top">
				<caption>Empresa</caption>
						<tbody>
							<tr>
								<th scope="row"><?=$proyecto["cltNombre"];?></th>
								
							</tr>

						</tbody>
				</table>

			</div>
			
			<div class="col-md-4">
				<table class="table table-borderless table caption-top">
				<caption>Estado Proyecto</caption>
						<tbody>
							<tr>
								<th scope="row"><?=$proyecto["prjEstadoProyecto"];?></th>
								
							</tr>

						</tbody>
				</table>

			</div>

			<div class="col-md-4">
				<table class="table table-borderless table caption-top">
				<caption>Estado Factura</caption>
						<tbody>
							<tr>
								<th scope="row"><?=$proyecto["prjFlagFacturacion"];?></th>
								
							</tr>

						</tbody>
				</table>

			</div>
		</div>
		<div class="row">

			<div class="col-md-4">
				<table class="table table-borderless table caption-top">
				<caption>Vicepresidencia</caption>
						<tbody>
							<tr>
								<th scope="row"><?=$proyecto["vprNombre"];?></th>
								
							</tr>

						</tbody>
				</table>

			</div>
			
			<div class="col-md-4">
			<table class="table table-borderless table caption-top">
			<caption>Gerencia</caption>
					<tbody>
						<tr>
							<th scope="row"><?=$proyecto["gerNombre"];?></th>
							
						</tr>

					</tbody>
			</table>

			</div>

			<div class="col-md-4">
			<table class="table table-borderless table caption-top">
			<caption>SubGerencia</caption>
					<tbody>
						<tr>
							<th scope="row"><?=$proyecto["sgrNombre"];?></th>
							
						</tr>

					</tbody>
			</table>

			</div>
		</div>
		<div class="row">

			<div class="col-md-4">
				<table class="table table-borderless table caption-top">
				<caption>Centro de Costos</caption>
						<tbody>
							<tr>
								<th scope="row"><?=$proyecto["ccoNombre"];?></th>
								
							</tr>

						</tbody>
				</table>

			</div>
			
			
		</div>


		<div class="row">
			
		<div class="col-md-3">
				<div class="custom-control custom-switch">
				<input type="checkbox" <?=$interno == 1? 'checked' : ''?> class="custom-control-input" id="interno-switch">
				<label class="custom-control-label" for="interno-switch">Interno</label>
				<?php 
				if ($esJefe) { ?>
				
				<button id="btn-guardar_interno" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarInterno(event)">Guardar<span class="fa fa-check"></span></button><?php 
				}
				
				?>				
					
				</div>
			</div>


			<div class="col-md-3">
				<div class="custom-control custom-switch">
				<input type="checkbox" <?=$proveedor == 1? 'checked' : ''?> class="custom-control-input" id="proveedor-switch">
				<label class="custom-control-label" for="proveedor-switch">Proveedor</label>
				<?php 
				if ($esJefe) { ?>
				
				<button id="btn-guardar_proveedor" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarProveedor(event)">Guardar<span class="fa fa-check"></span></button><?php 
				}
				
				?>			
					
				</div>
			</div>

			<div class="col-md-3">
				<div class="custom-control custom-switch">
				<input type="checkbox" <?=$freelance == 1? 'checked' : ''?> class="custom-control-input" id="freelance-switch">
				<label class="custom-control-label" for="freelance-switch">Freelance</label>
				<?php 
				if ($esJefe) { ?>
				
				<button id="btn-guardar_freelance" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarFreelance(event)">Guardar<span class="fa fa-check"></span></button><?php 
				}
				
				?>
				
					
				</div>
			</div>

			<div class="col-md-3">
				<div class="custom-control custom-switch">
				<input type="checkbox" <?=$bonos == 1? 'checked' : ''?> class="custom-control-input" id="bonos-switch">
				<label class="custom-control-label" for="bonos-switch">Bonos</label>
				<?php 
				if ($esJefe) { ?>
				
				<button id="btn-guardar_bonos" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarBonos(event)">Guardar<span class="fa fa-check"></span></button><?php 
				}
				
				?>
				
					
				</div>
			</div>

			
		</div>

		

		
	<div class="row">
		<div id="div-proj_det_fechas" class="col-md-6">
			<div class="panel panel-default padding-15">
				<div class="panel-title b-b b-grey">
					<h5 id="btn-collapseFechas" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
						data-toggle="collapse" data-target="#collapseFechas" aria-expanded="false" role="button" 
						aria-controls="collapseFechas">&nbsp;Fechas <span class="pg pg-arrow_down"></span></h5>
				</div>
				<div id="collapseFechas" class="panel-body m-t-15 m-b-15 no-padding no-overflow collapse in" aria-expanded="false">
					<div class="auto-y-overflow no-x-overflow text-center" style="height: 220px !important">
						<div class="col-xs-6 form-group">
							<label for="ipt-fecha_inicio">Inicio</label>
					<?php if ($esJefe) { ?>
							<input id="ipt-fecha_inicio" type="date" class="form-control btn" value="<?=$proyecto["prjFechaInicio"];?>" />
					<?php } else { ?>
							<p id="ipt-fecha_inicio" class="form-control text-center"><?=$proyecto["prjFechaInicio"];?></p>
					<?php } ?>
						</div>
						<div class="col-xs-6 form-group">
							<label for="ipt-fecha_termino_des">Término (Desarrollo)</label>
					<?php if ($esJefe) { ?>
							<input id="ipt-fecha_termino_des" type="date" class="form-control btn" value="<?=$proyecto["prjFechaTerminoDes"];?>" />
					<?php } else { ?>
							<p id="ipt-fecha_termino_des" class="form-control text-center"><?=$proyecto["prjFechaTerminoDes"];?></p>
					<?php } ?>
						</div>
						<div class="col-xs-6 form-group">
							<label for="ipt-fecha_termino_est">Término (Estimada)</label>
					<?php if ($esJefe) { ?>
							<input id="ipt-fecha_termino_est" type="date" class="form-control btn" value="<?=$proyecto["prjFechaTerminoEst"];?>" />
					<?php } else { ?>
							<p id="ipt-fecha_termino_est" class="form-control text-center"><?=$proyecto["prjFechaTerminoEst"];?></p>
					<?php } ?>
						</div>
						<div class="col-xs-6 form-group">
							<label for="ipt-fecha_termino">Término (Real)</label>
					<?php if ($esJefe) { ?>
							<input id="ipt-fecha_termino" type="date" class="form-control btn" value="<?=$proyecto["prjFechaTermino"];?>" />
					<?php } else { ?>
							<p id="ipt-fecha_termino" class="form-control text-center"><?=$proyecto["prjFechaTermino"];?></p>
					<?php } ?>
						</div>
					</div>
					<?php if ($esJefe) { ?>
					<div class="text-right m-t-15 m-b-0">
						<button id="btn-guardar_fechas" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarFechas(event)">Guardar <span class="fa fa-check"></span></button>
						
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ($esJefe || !empty($proyecto["prjDescripcion"])) { ?>
		<div id="div-proj_det_descripcion" class="col-md-6 no-overflow">
			<div class="panel panel-default padding-15">
				<div class="panel-title b-b b-grey">
					<h5 id="btn-collapseDescripcion" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
						data-toggle="collapse" data-target="#collapseDescripcion" aria-expanded="false" role="button" 
						aria-controls="collapseDescripcion">Descripción <span class="pg pg-arrow_down"></span></h5>
				</div>
				<div id="collapseDescripcion" class="panel-body m-t-15 m-b-15 no-padding no-overflow collapse in" aria-expanded="false">
					<?php if ($esJefe) { ?>
					<textarea id="ipt-descripcion" rows="4" class="form-control" style="min-width: 100% !important; max-width: 100% !important; min-height: 220px !important; max-height: 220px !important" placeholder="Describa el proyecto en breve..."><?=$proyecto["prjDescripcion"];?></textarea> 
					<?php } 
						else { ?>
					<p style="min-width: 100% !important; max-width: 100% !important; min-height: 220px !important; max-height: 220px !important"><?=$proyecto["prjDescripcion"];?></p>
					<?php } ?>
					<?php if ($esJefe) { ?>
					<div class="text-right m-t-15 m-b-0">
						<button id="btn-guardar_descripcion" class="btn btn-complete no-margin" onclick="system.project_details.alGuardarDescripcion(event)">Guardar <span class="fa fa-check"></span></button>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<script type="text/javascript" src="js/detalles_proyecto.js"></script>
	<div class="row">
		<div id="div-proj_det_colaboradores" class="col">
			
			<?php detalle_proyecto_colaboradores(new InterfazPDO(), $usrID, $prjID); ?>
		</div>
		<div id="div-proj_det_proveedores" class="col">
			
			<?php detalle_proyecto_proveedores(new InterfazPDO(), $usrID, $prjID); ?>
		</div>
				
		<div id="div-proj_det_hitos" class="col">
			<?php detalle_proyecto_hitos(new InterfazPDO(), $usrID, $prjID); ?>
		</div>
		<div id="div-proj_det_estimacion" class="col">
			<?php detalle_proyecto_estimacion(new InterfazPDO(), $usrID, $prjID); ?>
		</div>
		<div id="div-proj_det_avances" class="col">
			<?php detalle_proyecto_avances(new InterfazPDO(), $usrID, $prjID); ?>
		</div>
		<div id="div-proj_det_bitacoras" class="col">
			<?php detalle_proyecto_bitacoras(new InterfazPDO(), $usrID, $prjID); ?>
		</div>

		
		
		
		
	</div>
</div>
<?php 
}
?>