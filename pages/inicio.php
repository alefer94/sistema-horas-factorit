<?php
require_once __DIR__ . '/../common/InterfazPDO.php';
require_once __DIR__ . '/../common/PDOProyectos.php';
require_once __DIR__ . '/../common/PDOSkillUsuarios.php';
require_once __DIR__ . '/../snippets/tr_resumen_periodos.php';


?>



<div id="content" class="content auto-overflow padding-10">
	<div class="container container-fixed-lg" >

		<div class="row">
			<div class="col-md-12">
				<small class="font-montserrat p-l-5 hidden-xs">Bienvenido/a:</small>
				<?php echo utf8_decode($GLOBALS['USER_NAME']);  ?>
				<br><br>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default" style="border-radius: 10px">
					<div class="panel-body">
						<h4 class="m-t-0">Proyectos</h4>
						<div class="auto-y-overflow no-x-overflow p-r-10 m-t-10 m-b-10">
							<table class="table table-condensed table-striped full-width no-margin">
								<thead class="bg-info-light">
									<tr>
										<th style="width: 10rem; max-width: 15rem" class="text-white">Nombre Proyecto</th>
										<th style="width: 5rem; min-width: 5rem" class="text-center text-white">Horas</th>
									</tr>
								</thead>
								<tbody><?php
										foreach (PDOProyectos::select_proy_from_user(new InterfazPDO(), $GLOBALS["USER_ID"]) as $proyecto) { ?>
										<tr>
											<td><?= $proyecto["prjNombre"]; ?></td>
											<td class="text-center"><?= $proyecto["hrsUsuarioProyecto"]; ?></td>
										</tr><?php
											} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default" style="border-radius: 10px">
					<div class="panel-heading">
						<h3 class="panel-title">Resumen de Horas</h3>
						<p class="hint-text">Esta tabla mostrará el total de horas que ud. ha ingresado para cada uno de los meses indicados.</p>
					</div>
					<div class="panel-body">
						<table class="table table-striped">
							<thead class="bg-warning-light">
								<tr>
									<td style="width: 4rem !important; min-width: 6rem !important" class="text-center">Mes</td>
									<td style="width: 4rem !important; min-width: 6rem !important" class="text-center">Horas</td>
									<td style="width: 4rem !important; min-width: 6rem !important" class="text-center">Total</td>
								</tr>
							</thead>
							<tbody><?php tr_resumen_periodos(new InterfazPDO(), $GLOBALS["USER_ID"]); ?></tbody>
						</table>
					</div>
				</div>
			</div>

			

		</div>
		<div class="row">


			<div class="col-md-8">
				<h2 class="font-montserrat p-l-5 hidden-xs">¿Qué se ve en cada sección?</h2>
				<ul class="no-margin no-padding hidden-xs">
					<?php
					foreach ($GLOBALS["SITE_LINKS"] as $item) {
						if (is_array($item) && $item["descripcion"] != "" && $GLOBALS["USER_PRIVILEGE"] >= $item["nvl_restriccion"]) {
					?>
							<li class="list-group-item row no-margin padding-10" style="border-radius: 10px">
								<div class="col-xs-2 text-center v-align-middle b-r b-grey">
									<span class="<?= $item["ico_clases"]; ?>" style="font-size: 1.75em !important"></span><br />
									<b class="font-montserrat"><?= $item["titulo"]; ?></b>
								</div>
								<div class="col-xs-10"><?= $item["descripcion"]; ?></div>
							</li>
					<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default" style="border-radius: 10px" >
					<div class="panel-body">
						<div class="panel-heading">
							<h3 class="panel-title">Skill</h3>
							<p class="hint-text">Esta tabla mostrará todas las skill.</p>
						</div>
						<div class="auto-y-overflow no-x-overflow p-r-10 m-t-10 m-b-10" style="max-height: 20rem;border-radius:20px">
							<table class="table table-condensed table-striped full-width no-margin">
								<thead class="bg-warning-light">
									<tr>
										<th style="width: 4rem !important; min-width: 8rem !important" class="text-center">Nombre</th>
										<th style="width: 4rem !important; min-width: 8rem !important" class="text-center">Nivel</th>

									</tr>
								</thead>
								<tbody><?php
										foreach (PDOSkillUsuarios::select(new InterfazPDO(), $GLOBALS["USER_ID"]) as $skillUsuarios) { ?>
										<tr>
											<td><?= $skillUsuarios["sklNombre"]; ?></td>
											<td class="text-center"><?= $skillUsuarios["lskNombre"]; ?></td>
										</tr><?php
											} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div id="footer" class="full-width text-center">
	<div id="footer-actions-container" class="b-t b-grey full-width" style="height: 1.5rem !important">
	</div>
	<p class="small no-margin full-width" style="height: 2rem !important; margin-top: 0.5rem !important">
		<b>Copyright <i class="fa fa-copyright"></i> 2017-2021 </b>
		<span class="font-montserrat">FactorIT Ingeniería</span>.
		Todos los derechos reservados.
	</p>
</div>