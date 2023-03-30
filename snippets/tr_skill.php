<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_skill.php
 * Autor: Diego Garcia
 */
require_once __DIR__."/../common/PDOSkill.php";

function tr_skill($pdo = null, $limit = 200, $page = 1, $filters = []){
	foreach (PDOSkill::select($pdo, $limit, $page, $filters) as $row) { 
	  ?><tr data-sid="<?=$row["sklID"]; ?>"><?php
		  ?><td class="p-t-0 p-b-0"><?=$row["sklNombre"]; ?></td><?php
		  
		if ($GLOBALS["esUsrJefe"]){ 
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			  ?><button class="btn btn-primary btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.skill.alEditar(event)"><span class="fa fa-pencil"></span></button><?php
			  ?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar"onclick="system.skill.alBorrar(event)"><span class="fa fa-remove"></span></button><?php
		  ?></div></td><?php 
		}
	  ?></tr><?php 
	}
}