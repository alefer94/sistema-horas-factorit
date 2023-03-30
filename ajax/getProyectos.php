<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/getProyectos.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para mostrar proyectos de la base de datos para un dropdown select2.
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest" || sizeof($_POST) > 0) { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$callback_response = false;

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID !== -1){
	require_once __DIR__.'/../common/InterfazPDO.php';
	require_once __DIR__.'/../common/PDOProyectos.php';
	require_once __DIR__.'/../common/jTraceEx.php';
	$filtro = empty($_GET["filtro"])? [] : 
				  [ "prjNombre" => ["LIKE", $_GET["filtro"]], 
					"prjCodigo" => ["LIKE", $_GET["filtro"]] ];
	$page = empty($_GET["page"])? 1 : intval($_GET["page"]);
	
	$callback_response = [];
	
	try { 
		$pdo = new InterfazPDO();
		
		foreach (PDOProyectos::select($pdo, 10, $page, $filtro) as $item){
			array_push( 
				$callback_response, 
			  [ "id" => $item["prjID"],
				"text" => $item["prjNombre"],
				"code" => $item["prjCodigo"]  ]
			);
		}
	}
	catch (Exception $exc) { $callback_response = false; }
}

echo json_encode($callback_response);