<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_areas($pdo = null, $seleccion = null){
	foreach (PDOUsuarios::select_areas($pdo) as $area) { 
	  ?><option value="<?=$area["usrArea"];?>" <?=($area["usrArea"]===$seleccion)?"selected":"";?> ><?=$area["usrArea"];?></option><?php
	}
}