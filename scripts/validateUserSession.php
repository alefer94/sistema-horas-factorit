<?php 
/*
 * Sistema: Gestión de Horas de Colaboradores
 * Cliente: FactorIT
 * Archivo fuente: scripts/validateUserSession.php
 * Autor: Benjamin La Madrid
 *
 * Script común que valida la sesión del usuario.
 * También declara y asigna una variable global 'usrData' sobre el usuario como su nombre, mail y nivel de acceso.
 * Una sesión es válida si existe en la base de datos y la diferencia  de tiempo entre 
 * el momento de creación de la sesión (login) y el momento de 
 * ejecución de este script es menor al indicado en la clase PDOSesiones (revisar SESSION_LIFETIME)
 *
 * Este script nunca debería ser usado durante peticiones AJAX. En su lugar, se debe usar la función validateAjaxRequest()
 */

require_once __DIR__.'/../common/globals.php';
require_once __DIR__.'/../common/PDOSesiones.php';
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOUsuarios.php';

function redirectToLogin(){
	header("Location: ".$GLOBALS["ROOT_DIRECTORY"]."/login");
	exit();
}

session_start();
$sesion = (isset($_SESSION["hash"]))? $_SESSION["hash"] : null;
session_write_close();

if ($sesion === null){ redirectToLogin(); }

$time = date(DateTime::ISO8601);
$pdo = new InterfazPDO();
$usrID = PDOSesiones::validate_user_from_session_hash($pdo, $sesion, $time);

if ($usrID === 0){ redirectToLogin(); }

$usrData = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $usrID])->fetch();
$GLOBALS["USER_ID"] = intval($usrID);
$GLOBALS["USER_NAME"] = $usrData["usrNombre"];
$GLOBALS["USER_MAIL"] = $usrData["usrMail"];
$GLOBALS["USER_RUT"] = $usrData["usrRut"].(empty($usrData["usrDv"])? "": "-".$usrData["usrDv"]);
$GLOBALS["USER_PRIVILEGE"] = intval($usrData["tpUsrID"]);
$GLOBALS["USER_SITE_THEME"] = intval($usrData["usrTemaSitio"]);

unset($usrID, $usrData, $pdo, $time, $sesion);
?>