<?php
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../snippets/tr_skill.php';
?>

<div id="content" class="content wrapper no-padding no-margin auto-overflow">
	<table id="tbl-mantenedor" class="table table-condensed table-striped table-bordered no-padding no-margin full-width">
		<thead class="bg-warning-light">
			<tr>
				<th style="width: 40rem !important; min-width: 30rem !important" class="no-border text-center">Nombre</th>
				
				<?php if ($GLOBALS["esUsrJefe"]){ ?><th style="width: 121px !important; max-width: 121px !important" class="no-border text-center">Acciones</th><?php }?>
			</tr>
		</thead>
		<tbody><?php tr_skill(new InterfazPDO()); ?></tbody>
	</table>
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width" style="height: 2.5rem !important">
	<?php if ($GLOBALS["esUsrJefe"]) { ?><button id="btn-agregar" class="btn btn-primary btn-animated from-top fa fa-plus no-border b-rad-0 full-width full-height" type="button" onclick="system.alAgregar()"><span>Agregar Skill</span></button><?php } ?>
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
			'<td class="no-padding"><input class="form-control ipt-nombre no-border b-rad-0" type="text" placeholder="..." required/></td>'+
			'<td class="no-border no-padding full-height"><div class="no-padding full-width full-height">'+
				'<button class="btn btn-complete btn-save no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.skill.alGuardar(event)"><span class="fa fa-save"></span></button>'+
				'<button class="btn btn-danger btn-remove no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.skill.alBorrar(event)"><span class="fa fa-remove"></span></button>'+
			'</div></td>'
		); 
	}
</script>
<script type="text/javascript" src="js/adm_skill.js"></script>