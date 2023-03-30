<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_clientes.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOFeriados2.php";
require_once __DIR__.'/../snippets/options_meses.php';

function tr_feriados($pdo = null, $limit = 100, $page = 1, $filters = []){
	foreach (PDOFeriados2::select($pdo, $limit, $page, $filters) as $row) { 
	  ?><tr data-sid="<?=$row["frdID"]; ?>"><?php
		  ?><td class="p-t-0 p-b-0"><?=$row["frdDescripcion"]; ?></td><?php
		  ?><td class="p-t-0 p-b-0 text-center"><?=$row["frdDia"]; ?></td><?php 
		  ?><td class="p-t-0 p-b-0 text-center"><?=$row["frdMesNombre"]; ?></td><?php 
		  ?><td class="p-t-0 p-b-0 text-center"><?=$row["frdAnio"]; ?></td><?php 
		  ?><td class="p-t-0 p-b-0 text-center" data-sid="<?=$row["empID"]; ?>"><?=$row["empNombre"]; ?></td><?php
		if ($GLOBALS["esUsrJefe"]){ 
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			  ?><button class="btn btn-primary btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.feriado.alEditar(event)"><span class="fa fa-pencil"></span></button><?php
			  ?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar"onclick="system.feriado.alBorrar(event)"><span class="fa fa-remove"></span></button><?php
		  ?></div></td><?php 
		}
	  ?></tr><?php 
	}
}