<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Diego Garcia
 */
require_once __DIR__.'/../common/PDOSkill.php';
 
function options_skill($pdo = null, $seleccion = null){
	foreach (PDOSkill::select($pdo) as $skl) { 
	  ?><option value="<?=$skl["sklID"];?>" <?=($skl["sklID"]===$seleccion)?"selected":"";?> ><?=$skl["sklNombre"];?></option><?php
	}
}