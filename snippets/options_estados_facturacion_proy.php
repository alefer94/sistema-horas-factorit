<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_estados_facturacion_proy.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOProyectos.php';
 
function options_estados_facturacion_proy($pdo = null, $seleccion = null){
	foreach (PDOProyectos::select_estados_facturacion_proy($pdo) as $estado_facturacion) { 
	  ?><option value="<?=$estado_facturacion["prjFlagFacturable"];?>" <?=($estado_facturacion["prjFlagFacturable"]===$seleccion)?"selected":"";?> ><?=$estado_facturacion["prjFlagFacturable"];?></option><?php
	}
}