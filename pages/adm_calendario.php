<?php 
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../snippets/options_meses.php';
require_once __DIR__.'/../snippets/options_empresas.php';
require_once __DIR__.'/../snippets/tr_feriados.php';

$año_actual = date("Y");
?>

<div id="content" class="content bg-white no-margin no-padding">
	<div class="container-fluid no-padding no-margin full-height no-overflow">
		<div id="div_tabla_anio" class="wrapper no-margin no-padding auto-overflow" style="height: calc(100% - 4.5rem) !important">
			<table id="tbl-mantenedor" class="table table-condensed table-bordered no-padding no-margin full-width">
				<thead class="bg-warning-light">
					<tr>
						<th style="width: 20rem; min-width: 20rem" class="no-border text-center">Empresa</th>
						<th style="width: 20rem; min-width: 20rem" class="no-border text-center">Nombre</th>
						<th style="width: 10rem; min-width: 10rem" class="no-border text-center">Año</th>
						<th style="width: 10rem; min-width: 10rem" class="no-border text-center">Mes</th>
						<th style="width: 10rem; min-width: 10rem" class="no-border text-center">Día</th>
						<?php if ($GLOBALS["esUsrAdmin"]) { ?><th style="width: 20rem; min-width: 20rem;" class="no-border text-center">Acciones</th><?php } ?>
					</tr>
				</thead>
				<tbody><?php tr_feriados2(new InterfazPDO(), $año_actual, $GLOBALS["esUsrAdmin"]);?></tbody>
			</table>
		</div>
	</div>
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width hidden-sm hidden-xs" style="height: 2.5rem !important">
	<?php if ($GLOBALS["esUsrAdmin"]) { ?>
		<button id="btn-agregar"   class="btn btn-primary btn-animated from-top fa fa-plus no-border b-rad-0 no-margin half-width full-height" type="button" onclick="system.alAgregar()"><span>Agregar Fecha</span></button><?php 
	  ?>
	</div>
	<?php } ?>
	<p class="small no-margin full-width" style="height: 2rem !important; margin-top: 0.5rem !important">
		<b>Copyright <i class="fa fa-copyright"></i>  2017-2019 </b> 
		<span class="font-montserrat">FactorIT Ingeniería</span>.
		Todos los derechos reservados.
	</p>
</div>
<script type="text/javascript">	
	function newRow(){			
		return (
			'<td class="no-border no-padding">' +
				'<select class="slt-empresas full-width" placeholder="Empresa..."><option></option><?php options_empresas(new InterfazPDO()); ?></select>' +
			'</td>' +
			'<td class="no-padding"><input type="text" class="ipt-desc form-control b-rad-0 full-width" placeholder="Descripción feriado..." required/></td>'+
			'<td class="no-padding">'+
				'<select class="slt-mes no-margin b-rad-0" style="width: 60% !important" placeholder="Mes..." required>'+
					'<option></option>'+
					'<?php options_meses();?>'+
				'</select>'+
				'<select class="slt-dia no-margin b-rad-0" style="width: 40% !important" placeholder="Día..." required>'+
					'<option></option>'+
					<?php for ($i = 1; $i <= 31; $i++){ ?>
					'<option value="<?=$i;?>"><?=$i;?></option>'+
					<?php } ?>
				'</select>'+
			'</td>'+
			'<td class="no-padding"><button id="btn-eliminar" class="btn btn-danger  btn-remove no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.calendar.alBorrar(event)"> <span class="fa fa-remove"></span></button></td>' +
			'<td class="no-padding"><button id="btn-guardar"  class="btn btn-success btn-remove no-margin b-rad-0 no-border full-height half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardad"  onclick="system.calendar.alGuardar(event)"><span class="fa fa-remove"></span></button></td>'
		);
	}
</script>
<?php unset($año_actual); ?>