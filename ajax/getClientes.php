<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/getClientes.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para mostrar clientes de la base de datos para un dropdown select2.
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest" || sizeof($_POST) > 0) { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_response = false;

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID !== -1){
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOClientes.php';
	require_once __DIR__.'/../common/jTraceEx.php';
	$filtro = empty($_GET["filtro"])? [] : 
				  [ "cltNombre" => [$_GET["filtro"], "LIKE"], 
				    "cltCodigo" => [$_GET["filtro"], "LIKE"] ];
	$page = empty($_GET["page"])? 1 : intval($_GET["page"]);
	
	$callback_response = [];
	
	try { 
		$pdo = new InterfazPDO();
		
		foreach (PDOClientes::select($pdo, 10, $page, $filtro) as $item){
			array_push( 
				$callback_response, 
			  [ "id" => $item["cltID"],
				"text" => $item["cltNombre"],
				"code" => $item["cltCodigo"] ]
			);
		}
	}
	catch (Exception $exc) { $callback_response = false; }
}

echo json_encode($callback_response);