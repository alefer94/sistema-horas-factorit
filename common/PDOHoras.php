<?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOHoras.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla horas.
 */
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");
date_default_timezone_set("America/Santiago");

abstract class PDOHoras {

	private const COLUMNAS = [ 
		"hrID", "usrID",   "prjID",   "etapaID", "hrMes",
		"hrDia1",  "hrDia2",  "hrDia3",  "hrDia4",  "hrDia5",  "hrDia6",  "hrDia7",  "hrDia8",  "hrDia9",  "hrDia10",
		"hrDia11", "hrDia12", "hrDia13", "hrDia14", "hrDia15", "hrDia16", "hrDia17", "hrDia18", "hrDia19", "hrDia20",
		"hrDia21", "hrDia22", "hrDia23", "hrDia24", "hrDia25", "hrDia26", "hrDia27", "hrDia28", "hrDia29", "hrDia30", "hrDia31" 
	];
	
    public function select($pdo = null, $limit = 150, $page = 1, $filters = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			is_numeric($limit) && $limit >= 0 &&
			is_numeric($page) && $page > 0 &&
			is_array($filters)){
				
			$query = 
				"SELECT
					usrID, hrID, hrMes, prjID, etapaID,
					hrDia1, hrDia2, hrDia3, hrDia4, hrDia5, hrDia6, hrDia7, hrDia8, hrDia9, hrDia10, 
					hrDia11, hrDia12, hrDia13, hrDia14, hrDia15, hrDia16, hrDia17, hrDia18, hrDia19, hrDia20, 
					hrDia21, hrDia22, hrDia23, hrDia24, hrDia25, hrDia26, hrDia27, hrDia28, hrDia29, hrDia30, hrDia31, 
					(SELECT usrNombre FROM usuarios U WHERE U.usrID = H.usrID) AS 'Usuario', 
					(SELECT prjNombre FROM proyectos P WHERE P.prjID = H.prjID) AS 'Proyecto',
					(SELECT prjCodigo FROM proyectos P WHERE P.prjID = H.prjID) AS 'CodigoProyecto',
					(SELECT cltNombre FROM clientes C, proyectos P WHERE C.cltID = P.cltID AND P.prjID = H.prjID) AS 'Cliente', 
					(SELECT etapaNombre FROM etapas E WHERE E.etapaID = H.etapaID) AS 'Etapa'
				FROM horas H ";
			
			$condition_clause = pdo_filters_array_to_string($filters);
			
			$order_by_clause = "ORDER BY usrID ASC, hrMes DESC, prjID DESC, etapaID DESC ";
			
			$limit_clause = "LIMIT ".($page-1)*$limit.",".$limit;
			

			return $pdo->query($query." ".(!is_null($condition_clause)? $condition_clause." " :"").$order_by_clause." ".$limit_clause);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_sum_horas($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			isset($datos["usrID"]) && is_numeric($datos["usrID"]) && $datos["usrID"] > 0 &&
			isset($datos["hrMes"]) && is_array(date_parse($datos["hrMes"])) &&
			isset($datos["hrID"]) && is_numeric($datos["mes"])) {
				
            return $pdo->query(
                "SELECT
                    SUM(hrDia1)  AS sd1,  SUM(hrDia2)  AS sd2,  SUM(hrDia3)  AS sd3,  SUM(hrDia4)  AS sd4,  SUM(hrDia5)  AS sd5, 
                    SUM(hrDia6)  AS sd6,  SUM(hrDia7)  AS sd7,  SUM(hrDia8)  AS sd8,  SUM(hrDia9)  AS sd9,  SUM(hrDia10) AS sd10, 
                    SUM(hrDia11) AS sd11, SUM(hrDia12) AS sd12, SUM(hrDia13) AS sd13, SUM(hrDia14) AS sd14, SUM(hrDia15) AS sd15, 
                    SUM(hrDia16) AS sd16, SUM(hrDia17) AS sd17, SUM(hrDia18) AS sd18, SUM(hrDia19) AS sd19, SUM(hrDia20) AS sd20, 
                    SUM(hrDia21) AS sd21, SUM(hrDia22) AS sd22, SUM(hrDia23) AS sd23, SUM(hrDia24) AS sd24, SUM(hrDia25) AS sd25, 
                    SUM(hrDia26) AS sd26, SUM(hrDia27) AS sd27, SUM(hrDia28) AS sd28, SUM(hrDia29) AS sd29, SUM(hrDia30) AS sd30, SUM(hrDia31) AS sd31 
                FROM (
                    SELECT usrID, hrMes 
                            , hrDia1,  hrDia2,  hrDia3,  hrDia4,  hrDia5
                            , hrDia6,  hrDia7,  hrDia8,  hrDia9,  hrDia10
                            , hrDia11, hrDia12, hrDia13, hrDia14, hrDia15
                            , hrDia16, hrDia17, hrDia18, hrDia19, hrDia20
                            , hrDia21, hrDia22, hrDia23, hrDia24, hrDia25
                            , hrDia26, hrDia27, hrDia28, hrDia29, hrDia30, hrDia31 
                    FROM horas 
                    WHERE 
                        usrID = ".$datos["usrID"]." AND  
                        hrMes = '".$datos["hrMes"]."' " . ($datos["hrID"]!=0? "AND hrID <> ".$datos["hrID"]." " : "") . ") H
                GROUP BY H.usrID, H.hrMes
                UNION (
                    SELECT 
                        0 AS sd1,  0 AS sd2,  0 AS sd3,  0 AS sd4,  0 AS sd5,
                        0 AS sd6,  0 AS sd7,  0 AS sd8,  0 AS sd9,  0 AS sd10, 
                        0 AS sd11, 0 AS sd12, 0 AS sd13, 0 AS sd14, 0 AS sd15, 
                        0 AS sd16, 0 AS sd17, 0 AS sd18, 0 AS sd19, 0 AS sd20, 
                        0 AS sd21, 0 AS sd22, 0 AS sd23, 0 AS sd24, 0 AS sd25, 
                        0 AS sd26, 0 AS sd27, 0 AS sd28, 0 AS sd29, 0 AS sd30, 0 AS sd31 )
                LIMIT 1"
			);
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
    public function select_sum_horas_dia($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			isset($datos["usrID"]) && is_numeric($datos["usrID"]) && $datos["usrID"] > 0 &&
			isset($datos["año"]) && is_numeric($datos["año"]) &&
			isset($datos["mes"]) && is_numeric($datos["mes"]) &&
			isset($datos["dia"]) && is_numeric($datos["dia"]) )  {
        
			$q_sum_horas = $pdo->query(
                "SELECT
                    SUM(horasDia) AS suma
                FROM (
                    SELECT hrDia".$datos["dia"]." as horasDia
                    FROM horas 
                    WHERE 
                        usrID = ".$datos["usrID"]." AND  
                        hrMes = '".$datos["año"]."-".$datos["mes"]."' 
				) H
                UNION ( SELECT 0 AS suma )
                LIMIT 1"
			);
			$suma = intval($q_sum_horas->fetchColumn(0));
			unset($q_sum_horas);
			return $suma;
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_resumenes_periodos($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" && 
			isset($datos["usrID"]) && is_numeric($datos["usrID"]) && $datos["usrID"] > 0 ) {
			
            return $pdo->query(
                "SELECT
                    usrID,
					hrMes,
                    (
                        SUM(hrDia1) + SUM(hrDia2) + SUM(hrDia3) + SUM(hrDia4) + SUM(hrDia5) +
                        SUM(hrDia6) + SUM(hrDia7) + SUM(hrDia8) + SUM(hrDia9) + SUM(hrDia10) +
                        SUM(hrDia11) + SUM(hrDia12) + SUM(hrDia13) + SUM(hrDia14) + SUM(hrDia15) +
                        SUM(hrDia16) + SUM(hrDia17) + SUM(hrDia18) + SUM(hrDia19) + SUM(hrDia20) +
                        SUM(hrDia21) + SUM(hrDia22) + SUM(hrDia23) + SUM(hrDia24) + SUM(hrDia25) +
                        SUM(hrDia26) + SUM(hrDia27) + SUM(hrDia28) + SUM(hrDia29) + SUM(hrDia30) + SUM(hrDia31)
                    ) AS totalHorasMes
                FROM horas
                    WHERE
                        usrID = ".$datos["usrID"].
						(empty($datos["hrMes"])? "" : " AND hrMes <> '".$datos["hrMes"]."'")." 
				GROUP BY usrID, hrMes 
				ORDER BY hrMes DESC
				LIMIT 5"
			);
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

    public function select_horas_full_key($pdo = null, $datos = []){
        if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			isset($datos["usrID"]) && is_numeric($datos["usrID"]) && $datos["usrID"] > 0 &&
			isset($datos["prjID"]) && is_numeric($datos["prjID"]) && $datos["prjID"] > 0 &&
			isset($datos["etapaID"]) && is_numeric($datos["etapaID"]) && $datos["etapaID"] > 0 &&
			isset($datos["hrMes"]) && is_array(date_parse($datos["hrMes"])) ){
				
            return $pdo->query(
                "SELECT hrID
                FROM horas
                WHERE 
                    usrID = ".$datos["usrID"]." AND
                    hrMes = ".$datos["hrMes"]." AND
                    prjID = ".$datos["prjID"]." AND
                    etapaID = ".$datos["etapaID"]
			);
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
	public function select_horas_from_latest_periodo($pdo = null, $usrID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0 ){

			return $pdo->query(
				"SELECT 
					usrID, hrID, hrMes, prjID, etapaID,
					hrDia1, hrDia2, hrDia3, hrDia4, hrDia5, hrDia6, hrDia7, hrDia8, hrDia9, hrDia10, 
					hrDia11, hrDia12, hrDia13, hrDia14, hrDia15, hrDia16, hrDia17, hrDia18, hrDia19, hrDia20, 
					hrDia21, hrDia22, hrDia23, hrDia24, hrDia25, hrDia26, hrDia27, hrDia28, hrDia29, hrDia30, hrDia31 
				FROM horas 
				WHERE 
					usrID = ".$usrID." AND
					hrMes = (SELECT MAX(hrMes) FROM horas WHERE usrID = ".$usrID.")"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
	
	public function insert($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && count($datos) > 0 ){
				
			if (isset($datos["hrID"])) { unset($datos["hrID"]); }
			$columnas_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			
			if (count($columnas_invalidas) === 0) { 
				return $pdo->insert("horas", $datos);
			}
			else {				
				throw new Exception("Las siguientes columnas de ingreso son erróneas: ". implode(", ", $columnas_invalidas) .".");
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

    public function update($pdo = null, $datos = [], $filtros = []) {
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && count($datos) > 0 &&
			is_array($filtros) && count($filtros) >= 0 ){
			
			if (isset($datos["hrID"])) { unset($datos["hrID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (count($columnas_datos_invalidas) === 0 && count($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("horas", $datos, $filtros);
			}
			else {
				$mensaje_excepcion = "";
				if (count($columnas_datos_invalidas) > 0) {
					throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.");
				}
				
				if (count($columnas_filtros_invalidas) > 0) {
					throw new Exception("Las siguientes columnas de filtrado son erróneas: '".implode("', '", $columnas_filtros_invalidas)."'.");
				}
				
				throw new Exception($mensaje_excepcion);
			}
        }
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

    public function remove($pdo = null, $hrID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($hrID) && $hrID > 0 ){
			
			return $pdo->deleteFrom(
				"horas", 
			  [ "hrID" => $hrID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
}