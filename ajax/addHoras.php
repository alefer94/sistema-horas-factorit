<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/addHoras.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para crear un registro de horas. 
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_success = false;
$callback_response = "";
$hrID = 0;

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID === -1){
	$callback_response = "Su sesión no pudo ser validada.<br/>Por favor, cierre su sesión o recargue la página para ingresar nuevamente.";
}
else {
	require_once __DIR__.'/../common/repairPOST.php';
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOHoras.php';
	require_once __DIR__.'/../common/jTraceEx.php'; 
	$_POST = repairPOST($_POST);
	$_POST["usrID"] = $ajaxUsrID;
	
	$dias_incorrectos = [];
	for ($i = 1; $i <= 31; $i++){
		$datosDia = isset($_POST["hrDia".$i])? intval($_POST["hrDia".$i]) : false;
		if (!is_numeric($datosDia) || $datosDia > 8) {
			array_push($dias_incorrectos, $i);
		}
	}
	if (count($dias_incorrectos) === 0){
		unset($dias_incorrectos);
	
		$dias_pasados_de_fecha = false;
		if ($_POST["hrMes"] === date("Y-m")."-01") {
			$dia_actual = intval(date("d"));
			for ($i = $dia_actual; $i <= 31; $i++){
				if (intval($_POST["hrDia".$i]) > 0 && $i > $dia_actual) {
					$dias_pasados_de_fecha = true;
					break;
				}
			}
		}

		if (!$dias_pasados_de_fecha) {
			try {
				$hrID = PDOHoras::insert(
					new InterfazPDO(), 
					$_POST
				);
				if ($hrID !== 0) {
					$callback_success = true;
					$callback_response = "Registro de horas creado.";
				}
				else {
					$callback_response = "El registro no pudo ser agregado.<br/>Verifique que no exista un registro para el mismo periodo/proyecto/fase e inténtelo nuevamente.";
				}
			}
			catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
		}
		else {
			$callback_response = "No se pueden ingresar horas para días aún no transcurridos. Por favor, corrija los días ingresados e inténtelo nuevamente.";
		}
	}
	else {
		$muchos = count($dias_incorrectos) != 1;
		$callback_response = ($muchos?"Los":"El")." día".($muchos?"s":"")." ". (implode(", ", $dias_incorrectos)) ." de este periodo lleva".($muchos?"n":"")." más de 8 horas cargadas.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response,
	$hrID
]);