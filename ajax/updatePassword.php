<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updatePassword.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar la contraseña del usuario solicitante.
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
	
	if (isset($_POST["usrClave"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOUsuarios.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		try {
			$pdo = new InterfazPDO();
			$filas_afectadas = PDOUsuarios::update_clave(
				$pdo, 
				$ajaxUsrID, 
				$_POST["usrClave"]
			);
			$callback_success = true;
			
			if ($filas_afectadas > 0) {
				$callback_response = "Contraseña actualizada.";
			}
			else {
				$callback_response = "La operación fue exitosa, aunque ea contraseña no se actualizó.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
		
	}
	else {
		$callback_response = "No se pudo actualizar la contraseña porque no se recibió el parámetro que correspondía a ésta.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);