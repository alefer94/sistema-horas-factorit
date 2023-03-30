<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/validateAjaxRequest.php
 * Autor: Benjamin La Madrid
 * 
 * Valida la sesión del usuario actual, comparando un hash en una cookie de éste contra un hash en la base de datos.
 * @return int El ID del usuario, si la sesión proveída es válida. -1 si no es así.
 */
 
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOSesiones.php';
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function validateAjaxRequest(){
	session_start();
	$sesion = (isset($_SESSION["hash"]))? $_SESSION["hash"] : null;
	session_write_close();

	if ($sesion == null){ return -1; }
	
	setlocale(LC_TIME, "");
	$time = date(DateTime::ISO8601);

	$pdo = new InterfazPDO();
	$usrID = PDOSesiones::validate_user_from_session_hash($pdo, $sesion, $time);
	unset($sesion, $time, $pdo);
	
	if ($usrID === null){ return -1; }
	else { return $usrID; }
}