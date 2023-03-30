<?php
/**
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/getTotalHorasHabilesMes.php
 * Autor: Benjamin La Madrid
 *
 * Obtiene la suma de las horas que se pueden marcar como trabajadas a lo largo de un mes. 
 * @param string $hrMes (Opcional) Un mes en formato 'YYYY-MM-01', o el mes actual si no se especifica este parámetro.
 * @return array Un array conteniendo los números del mes de los días no hábiles encontrados.
 */
require_once __DIR__.'/getDiasNoHabiles.php';

function getTotalHorasHabilesMes($hrMes = null, $usrID = null){
	if (!is_array(date_parse($hrMes))){
		$hrMes = date("Y-m-d");
	}
    $no_habiles = getDiasNoHabiles($hrMes, $usrID);
	$habiles = 31 - sizeof($no_habiles);
	$total = $habiles * 8;
    return $total;
}