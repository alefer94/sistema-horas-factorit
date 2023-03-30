<?php
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");

abstract class PDOProyectoUsuario{

	private const COLUMNAS = ["prjUsrID","prjID","usrID", "prjUsrFechaInicioAsignacion","prjUsrFechaFinAsignacion","prjUsrPorcentajeAsignacion" ];
	private const COLUMNAS2 = ["prjProID","prjID","usrID", "prjProFechaInicioAsignacion","prjProFechaFinAsignacion","prjProPorcentajeAsignacion", "prjProCosto", "monID" ];
    
    public function select($pdo, $prjID) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
				
			$query = 
				"SELECT 
					prjUsrID,
                    prjID,
                    usrID,
					(SELECT usrNombre FROM usuarios U WHERE U.usrID = PU.usrID) AS 'usrNombre',
                    prjUsrFechaInicioAsignacion,
                    prjUsrFechaFinAsignacion,
                    prjUsrPorcentajeAsignacion
				FROM proyectos_usuarios PU 
				WHERE prjID = ".$prjID." ";
			
			return $pdo->query($query);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}

    }

	public function select_proveedores($pdo, $prjID) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
				
			$query = 
				"SELECT 
					prjProID,
                    prjID,
                    usrID,
					(SELECT usrNombre FROM usuarios U WHERE U.usrID = PP.usrID) AS 'usrNombre',
                    prjProFechaInicioAsignacion,
                    prjProFechaFinAsignacion,
                    prjProPorcentajeAsignacion,
					monID,
					(SELECT monCodigo FROM  monedas M WHERE M.monID = PP.monID) AS 'monCodigo',
					prjProCosto
				FROM proyectos_proveedores PP 
				WHERE prjID = ".$prjID." ";
			
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
				return $pdo->insert("proyectos_usuarios", 
					[
                        "prjID" => $datos['prjID'],
                        "usrID" => $datos['usrID'],
                        "prjUsrFechaInicioAsignacion" => $datos['prjUsrFechaInicioAsignacion'],
                        "prjUsrFechaFinAsignacion" => $datos['prjUsrFechaFinAsignacion'],
                        "prjUsrPorcentajeAsignacion"  => $datos['prjUsrPorcentajeAsignacion']

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
	

	public function insert_proveedores($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 ){
			
			
			$columnas_invalidas = array_diff(array_keys($datos), self::COLUMNAS2);
			
			if (sizeof($columnas_invalidas) === 0) {	
				return $pdo->insert("proyectos_proveedores", 
					[
                        "prjID" => $datos['prjID'],
                        "usrID" => $datos['usrID'],
                        "prjProFechaInicioAsignacion" => $datos['prjProFechaInicioAsignacion'],
                        "prjProFechaFinAsignacion" => $datos['prjProFechaFinAsignacion'],
                        "prjProPorcentajeAsignacion"  => $datos['prjProPorcentajeAsignacion'],
						"prjProCosto" => $datos['prjProCosto'],
						"monID" => $datos['monID']
						

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
				
			if (isset($datos["prjUsrID"])) { unset($datos["prjUsrID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (sizeof($columnas_datos_invalidas) === 0 && sizeof($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("proyectos_usuarios", $datos, $filtros);
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

	public function update_proveedores($pdo = null, $datos = [], $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 && 
			is_array($filtros) && sizeof($filtros) > 0){
				
			if (isset($datos["prjProID"])) { unset($datos["prjProID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS2);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS2);
			
			if (sizeof($columnas_datos_invalidas) === 0 && sizeof($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("proyectos_proveedores", $datos, $filtros);
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
    
    public function remove($pdo = null, $prjUsrID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjUsrID) && $prjUsrID > 0 ){
			
			return $pdo->deleteFrom(
				"proyectos_usuarios", 
			  [ "prjUsrID" => $prjUsrID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }


	public function remove_proveedores($pdo = null, $prjProID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjProID) && $prjProID > 0 ){
			
			return $pdo->deleteFrom(
				"proyectos_proveedores", 
			  [ "prjProID" => $prjProID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
}