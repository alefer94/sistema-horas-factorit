<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_estados_hitos.php
 * Autor: Francisco Espinoza
 */
require_once __DIR__.'/../common/PDOProyectos.php';
 
function options_estados_hitos($pdo = null, $seleccion = null){
	foreach (PDOProyectos::select_estados_hitos($pdo) as $tipo) { 
        ?><option value="<?=$tipo["htEstID"];?>" <?=($tipo["htEstID"]===$seleccion)?"selected":"";?>><?=$tipo["htEstDescripcion"];?></option><?php
	}
}