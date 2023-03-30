<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateHoras.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para actualizar un registro de horas.
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
	
	if (isset($_POST["hrID"]) && is_numeric($_POST["hrID"])){
		require_once __DIR__.'/../common/InterfazPDO.php';
		require_once __DIR__.'/../common/PDOHoras.php';
		require_once __DIR__.'/../common/jTraceEx.php';
		
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
			$data_horas = PDOHoras::select(new InterfazPDO(), 1, 1, ["hrID" => $_POST["hrID"]])->fetch(PDO::FETCH_ASSOC);

			if (!is_array($data_horas)) {
				$callback_response = "El registro de horas no existe.";
			}
			else {
				if ($data_horas["hrMes"] === date("Y-m")."-01") {
					$dia_actual = intval(date("d"));
					for ($i = $dia_actual; $i <= 31; $i++){
						if (intval($_POST["hrDia".$i]) > 0 && $i > $dia_actual) {
							$dias_pasados_de_fecha = true;
							break;
						}
					}
				}
				unset($data_horas);
				
				if (!$dias_pasados_de_fecha) {
					try {
						$filas_afectadas = PDOHoras::update(
							new InterfazPDO(), 
							$_POST, 
						[ "hrID" => intval($_POST["hrID"]) ]
						);
						$callback_success = true;
						
						if ($filas_afectadas > 0) {
							$callback_response = "Registro de horas actualizado.";
						}
						else {
							$callback_response = "La operación fue exitosa, aunque el registro no se actualizó.";
						}
					}
					catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
				}
				else {
					$callback_response = "No se pueden ingresar horas para días aún no transcurridos. Por favor, corrija los días ingresados e inténtelo nuevamente.";
				}
			}
		}
		else {
			$callback_response = "Los días ". (implode(", ", $dias_incorrectos)) ." de este periodo llevan más de 8 horas cargadas.";
		}
	}
	else {
		$callback_response = "No se recibió el parámetro 'hrID' para actualizar un registro de horas.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);