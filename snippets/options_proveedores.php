<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_usuarios.php
 
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_proveedores($pdo = null, $seleccion = null ){
	foreach (PDOUsuarios::select_proveedores($pdo) as $proveedor) { 
	  ?><option value="<?=$proveedor["usrID"];?>" <?=($proveedor["usrID"]===$seleccion)?"selected":"";?>><?=$proveedor["usrNombre"];?></option><?php
	}
}