<?php
require_once __DIR__.'/common/globals.php';
require_once __DIR__.'/common/InterfazPDO.php';
require_once __DIR__.'/common/PDOSesiones.php';
require_once __DIR__.'/common/PDOUsuarios.php';
$error_title = "";
$error_mensaje = "";
session_start();

try {
	$sesion = (isset($_SESSION["hash"]))? $_SESSION["hash"] : null;
	$mail  = (isset($_POST["mail"]))?   $_POST["mail"] : null;
	$clave = (isset($_POST["clave"]))? $_POST["clave"]: null;
    
	$pdo = new InterfazPDO();
    if ($sesion != null) {
		$time = date(DateTime::ISO8601);
		if (PDOSesiones::validate_user_from_session_hash($pdo, $sesion, $time) != null){
			session_write_close();
			header("Location: ".$GLOBALS["SITE_LINKS"][0]);
			exit();
		}
		else {
			$error_title = "Sesión expirada";
			$error_mensaje = "Por favor, ingrese sus credenciales para ingresar al sistema.";
			
			$_SESSION = [];
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}

			session_destroy();
		}
    }
    else if (!is_null($mail) && !is_null($clave)) {
		$usuario_query = PDOUsuarios::select($pdo, 1, 1, ["usrMail" => $mail, "usrClave" => $clave]);
		if ($usuario = $usuario_query->fetch(PDO::FETCH_ASSOC)){
			if (intval($usuario["usrEstID"]) === 1) {
				$sessionHash = PDOSesiones::insert($pdo, intval($usuario["usrID"]));
				if ($sessionHash != null) {
					$_SESSION["hash"] = $sessionHash;
					session_write_close();
					header("Location: ".$GLOBALS["SITE_LINKS"][0]);
					exit();
				}
				else {
					$error_title = "Error";
					$error_mensaje = "El servidor no pudo generar un token de sesión. Por favor, inténtenlo nuevamente.";
				}
			}
			else {
				$error_title = "Error";
				$error_mensaje = "Su cuenta se encuentra inactiva.";
			}
		}
		else {
			$error_title = "Autenticación fallida";
			$error_mensaje = "Por favor, verifique sus credenciales e inténtelo nuevamente.";
		}
    }
?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once __DIR__.'/template/include_head.php'; ?>
	<title>Ingresar - <?=$GLOBALS["SITE_TITLE"];?></title>
</head>
<body class="bg-info">
	<div class="container-fluid flex-container" style="height: 100vh">
		<div class="panel m-auto b-rad-lg">
			<div class="panel-heading text-center bg-white">
				<img src="img/logo_2x.png" width="100%" /><br/>
				<h3 class="font-montserrat bold hint-text text-capitalize"><?=$GLOBALS["SITE_TITLE"];?></h3>
			</div>
			<div class="panel-body bg-complete-dark text-white b-rad-lg">
					<div class="panel-heading separator text-center m-b-10">
						Inicia sesión usando tu correo de FactorIT
					</div>
				<?php if ($error_title != "" && $error_mensaje != "") { ?>
						<div class="alert alert-danger text-center" role="alert">
							<b><?=$error_title; ?></b>
							<p><?=$error_mensaje; ?></p>
						</div>
				<?php } ?>
					<form action="login" method="post" class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								<label for="txtEmail">Correo electrónico</label>
								<input name="mail" class="form-control" type="email" placeholder="ejemplo@factorit.com..." id="txtEmail" required />
							</div>
							<div class="form-group">
								<label for="txtPassword">Contraseña</label>
								<input name="clave" class="form-control" type="password" placeholder="....." id="txtPassword" required />
							</div>
							<div class="form-group">
								<div class="col-sm-7">
									<button class="btn btn-info btn-sm full-width" style="font-size: 14px" type="submit" id="btnIngresar">Ingresar<span class="fa fa-caret-right m-l-10" ></span></button>
								</div>
								<div class="col-sm-5">
									<button class="btn btn-primary btn-sm full-width" type="reset" id="btnLimpiar">Limpiar  <span class="fa fa-trash m-l-5"></span></button>
								</div>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>
</body>

<?php 
}
catch (Exception $exc) {
	echo $exc->getMessage();
    echo $exc->getTraceAsString();
}
finally {
    session_write_close();
}
?>