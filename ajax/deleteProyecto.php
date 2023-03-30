<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/deleteProyecto.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para eliminar un proyecto. 
*/
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

	if (isset($_POST["prjID"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectos.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		try {
			$pdo = new InterfazPDO();
			$filas_afectadas = PDOProyectos::remove( $pdo, intval($_POST["prjID"]) );
			unset($pdo);
			if ($filas_afectadas != 0) {
				$callback_success = true;
				$callback_response = "Proyecto eliminado.";
			}
			else {
				$callback_response = "El proyecto no pudo ser eliminado.<br/>Por favor, recargue la página e inténtelo nuevamente.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else{
		$callback_response = "No se recibió el parámetro 'hrID' para eliminar un registro de horas.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);