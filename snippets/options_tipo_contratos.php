<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/options_tipo_contratos.php
 * Autor: Cristian Lobos 
 */
require_once __DIR__.'/../common/PDOUsuarios.php';
 
function options_tipo_contratos($pdo = null, $seleccion = null){
	foreach (PDOUsuarios:: select_tipo_contratos($pdo) as $tipo_contratos) { 
	  ?><option value="<?=$tipo_contratos["tctID"];?>" <?=($tipo_contratos["tctID"]===$seleccion)?"selected":"";?> ><?=$tipo_contratos["tctNombre"];?></option><?php
	}
}