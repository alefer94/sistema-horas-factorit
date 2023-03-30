<?php

if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_success = false;
$callback_response = "";

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID === -1){
	$callback_response = "Su sesión no pudo ser validada.";
}
else {
	require_once __DIR__.'/../common/repairPOST.php';
	$_POST = repairPOST($_POST);

	if (isset($_POST["prjUsrID"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectoUsuario.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		try {
			$pdo = new InterfazPDO();
			$filas_afectadas = PDOProyectoUsuario::remove( $pdo, intval($_POST["prjUsrID"]) );
			unset($pdo);
			if ($filas_afectadas != 0) {
				$callback_success = true;
				$callback_response = "colaborador eliminado.";
			}
			else {
				$callback_response = "La asignacion no pudo ser eliminada.<br/>Por favor, recargue la página e inténtelo nuevamente.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else{
		$callback_response = "No se recibió el parámetro 'prjUsrID' para eliminar un registro de horas.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);