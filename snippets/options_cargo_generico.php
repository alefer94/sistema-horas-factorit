<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_cargo_generico.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_cargo_generico($pdo = null, $seleccion = null){
	foreach (PDOUsuarios:: select_cargo_generico($pdo) as $cargo_generico) { 
	  ?><option value="<?=$cargo_generico["cgcID"];?>" <?=($cargo_generico["cgcID"]===$seleccion)?"selected":"";?> ><?=$cargo_generico["cgcNombre"];?></option><?php
	}
}