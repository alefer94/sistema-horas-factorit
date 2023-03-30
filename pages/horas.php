<?php 
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOProyectos.php';
require_once __DIR__.'/../snippets/tr_horas.php';
require_once __DIR__.'/../snippets/options_periodos.php';

$pdo = new InterfazPDO();
$periodo_actual = date("Y-m")."-01";
$periodo_humano = ucfirst(strftime("%b-%Y", (new DateTime($periodo_actual))->getTimestamp()));

?>
<div id="content" class="content no-padding no-margin">
	<div class="flex-container full-width full-height no-overflow">
		<div class="no-border no-padding no-margin no-overflow full-height" style="width: 45rem; max-width: 60vw">
			<div class="flex-container thead-warning-light" style="height: 4rem !important">
				<div style="width: 10rem; min-width: 6rem" class="flex-container bg-warning-light full-height"><span class="m-auto">Código</span></div>
				<div style="width: 15rem; min-width: 8rem" class="flex-container bg-warning-light full-height"><span class="m-auto">Proyecto</span></div>
				<div style="width: 11rem; min-width: 8rem" class="flex-container bg-warning-light full-height"><span class="m-auto">Etapa</span></div>
				<div style="width: 8rem; min-width: 8rem" class="flex-container bg-warning-light full-height">
					<div class="m-auto text-center">
						Periodo
						<span class="btn-filtrar m-t-auto inline" role="button" title="Filtrar con esta columna" data-toggle="tooltip" data-placement="bottom" onclick="system.hours.alFiltrar(event, 'periodo')">(<?=$periodo_humano;?>)</span><span class="fa fa-filter"></span>
					</div>
				</div>
				<div style="width: 6rem; max-width: 6rem" class="flex-container bg-warning-light full-height"><span class="m-auto">Acciones</span></div>
			</div>
			<div id="scroll_columnas" class="scroll-sibling no-padding" style="height: calc(100% - 4rem); width: calc(100% + 1rem); overflow-x: scroll">
				<table id="tbl-columnas" class="table table-condensed no-margin" style="max-width: 39rem">
					<tbody><?php 
						tr_horas_columnas($pdo, 50, 1, ["usrID" => intval($GLOBALS["USER_ID"]), "hrMes" => $periodo_actual ]); ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="no-border no-padding no-margin full-height" style="min-width: 40vw; width: calc(100% - 45rem)" >
			<div class="no-padding no-margin" style="height: 4rem !important">
				<div class="flex-container thead-warning-light" id="scroll_head_horas" style="height: 5rem !important; overflow-x: scroll">
					<?php for ($i = 1; $i <= 31; $i++) { ?>
						<div style="width: 2.5rem;  min-width: 2.5rem" class="flex-container bg-warning-light full-height no-padding"><span class="m-auto"><?=$i; ?></span></div>
					<?php } ?>
					<div class="bg-warning-light full-height" style="width: 1rem; padding: 0.5rem"></div>
				</div>
			</div>
			<div id="scroll_horas" class="scroll-sibling full-width no-padding" style="height: calc(100% - 4rem); overflow: scroll">
				<table id="tbl-horas" class="table table-condensed no-margin">
					<tbody><?php 
						tr_horas($pdo, 50, 1, ["usrID" => intval($GLOBALS["USER_ID"]), "hrMes" => $periodo_actual ]); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width hidden-sm hidden-xs" style="height: 2.5rem !important">
		<div class="row no-padding no-margin full-width full-height">
			<div class="bg-primary no-padding no-margin col-md-4">
				<select id="slt-proyecto" class="full-width" placeholder="Proyecto....." required><option selected></option>
				<?php foreach (PDOProyectos::select_proy_from_user($pdo, $GLOBALS["USER_ID"]) as $proyecto) { ?>
					<option value="<?=$proyecto["prjID"];?>"><?=$proyecto["prjCodigo"];?>/<?=$proyecto["prjNombre"];?></option><?php
				} ?>
				</select>
			</div><?php
		?><div class="bg-primary no-padding no-margin col-md-3">
			<select id="slt-etapa" class="full-width" placeholder="Etapa..." required><option selected></option><?php 
				foreach (PDOProyectos::select_etapas($pdo) as $etapa){ ?>
					<option value="<?=$etapa["etapaID"];?>"><?=$etapa["etapaNombre"];?></option><?php
				} 
			?></select>
		</div><?php
		?><div class="bg-primary no-padding no-margin col-md-3">
			<select id="slt-periodo" class="full-width" placeholder="Periodo..." required><option selected></option><?=options_periodos(); ?></select>
		</div><?php
		?><div class="no-padding no-margin col-md-2 full-height">
			<button id="btn-agregar" class="btn btn-primary btn-animated from-top fa fa-plus b-rad-0 full-width" type="button" onclick="system.hours.alAgregar()"><span>Agregar Registro</span></button>
		</div>
		</div>
	</div>
	<p class="text-center small no-margin full-width" style="height: 2rem !important; margin-top: 0.5rem !important">
		<b>Copyright <i class="fa fa-copyright"></i> 2017-2021</b> 
		<span class="font-montserrat">FactorIT Ingeniería</span>.
		Todos los derechos reservados.
	</p>
</div>
<script type="text/javascript">
	function newRow(){
		

		
		var proyecto = $("#slt-proyecto option:selected");
		var etapa = $("#slt-etapa option:selected");
		var periodo = $("#slt-periodo option:selected");

		let [codigoProyecto, nombreProyecto] = proyecto.text().split("/");
		
		return [
			'<td class="no-padding" style="display:none;">'+
			'<input class="slt-usr full-width" value="<?php echo $GLOBALS["USER_ID"];?>"/>' +
			'</td>' +
			'<td style="width: 8rem; min-width: 10rem" class="b-r b-grey p-t-0 p-b-0 " data-sid="'+ codigoProyecto +'">'+ codigoProyecto +'</td>' +
			'<td style="width: 15rem; min-width: 20rem" class="b-r b-grey p-t-0 p-b-0 " data-sid="'+ proyecto.val() +'">'+ nombreProyecto +'</td>' +
			'<td style="width: 8rem; min-width: 11rem" class="b-r b-grey p-t-0 p-b-0 " data-sid="'+ etapa.val() +'">'+ etapa.text() +'</td>' +
			'<td style="width: 8rem; min-width: 8rem" class="p-t-0 p-b-0 text-center" data-sid="'+ periodo.val() +'">'+ periodo.text() +'</td>' +
			'<td style="width: 6rem; min-width: 6rem" class="no-padding"><div class="flex-container">' + 
				'<button class="btn btn-complete btn-save b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.hours.alGuardar(event)"><i class="fa fa-save"></i></button>' +
				'<button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.hours.alBorrar(event)"><i class="fa fa-remove"></i></button>' +
			'</div></td>'
			,
		<?php for ($i = 1; $i <= 31; $i++) { ?>
			'<td style="width: 2.5rem;  min-width: 2.5rem" class="no-padding b-l b-r b-grey">' +
				'<input class="form-control text-center full-width no-margin no-border b-rad-0 ipt-dia ipt-dia<?=$i;?>" placeholder="0" maxlength="1" max="8" min="0" value="" disabled />' +
			'</td>' <?=($i<31)? "+" : "";?>
		<?php } ?>
		
		];

		
	}
</script>