<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/updateProyectoProveedor.php
 * Autor: Cristian Lobos
 * 
 * Controlador AJAX para actualizar el proveedor de un proyecto.
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
				
		$prjProveedor = !empty($_POST["prjProveedor"])?$_POST["prjProveedor"] : null;
		
		
		try { 
			$pdo = new InterfazPDO();
			$filas_afectadas = PDOProyectos::update(
				$pdo, 
			  [ "prjProveedor" => $prjProveedor ], 
			  [ "prjID" => intval($_POST["prjID"]) ]
		    );
			$callback_success = true;
			
			if ($filas_afectadas > 0){
				$callback_response = "El proveedor del proyecto fue actualizado.<br/>";
			}
			else {
				$callback_response = "La operación fue exitosa, aunque el proveedor no fue actualizado.";
			}
		}
		catch (Exception $exc) { $callback_response = "Hubo un error de ejecución.<br/>Por favor, inténtelo nuevamente. Si el problema persiste, contacte al administrador.<br/><b>Detalles del error:</b><br/><pre>".jTraceEx($exc)."</pre>"; }
	}
	else {
		$callback_response = "No se recibió el parámetro 'prjID' para actualizar el proveedor del proyecto.";
	}
}

echo json_encode([
	$callback_success,
	$callback_response
]);