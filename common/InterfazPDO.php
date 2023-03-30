<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/InterfazPDO.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción para consultas a MySQL con driver PDO.
 * La conexión debe ser previamente configurada por un archivo InterfazPDO.ini, ubicado en el mismo directorio que éste.
 */
require_once __DIR__.'/pdo_filters_array_to_string.php';

final class InterfazPDO {
    private static $originPDO = null;
	
    /** Genera una conexión */
    private function getConnection() : PDO {
        if (self::$originPDO === null){
			$ini_array = parse_ini_file(__DIR__."/InterfazPDO.ini", true);
			$pdo_settings = $ini_array["PDO"];
			unset($ini_array);

            $db = $pdo_settings["database"];
            $host = $pdo_settings["host"];
            $charset = $pdo_settings["charset"];
            self::$originPDO = new PDO(
                "mysql:dbname=$db; host=$host; charset=$charset",
                $pdo_settings["username"],
                $pdo_settings["password"]
            );
			self::$originPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$originPDO;
    }
	
	/** Ejecuta una query literal */
    public function query($sql) : PDOStatement {
		$connection = $this->getConnection();
        return $connection->query($sql);
    }
	
	/** Inserta los datos de un array asociativo a la tabla especificada.
	 * Devuelve el ID de la fila ingresada, o -1 si los parámetros entregados no son válidos.
	 */
    public function insert($tabla = null, $datos = []) : int {
        if (is_string($tabla) && !empty($tabla) &&
			is_array($datos) && count($datos) > 0) {
			
			$size_datos = count($datos);
			$string_columnas = "(";
			$string_values = "(";
			$i = 0;
			
			foreach ($datos as $col => $val){ 
				$i++;
				$string_columnas .= $col;
				$string_values .= ":".$col."";
				if ($i < $size_datos){
					$string_columnas .= ",";
					$string_values .= ",";
				}
				else {
					$string_columnas .= ")";
					$string_values .= ")";
				}
			}
			
			$sql = "INSERT INTO ".$tabla." ".$string_columnas." VALUES ".$string_values;			
			unset($size_datos, $string_columnas, $string_values, $i, $tabla);
			
			$connection = $this->getConnection();
			$stm_id = $connection->prepare("SELECT LAST_INSERT_ID()");
			$statement = $connection->prepare($sql);
			foreach ($datos as $col => $val){
				$statement->bindValue(":".$col, $val);
			}
			unset($datos);
			
			$connection->beginTransaction();
			$statement->execute();
			$stm_id->execute();
			$id = $stm_id->fetchColumn();
			$connection->commit();
			$stm_id->closeCursor();
			$statement->closeCursor();
			return intval($id);
		}
		else {
			return -1;
		}
    }
	
	/** Actualiza filas de una tabla con los datos de un array asociativo. 
	 * Puede filtrar las filas a actualizar con un segundo array asociativo.  */
    public function update($tabla = null, $datos = [], $filtros = []) {
        if (is_string($tabla) && !empty($tabla) &&
			is_array($datos) && count($datos) > 0 &&
			is_array($filtros)) {
			
			$size_datos = count($datos);
			$string_datos = "";
			$i = 0;
			
			foreach ($datos as $col => $val){ 
				$i++;
				$string_datos .= $col." = :".$col;
				if ($i < $size_datos){
					$string_datos .= ", ";
				}
			}
			$string_condiciones = pdo_filters_array_to_string($filtros);
			
			$sql = "UPDATE ".$tabla." SET ".$string_datos. (is_null($string_condiciones)? "" : " ".$string_condiciones);
			unset($size_datos, $string_datos, $string_condiciones, $i, $tabla, $filtros);
			
			$connection = $this->getConnection();
			$statement = $connection->prepare($sql);
			foreach ($datos as $col => $val){
				$statement->bindValue(":".$col, $val);
			}
			unset($datos);
			
			$connection->beginTransaction();
			$statement->execute();
			$actualizadas = $statement->rowCount();
			$connection->commit();
			$statement->closeCursor();
			return $actualizadas;
		}
		else {
			return -1;
		}
	}
	
	
    public function deleteFrom($tabla = null, $filtros = []) {
        if (is_string($tabla) && !empty($tabla) &&
			is_array($filtros)) {
			
			$string_condiciones = pdo_filters_array_to_string($filtros);
			
			$sql = "DELETE FROM ".$tabla. (is_null($string_condiciones)? "" : " ".$string_condiciones);
			unset($string_condiciones, $tabla, $datos, $filtros);
			
			$connection = $this->getConnection();
			$statement = $connection->prepare($sql);
			
			$connection->beginTransaction();
			$statement->execute();
			$eliminadas = $statement->rowCount();
			$connection->commit();
			$statement->closeCursor();
			return $eliminadas;
		}
		else {
			return -1;
		}
	}
}
?>