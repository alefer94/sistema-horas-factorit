<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOSubGerencias.php';
 
function options_subgerencias($pdo = null, $seleccion = null){
	foreach (PDOSubGerencias::select($pdo) as $subgerencia) { 
	  ?><option value="<?=$subgerencia["sgrID"];?>" <?=($subgerencia["sgrID"]===$seleccion)?"selected":"";?> ><?=$subgerencia["sgrNombre"];?></option><?php
	}
}