<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_usuarios($pdo = null, $limit = 500, $page = 1, $filters = []){
	foreach (PDOUsuarios::select($pdo, $limit, $page, $filters) as $usuario) { 
	  ?><option value="<?=$usuario["usrID"];?>"><?=$usuario["usrNombre"];?></option><?php
	}
}

function options_internos($pdo = null, $seleccion = null ){
	foreach (PDOUsuarios::select_internos($pdo) as $interno) { 
	  ?><option value="<?=$interno["usrID"];?>" <?=($interno["usrID"]===$seleccion)?"selected":"";?>><?=$interno["usrNombre"];?></option><?php
	}
}