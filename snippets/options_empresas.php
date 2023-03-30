<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_empresas.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
require_once __DIR__.'/../common/PDOEmpresas.php';
 
function options_empresas2($pdo = null, $seleccion = null){
	foreach (PDOEmpresas::select($pdo) as $empresa) { 
	  ?><option value="<?=$empresa["empID"];?>" <?=($empresa["empID"]===$seleccion)?"selected":"";?> ><?=$empresa["empNombre"];?></option><?php
	}
}
 
function options_empresas($pdo = null, $seleccion = null){
	foreach (PDOUsuarios::select_empresas($pdo) as $empresa) { 
	  ?><option value="<?=$empresa["usrEmpresa"];?>" <?=($empresa["usrEmpresa"]===$seleccion)?"selected":"";?> ><?=$empresa["usrEmpresa"];?></option><?php
	}
}