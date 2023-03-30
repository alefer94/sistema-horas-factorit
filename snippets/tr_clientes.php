<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_clientes.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOClientes.php";

function tr_clientes($pdo = null, $limit = 100, $page = 1, $filters = []){
	foreach (PDOClientes::select($pdo, $limit, $page, $filters) as $row) { 
	  ?><tr data-sid="<?=$row["cltID"]; ?>"><?php
		  ?><td class="p-t-0 p-b-0"><?=$row["cltNombre"]; ?></td><?php
		  ?><td class="p-t-0 p-b-0 text-center"><?=$row["cltCodigo"]; ?></td><?php 
		  ?><td class="p-t-0 p-b-0 text-center" data-sid="<?=$row["empID"]; ?>"><?=$row["empNombre"]; ?></td><?php
		  ?><td class="p-t-0 p-b-0 text-center" data-sid="<?=$row["indID"]; ?>"><?=$row["indNombre"]; ?></td><?php
		if ($GLOBALS["esUsrJefe"]){ 
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			  ?><button class="btn btn-primary btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.clients.alEditar(event)"><span class="fa fa-pencil"></span></button><?php
			  ?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar"onclick="system.clients.alBorrar(event)"><span class="fa fa-remove"></span></button><?php
		  ?></div></td><?php 
		}
	  ?></tr><?php 
	}
}