<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoPlaneacion.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar la planeacion de un proyecto. 
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_success = false;
$callback_response = "";

$ajaxUsrID = validateAjaxRequest(2);
if ($ajaxUsrID === -1){
	$callback_response = "Su sesión no pudo ser validada.<br/>Por favor, cierre su sesión o recargue la página para ingresar nuevamente.";
}
else{
	require_once __DIR__.'/../common/repairPOST.php';
	$_POST = repairPOST($_POST);
	
	if (isset($_POST["prjID"])){
		
		$prjID = intval($_POST["prjID"]);
		if (isset($_POST["planeacion"]) && is_array($_POST["planeacion"]) && sizeof($_POST["planeacion"]) > 0) {
			require_once __DIR__.'/../common/InterfazPDO.php';
			require_once __DIR__.'/../common/PDOProyectos.php';
			require_once __DIR__.'/../common/jTraceEx.php';
			
			try {
				$pdo = new InterfazPDO();
				$horas_planeacion = PDOProyectos::update_planeacion_inicial( //devuelve el total de horas ingresadas
					$pdo, 
					$prjID, 
					$_POST["planeacion"]
				);
				
				if ($horas_planeacion > 0){
					$callback_success = true;
					$callback_response = "La planeación fue asignada con éxito.";
				}
				else {
					$callback_response = "La planeación no pudo ser ingresada.";
				}
			}
			catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
		}
		else {
			$callback_response = "No se recibió la planeación del proyecto.";
		}
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar un proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);