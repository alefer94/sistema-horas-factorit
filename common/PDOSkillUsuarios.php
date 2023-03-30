<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOSkillNivel.php
 * Autor: Diego Garcia
 *
 * Clase de abstracción de consultas PDO para operaciones de usuarios skill .
 */
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");

abstract class PDOSkillUsuarios{

	private const COLUMNAS = ["uslID","usrID", "sklID", "lskID"];
    
    public function select($pdo, $usrID) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
				
			$query = 
				"SELECT 
					uslID,
					usrID, 
					sklID,
					(SELECT sklNombre FROM skill S WHERE S.sklID = US.sklID) AS 'sklNombre',
					lskID ,
					(SELECT lskNombre FROM skill_nivel SN WHERE SN.lskID = US.lskID) AS 'lskNombre'
				FROM usuarios_skill US 
				WHERE usrID = ".$usrID."";
			
			return $pdo->query($query);
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
				return $pdo->insert("usuarios_skill", 
					[
						"usrID" => $datos['usrID'],
						"sklID" => $datos['sklID'],
						"lskID" => $datos['lskID']
					]
					);
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
				
			if (isset($datos["uslID"])) { unset($datos["uslID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (sizeof($columnas_datos_invalidas) === 0 && sizeof($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("usuarios_skill", $datos, $filtros);
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
    
    public function remove($pdo = null, $uslID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($uslID) && $uslID > 0 ){
			
			return $pdo->deleteFrom(
				"usuarios_skill", 
			  [ "uslID" => $uslID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_from_user($pdo = null, $usrID = 0){
        if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0){
		
			return $pdo->query(
				"SELECT
					us.usrID,
					us.sklID,
					sk.sklNombre,
					us.lskID,
					sn.lskNombre					
				FROM usuarios_skill us 
					INNER JOIN skill sk on us.sklID = sk.sklID
					INNER JOIN skill_nivel sn on us.lskID = sn.lskID 
				WHERE us.usrID = ".$usrID." 
				ORDER BY us.sklID  DESC"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_count_skill($pdo = null, $sklID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($sklID) && $sklID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(sklID) 
				FROM usuarios_skill 
				WHERE sklID = ".$sklID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
	public function update_skill($pdo = null, $sklID = 0, $skill = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($sklID) && $sklID > 0 &&
			is_array($skill)) {
			
			$pdo->deleteFrom( 
				"usuarios_skill", 
			  [	"sklID" => $sklID ]
			);
			
			$columnas_tabla_skill = ["sklID", "lskNombre"];
			$inserciones = 0;
			foreach ($skill as $i => $skill) {
				if (is_array($skill)){
					$columnas_datos_invalidas = array_diff(array_keys($skill), $columnas_tabla_skill);
					
					if (count($columnas_datos_invalidas) === 0){
						$skill["sklID"] = $sklID;
						$insert_id = $pdo->insert(
							"usuarios_skill",
							$skill
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar un skill entrante.");
				}
			}

			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

}