<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Diego Garcia
 */
require_once __DIR__.'/../common/PDOCentroCostos.php';
 
function options_centro_costos($pdo = null, $limit = 50, $page = 1, $filters = []){
	foreach (PDOCentroCostos::select($pdo, $limit, $page, $filters) as $centrocosto) { 
	  ?><option value="<?=$centrocosto["ccoID"];?>"><?=$centrocosto["ccoNombre"];?></option><?php
	}
}