<?php

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
	
	if (isset($_POST["prjUsrID"]) && is_numeric($_POST["prjUsrID"])){
		if (sizeof($_POST) > 1) {
			require_once __DIR__.'/../common/InterfazPDO.php';
			require_once __DIR__.'/../common/PDOProyectoUsuario.php';
			require_once __DIR__.'/../common/jTraceEx.php';
			
			try {
				$pdo = new InterfazPDO();
				$filas_afectadas = PDOProyectoUsuario::update(
					$pdo, 
					$_POST, 
				  [ "prjUsrID" => intval($_POST["prjUsrID"]) ]
			    );
				$callback_success = true;
				
				if ($filas_afectadas > 0) {
					$callback_response = "La asignacion a sido actualizada.";
				}
				else {
					$callback_response = "La operación fue exitosa, aunque la asignacion no se actualizó.";
				}
			}
			catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
		}
		else {
			$callback_response = "No se recibieron datos para actualizar.";
		}
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjUsrID' para actualizar una asignacion.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);