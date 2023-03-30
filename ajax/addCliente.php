<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/addCliente.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para crear un cliente. 
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_success = false;
$callback_response = "";
$cltID = 0;

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID === -1){
	$callback_response = "Su sesión no pudo ser validada.<br/>Por favor, cierre su sesión o recargue la página para ingresar nuevamente.";
}
else {
	require_once __DIR__.'/../common/repairPOST.php';
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOClientes.php';
	require_once __DIR__.'/../common/jTraceEx.php';
	$_POST = repairPOST($_POST);
	
	try {
		$cltID = PDOClientes::insert(
			new InterfazPDO(), 
			$_POST
		);
		if ($cltID != 0) {
			$callback_success = true;
			$callback_response = "Cliente creado.";
		}
		else {
			$callback_response = "El cliente no pudo ser agregado. Verifique que su código y/o nombre no se repitan e inténtelo nuevamente.";
		}
	}
	catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
}

echo json_encode([
	$callback_success,
	$callback_response,
	$cltID
]);