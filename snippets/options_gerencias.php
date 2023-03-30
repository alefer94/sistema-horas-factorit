<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOGerencias.php';
 
function options_gerencias($pdo = null, $seleccion = null){
	foreach (PDOGerencias::select($pdo) as $gerencia) { 
	  ?><option value="<?=$gerencia["gerID"];?>" <?=($gerencia["gerID"]===$seleccion)?"selected":"";?> ><?=$gerencia["gerNombre"];?></option><?php
	}
}