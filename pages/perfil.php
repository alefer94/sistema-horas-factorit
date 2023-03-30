<?php 
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOUsuarios.php';
require_once __DIR__.'/../common/PDOProyectos.php';
require_once __DIR__.'/../common/PDOSkillUsuarios.php';


?>
<div id="content" class="content auto-overflow padding-10">
	<div class="container container-fixed-lg">
		<div class="row	m-t-auto m-b-auto">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4 class="m-t-0">Mis Preferencias</h4>
						<form id="form_preferencias" method="post" onsubmit="system.preferences.alCambiarPreferencias(event)">
							<div class="form-group">
								<label for="slt-theme">Tema</label>
								<select id="slt-theme" class="full-width" type="password" name="temaSitio" required>
									<?php foreach ($GLOBALS["SITE_THEMES"] as $i => $tema) { ?>
										<option value="<?=$i; ?>" <?=($i===$GLOBALS["USER_SITE_THEME"]?"selected":"");?>><?=$tema; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label for="txt_password_new">Clave de Usuario</label>
								<div class="input-group">
									<input id="txt_password_new" class="form-control" type="password" name="claveNueva" />
									<div id="btn-cambiar_vista" class="input-group-addon cursor warning" data-toggle="tooltip" data-placement="right" title="Mostrar/Ocultar" onclick="system.preferences.alCambiarVistaClave()"><span class="fa fa-eye"></span></div>
								</div>
							</div>
							<div class="form-group m-t-30">
								<label for="txt_password_new">Clave actual</label>
								<input id="txt_password_old" class="form-control" type="password" name="clave" placeholder="Confirmar clave actual..." required />
							</div>
							<div class="text-center m-t-20">
								<button type="submit" id="btn-guardar_preferencias" class="btn btn-complete">Aceptar <span class="fa fa-check"></span></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4 class="m-t-0">Mis Proyectos</h4>
						<div class="auto-y-overflow no-x-overflow p-r-10 m-t-10 m-b-10" style="max-height: 20rem">
							<table class="table table-condensed table-striped full-width no-margin">
								<thead class="bg-info-light">
									<tr>
										<th style="width: 10rem; max-width: 15rem" class="text-white">Proyecto</th>
										<th style="width: 5rem; min-width: 5rem" class="text-center text-white">Horas</th>
									</tr>
								</thead>
								<tbody><?php 
								foreach (PDOProyectos::select_from_user(new InterfazPDO(), $GLOBALS["USER_ID"]) as $proyecto) { ?>
									<tr>
										<td><?=$proyecto["prjNombre"];?></td>
										<td class="text-center"><?=$proyecto["hrsUsuarioProyecto"];?></td>
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
	<div id="footer-actions-container" class="b-t b-grey full-width" style="height: 2.5rem !important"></div>
	<p class="small no-margin full-width" style="height: 32px !important">
		<b>Copyright <i class="fa fa-copyright"></i> 2021 </b> 
		<span class="font-montserrat">FactorIT Ingeniería.</span>
		Todos los derechos reservados.
	</p>
</div>