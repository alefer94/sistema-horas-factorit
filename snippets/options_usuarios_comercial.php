<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios_comercial.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/PDOProyectos.php';
 
function options_usuarios_comercial($pdo = null, $limit = 500, $page = 1, $filters = []){
	foreach (PDOProyectos::select_comercial($pdo, $limit, $page, $filters) as $usuario) { 
	  ?><option value="<?=$usuario["usrID"];?>"><?=$usuario["usrNombre"];?></option><?php
	}
}