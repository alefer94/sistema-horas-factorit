<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_estados_usuario($pdo = null, $seleccion = null){
	foreach (PDOUsuarios::select_estados($pdo) as $tipo) { 
	  ?><option value="<?=$tipo["usrEstID"];?>" <?=($tipo["usrEstID"]===$seleccion)?"selected":"";?>><?=$tipo["usrEstDescripcion"];?></option><?php
	}
}