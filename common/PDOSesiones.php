<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOSesiones.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla sesiones.
 */
date_default_timezone_set("America/Santiago");

abstract class PDOSesiones {
	
	const SESSION_LIFETIME = 7200;
	
	/**
     * Genera un Hash para el string de sesión de usuario indicada, usando el algoritmo estándar a nivel de sistema.
     * @param string $sesion
     * @return string
     */
    public function hashSession($sesion){
        return hash("sha256", ("FactorIT2018Hash").$sesion);
    }
	
	/** Obtiene el ID del usuario ligado al hash de sesión entregado, si dicha sesión está vigente. */
	public function validate_user_from_session_hash($pdo = null, $ssnHash = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_string($ssnHash) && strlen($ssnHash) === 64){
			
			$ssnHoraFecha = date("Y-m-d H:i:s");
			
			$statement = $pdo->query(
				"SELECT usrID 
				FROM sesiones 
				WHERE 
					ssnHash = '".$ssnHash."' 
					AND TIME_TO_SEC(TIMEDIFF('".$ssnHoraFecha."', ssnHoraFecha)) < ".self::SESSION_LIFETIME." 
				UNION SELECT 0
				LIMIT 1"
			);
			$id = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $id;
		}
		else {
			throw new Exception("Fallo al validar los parámetros para PDOSesiones::validate_user_from_session_hash");
		}
	}
    
    public function insert($pdo = null, $usrID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0){
				
			$time = date(DateTime::ISO8601);
			$sessionString = strval($usrID)."=".$time;
			$sessionHash = self::hashSession($sessionString);
			
			$insert_id = $pdo->insert( 
				"sesiones", 
			  	[ 
				  	"usrID" => $usrID, 
					"ssnHoraFecha" => $time, 
					"ssnHash" => $sessionHash 
				]
			);
			
			if ($insert_id > 0){
				return $sessionHash;
			}
			else {
				return null;
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros para PDOSesiones::insert");
		}
    }
}
?>