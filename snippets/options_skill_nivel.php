<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Diego Garcia
 */
require_once __DIR__.'/../common/PDOSkillNivel.php';
 
function options_skillNivel($pdo = null, $seleccion = null){
	foreach (PDOSkillNivel::select($pdo) as $lsk) { 
	  ?><option value="<?=$lsk["lskID"];?>" <?=($lsk["lskID"]===$seleccion)?"selected":"";?> ><?=$lsk["lskNombre"];?></option><?php
	}
}