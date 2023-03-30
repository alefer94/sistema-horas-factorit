<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_periodos.php
 * Autor: Benjamin La Madrid
 */

function options_periodos($fecha_actual = ""){
	setlocale(LC_TIME, ""); //aseguramos que los meses sean traducidos al español
	if ($fecha_actual === "") { $fecha_actual = date("Y-m"); }
	
	$dt_inicio = new DateTime("2017-01-01");
	$dt_intervalo_mes = new DateInterval("P1M"); //armamos un intervalo de 1 mes
	$dt_fin = new DateTime("now"); //creamos una referencia a la fecha actual y la avanzamos 1 año
	$dt_rango = new DatePeriod($dt_inicio, $dt_intervalo_mes, $dt_fin); //este rango es un objeto iterable
	$dt_rango = array_reverse(iterator_to_array($dt_rango));
	
	foreach ($dt_rango as $dt_cursor) {
		$fecha = $dt_cursor->format("Y-m");
		$fecha_humana = ucfirst(strftime("%b-%Y", $dt_cursor->getTimestamp()));
	
	  ?><option value="<?=$fecha;?>-01" <?=$fecha===$fecha_actual?"selected":""?>><?=$fecha_humana;?></option><?php
	}
}