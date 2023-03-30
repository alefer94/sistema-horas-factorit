<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOFeriados.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla clientes.
 */
abstract class PDOFeriados{
	
    public function select($pdo = null, $año = "", $mes = "", $emp =""){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_string($año) && (strlen($año) === 4) || empty($año) &&
			is_string($mes) && (strlen($mes) === 2) || empty($mes)){
			
			return $pdo->query(
				"SELECT frdID, frdDia, frdMes, frdAnio, frdDescripcion, empID 
				FROM feriados".
				" WHERE empID = (select empID from empresas e 
				where empNombre = '".$emp."')".
				( ($año!== "" || $mes!== "")?
					(" AND ". //se especifica al menos el año o el mes, aplicamos WHERE
						($año!="" && $mes!=""?  "frdAnio = '".$año."' AND frdMes = '".$mes."'" //mes y año
							: ( $año!=""? "frdAnio = '".$año."'" : "frdMes = '".$mes."'" )  ) //sólo el año o sólo el mes
					)
					:  "" //ni año ni mes, no hay filtro WHERE
				)
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
    public function select_count($pdo = null, $año = "", $mes = ""){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			(is_numeric($año) || empty($año)) &&
			(is_numeric($mes) || empty($mes))){
			
			$statement = $pdo->query(
				"SELECT COUNT(frdID) 
				FROM feriados".
				( ($año!="" || $mes!="")?
					(" WHERE ". //se especifica al menos el año o el mes, aplicamos WHERE
						($año!="" && $mes!=""?  "frdAnio = $año AND frdMes = $mes" //mes y año
							: ( $año!=""? "frdAnio = $año" : "frdMes = $mes" )  ) //sólo el año o sólo el mes
					)
					:  "" //ni año ni mes, no hay filtro WHERE
				).
				" UNION SELECT 0 LIMIT 1"
			);
			$count = $statement->fetchColumn();
			$statement->closeCursor();
			return intval($count);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function is_date_feriado($pdo = null, $año = "", $mes = "", $dia = ""){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_string($año) &&
			is_string($mes) &&
			is_string($dia)){
		
			$now = time();
			if (empty($año)) { $año = date("Y", $now); }
			if (empty($mes)) { $mes = date("m", $now); }
			if (empty($dia)) { $dia = date("d", $now); }
			
			$query_feriado = $pdo->query(
				"SELECT 1
					FROM feriados 
					WHERE frdAnio = $año AND frdMes = $mes AND frdDia = $dia
				UNION (SELECT 0)"
			);
			
			$es_feriado = (intval($query_feriado->fetchColumn(0)) === 1);
			unset($query_feriado);
			return $es_feriado;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function update_año($pdo = null, $frdAnio = "", $feriados = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_numeric($frdAnio) && strlen(strval($frdAnio)) === 4 &&
			is_array($feriados)){
			
			$pdo->deleteFrom(
				"feriados",
			  [	"frdAnio" => $frdAnio ]
			);
			
			$inserciones = 0;
			if (count($feriados) > 0){
				foreach ($feriados as $frd) {
					$insert_id = $pdo->insert(
						"feriados",
					  [ "frdAnio" => $frdAnio, 
						"frdMes" => $frd[0], 
						"frdDia" => $frd[1], 
						"frdDescripcion" => $frd[2] ]
					);
					if ($insert_id > 0) { $inserciones++; }
				}
			}
			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }	
}