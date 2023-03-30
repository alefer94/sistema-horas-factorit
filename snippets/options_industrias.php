<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_industrias.php
 * Autor: Cristian Lobos
 */
require_once __DIR__.'/../common/PDOIndustrias.php';
require_once __DIR__.'/../common/PDOClientes.php';
 
function options_industrias($pdo = null, $seleccion = null){
	foreach (PDOIndustrias::select($pdo) as $industrias) { 
	  ?><option value="<?=$industrias["indID"];?>" <?=($industrias["indID"]===$seleccion)?"selected":"";?> ><?=$industrias["indNombre"];?></option><?php
	}
}
 

