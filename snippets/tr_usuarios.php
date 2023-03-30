<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: snippets/tr_usuarios.php
 * Autor: Benjamin La Madrid
 *
 */
 
require_once __DIR__."/../common/InterfazPDO.php";
require_once __DIR__."/../common/PDOUsuarios.php";

function is_dv_valid($v) {
	return !empty($v) || $v === "0" || $v === 0;
}

function is_fecha_valid($v) {
	return !empty($v) && $v != '0000-00-00';
}

function tr_usuarios($pdo, $limit = null, $page = 1, $filters = []){
	foreach (PDOUsuarios::select($pdo, $limit, $page, $filters) as $row) { ?>
		<tr data-sid="<?=$row["usrID"]; ?>">
			<td class="b-r b-grey"><?=$row["usrNombre"]; ?></td>
			<td class="b-r b-grey text-center"><?=$row["usrMail"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["tpUsrID"]; ?>"><?=$row["tpUsrNombre"]; ?></td>
			<td class="b-r b-grey"><?=$row["usrCargoContractual"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["cgcID"]; ?>"><?=$row["cgcNombre"]; ?></td>
			<td class="b-r b-grey"><?=$row["usrNivelHAY"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["tctID"]; ?>"><?=$row["tctNombre"]; ?></td>
			<td class="b-r b-grey text-center"><?=(is_dv_valid($row["usrDv"])? $row["usrRut"]."-".$row["usrDv"] : $row["usrRut"]); ?></td>
			<td class="b-r b-grey text-center"><?=$row["usrFechaIngreso"]; ?></td>
			<td class="b-r b-grey text-center"><?=is_fecha_valid($row["usrFechaDesvinculacion"])? $row["usrFechaDesvinculacion"] :  ''; ?></td>
			<td class="b-r b-grey text-center"><?=$row["usrEmpresa"]; ?></td>
			<!--<td class="b-r b-grey text-center"><//$row["usrArea"]; ?></td> -->
			<td class="b-r b-grey text-center"><?=$row["usrFlagFacturable"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["usrEstID"]; ?>"><?=$row["usrEstDescripcion"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["vprID"]; ?>"><?=$row["vprNombre"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["gerID"]; ?>"><?=$row["gerNombre"]; ?></td>
			<td class="b-r b-grey text-center" data-sid="<?=$row["sgrID"]; ?>"><?=$row["sgrNombre"]; ?></td>
			<td class="no-border no-padding flex-container"><?php
			  ?><button class="btn btn-primary btn-edit third-width" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="system.users.alEditar(event)"><i class="fa fa-pencil"></i></button><?php 
			  ?><button class="btn btn-info btn-details third-width" type="button" data-toggle="tooltip" data-placement="top" title="Detalles" onclick="system.users.alVerDetalles(event)"><i class="fa fa-ellipsis-h"></i></button><?php 
			  ?><button class="btn btn-danger btn-remove third-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="system.users.alBorrar(event)"><i class="fa fa-remove" ></i></button><?php
		  ?></td>
		</tr><?php
	}	
}