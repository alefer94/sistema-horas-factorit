<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectohitos.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar la lista de hitos de un proyecto. 
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
	
	if (isset($_POST["prjID"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectos.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		$prjID = intval($_POST["prjID"]);
		$count_hitos = isset($_POST["hitos"]) && is_array($_POST["hitos"])? count($_POST["hitos"]) : 0;
        
		try { 
			$pdo = new InterfazPDO();
			$count_hitos_anteriores = PDOProyectos::select_count_hitos($pdo, $prjID);
			
			if ($count_hitos > 0){ 
				$inserciones = PDOProyectos::update_hitos(

					$pdo, 
					$prjID, 
					$_POST["hitos"]

				);
				
				$muchas_ins = $inserciones!=1;
				if ($inserciones === $count_hitos){
					$callback_success = true;
					$callback_response = "Se design".($muchas_ins?"aron":"ó")." ".$count_hitos." hito".($muchas_ins?"s":"")." correctamente.<br/>";	
				}
				else {
					$callback_response = "Se design".($muchas_ins?"aron":"ó")." ".$inserciones." de ".$count_hitos." hito".($muchas_ins?"s":"").".<br/>Por favor, inténtelo nuevamente.";
				}
			}
			else {
				if ($count_hitos_anteriores === 0) {
					$callback_response = "No se designaron hitos para este proyecto.";
				}
				else {
					PDOProyectos::update_hitos(
						$pdo, 
						$prjID, 
						[]
					);
					$callback_success = true;
					$callback_response = "Se eliminaron todos los hitos del proyecto correctamente.<br/>"; 
				}
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar la lista de hitos del proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);