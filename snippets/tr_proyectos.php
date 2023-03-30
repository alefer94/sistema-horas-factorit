<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_proyectos.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOProyectos.php";




function tr_proyectos($pdo = null, $limit = 100, $page = 1, $filters = []){

	foreach (PDOProyectos::select_proyectos_hitos_horas($pdo, $limit, $page, $filters) as $proyecto) { ?>
		<tr data-sid="<?=$proyecto["prjID"]; ?>">
			<td class="p-t-0 p-b-0"><?=$proyecto["prjCodigo"]; ?></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjNombre"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["cltID"]; ?>"><?=$proyecto["cltNombre"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["usrIDJefe"]; ?>"><?=$proyecto["usrNombreJefe"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["usrIDComercial"]; ?>"><?=$proyecto["usrNombreComercial"]; ?></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjFlagFacturacion"]; ?></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjEstadoProyecto"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["ccoID"]; ?>"><?=$proyecto["ccoNombre"]; ?></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjCodigoPadre"]; ?></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjCostoVenta"]; ?><span> USD</span></td>
			<td class="p-t-0 p-b-0"><?=$proyecto["prjMargenEsperado"]; ?><span>%</span></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["vprID"]; ?>"><?=$proyecto["vprNombre"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["gerID"]; ?>"><?=$proyecto["gerNombre"]; ?></td>
			<td class="p-t-0 p-b-0" data-sid="<?=$proyecto["sgrID"]; ?>"><?=$proyecto["sgrNombre"]; ?></td>
			<td class="no-border no-padding">
				<div class="flex-container full-height">
					<?php
						if ($GLOBALS["esUsrJefe"]) {
							?>
							<button class="btn btn-primary btn-edit no-border b-rad-0 third-width" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="system.projects.alEditar(event, <?=$proyecto['numHitosPagados']?>,<?=$proyecto['hrsProyecto']?>)"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-info btn-details no-border b-rad-0 third-width" type="button" data-toggle="tooltip" data-placement="top" title="Detalles" onclick="system.projects.alVerDetalles(event)"><i class="fa fa-ellipsis-h"></i></button>
							<!-- verificamos si existen hitos facturados, si hay al menos uno en el proyecto no se permite eliminar, se utiliza un operador ternario --> 
							<button class="btn btn-danger btn-remove no-border b-rad-0 third-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.projects.alBorrar(event,<?=$proyecto['numHitosPagados']?>,<?=$proyecto['hrsProyecto']?>)"><i class="fa fa-remove"></i></button>
							<?php
						} else {
							?>
							<button class="btn btn-info btn-details no-border b-rad-0 full-width full-height" type="button" data-toggle="tooltip" data-placement="top" title="Detalles" onclick="system.projects.alVerDetalles(event)"><i class="fa fa-ellipsis-h"></i></button>
							<?php
						}
					?>
				</div>
			</td>

		</tr><?php
	}
	 		        
}