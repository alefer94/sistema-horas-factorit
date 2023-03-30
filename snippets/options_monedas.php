<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOMonedas.php';
 
function options_monedas($pdo = null, $seleccion = null){
	foreach (PDOMonedas::select($pdo) as $monedas) { 
	  ?><option value="<?=$monedas["monID"];?>" <?=($monedas["monID"]===$seleccion)?"selected":"";?> ><?=$monedas["monCodigo"];?></option><?php
	}
}