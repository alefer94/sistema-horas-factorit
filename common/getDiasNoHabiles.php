<?php
/**
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/getDiasNoHabiles.php
 * Autor: Benjamin La Madrid
 *
 * Obtiene todos los días no hábiles (sábados, domingos y feriados) del mes indicado. 
 * Los sábados y domingos son extraídos usando funciones de hora y fecha de PHP, mientras que los feriados son obtenidos de la base de datos del sistema. 
 * @param string $hrMes (Opcional) Un mes en formato 'YYYY-MM-01', o el mes actual si no se especifica este parámetro.
 * @return array Un array conteniendo los números del mes de los días no hábiles encontrados.
 */
require_once __DIR__.'/../common/InterfazPDO.php';
require_once __DIR__.'/../common/PDOFeriados.php';
require_once __DIR__.'/../common/PDOUsuarios.php';

function getDiasNoHabiles($hrMes = null, $usrID = null){
    if (is_null($hrMes)) { $hrMes = (date("Y-m")."-01"); }
    $no_habiles = [];
    
    //Sábados y Domingos del mes
    $es_mes_actual = ($hrMes === date("Y-m")."-01");
    $dia_actual = intval(date("d"));

    $dt_inicio = new DateTime($hrMes);
    $dt_intervalo_dia = new DateInterval("P1D");
    $dt_fin = (new DateTime($hrMes))->modify("+1 month");
    $dt_rango = new DatePeriod($dt_inicio, $dt_intervalo_dia, $dt_fin);
	$nro_dia = 1;
    foreach ($dt_rango as $dt_cursor) {
        if ($es_mes_actual && $nro_dia > $dia_actual) {
            array_push($no_habiles, $nro_dia);
        }
        else {
            $dia_semana = $dt_cursor->format("D");
            if ($dia_semana === "Sat" || $dia_semana === "Sun"){
                array_push($no_habiles, $nro_dia);
            }
        }
        $nro_dia++;
    }
    unset($dt_inicio, $dt_intervalo_dia, $dt_fin, $dt_rango);
	
	for ($i = $nro_dia; $i <= 31; $i++){
		array_push($no_habiles, $i);
	}
    
    //Feriados del mes
    $pdo = new InterfazPDO();
    $año = substr($hrMes,0,4);
    $mes = substr($hrMes,5,2);
    $pdo_usr = new InterfazPDO();
	//echo('Inicio common/getDiasNoHabiles.php \n');
    //$usrID = 99;
    //echo($usrID);
    //echo('Fin common/getDiasNoHabiles.php \n');
	$usuario = PDOUsuarios::select($pdo_usr, 1, 1, ["usrID" => $usrID] )->fetch();
    $q_feriados = PDOFeriados::select($pdo, $año, $mes, $usuario["usrEmpresa"]);
    unset($pdo_usr);
    unset($pdo);
    foreach ($q_feriados as $item) { 
        $dia_mes = intval($item["frdDia"]);
        if (!in_array($dia_mes, $no_habiles, true)){
            array_push($no_habiles, $dia_mes);
        }
    }
    $q_feriados->closeCursor();
    unset($q_feriados);
    
    return $no_habiles;
}