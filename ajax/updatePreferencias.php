<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updatePreferencias.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar preferencias del solicitante. 
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_success = false;
$callback_response = "";

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID === -1){
	$callback_response = "Su sesión no pudo ser validada.<br/>Por favor, cierre su sesión o recargue la página para ingresar nuevamente.";
}
else {
	require_once __DIR__.'/../common/repairPOST.php';
	$_POST = repairPOST($_POST);
	if (isset($_POST["usrTemaSitio"] )){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOSesiones.php';
		require_once __DIR__.'/../common/PDOUsuarios.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		$time = date(DateTime::ISO8601);
		session_start();
		$sesion = (isset($_SESSION["hash"]))? $_SESSION["hash"] : null;
		session_write_close();
		
		try {
			$pdo = new InterfazPDO();
			$usrID = PDOSesiones::validate_user_from_session_hash($pdo, $sesion, $time);
			
			$filas_afectadas = PDOUsuarios::update(
				$pdo, 
			  [ "usrTemaSitio" => $_POST["usrTemaSitio"] ],
			  [ "usrID" => $usrID ]
			);
			$callback_success = true;
			
			if ($filas_afectadas > 0) {
				$callback_response = "Preferencias actualizadas.";
			}
			else {
				$callback_response = "La operación fue exitosa, aunque las preferencias no se actualizaron.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibieron datos de preferencias a actualizar.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);