<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoDescripcion.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar la descripción de un proyecto. 
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
	require_once __DIR__.'/../common/validateRequestParameters.php';
	require_once __DIR__.'/../common/repairPOST.php';
	$_POST = repairPOST($_POST);
	$validaParams = validateRequestParameters(
		$_POST, 
	  [ "prjID", "descripcion" ]
	);
	
	if ($validaParams === true){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOProyectos.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		try { 
			$pdo = new InterfazPDO();
			$actualizaciones = PDOProyectos::update(
				$pdo, 
			  [ "prjDescripcion" => $_POST["descripcion"]===false? "" : $_POST["descripcion"] ], 
			  [ "prjID" => intval($_POST["prjID"]) ]
		    );
			$callback_success = true;
			
			if ($actualizaciones > 0){
				$callback_response = "La descripcion fue actualizada.<br/>";
			}
			else {
				$callback_response = "La operación fue exitosa, aunque la descripción no fue actualizada.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' o 'descripcion' para actualizar la descripción de un proyecto.";
	}
}
	
echo json_encode([
	$callback_success,
	$callback_response
]);