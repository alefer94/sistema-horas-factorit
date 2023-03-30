<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_tipos_usuario($pdo = null, $seleccion = null){
	foreach (PDOUsuarios::select_tipos($pdo) as $tipo) { 
	  ?><option value="<?=$tipo["tpUsrID"];?>" <?=($tipo["tpUsrID"]===$seleccion)?"selected":"";?> ><?=$tipo["tpUsrNombre"];?></option><?php
	}
}