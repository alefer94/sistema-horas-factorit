<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateDiasNoHabiles.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar un año de feriados. 
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
	
	if (isset($_POST["anio"]) && is_numeric($_POST["anio"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOFeriados.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
		$año = intval($_POST["anio"]);
		$count_feriados = isset($_POST["feriados"]) && is_array($_POST["feriados"])? count($_POST["feriados"]) : 0;
		
		try {
			$pdo = new InterfazPDO();
			$count_feriados_anteriores = PDOFeriados::select_count($pdo, $año);
			
			if ($count_feriados > 0){
				$inserciones = PDOFeriados::update_año(
					$pdo, 
					$año, 
					$_POST["feriados"]
				);
				
				$muchas_ins = $inserciones!=1;
				if ($inserciones === $count_feriados){
					$callback_success = true;
					$callback_response = "Se asign".($muchas_ins?"aron":"ó")." ".$inserciones." feriado".($muchas_ins?"s":"")." para el año ".$año.".";
				}
				else {
					$callback_response = "Se asign".($muchas_ins?"aron":"ó")." ".$inserciones." de ".$count_feriados." feriado".($muchas_ins?"s":"")." para el año ".$año.".";
				}
			}
			else {
				if ($count_feriados_anteriores === 0){
					$callback_response = "No se configuraron feriados.";
				}
				else {
					PDOFeriados::update_año(
						$pdo, 
						$año, 
						[]
					);
					$callback_success = true;
					$callback_response = "Se eliminaron todos los feriados del año ".$año.".";
				}
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "La validación de datos para actualizar los días feriados del año falló.<br/><b>Detalles del problema:</b><br/><pre>".$validaParams[1]."<pre/>"; 
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);