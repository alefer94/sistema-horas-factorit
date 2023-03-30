<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_clientes.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOClientes.php';
 
function options_clientes($pdo = null, $limit = 150, $page = 1, $filters = []){
	foreach (PDOClientes::select($pdo, $limit, $page, $filters) as $cliente) { 
	  ?><option value="<?=$cliente["cltID"];?>"><?=$cliente["cltNombre"];?></option><?php
	}
}