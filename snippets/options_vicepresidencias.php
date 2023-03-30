<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_vicepresidencia.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_vicepresidencias($pdo = null, $seleccion = null){
	foreach (PDOUsuarios:: select_vicepresidencias($pdo) as $vicepresidencias) { 
	  ?><option value="<?=$vicepresidencias["vprID"];?>" <?=($vicepresidencias["vprID"]===$seleccion)?"selected":"";?> ><?=$vicepresidencias["vprNombre"];?></option><?php
	}
}