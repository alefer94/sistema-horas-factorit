<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOClientes.php
 * Autor: Diego Garcia
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla clientes.
 */
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");

abstract class PDOCentroCostos{

	private const COLUMNAS = ["ccoID", "ccoCodigo", "ccoNombre"];
    
    public function select($pdo, $limit = 150, $page = 1, $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_numeric($limit) && $limit >= 0 &&
			is_numeric($page) && $page > 0 &&
			is_array($filtros)){
				
			$query = 
				"SELECT 
					ccoID, 
					ccoCodigo, 
					ccoNombre 
				FROM centro_costos ";
			
			$condition_clause = pdo_filters_array_to_string($filtros);

			$order_by_clause = "ORDER BY ccoID ";
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
				return $pdo->insert("centro_costos", $datos);
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
				
			if (isset($datos["ccoID"])) { unset($datos["ccoID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (sizeof($columnas_datos_invalidas) === 0 && sizeof($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("centro_costos", $datos, $filtros);
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
    
    public function remove($pdo = null, $ccoID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($ccoID) && $ccoID > 0 ){
			
			return $pdo->deleteFrom(
				"centro_costos", 
			  [ "ccoID" => $ccoID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
}