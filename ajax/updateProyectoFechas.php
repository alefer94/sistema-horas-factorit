<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoFechas.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar las fechas de inicio y término de un proyecto.
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
	
	if (isset($_POST["prjID"]) && is_numeric($_POST["prjID"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectos.php';
		require_once __DIR__.'/../common/jTraceEx.php';
				
		$prjFechaInicio = !empty($_POST["prjFechaInicio"]) && is_array(date_parse($_POST["prjFechaInicio"]))? $_POST["prjFechaInicio"] : null;
		$prjFechaTerminoDes = !empty($_POST["prjFechaTerminoDes"]) && is_array(date_parse($_POST["prjFechaTerminoDes"]))? $_POST["prjFechaTerminoDes"] : null;
		$prjFechaTerminoEst = !empty($_POST["prjFechaTerminoEst"]) && is_array(date_parse($_POST["prjFechaTerminoEst"]))? $_POST["prjFechaTerminoEst"] : null;
		$prjFechaTermino = !empty($_POST["prjFechaTermino"]) && is_array(date_parse($_POST["prjFechaTermino"]))? $_POST["prjFechaTermino"] : null;
		
		try { 
			$pdo = new InterfazPDO();
			$filas_afectadas = PDOProyectos::update(
				$pdo, 
			    [
					"prjFechaInicio" => $prjFechaInicio,
					"prjFechaTerminoDes" => $prjFechaTerminoDes, 
					"prjFechaTerminoEst" => $prjFechaTerminoEst, 
					"prjFechaTermino" => $prjFechaTermino 
				],
			  [ "prjID" => intval($_POST["prjID"]) ]
		    );
			$callback_success = true;
			
			if ($filas_afectadas > 0){
				$callback_response = "Las fechas clave del proyecto fueron actualizadas.<br/>";
			}
			else {
				$callback_response = "La operación fue exitosa, aunque las fechas no fueron actualizadas.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar las fechas clave del proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);