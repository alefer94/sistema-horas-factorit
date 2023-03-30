<?php 
/*
 * Sistema: Gestión de Horas de Colaboradores
 * Cliente: FactorIT
 * Archivo fuente: scripts/checkCurrentHorasNow.php
 * Autor: Benjamin La Madrid
 *
 * Script directo que valida los días que llevan los usuarios sin ingresar horas a partir de la fecha de ejecución.
 * Este script se debe ejecutar desde línea de comandos.
 */

require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOProyectos.php';
require_once __DIR__.'/../common/checkCurrentHoras.php';

$log = "";

//parámetros
$dias_hacia_atras_a_revisar 			= isset($argv[1]) && filter_var($argv[1], FILTER_VALIDATE_INT)? $argv[1] : 10;
$tolerancia_horas_ingresadas_diarias 	= isset($argv[2]) && filter_var($argv[2], FILTER_VALIDATE_INT)? $argv[2] : 7;
$tolerancia_dias_con_falta_1 			= isset($argv[3]) && filter_var($argv[3], FILTER_VALIDATE_INT)? $argv[3] : 1;
$tolerancia_dias_con_falta_2 			= isset($argv[4]) && filter_var($argv[4], FILTER_VALIDATE_INT)? $argv[4] : 3;
$tolerancia_dias_con_falta_3 			= isset($argv[5]) && filter_var($argv[5], FILTER_VALIDATE_INT)? $argv[5] : 7;
$remitente 								= isset($argv[6]) && filter_var($argv[6], FILTER_VALIDATE_INT)? $argv[6] : "test@factorit.com";
$logpath 								= isset($argv[7])? $argv[7] : __DIR__."/../logs/checkCurrentHorasNow.log"; 

//memoriza los correos de los jefes de proyecto y gerentes
$pdo = new InterfazPDO();
$jefes = PDOProyectos::select_all_jefes($pdo);
$gerentes = PDOProyectos::select_all_gerentes($pdo);
unset($pdo);


try {
	//$logfile = fopen($logpath, 'a');

	//ejecuta la verificacion, memoriza los usuarios a notificar por correo
	$array_usuarios = checkCurrentHoras(
		$dias_hacia_atras_a_revisar, 
		$tolerancia_horas_ingresadas_diarias, 
		$tolerancia_dias_con_falta_1);
		
	$cabeceras = [
		"From" => $remitente
	];

	foreach ($array_usuarios as $i => $u) {
		//del usuario
		$email = $u[0];
		$nombre_completo = $u[1];
		$primer_nombre = (explode(" ", $nombre_completo, 2))[0];
		$dias_sin_ingresar = $u[2];
		
		//del mail
		$asunto = "$primer_nombre, lleva $dias_sin_ingresar días de retraso en su ingreso de horas.";
		$mensaje = "Estimado:\r\n";
		if ($dias_sin_ingresar > tolerancia_dias_con_falta_1) {
			$recibido = mail($email, $asunto, $mensaje, $cabeceras);
			//fwrite($logfile, "Correo enviado a $email: ".($recibido?"OK" : "FALLO"));
				
			if ($dias_sin_ingresar > tolerancia_dias_con_falta_2) {
				$asunto = "Horas $nombre";
				$mensaje = "";
				
				foreach ($jefes as $j){
					$email = $j["usrMail"];
					$recibido = mail($jefe_email, $asunto, $mensaje, $cabeceras);
					//fwrite($logfile, "Correo enviado a $email: ".($recibido?"OK" : "FALLO"));
				}
			
				if ($dias_sin_ingresar > tolerancia_dias_con_falta_3) {
					
					foreach ($gerentes as $g){
						$email = $g["usrMail"];
						$recibido = mail($jefe_email, $asunto, $mensaje, $cabeceras);
						//fwrite($logfile, "Correo enviado a $email: ".($recibido?"OK" : "FALLO"));
					}
				}
			}
		}
	}
	
	//fclose($logfile);
}
catch (Exception $exc) {
	//$log = "Script fallado.\nMensaje: ".$exc->getMessage()."\nDetalles de la traza: ".$exc->getTraceAsString();
}


?>