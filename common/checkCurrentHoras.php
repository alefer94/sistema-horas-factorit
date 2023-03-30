<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/checkCurrentHoras.php
 * Autor: Benjamin La Madrid
 *
 * Esta función analiza las horas ingresadas de todos los usuarios, para encontrar aquellos que no han ingresado horas.
 * Dicha verifciación se a través de un periodo desde la fecha actual hasta tantos días atrás como se indique. 
 * Devuelve un array con los correos y días sin ingresar horas de aquellos que llevan el minimo indicado.
 *
 * $dias_hacia_atras_a_revisar - Cabe recalcar que no considera feriados, sabados ni domingos.
 * $tolerancia_horas_ingresadas_diarias - El maximo de horas ingresadas para que un dia se considere con falta.
 * $tolerancia_dias_con_falta - La cantidad de dias con falta de horas ingresadas minima para que un usuario sea reportado.
 */
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/PDOUsuarios.php';
require_once __DIR__.'/PDOHoras.php';

function checkCurrentHoras( $dias_hacia_atras_a_revisar, 
							$tolerancia_horas_ingresadas_diarias, 
							$tolerancia_dias_con_falta_1,
							$tolerancia_dias_con_falta_2,
							$tolerancia_dias_con_falta_3){
	$pdo = new InterfazPDO();
	
	//1. Tener en claro qué días vamos a verificar.
	//Creamos un array que los almacenará, en formato [ año, mes, dia ]
	$array_dias_a_revisar = [];
	
	//DateTime provee un cursor de fechas, a manera de recorrerlas fácilmente sin tener que idear un algoritmo muy complejo
	$now = time(); //unix timestamp estático
	$date = new DateTime(date("Y", $now)."-".date("m", $now)."-".date("d", $now));
	unset($now);
	
	//ejecutaremos un bucle hasta que los días que vamos a revisar (array) sean tantos como los que le dijimos a la función
	while(sizeof($array_dias_a_revisar) < $dias_hacia_atras_a_revisar) {
		$date_y = $date->format("Y");
		$date_m = $date->format("m");
		$date_d = $date->format("d");
		$dia_semana  = $date->format("D");
		$es_feriado = PDOHoras::is_date_feriado($pdo, $date_y, $date_m, $date_d);
		
		//no queremos revisar días que no sean hábiles, puesto que los usuarios no pueden ingresar horas en ellos
		if (!($dia_semana === "Sat" || $dia_semana === "Sun" || $es_feriado)){
			array_push($array_dias_a_revisar, [$date_y, $date_m, $date_d]); //si el día ES hábil, va al array
		}
		
		$date->modify("-1 day"); //el cursor de fechas va atrás un día
	}; 
	unset($date); //desde aquí ya no necesitamos el DateTime


	//2. Miramos las horas de los usuarios
	//Primero un array para almacenar a aquellos usuarios que queremos eventualmente notificar
	$usuarios_notificados = [ ];
	
	$q_usuarios = PDOUsuarios::select_all($pdo);
	foreach ($q_usuarios as $usuario){
		
		$dias_sin_ingresar_horas = 0;
		foreach($array_dias_a_revisar as $dia) {
			$horas_dia = PDOHoras::select_sum_horas_dia($pdo, $usuario["usrID"], $dia[0], $dia[1], intval($dia[2]));
			if ($horas_dia <= $tolerancia_horas_ingresadas_diarias){
				$dias_sin_ingresar_horas++;
			}
		}
		
		if ($dias_sin_ingresar_horas > $tolerancia_dias_sin_horas) {
			array_push($usuarios_notificados, [ $usuario["usrMail"], $usuario["usrNombre"], $dias_sin_ingresar_horas ]);
		}
	}
	unset($q_usuarios);
	unset($pdo);

	return $usuarios_notificados;
}