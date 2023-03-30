<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_resumen_periodos.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOHoras.php";
require_once __DIR__.'/../common/getTotalHorasHabilesMes.php';

function tr_resumen_periodos($pdo = null, $usrID = 0){
	foreach (PDOHoras::select_resumenes_periodos($pdo, ["usrID" => $usrID]) as $resumen_periodo) { 
		$timestamp_now = (new DateTime($resumen_periodo["hrMes"]))->getTimestamp(); ?>
		<tr>
			<td class="text-center"><?=ucfirst(strftime("%b-%Y", $timestamp_now)); ?></td>
			<td class="text-center"><?=$resumen_periodo["totalHorasMes"]; ?></td>
			<td class="text-center"><?=getTotalHorasHabilesMes($resumen_periodo["hrMes"], $resumen_periodo["usrID"]); ?></td>
		</tr><?php 
	}
}