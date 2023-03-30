<?php
require_once __DIR__.'/../common/validateAjaxRequest.php';

$ajaxUsrID = validateAjaxRequest();

if ($ajaxUsrID !== -1){
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOUsuarios.php';
	require_once __DIR__.'/../common/PDOProyectos.php';
	require_once __DIR__.'/../snippets/options_estados_usuario.php';
	require_once __DIR__.'/../snippets/options_tipos_usuario.php';
	require_once __DIR__.'/../snippets/options_empresas.php';
	require_once __DIR__.'/../snippets/options_areas.php';
	require_once __DIR__.'/../snippets/options_estados_facturacion_usua.php';
	require_once __DIR__.'/../snippets/options_vicepresidencias.php';
	require_once __DIR__.'/../snippets/options_gerencias.php';
	require_once __DIR__.'/../snippets/options_subgerencias.php';
	require_once __DIR__.'/../snippets/options_cargo_generico.php';
	require_once __DIR__.'/../snippets/options_tipo_contratos.php';
	
	
	$pdo = new InterfazPDO();
	$usrID = intval($_GET["usrID"]);
	$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID] )->fetch();
	unset($pdo);
	
	$path_img = __DIR__.'/../img/logo.png';
	
?>
<div id="div-detalles_usuario" class="container-fluid full-height padding-20" data-sid="<?=$usrID; ?>">
	<div class="row full-height">
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4 class="m-t-0">Detalles del Usuario</h4>
					<form action="" method="post" onsubmit="system.user_details.alGuardar(event)"> 
						<div class="auto-y-overflow no-x-overflow p-r-10 m-t-10 m-b-10">
							<div class="row">
								<div class="col-sm-4 col-offset-8">
								<img src="img/img_perfil/sin_foto.png" width="100%">
								</div>
								<div class="form-group col-sm-8">
									<label for="ipt-nombre">Nombre</label>
									<input name="usrNombre" value="<?=$usuario["usrNombre"];?>" id="ipt-nombre" type="text" class="form-control" required />
								</div>
								<div class="form-group col-sm-4">
									<label for="ipt-rut">Rut</label>
									<div class="flex-container">
									<input name="usrRut" value="<?=$usuario["usrRut"];?>" id="ipt-rut" type="text" class="form-control text-center" style="width:calc(100%-3rem)" required />
									<input name="usrDv" value="<?=$usuario["usrDv"];?>" id="ipt-dv" type="text" maxlength="1" class="form-control text-center" style="width:3rem" />
									</div>
								</div>
								<div class="form-group col-sm-8">
									<label for="ipt-mail">E-mail</label>
									<input name="usrMail" value="<?=$usuario["usrMail"];?>" id="ipt-mail" type="email" class="form-control" required />
								</div>
							</div>
							<div class="row">							
								<div class="form-group col-sm-4">
									<label for="slt-tipo">Tipo de Usuario</label>
									<select name="tpUsrID" id="slt-tipo" class="full-width" required><?php options_tipos_usuario(new InterfazPDO(), $usuario["tpUsrID"]); ?></select>
								</div>
							</div>
							<div class="row">
							<div class="form-group col-sm-4">
									<label for="ipt-nombre">Cargo Contractual</label>
									<input name="usrNombre" value="<?=$usuario["usrCargoContractual"];?>" id="ipt-cargo_contractual" type="text" class="form-control" required />
								</div>
							</div>
							<div class="row">							
								<div class="form-group col-sm-4">
									<label for="slt-tipo">Cargo Generico</label>
									<select name="cgcID" id="slt-cargo_generico" class="full-width" required><?php options_cargo_generico(new InterfazPDO(), $usuario["cgcID"]); ?></select>
								</div>
							</div>
							<div class="row">							
								<div class="form-group col-sm-4">
									<label for="slt-tipo">Tipo Contrato</label>
									<select name="tctID" id="slt-tipo_contratos" class="full-width" required><?php options_tipo_contratos(new InterfazPDO(), $usuario["tctID"]); ?></select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="slt-estado-facturacion">Estado Facturación</label>
									<select name="usrFlagFacturable" id="slt-estado-facturacion" class="full-width" required><?php options_estados_facturacion_usua(new InterfazPDO(), $usuario["usrFlagFacturable"]); ?></select>
								</div>
								<div class="form-group col-sm-6">
									<label for="slt-estado">Estado Usuario</label>
									<select name="usrEstID" id="slt-estado" class="full-width" required><?php options_estados_usuario(new InterfazPDO(), $usuario["usrEstID"]); ?></select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="slt-empresa">Empresa</label>
									<select name="usrEmpresa" id="slt-empresa" class="full-width" required><?php options_empresas(new InterfazPDO(), $usuario["usrEmpresa"]); ?></select>
								</div>
								<div class="form-group col-sm-6">
									<label for="slt-area">Area</label>
									<select name="usrArea" id="slt-area" class="full-width" required><?php options_areas(new InterfazPDO(), $usuario["usrArea"]); ?></select>
								</div>
							</div>
							<div class="row">							
							<div class="form-group col-sm-4">
									<label for="slt-vicepresidencias">Vicepresidencias</label>
									<select name="vprID" id="slt-vicepresidencias" class="full-width" required><?php options_vicepresidencias(new InterfazPDO(), $usuario["vprID"]); ?></select>
								</div>
							</div>
							<div class="row">	
														
								<div class="form-group col-sm-4">
									<label for="slt-gerencias">Gerencia</label>
									<select name="gerID" id="slt-gerencias" class="full-width" required><?php options_gerencias(new InterfazPDO(), $usuario["gerID"]); ?></select>
								</div>						
								<div class="form-group col-sm-4">
									<label for="slt-subgerencias">Sub Gerencia</label>
									<select name="sgrID" id="slt-subgerencias" class="full-width" required><?php options_subgerencias(new InterfazPDO(), $usuario["sgrID"]); ?></select>
								</div>
							</div>
							<div class="row no-padding">
								<div class="col-md-6 form-group">
									<label for="ipt-fecha_ingreso">Ingresado en</label>
									<input name="usrFechaIngreso" value="<?=$usuario["usrFechaIngreso"];?>" id="ipt-fecha_ingreso" type="date" class="form-control btn" required />
								</div>
								<div class="col-md-6 form-group">
									<label for="ipt-fecha_desvinculacion">Desvinculado en</label>
									<input name="usrFechaDesvinculacion" value="<?=$usuario["usrFechaDesvinculacion"];?>" id="ipt-fecha_desvinculacion" type="date" class="form-control btn"  />
								</div>
							</div>
						</div>
						<div class="row text-center">
							<input type="hidden" name="usrID" value="<?=$usrID; ?>"/>
							<div class="col-md-6">
								<button id="btn-guardar_cambios" type="submit" class="btn btn-complete" onclick="system.user_details.alGuardar()">Guardar Cambios <span class="fa fa-check"></span></button>
							</div>
							<div class="col-md-6">
								<button id="btn-cambiar_clave" type="button" class="btn btn-info" onclick="system.user_details.alCambiarClave()">Cambiar Clave <span class="fa fa-key"></span></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4 class="m-t-0">Proyectos</h4>
					<div class="auto-y-overflow no-x-overflow p-r-10 m-t-10 m-b-10" style="max-height: 20rem">
						<table class="table table-condensed table-striped full-width no-margin">
							<thead class="bg-info-light">
								<tr>
									<th style="width: 10rem; max-width: 15rem" class="text-white">Proyecto</th>
									<th style="width: 5rem; min-width: 5rem" class="text-center text-white">Horas</th>
								</tr>
							</thead>
							<tbody><?php 
							foreach (PDOProyectos::select_from_user(new InterfazPDO(), $usrID) as $proyecto) { ?>
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
	<script type="text/javascript" src="js/detalles_usuario.js"></script>
</div>
<?php 
}

