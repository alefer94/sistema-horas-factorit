<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoBitacoras.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar la lista de bitacoras de un proyecto. 
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
		$count_bitacoras = isset($_POST["bitacoras"]) && is_array($_POST["bitacoras"])? count($_POST["bitacoras"]) : 0;
        
		try { 
			$pdo = new InterfazPDO();
			$count_bitacoras_anteriores = PDOProyectos::select_count_bitacoras($pdo, $prjID);
			
			if ($count_bitacoras > 0){ 
				$inserciones = PDOProyectos::update_bitacoras(

					$pdo, 
					$prjID, 
					$_POST["bitacoras"]

				);
				
				$muchas_ins = $inserciones!=1;
				if ($inserciones === $count_bitacoras){
					$callback_success = true;
					$callback_response = "Se design".($muchas_ins?"aron":"ó")." ".$count_bitacoras."bitacora".($muchas_ins?"s":"")." correctamente.<br/>";	
				}
				else {
					$callback_response = "Se design".($muchas_ins?"aron":"ó")." ".$inserciones." de ".$count_bitacoras."bitacora".($muchas_ins?"s":"").".<br/>Por favor, inténtelo nuevamente.";
				}
			}
			else {
				if ($count_bitacoras_anteriores === 0) {
					$callback_response = "No se designaron bitacoras para este proyecto.";
				}
				else {
					PDOProyectos::update_bitacoras(
						$pdo, 
						$prjID, 
						[]
					);
					$callback_success = true;
					$callback_response = "Se eliminaron todos las bitacoras del proyecto correctamente.<br/>"; 
				}
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar la lista de bitacoras del proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);