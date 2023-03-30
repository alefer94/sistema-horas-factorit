<?php 



function detalle_proyecto_bitacoras($pdo = null, $usrID = 0, $prjID = 0){

	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	$proyecto = PDOProyectos::select($pdo, 1, 1, ["prjID" => $prjID] )->fetch();


	$esJefe = ($usuario["tpUsrID"] >= 2);
?>
<div class="panel panel-default padding-15">
	<div class="panel-title b-b b-grey">
		<h5 id="btn-collapseBitacoras" class="btn font-montserrat text-left p-l-10 no-margin no-border b-rad-0 full-width" 
			data-toggle="collapse" data-target="#collapseBitacoras" aria-expanded="false" role="button" 
			aria-controls="collapseBitacoras">Bitacoras<span class="pg pg-arrow_down"></span></h5>
	</div>
	<div id="collapseBitacoras" class="panel-body no-padding no-margin collapse" aria-expanded="false">
		<div class="auto-y-overflow no-x-overflow m-t-15 m-b-0 no-padding b-t b-b b-l b-r b-grey" style="height: 200px !important">
			<div class="table-responsive">
				<table id="tbl-bitacoras" class="table table-condensed table-bordered no-margin full-width">
					<thead class="bg-info-light">
						<tr>
							<th style="width: 10rem !important" class="no-border text-center text-white">Fecha</th>
							<th style="width: 10rem !important" class="no-border text-center text-white">Actividad</th>
							<th style="width: 8rem !important" class="no-border text-center text-white">Tiempo</th>
						<?php if ($esJefe) { ?>
							<th style="width: 8rem !important; max-width: 8rem !important;" class="no-border text-center text-white">Quitar</th>
						<?php } ?>
						</tr>
					</thead>
					<tbody><?php 
					foreach (PDOProyectos::select_bitacoras($pdo, intval($proyecto["prjID"])) as $bitacora) {
						if ($esJefe) { 
					  ?><tr data-sid="<?=$bitacora["bitID"];?>"><?php
					  	  ?><td class="no-padding"><input class="form-control ipt-bitacora_fch b-rad-0 no-border text-center full-width btn" type="date" value="<?=$bitacora["bitFecha"];?>" /></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-bitacora_desc b-rad-0 no-border" type="text" placeholder="..." value="<?=$bitacora["bitDescripcion"];?>" required/></td><?php
						  ?><td class="no-padding"><input class="form-control ipt-bitacora_hora b-rad-0 no-border" type="text" placeholder="..." value="<?=$bitacora["bitHora"];?>" required/></td><?php
						  ?><td class="no-padding no-border full-height"><button class="btn btn-danger btn-remove_bitacora no-border b-rad-0 full-height full-width" type="button" onclick="system.project_details.bitacoras.alQuitar(event)"><i class="fa fa-remove" ></i></button></td><?php
						} 
						else {
						?><tr><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$bitacora["bitFecha"];?></td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$bitacora["bitDescripcion"];?>%</td><?php
						  ?><td class="p-t-0 p-b-0 text-center"><?=$bitacora["bitHora"];?></td><?php
						}
					  ?></tr><?php 
					} 
				  ?></tbody>
				</table>
			</div>
		</div><?php 
		if ($esJefe) { 
	  ?><div class="text-right m-t-15 m-b-0">
	        <button id="btn-agregar_bitacora" type="button" class="btn btn-primary" onclick="system.project_details.bitacoras.alAgregar()">Agregar Bitacora <span class="fa fa-plus"></span></button>
		    <button id="btn-guardar_bitacoras" class="btn btn-complete" onclick="system.project_details.bitacoras.alGuardar(event)">Guardar bitacoras <span class="fa fa-check"></span></button>
	    </div><?php
		}
  ?></div>
</div>
<script type="text/javascript">
	function newBitacoraRow(){
		return (
			'<td class="no-padding"><input class="form-control ipt-bitacora_fch b-rad-0 no-border text-center full-width btn" type="date"/></td>'+
			'<td class="no-padding"><input class="form-control ipt-bitacora_desc b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
			'<td class="no-padding"><input class="form-control ipt-bitacora_hora b-rad-0 no-border" type="text" placeholder="..." required/></td>'+
			
			
			'<td class="no-border no-padding full-height"><div class="no-padding full-width full-height"><button class="btn btn-danger btn-remove_bitacora b-rad-0 no-border full-height full-width" type="button" onclick="system.project_details.bitacoras.alQuitar(event)"><i class="fa fa-remove" ></i></button></td>'
		);
	}
</script>
<script src="js/detalle_proyecto_bitacoras.js" type="text/javascript"></script>
<?php
}