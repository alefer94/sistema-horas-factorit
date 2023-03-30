<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/getDiasNoHabiles.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para generación y obtención de los días no hábiles de un mes determinado.
 * Si el parámetro "hrMes" necesario viene vacío, utiliza el mes actual.
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/getDiasNoHabiles.php';

$hrMes = isset($_GET["hrMes"])? $_GET["hrMes"] : "";
$usrID = isset($_GET["usrID"])? $_GET["usrID"] : "";
echo json_encode(getDiasNoHabiles($hrMes, $usrID));