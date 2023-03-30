<?php
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../snippets/tr_proyectos.php';
require_once __DIR__.'/../snippets/options_clientes.php';
require_once __DIR__.'/../snippets/options_usuarios_jefes.php';
require_once __DIR__.'/../snippets/options_estados_facturacion_proy.php';
require_once __DIR__.'/../snippets/options_estados_proyecto.php';
require_once __DIR__.'/../snippets/options_centro_costos.php';
require_once __DIR__.'/../snippets/options_vicepresidencias.php';
require_once __DIR__.'/../snippets/options_gerencias.php';
require_once __DIR__.'/../snippets/options_subgerencias.php';
require_once __DIR__.'/../snippets/options_usuarios_comercial.php';


$anchoTdAcciones = $GLOBALS["esUsrJefe"]? 9 : 6;

?>
<div id="content" class="content bg-white wrapper no-padding no-margin auto-overflow">
	<table id="tbl-mantenedor" class="table table-condensed table-striped table-bordered no-padding no-margin full-width">
		<thead class="bg-warning-light">
			<tr>
				<th style="width: 7rem !important; min-width: 7rem !important;" class="no-border text-center">
					Código<br/>
					<span id="btn-filtrar_codigo" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" onclick="system.projects.alFiltrar(event, 'codigo')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 22rem !important; min-width: 22rem !important;" class="no-border text-center">
					Nombre<br/>
					<span id="btn-filtrar_nombre" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'nombre')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 10rem !important; min-width: 10rem !important;" class="no-border text-center">
					Cliente<br/>
					<span id="btn-filtrar_cliente" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'cliente')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 15rem !important; min-width: 15rem !important;" class="no-border text-center">
					Líder de Proyecto<br/>
					<span id="btn-filtrar_jefe" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'jefe')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 15rem !important; min-width: 15rem !important;" class="no-border text-center">
				    Responsable Comercial<br/>
					<span id="btn-filtrar_comercial" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'comercial')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 16rem !important; min-width: 9rem !important;" class="no-border text-center">
					Facturación<br/>
					<!--<span id="btn-filtrar_facturacion" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'facturacion')">(Todos)</span>-->
				</th>
				<th style="width: 7rem !important; min-width: 7rem !important;" class="no-border text-center">
					Estado<br/>
					<!--<span id="btn-filtrar_estado" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'estado')">(Todos)</span>-->
				</th>
				<th style="width: 12rem !important; min-width: 12rem !important;" class="no-border text-center">
					Centro de Costo<br/>
					<span id="btn-filtrar_centro_costo" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'centro_costo')">(Todo)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 10rem !important; min-width:7rem !important;" class="no-border text-center">
					Codigo Padre<br/>
					<!-- <span id="btn-filtrar_codigoPadre" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'codigoPadre')">(Todos)<span class="fa fa-filter"></span></span> -->
				</th>
				<th style="width: 12rem !important; min-width:7rem !important;" class="no-border text-center">
				    Costo Venta (USD)<br/>
				</th>
				<th style="width: 12rem !important; min-width:7rem !important;" class="no-border text-center">
				    Margen Esperado<br/>
				</th>
				<th style="width: 12rem !important; min-width: 12rem !important;" class="no-border text-center">
					Vicepresidencias<br/>
					<span id="btn-filtrar_vicepresidencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'vicepresidencias')">(Todo)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 12rem !important; min-width: 12rem !important;" class="no-border text-center">
					Gerencias<br/>
					<span id="btn-filtrar_gerencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'gerencias')">(Todo)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 12rem !important; min-width: 12rem !important;" class="no-border text-center">
					Sub Gerencias<br/>
					<span id="btn-filtrar_subgerencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.projects.alFiltrar(event, 'subgerencias')">(Todo)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: <?=$anchoTdAcciones;?>rem !important; max-width: <?=$anchoTdAcciones;?>rem !important;" class="no-border text-center">Acciones</th>
			</tr>
		</thead>
		<tbody><?php tr_proyectos(new InterfazPDO()); ?></tbody>
	</table>     		
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width" style="height: 2.5rem !important"><?php 
	if ($GLOBALS["esUsrJefe"]) { ?>
		<button id="btn-agregar" class="btn btn-primary btn-animated from-top fa fa-plus full-width b-rad-0 m-b-10" style="height:2.5rem !important" type="button" onclick="system.alAgregar()"><span>Agregar Proyecto</span></button><?php 
	} ?>
		<button id="btn-salir_detalles" type="button" class="btn btn-info text-white btn-animated from-top fa fa-arrow-left b-rad-0 m-b-10 full-width" style="height:2.5rem !important; display: none" type="button" onclick="system.projects.alVolverDetalles(event)"><span>Volver</span></button>
	</div>
	<p class="small m-b-0 m-l-0 m-r-0 full-width" style="height: 2rem !important; margin-top: 0.5rem !important">
		<b>Copyright <i class="fa fa-copyright"></i> 2017-2021</b> 
		<span class="font-montserrat">FactorIT Ingeniería.</span>
		Todos los derechos reservados.
	</p>
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
	function newRow(hitos, hrsProyecto){
		return (
			'<td class="no-padding"><input class="form-control ipt-codigo no-border maxlength="7" b-rad-0" type="text" placeholder="..." required/></td>' +
			'<td class="no-padding"><input class="form-control ipt-nombre no-border b-rad-0" type="text" placeholder="..." required/></td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-clientes full-width"><option></option><?php options_clientes(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-jefes full-width full-height no-padding no-margin"><option></option><?php options_usuarios_jefes(new InterfazPDO(), 50, 1, ["tpUsrID" => ["MORE", 1]]);?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-comercial full-width full-height no-padding no-margin"><option></option><?php options_usuarios_comercial(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-estado-facturacion-pry full-width"><option></option><?php options_estados_facturacion_proy(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-estado-pry full-width"><option></option><?php options_estados_proyecto(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-border no-padding">' +
				'<select class="slt-centro-cos full-width"><option></option><?php options_centro_costos(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-padding"><input class="form-control ipt-codigo-padre no-border b-rad-0" type="text" placeholder="..." required/></td>' +
			'<td class="no-padding"><input class="form-control ipt-costo-venta b-rad-0 no-border text-center full-width" type="number" min="0" step="0.01"  placeholder="0"/></td>'+
			'<td class="no-padding"><div class="input-group full-width no-padding"><input class="form-control ipt-margen-esperado b-rad-0 no-border text-center" type="number" placeholder="0" min="0" max="100"/><span class="input-group-addon no-border b-rad-0">%</span></div></td>'+
			'<td class="no-padding">'+
				'<select class="slt-vicepresidencias full-width"><option></option><?php options_vicepresidencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-gerencias full-width"><option></option><?php options_gerencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-subgerencias full-width"><option></option><?php options_subgerencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-border no-padding full-height"><div class="flex-container full-height">' +
				// Se mandan los hitos para que persistan después de la petición ajax, eliminar no eliminara si hay hitos facturados, update se mandan para que la vista siga haciendo la logica
				'<button class="btn btn-complete btn-save no-border b-rad-0 full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.projects.alGuardar(event,'+hitos+','+hrsProyecto+')"><span class="fa fa-save"></span></button>' +
				'<button class="btn btn-danger btn-remove no-border b-rad-0 full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.projects.alBorrar(event,'+hitos+','+hrsProyecto+')"><span class="fa fa-remove"></span></button>' +
			'</div></td>'
		);
	}
	
	function updatedTd(hitos, hrsProyecto){
		return (
			'<button class="btn btn-primary btn-edit no-border b-rad-0 full-height third-width" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="system.projects.alEditar(event,'+hitos+','+hrsProyecto+')"><i class="fa fa-pencil"></i></button>' +
			'<button class="btn btn-info btn-details no-border b-rad-0 full-height third-width" type="button" data-toggle="tooltip" data-placement="top" title="Detalles" onclick="system.projects.alVerDetalles(event)"><i class="fa fa-ellipsis-h"></i></button>' +
			'<button class="btn btn-danger btn-remove no-border b-rad-0 full-height third-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.projects.alBorrar(event,'+hitos+','+hrsProyecto+')"><i class="fa fa-remove" ></i></button>' 
		);
	}
</script>
<?php
unset($anchoTdAcciones);
?>