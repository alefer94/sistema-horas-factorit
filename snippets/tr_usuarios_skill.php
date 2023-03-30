<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_clientes.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOSkillUsuarios.php";

function tr_usuarios_skill($pdo = null,$usrID){
	foreach (PDOSkillUsuarios::select($pdo, $usrID) as $row) { 
	  ?><tr data-sid="<?=$row["uslID"]; ?>"><?php
		  ?><td class="b-r b-grey text-center" data-sid="<?=$row["sklID"]; ?>"><?=$row["sklNombre"]; ?></td><?php
		  ?><td class="b-r b-grey text-center" data-sid="<?=$row["lskID"]; ?>"><?=$row["lskNombre"]; ?></td><?php 
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			  ?><button class="btn btn-primary btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.usr_skill.alEditar(event)"><span class="fa fa-pencil"></span></button><?php
			  ?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar"onclick="system.usr_skill.alBorrar(event)"><span class="fa fa-remove"></span></button><?php
		  ?></div></td><?php 
	  ?></tr><?php 
	}
}