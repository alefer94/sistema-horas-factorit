<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: ajax/getTabla.php
 * Autor: Benjamin La Madrid
 * 
 * Controlador AJAX para generación y obtención de las filas de una tabla HTML.
 * Debe traer el parámetro "tabla" con uno de los valores indicados.
*/
if (strtolower(filter_input(INPUT_SERVER, "HTTP_X_REQUESTED_WITH")) !== "xmlhttprequest") { die(); }
require_once __DIR__.'/../common/validateAjaxRequest.php';

$ajaxUsrID = validateAjaxRequest();
if ($ajaxUsrID !== -1 && !empty($_GET["tabla"])){
	require_once __DIR__.'/../common/InterfazPDO.php';
	
	$pdo = new InterfazPDO();
	switch ($_GET["tabla"]){
		case "horas": 
			require_once __DIR__.'/../snippets/tr_horas.php';
			
			$trs = [];
			$periodo = isset($_GET["periodo"])? $_GET["periodo"] : date("Y-m")."-01";

			ob_start();
			tr_horas_columnas($pdo, 50, 1, [ "hrMes" => $periodo, "usrID" => $ajaxUsrID ]);
			$trs[0] = ob_get_clean();

			ob_start();
			tr_horas($pdo, 50, 1, [ "hrMes" => $periodo, "usrID" => $ajaxUsrID ]);
			$trs[1] = ob_get_clean();

			echo json_encode($trs);
			break;
		
		case "proyectos": 
			require_once __DIR__.'/../snippets/tr_proyectos.php';
			
			$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $ajaxUsrID] )->fetch();
			$GLOBALS["esUsrJefe"] = (intval($usuario["tpUsrID"]) >= 2);
			unset($_GET["tabla"], $usuario);
			foreach ($_GET as $col => $val) {
				if ($val === "NULL"){
					$_GET[$col] = null;
				}
				else if (empty($val)){
					unset($_GET[$col]);
				}
				else if (strlen(strstr($col, "ID")) === 0){
					$_GET[$col] = ["LIKE", $val];
				}
			}
			tr_proyectos($pdo, 50, 1, $_GET);
			break;		

		case "usuarios": 
			require_once __DIR__.'/../snippets/tr_usuarios.php';
				
			$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $ajaxUsrID] )->fetch();
			$GLOBALS["esUsrJefe"] = (intval($usuario["tpUsrID"]) >= 2);
			unset($_GET["tabla"], $usuario);
			foreach ($_GET as $col => $val) {
				if ($val === "NULL"){
					$_GET[$col] = null;
				}
				else if (empty($val)){
					unset($_GET[$col]);
				}
				else if (strlen(strstr($col, "ID")) === 0){
					$_GET[$col] = ["LIKE", $val];
				}
			}
			tr_usuarios($pdo, 50, 1, $_GET);
			break;	

		case "feriados": 
			require_once __DIR__.'/../snippets/tr_feriados.php';
			
			$usuario = PDOUsuarios::select($pdo, 1, 1, ["usrID" => $ajaxUsrID] )->fetch();
			$GLOBALS["esUsrJefe"] = (intval($usuario["tpUsrID"]) >= 2);
			unset($_GET["tabla"], $usuario);
			foreach ($_GET as $col => $val) {
				if ($val === "NULL"){
					$_GET[$col] = null;
				}
				else if (empty($val)){
					unset($_GET[$col]);
				}
				else if (strlen(strstr($col, "ID")) === 0){
					$_GET[$col] = ["LIKE", $val];
				}
			}
			tr_feriados($pdo, 50, 1, $_GET);
			break;
	


		case "ddddd": 
			require_once __DIR__.'/../common/PDOUsuarios.php';
			require_once __DIR__.'/../snippets/tr_feriados.php';
			
			$año = isset($_GET["anio"])? $_GET["anio"] : date("Y");
			$usrData = PDOUsuarios::select($pdo, 1, 1, [ "usrID" => $ajaxUsrID ])->fetch();
			$permisos = intval($usrData["tpUsrID"]) >= 2;
			tr_feriados($pdo, $año, $permisos);
			break;		

		case "proyecto_planeacion": 
			require_once __DIR__.'/../common/PDOProyectos.php';
			require_once __DIR__.'/../snippets/detalle_proyecto_planeacion.php';

			$prjID = isset($_GET["prjID"])? intval($_GET["prjID"]) : 0;
			detalle_proyecto_planeacion($pdo, $ajaxUsrID, $prjID);		
		
		default: break;
	}
}