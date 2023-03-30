<?php
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../snippets/tr_usuarios.php';
require_once __DIR__.'/../snippets/options_tipos_usuario.php';
require_once __DIR__.'/../snippets/options_estados_usuario.php';
require_once __DIR__.'/../snippets/options_empresas.php';
require_once __DIR__.'/../snippets/options_areas.php';
require_once __DIR__.'/../snippets/options_estados_facturacion_usua.php';
require_once __DIR__.'/../snippets/options_vicepresidencias.php';
require_once __DIR__.'/../snippets/options_gerencias.php';
require_once __DIR__.'/../snippets/options_subgerencias.php';
require_once __DIR__.'/../snippets/options_cargo_generico.php';
require_once __DIR__.'/../snippets/options_tipo_contratos.php';



?>

<div id="content" class="content wrapper no-padding no-margin auto-overflow">
	<table id="tbl-mantenedor" class="table table-condensed table-striped no-padding no-margin full-width">
		<thead class="bg-warning-light">
			<tr>
				<th style="width: 18rem; min-width: 18rem">Nombre Completo</th>
				<th style="width: 18rem; min-width: 18rem" class="text-center">E-Mail</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Tipo</th>
				<th style="width: 12rem; min-width: 12rem" class="text-center">Cargo Contrac.</th>
				<th style="width: 12rem; min-width: 12rem" class="text-center">Cargo Generico</th>
				<th style="width: 12rem; min-width: 12rem" class="text-center">Nivel HAY</th>
				<th style="width: 12rem; min-width: 12rem" class="text-center">Tipo Contratos</th>
				<th style="width: 8rem; min-width: 8rem" class="text-center">Rut</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Fecha Ingreso</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Fecha Desv.</th>
				<th style="width: 8rem; min-width: 8rem" class="text-center">Empresa</th>
				<!--<th style="width: 8rem; min-width: 8rem" class="text-center">Area</th>-->
				<th style="width: 10rem; min-width: 10rem" class="text-center">Facturación</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Estado<br/>
					<span id="btn-filtrar_estados" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.users.alFiltrar(event, 'estados')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 12rem; min-width: 12rem" class="text-center">Vicepresidencias<br/>
					<span id="btn-filtrar_vicepresidencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.users.alFiltrar(event, 'vicepresidencias')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Gerencias<br/>
					<span id="btn-filtrar_gerencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.users.alFiltrar(event, 'gerencias')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 10rem; min-width: 10rem" class="text-center">Sub Gerencias<br/>
					<span id="btn-filtrar_subgerencias" class="btn-filtrar no-margin no-padding" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.users.alFiltrar(event, 'subgerencias')">(Todos)<span class="fa fa-filter"></span></span>
				</th>
				<th style="width: 9rem; max-width: 9rem" class="text-center">Acciones</th>
				
			</tr>
		</thead>
		<tbody>
		<?php tr_usuarios(new InterfazPDO());?>
		</tbody>
	</table>
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width hidden-sm hidden-xs" style="height: 2.5rem !important"><?php 
	if ($GLOBALS["esUsrAdmin"]) { ?>
		<button id="btn-agregar" class="btn btn-primary btn-animated from-top fa fa-plus no-border b-rad-0 full-width full-height" type="button" onclick="system.alAgregar()"><span>Agregar Usuario</span></button><?php 
	} ?>
		<button id="btn-salir_detalles" type="button" class="btn btn-info text-white btn-animated from-top fa fa-arrow-left b-rad-0 m-b-10 full-width" style="height:2.5rem !important; display: none" type="button" onclick="system.users.alVolverDetalles(event)"><span>Volver</span></button>
	</div>
	<p class="small no-margin full-width" style="height: 2rem !important; margin-top: 0.5rem !important">
		<b>Copyright <i class="fa fa-copyright"></i> 2017-2021</b> 
		<span class="font-montserrat">FactorIT Ingeniería</span>.
		Todos los derechos reservados.
	</p>
</div>
<script type="text/javascript">
	function newRow(){
		return (
			'<td class="no-padding"><input class="form-control ipt-nombre" type="text" placeholder="Alejandro Rodrigo Jiménez de La Fuente..." style="border-radius: 0" required/></td>'+
			'<td class="no-padding"><input class="form-control ipt-mail" type="text" placeholder="ejemplo@factorit.com" style="border-radius: 0" required/></td>' +
			'<td class="no-padding">'+
				'<select class="slt-tipo full-width"><option></option><?php options_tipos_usuario(new InterfazPDO());?></select>' +
			'</td>'+
			'<td class="no-padding"><input class="form-control ipt-cargo_contractual" type="text" placeholder="Cargo Contractual..." style="border-radius: 0" required/></td>'+
			'<td class="no-padding">'+
				'<select class="slt-cargo_generico full-width"><option></option><?php options_cargo_generico(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding"><input class="form-control ipt-nivelHAY b-rad-0 no-border text-center full-width" type="number" min="0"   placeholder="0"/></td>'+
			'<td class="no-padding">'+
				'<select class="slt-tipo_contratos full-width"><option></option><?php options_tipo_contratos(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding form-inline"><input class="form-control text-center ipt-rut" type="text" placeholder="12345678..." style="width:70%" maxlength="10" style="border-radius: 0" required/><input class="form-control text-center ipt-dv" type="text" placeholder="k..." style="width:30%; border-radius: 0" maxlength="1" required/></td>' +
			'<td class="no-padding"><input class="form-control ipt-fecha-ingreso" type="date" placeholder="dd/mm/yyyy" style="border-radius: 0" required/></td>'+
			'<td class="no-padding"><input class="form-control ipt-fecha-desvinculacion" type="date" placeholder="dd/mm/yyy" style="border-radius: 0" required/></td>' +
			'<td class="no-padding">'+
				'<select class="slt-empresa full-width"><option></option><?php options_empresas(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-estado-facturacion full-width"><option></option><?php options_estados_facturacion_usua(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-estado full-width"><option></option><?php options_estados_usuario(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-vicepresidencias full-width"><option></option><?php options_vicepresidencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-gerencias full-width"><option></option><?php options_gerencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding">'+
				'<select class="slt-subgerencias full-width"><option></option><?php options_subgerencias(new InterfazPDO());?></select>' +
			'</td>' +
			'<td class="no-padding no-border full-height"><div class="no-padding full-height">' +
				'<button class="btn btn-complete btn-save no-border b-rad-0 half-width full-height" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.users.alGuardar(event)"><span class="fa fa-save"></span></button>'+
				'<button class="btn btn-danger btn-remove no-border b-rad-0 half-width full-height" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.users.alBorrar(event)"><span class="fa fa-remove"></span></button>'+
			'</div></td>'
		);
	}

	function updatedTd(){
		return (
			'<button class="btn btn-primary btn-edit no-border b-rad-0 third-width" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="system.users.alEditar(event)"><i class="fa fa-pencil"></i></button>' +
			'<button class="btn btn-info btn-details third-width" type="button" data-toggle="tooltip" data-placement="top" title="Detalles" onclick="system.users.alVerDetalles(event)"><i class="fa fa-ellipsis-h"></i></button>' +
			'<button class="btn btn-danger btn-remove no-border b-rad-0 third-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.users.alBorrar(event)"><i class="fa fa-remove" ></i></button>'
			
		);
	}
</script>
<script type="text/javascript" src="js/adm_usuarios.js"></script>