<?php 
require_once __DIR__.'/../snippets/options_monedas.php';
require_once __DIR__.'/../snippets/options_proveedores.php';

require_once __DIR__.'/../snippets/tr_proyecto_proveedor.php';


function detalle_proyecto_proveedores($pdo = null, $usrID = 0, $prjID = 0){

	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();


	$esJefe = ($usuario["tpUsrID"] >= 2);
?>
<div class="panel panel-default padding-15">
	<div class="panel-title b-b b-grey">
		<h5 id="btn-collapseProveedores" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
			data-toggle="collapse" data-target="#collapseProveedores" aria-expanded="false" role="button" 
			aria-controls="collapseProveedores">Colaboradores Externos<span class="pg pg-arrow_down"></span></h5>
	</div>
	<div id="collapseProveedores" class="panel-body no-padding no-margin collapse" aria-expanded="false">
		<div class="auto-y-overflow no-x-overflow m-t-15 m-b-0 no-padding b-t b-b b-l b-r b-grey" style="height: 200px !important">
			<div class="table-responsive">
				<table id="tbl-proveedores" class="table table-condensed table-bordered no-margin full-width">
					<thead class="bg-info-light">
						<tr>
							<th style="width: 5rem; min-width: 5rem;"class="no-border text-center text-white">Nombre</th>
							<th style="width: 5rem; min-width: 5rem;" class="text-center text-white">Fecha Inicio Asiganción</th>
							<th style="width: 5rem; min-width: 5rem;" class="text-center text-white">Fecha Fin Asignación</th>
							<th style="width: 5rem; min-width: 5rem;" class="text-center text-white">Asignación</th>
							<th style="width: 5rem; min-width: 5rem;" class="text-center text-white">Costo</th>
							<th style="width: 5rem; min-width: 5rem;" class="text-center text-white">Moneda</th>
						
						<?php if ($esJefe){ ?>
								<th style="width: 3rem; min-width: 3rem" class="text-center text-white">Acciones</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php tr_proyecto_proveedor(new InterfazPDO(),$GLOBALS["prjID"],$esJefe);?>
				</tbody>
				</table>
			</div>
		</div>
		<div id="footer-actions-container" class="b-t b-grey full-width hidden-sm hidden-xs" style="height: 2.5rem !important"><?php 
				if ($esJefe) { ?>
				
				<button id="btn-agregar_proveedor" class="btn btn-primary btn-animated from-top fa fa-plus no-border b-rad-0 full-width full-height" type="button" onclick="system.project_details.proveedores.alAgregar2();"><span>Agregar Asignación</span></button><?php 
				}
				
				?>
			</div>
		</div>
</div>
<script type="text/javascript">
	function newProveedorRow(){
		return (
				
			'<td class="no-padding" style="display:none;">'+
			   	'<input class="slt-prj full-width" value="<?php echo $GLOBALS["prjID"];?>"/>' +
				'</td>'+
				'<td class="no-border no-padding">'+
				'<select class="slt-proveedores full-width full-height no-padding no-margin"><option></option><?php options_proveedores(new InterfazPDO(), 500, 1);?></select>' +
				'</td>'+
				'<td class="no-padding"><input class="form-control ipt-prjProFechaInicioAsignacion b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
				'<td class="no-padding"><input class="form-control ipt-prjProFechaFinAsignacion b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
				'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-prjProPorcentajeAsignacion b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" id="a"/><span class="input-group-addon no-border b-rad-0">%</span></div></td>'+
				'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-prjProCosto b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100" id="a"/></div></td>'+
				'<td class="no-border no-padding">' +
				'<select class="slt-moneda full-width full-height no-padding no-margin"><option></option><?php options_monedas(new InterfazPDO(), 500, 1);?></select>' +
				'</td>'+
				'<td class="no-border no-padding full-height"><div class="no-padding full-width full-height">'+
				'<button class="btn btn-complete btn-save no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.project_details.proveedores.alGuardar(event)"><span class="fa fa-save"></span></button>'+
				'<button class="btn btn-danger btn-remove no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.project_details.proveedores.alBorrar(event)"><span class="fa fa-remove"></span></button>'+
			'</div></td>'
			);
	}
</script>
<script src="js/detalle_proyecto_proveedores.js" type="text/javascript"></script>
<?php
}