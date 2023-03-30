<?php
/*
 * Sistema: Gestión de Horas de Colaboradores
 * Cliente: FactorIT
 * Archivo fuente: util/PDOUsuarios.js
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla usuarios.
 */
require_once __DIR__.'/InterfazPDO.php';
require_once __DIR__.'/pdo_filters_array_to_string.php';

abstract class PDOUsuarios {
    
	private const MINIMUN_PASSWORD_LENGTH = 1;
	private const COLUMNAS = [ "usrID", "usrNombre", "usrMail", "usrClave", "tpUsrID", "usrRut", "usrDv", "usrTemaSitio", "usrEstID", "cltIDActual", "usrFechaIngreso", "usrFechaDesvinculacion", "usrEmpresa",  "usrArea", "usrFlagFacturable","vprID","gerID","sgrID","cgcID","tctID","usrCargoContractual","usrNivelHAY"];
	
    /**  Genera un Hash para la clave de usuario indicada, usando el algoritmo estándar a nivel de sistema.
     * @param string $password
     * @return string
     */
    function hashPassword($password){
        return hash("sha256", ("FIT17").$password);
    }
	
    public function select($pdo = null, $limit = 100, $page = 1, $filters = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($page) && $page > 0 &&
			is_array($filters)){

			$query =
				"SELECT 
					usrID, 
					usrNombre, 
					usrMail, 
					tpUsrID, 
					usrRut, 
					usrDv, 
					tpUsrID, 
					usrClave, 
					usrTemaSitio,
					usrEstID, 
					cltIDActual, 
					usrFechaIngreso, 
					usrFechaDesvinculacion,
					usrEmpresa,
					usrArea,
					vprID,
					cgcID,
					tctID,
					usrCargoContractual,
					usrFlagFacturable,
					usrNivelHAY,
					(SELECT usrEstDescripcion FROM usuarios_estados E WHERE E.usrEstID = U.usrEstID) AS usrEstDescripcion,
					(SELECT cgcNombre FROM cargo_generico CG WHERE CG.cgcID = U.cgcID) AS 'cgcNombre',
					(SELECT tctNombre FROM tipo_contratos TC WHERE TC.tctID = U.tctID) AS 'tctNombre',
					(SELECT vprNombre FROM  vicepresidencias VP WHERE VP.vprID = U.vprID) AS 'vprNombre',  
					(SELECT tpUsrNombre FROM tipo_usuarios T WHERE T.tpUsrID = U.tpUsrID) AS 'tpUsrNombre' ,
					gerID, 
					(SELECT gerNombre FROM gerencias G WHERE G.gerID = U.gerID) AS 'gerNombre',
					sgrID, 
					(SELECT sgrNombre FROM subgerencias SG WHERE SG.sgrID = U.sgrID) AS 'sgrNombre'
					
				FROM usuarios U "; 

			if (isset($filters["usrClave"])) { $filters["usrClave"] = self::hashPassword($filters["usrClave"]);  }
			$condition_clause = pdo_filters_array_to_string($filters);
			
			$order_by_clause = "ORDER BY usrNombre";
			if (is_numeric($limit) && $limit >= 0) {
				$limit_clause = "LIMIT ".($page-1)*$limit.",".$limit;
			
				return $pdo->query($query." ".(is_null($condition_clause)? "" : $condition_clause." ").$order_by_clause." ".$limit_clause);
			}
			else {
				return $pdo->query($query." ".(is_null($condition_clause)? "" : $condition_clause." ").$order_by_clause);
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }


	public function select_proveedores($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){

			return $pdo->query(
				"SELECT 
					usrID, 
					usrNombre, 
					usrMail, 
					tpUsrID, 
					usrRut, 
					usrDv, 
					tpUsrID, 
					usrClave, 
					usrTemaSitio,
					usrEstID, 
					cltIDActual, 
					usrFechaIngreso, 
					usrFechaDesvinculacion,
					usrEmpresa,
					usrArea,
					vprID,
					cgcID,
					tctID,
					usrCargoContractual,
					usrFlagFacturable,
					(SELECT usrEstDescripcion FROM usuarios_estados E WHERE E.usrEstID = U.usrEstID) AS usrEstDescripcion,
					(SELECT cgcNombre FROM cargo_generico CG WHERE CG.cgcID = U.cgcID) AS 'cgcNombre',
					(SELECT tctNombre FROM tipo_contratos TC WHERE TC.tctID = U.tctID) AS 'tctNombre',
					(SELECT vprNombre FROM  vicepresidencias VP WHERE VP.vprID = U.vprID) AS 'vprNombre',  
					(SELECT tpUsrNombre FROM tipo_usuarios T WHERE T.tpUsrID = U.tpUsrID) AS 'tpUsrNombre' ,
					gerID, 
					(SELECT gerNombre FROM gerencias G WHERE G.gerID = U.gerID) AS 'gerNombre',
					sgrID, 
					(SELECT sgrNombre FROM subgerencias SG WHERE SG.sgrID = U.sgrID) AS 'sgrNombre'
					
				FROM usuarios U WHERE usrEmpresa  not like 'FactorIT%'"); 

		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_internos($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){

			return $pdo->query(
				"SELECT 
					usrID, 
					usrNombre, 
					usrMail, 
					tpUsrID, 
					usrRut, 
					usrDv, 
					tpUsrID, 
					usrClave, 
					usrTemaSitio,
					usrEstID, 
					cltIDActual, 
					usrFechaIngreso, 
					usrFechaDesvinculacion,
					usrEmpresa,
					usrArea,
					vprID,
					cgcID,
					tctID,
					usrCargoContractual,
					usrFlagFacturable,
					(SELECT usrEstDescripcion FROM usuarios_estados E WHERE E.usrEstID = U.usrEstID) AS usrEstDescripcion,
					(SELECT cgcNombre FROM cargo_generico CG WHERE CG.cgcID = U.cgcID) AS 'cgcNombre',
					(SELECT tctNombre FROM tipo_contratos TC WHERE TC.tctID = U.tctID) AS 'tctNombre',
					(SELECT vprNombre FROM  vicepresidencias VP WHERE VP.vprID = U.vprID) AS 'vprNombre',  
					(SELECT tpUsrNombre FROM tipo_usuarios T WHERE T.tpUsrID = U.tpUsrID) AS 'tpUsrNombre' ,
					gerID, 
					(SELECT gerNombre FROM gerencias G WHERE G.gerID = U.gerID) AS 'gerNombre',
					sgrID, 
					(SELECT sgrNombre FROM subgerencias SG WHERE SG.sgrID = U.sgrID) AS 'sgrNombre'
					
				FROM usuarios U WHERE usrEmpresa like 'FactorIT%'"); 

		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_count($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			$statement = $pdo->query("SELECT COUNT(usrID) FROM usuarios");
			$count = $statement->fetchColumn();
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_tipos($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					tpUsrID, 
					tpUsrNombre 
				FROM tipo_usuarios 
				ORDER BY tpUsrID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_estados($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					usrEstID, 
					usrEstDescripcion 
				FROM usuarios_estados 
				ORDER BY usrEstID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_vicepresidencias($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					vprID, 
					vprNombre 
				FROM vicepresidencias 
				ORDER BY  vprID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_cargo_generico($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					cgcID, 
					cgcNombre 
				FROM cargo_generico
				ORDER BY  cgcID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_tipo_contratos($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					tctID, 
					tctNombre 
				FROM tipo_contratos 
				ORDER BY  tctID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

    public function select_empresas($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT  P.proNombre as usrEmpresa
				FROM proveedores P
				union all
				SELECT E.empNombre
				from empresas E
				"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
	public function select_areas($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"select 'Consultoría' as usrArea from DUAL 
				union 
				 select 'Soporte' as usrArea from DUAL 
				union 
				 select 'Comercial' as usrArea from DUAL"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_estados_facturacion_usua($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"select 'No Facturable' as usrFlagFacturable from DUAL 
				union 
				 select 'Facturable' as usrFlagFacturable from DUAL"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }	
	
    public function insert($pdo = null, $datos = [], $hashearClave = true){
        if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0){
			
			if (isset($datos["usrID"])) { unset($datos["usrID"]); }
			$columnas_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			
			if (count($columnas_invalidas) === 0) {	
				if (empty($datos["usrClave"]) && !empty($datos["usrRut"])) {
					$datos["usrClave"] = $datos["usrRut"];
				}
				if ($hashearClave === true) { 
					$datos["usrClave"] = self::hashPassword($datos["usrClave"]);
				}
				
				return $pdo->insert( "usuarios", $datos );
			}
			else {
				throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_invalidas)."'.");
			}    
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function update($pdo = null, $datos = [], $filtros = [], $hashearClave = true){
        if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 &&
			is_array($filtros) && sizeof($filtros) > 0 &&
			is_bool($hashearClave)){
			
			if (isset($datos["usrID"])) { unset($datos["usrID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (count($columnas_datos_invalidas) === 0 && count($columnas_filtros_invalidas) === 0) {	
				if (isset($datos["usrClave"])){
					if ($datos["usrClave"] === "" && $datos["usrRut"] !== "") {
						$datos["usrClave"] = self::hashPassword($datos["usrRut"]);
					}
					else if ($hashearClave === true) { 
						$datos["usrClave"] = self::hashPassword($datos["usrClave"]);
					}
				}
				return $pdo->update("usuarios", $datos, $filtros);
			}
			else {
				$mensaje_excepcion = "";
				if (count($columnas_datos_invalidas) > 0) {
					$mensaje_excepcion = "Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>";
				}
				
				if (count($columnas_filtros_invalidas) > 0) {
					$mensaje_excepcion = "Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_filtros_invalidas)."'.<br/>";
				}
				
				throw new Exception($mensaje_excepcion);
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function update_clave($pdo = null, $usrID = 0, $usrClave = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0 &&
			is_string($usrClave) && strlen($usrClave) >= self::MINIMUN_PASSWORD_LENGTH){
				
            return $pdo->update(
                "usuarios",
				["usrClave" => self::hashPassword($usrClave)],
                ["usrID" => $usrID ]
            );
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function remove($pdo = null, $usrID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0 ){
			
			return $pdo->deleteFrom(
				"usuarios", 
			  [ "usrID" => $usrID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
}