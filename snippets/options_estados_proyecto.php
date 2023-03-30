<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_estados_proyecto.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOProyectos.php';
 
function options_estados_proyecto($pdo = null, $seleccion = null){
	foreach (PDOProyectos::select_estados_proy($pdo) as $tipo) { 
	  ?><option value="<?=$tipo["prjEstado"];?>" <?=($tipo["prjEstado"]===$seleccion)?"selected":"";?>><?=$tipo["prjEstado"];?></option><?php
	}
}