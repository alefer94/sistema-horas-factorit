<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateSkill.php
 * Autor: Diego Garcia
 * 
 * Controlador AJAX para actualizar un skill. 
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
	
	if (isset($_POST["sklID"]) && is_numeric($_POST["sklID"])){
		if (sizeof($_POST) > 1) {
			require_once __DIR__.'/../common/InterfazPDO.php';
			require_once __DIR__.'/../common/PDOSkill.php';
			require_once __DIR__.'/../common/jTraceEx.php';
			
			try {
				$pdo = new InterfazPDO();
				$filas_afectadas = PDOSkill::update(
					$pdo, 
					$_POST, 
				  [ "sklID" => intval($_POST["sklID"]) ]
			    );
				$callback_success = true;
				
				if ($filas_afectadas > 0) {
					$callback_response = "Skill actualizado.";
				}
				else {
					$callback_response = "La operación fue exitosa, aunque el Skill no se actualizó.";
				}
			}
			catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
		}
		else {
			$callback_response = "No se recibieron datos para actualizar.";
		}
	}
	else {
		$callback_response = "No se recibió el parámetro 'sklID' para actualizar un skill.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);