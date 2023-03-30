<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOClientes.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla clientes.
 */
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");

abstract class PDOFeriados2{

	private const COLUMNAS = ["frdID", "frdDia", "frdMes","frdanio","frdDescripcion","empID"];
    
    public function select($pdo, $limit = 150, $page = 1, $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_numeric($limit) && $limit >= 0 &&
			is_numeric($page) && $page > 0 &&
			is_array($filtros)){
				
			$query = 
				"SELECT 
					frdID,
					frdDia,
					frdMes ,
					CASE WHEN frdMes = 1 THEN 'Enero' 
					WHEN frdMes = 2 THEN 'Febrero' 
					WHEN frdMes = 3 THEN 'Marzo' 
					WHEN frdMes = 4 THEN 'Abril' 
					WHEN frdMes = 5 THEN 'Mayo' 
					WHEN frdMes = 6 THEN 'Junio' 
					WHEN frdMes = 7 THEN 'Julio' 
					WHEN frdMes = 8 THEN 'Agosto' 
					WHEN frdMes = 9 THEN 'Septiembre' 
					WHEN frdMes = 10 THEN 'Octubre' 
					WHEN frdMes = 11 THEN 'Noviembre' 
					WHEN frdMes = 12 THEN 'Diciembre' ELSE 'Otro' END AS frdMesNombre,
					frdAnio,
					frdDescripcion,
					empID, 
					(SELECT empNombre FROM empresas E WHERE E.empID = FF.empID) AS 'empNombre'
				FROM feriados FF ";
			
			$condition_clause = pdo_filters_array_to_string($filtros);

			$order_by_clause = "ORDER BY frdAnio desc ";
			$limit_clause = "LIMIT ".($page-1)*$limit.",".$limit;
			
			return $pdo->query($query." ".(!is_null($condition_clause)? "" : $condition_clause." ").$order_by_clause." ".$limit_clause);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_meses($pdo, $limit = 150, $page = 1, $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_numeric($limit) && $limit >= 0 &&
			is_numeric($page) && $page > 0 &&
			is_array($filtros)){
				
			$query = 
				" SELECT 1 as frdMes, 'Enero' as frdMesNombre from dual
					union
					select 2 as frdMes, 'Febrero' as frdMesNombre from dual
					union
					select 3 as frdMes, 'Marzo' as frdMesNombre from dual
					union
					select 4 as frdMes, 'Abril' as frdMesNombre from dual
					union
					select 5 as frdMes, 'Mayo' as frdMesNombre from dual
					union
					select 6 as frdMes, 'Junio' as frdMesNombre from dual
					union
					select 7 as frdMes, 'Julio' as frdMesNombre from dual
					union
					select 8 as frdMes, 'Agosto' as frdMesNombre from dual
					union
					select 9 as frdMes, 'Septiembre' as frdMesNombre from dual
					union
					select 10 as frdMes, 'Octubre' as frdMesNombre from dual
					union
					select 11 as frdMes, 'Noviembre' as frdMesNombre from dual
					union
					select 12 as frdMes, 'Diciembre' as frdMesNombre from dual";
			
			$condition_clause = pdo_filters_array_to_string($filtros);

			$order_by_clause = "ORDER BY frdMes ";
			$limit_clause = "LIMIT ".($page-1)*$limit.",".$limit;
			
			return $pdo->query($query." ".(!is_null($condition_clause)? "" : $condition_clause." ").$order_by_clause." ".$limit_clause);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	    
    public function insert($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 ){
			
			$columnas_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			
			if (sizeof($columnas_invalidas) === 0) {	
				return $pdo->insert("feriados", $datos);
			}
			else {				
				throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_invalidas)."'.");
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	

    public function update($pdo = null, $datos = [], $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 && 
			is_array($filtros) && sizeof($filtros) > 0){
				
			if (isset($datos["frdID"])) { unset($datos["frdID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (sizeof($columnas_datos_invalidas) === 0 && sizeof($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("feriados", $datos, $filtros);
			}
			else {
				$mensaje_excepcion = "";
				if (sizeof($columnas_datos_invalidas) > 0) {
					$mensaje_excepcion = "Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>";
				}
				
				if (sizeof($columnas_filtros_invalidas) > 0) {
					$mensaje_excepcion .= "Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_filtros_invalidas)."'.";
				}
				
				throw new Exception($mensaje_excepcion);
			}
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function remove($pdo = null, $frdID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($frdID) && $frdID > 0 ){
			
			return $pdo->deleteFrom(
				"feriados", 
			  [ "frdID" => $frdID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
}