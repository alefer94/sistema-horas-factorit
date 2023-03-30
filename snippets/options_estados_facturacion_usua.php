<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_estados_facturacion_usua.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_estados_facturacion_usua($pdo = null, $seleccion = null){
	foreach (PDOUsuarios::select_estados_facturacion_usua($pdo) as $estado_facturacion) { 
	  ?><option value="<?=$estado_facturacion["usrFlagFacturable"];?>" <?=($estado_facturacion["usrFlagFacturable"]===$seleccion)?"selected":"";?> ><?=$estado_facturacion["usrFlagFacturable"];?></option><?php
	}
}