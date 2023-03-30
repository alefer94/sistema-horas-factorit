<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_meses.php
 * Autor: Benjamin La Madrid
 */
 require_once __DIR__.'/../common/days_in_month.php';
 
function options_meses($año = "", $selected = 0){
	setlocale(LC_TIME, ""); //aseguramos que los meses sean traducidos al español
	if ($año === "") { $año = date("Y"); }
	
	$dt_inicio 	= new DateTime($año."-01-01");
	$dt_fin 	= new DateTime($año."-01-01");
	$dt_fin->modify("+1 year");
	$dt_intervalo_mes = new DateInterval("P1M"); //armamos un intervalo de 1 mes
	
	foreach (new DatePeriod($dt_inicio, $dt_intervalo_mes, $dt_fin) as $dt_cursor) {
		$mes = intval($dt_cursor->format("m"));
		$dias_del_mes = intval(days_in_month(intval($mes), intval($año)));
		$nombre_mes = ucfirst(strftime("%B", $dt_cursor->getTimestamp())); 
	
	  ?><option value="<?=$mes;?>" data-dias="<?=$dias_del_mes;?>" <?=$mes===$selected?"selected":"";?>><?=$nombre_mes;?></option><?php
	}
}