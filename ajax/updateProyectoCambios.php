<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoCambios.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar los cambios de un proyecto tras su planeación inicial.
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
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectos.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		$prjID = intval($_POST["prjID"]);
		$count_cambios = isset($_POST["cambios"]) && is_array($_POST["cambios"])? count($_POST["cambios"]) : 0;
		
		try {
			$pdo = new InterfazPDO();

			if ($count_cambios > 0){
				$inserciones = PDOProyectos::update_cambios(
					$pdo,
					$prjID,
					$_POST["cambios"]
				);
				$muchas_ins = ($inserciones != 1);
				
				if ($inserciones === $count_cambios){
					$callback_success = true;
					$callback_response = "Se guard".($muchas_ins?"aron":"ó")." ".$inserciones." cambio".($muchas_ins?"s":"")." exitosamente.";
				}
				else {
					$callback_response = "Se guard".($muchas_ins?"aron":"ó")." ".$inserciones." cambio".($muchas_ins?"s":"")." de ".$count_cambios.".";
				}
			}
			else {
				$count_cambios_anteriores = PDOProyectos::select_count_cambios($pdo, $prjID);
				if ($count_cambios_anteriores === 1){
					$callback_response = "No se asignaron cambios.";
				}					
				else {
					PDOProyectos::update_cambios(
						$pdo,
						$prjID,
						[]
					);
					$callback_success = true;
					$callback_response = "Se eliminaron los cambios del proyecto.";
				}
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar un proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);