<?php 

require_once __DIR__."/../common/PDOProyectoUsuario.php";

function tr_proyecto_usuario($pdo = null,$prjID,$esJefe){
	foreach (PDOProyectoUsuario::select($pdo, $prjID) as $row) { 
		if($esJefe>=2){
	  ?><tr data-sid="<?=$row["prjUsrID"]; ?>"><?php
		  ?><td class="b-r b-grey text-center" data-sid="<?=$row["usrID"]; ?>"><?=$row["usrNombre"]; ?></td><?php 
		  ?><td class="b-r b-grey text-center"><?=$row["prjUsrFechaInicioAsignacion"]; ?></td><?php 
		  ?><td class="b-r b-grey text-center"><?=$row["prjUsrFechaFinAsignacion"]; ?></td><?php 
		  ?><td class="b-r b-grey text-center"><?=$row["prjUsrPorcentajeAsignacion"];?><span>  %</span></td><?php 
		  ?><td class="no-border no-padding"><div class="flex-container"><?php
			  ?><button class="btn btn-primary btn-edit b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="system.proyect_usr_col.alEditar(event)"><span class="fa fa-pencil"></span></button><?php
			  ?><button class="btn btn-danger btn-remove b-rad-0 no-border half-width" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar"onclick="system.proyect_usr_col.alBorrar(event)"><span class="fa fa-remove"></span></button><?php
		}
		else{
			?><td class="b-r b-grey text-center" data-sid="<?=$row["usrID"]; ?>"><?=$row["usrNombre"]; ?></td><?php 
			?><td class="b-r b-grey text-center"><?=$row["prjUsrFechaInicioAsignacion"]; ?></td><?php 
			?><td class="b-r b-grey text-center"><?=$row["prjUsrFechaFinAsignacion"]; ?></td><?php 
			?><td class="b-r b-grey text-center"><?=$row["prjUsrPorcentajeAsignacion"];?><span>  %</span></td><?php 

		}
		  ?></div></td><?php 
	  ?></tr><?php 
	}
}