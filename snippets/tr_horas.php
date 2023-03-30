<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_horas.php
 * Autor: Benjamin La Madrid
 */
require_once __DIR__."/../common/PDOHoras.php";

function tr_horas_columnas($pdo = null, $limit = 100, $page = 1, $filters = []){	
	foreach (PDOHoras::select($pdo, $limit, $page, $filters) as $hora) { 
		$datetime_mes = new DateTime($hora["hrMes"]);
		$mes = ucfirst(strftime("%b-%Y", ($datetime_mes)->getTimestamp()));
	  
	  ?><tr data-sid="<?=$hora["hrID"]; ?>" data-mes="<?=$hora["hrMes"];?>"><?php
	      ?><td class="no-padding" style="display:none;"><input class="slt-usr full-width" value="<?php echo $filters["usrID"];?>"/></td><?php
		  ?><td style="width: 8rem; min-width: 10rem" class="b-r b-grey p-t-0 p-b-0"><?=$hora["CodigoProyecto"]; ?></td><?php
	      ?><td style="width: 15rem; min-width: 20rem" class="b-r b-grey p-t-0 p-b-0"><?=$hora["Proyecto"]; ?></td><?php
		  ?><td style="width: 8rem; min-width: 11rem" class="b-r b-grey p-t-0 p-b-0 "><?=$hora["Etapa"]; ?></td><?php
		  ?><td style="width: 8rem; min-width: 8rem" class="p-t-0 p-b-0 text-center"><?=$mes; ?></td><?php
			?><td style="width: 6rem; max-width: 6rem" class="no-border no-padding"><div class="flex-container"><?php
					?><button class="btn btn-complete btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Guardar" onclick="system.hours.alGuardar(event)"><i class="fa fa-save"></i></button><?php
					?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.hours.alBorrar(event)"><i class="fa fa-remove"></i></button><?php
			?></div></td><?php
	  ?></tr><?php
	}
}

function tr_horas($pdo = null, $limit = 100, $page = 1, $filters = []){	
	foreach (PDOHoras::select($pdo, $limit, $page, $filters) as $hora) { 
	  ?><tr data-sid="<?=$hora["hrID"]; ?>"><?php
		for ($i = 1; $i <= 31; $i++) { ?>
		  <td style="width: 2.5rem;  min-width: 2.5rem" class="no-padding b-l b-r b-grey" ><?php
			  ?><input class="form-control text-center full-width no-border b-rad-0 ipt-dia ipt-dia<?=$i; ?>" placeholder="0" maxlength="1" max="8" min="0" value="<?=($hora["hrDia".$i] > 0)? $hora["hrDia".$i] : ""; ?>" style="padding: none !important" disabled /><?php
		  ?></td><?php	
		} 
		?></tr><?php
	}
}