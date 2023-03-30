<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/getModal.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para generación y obtención de modales.
 * Debe traer el parámetro "id" con uno de los valores indicados.
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID !== -1 && !empty($_GET["id"])){

	$modal_id = 'modal_'.$_GET["id"];
	$path = __DIR__.'/../snippets/'.$modal_id.'.php';

	if (file_exists($path)){
		unset($_GET["id"]);
		require $path;
		call_user_func($modal_id);
	}
	else {
		throw new Exception("El modal solicitado no existe.");
	}
}