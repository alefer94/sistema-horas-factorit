<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_clientes.php
 * Autor: Cristian Lobos
 */
require_once __DIR__."/../common/PDOSkillUsuarios.php";

function tr_usuarios_skill_expert($pdo = null,$usrID){
	foreach (PDOSkillUsuarios::select($pdo, $usrID) as $row) { 
	  ?><tr data-sid="<?=$row["uslID"]; ?>"><?php
		  ?><td class="b-r b-grey text-center" data-sid="<?=$row["sklID"]; ?>"><?=$row["sklNombre"]; ?></td><?php
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			 ?></div></td><?php 
	  ?></tr><?php 
	}
}