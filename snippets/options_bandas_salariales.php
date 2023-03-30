<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_bandas_salariales.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/PDOProyectos.php';
 
function options_bandas_salariales($pdo = null, $seleccion = null){
	foreach (PDOProyectos:: select_bandas_salariales($pdo) as $bandas_salariales) { 
	  ?><option value="<?=$bandas_salariales["bnsID"];?>" <?=($bandas_salariales["bnsID"]===$seleccion)?"selected":"";?> ><?=$bandas_salariales["bnsCodigo"];?></option><?php
	}
}