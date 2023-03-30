 <?php
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/PDOProyectos.php
 * Autor: Benjamin La Madrid
 *
 * Clase de abstracción de consultas PDO para operaciones con la tabla proyectos.
 */
require_once __DIR__.'/pdo_filters_array_to_string.php';
setlocale(LC_TIME, "");
date_default_timezone_set("America/Santiago");

abstract class PDOProyectos{

	private const COLUMNAS = ["prjID", "prjNombre", "prjCodigo", "prjHorasEstimadas", "prjDescripcion", "prjFechaInicio", "prjFechaTermino", "prjFechaTerminoEst", "prjFechaTerminoDes", "prjFlagFacturacion", "prjEstadoProyecto", "prjFlagAutoComercial", "prjNombreAutoComercial", "prjFechaAutoComercial", "cltID", "usrIDJefe","prjCostoVenta","prjMargenEsperado","ccoID","vprID","gerID","sgrID","prjBonos","prjProveedor","prjFreelance","prjInterno","usrIDComercial","prjCodigoPadre"];
	
    public function select($pdo = null, $limit = 150, $page = 1, $filters = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($limit) && $limit >= 0 &&
			is_numeric($page) && $page > 0 &&
			is_array($filters)){
						
			$query = 
				"SELECT 
				prjID, 
				prjNombre, 
				prjCodigo, 
				prjHorasEstimadas,
				prjDescripcion,
				prjFechaInicio, 
				prjFechaTerminoEst, 
				prjFechaTermino,
				prjFechaTerminoDes, 
				prjFlagFacturacion, 
				prjEstadoProyecto, 
				prjFlagAutoComercial, 
				prjNombreAutoComercial, 
				prjFechaAutoComercial,
				prjBonos,
				prjProveedor,
	            prjFreelance,
				prjInterno,			
				cltID, 
				prjCostoVenta,
				prjMargenEsperado,
				prjCodigoPadre,
				(SELECT cltNombre FROM clientes C WHERE C.cltID = P.cltID) AS 'cltNombre', 
				usrIDJefe,
				(SELECT usrNombre FROM usuarios U WHERE U.usrID = P.usrIDJefe) AS 'usrNombreJefe',
				ccoID,
				(SELECT ccoNombre FROM centro_costos CC WHERE CC.ccoID = P.ccoID) AS 'ccoNombre',
				vprID,
				(SELECT vprNombre FROM  vicepresidencias VP WHERE VP.vprID = P.vprID) AS 'vprNombre',  
				gerID, 
				(SELECT gerNombre FROM gerencias G WHERE G.gerID = P.gerID) AS 'gerNombre',
				sgrID, 
				(SELECT sgrNombre FROM subgerencias SG WHERE SG.sgrID = P.sgrID) AS 'sgrNombre',
				usrIDComercial,
				(SELECT usrNombre FROM usuarios U WHERE U.usrID = P.usrIDComercial) AS 'usrNombreComercial'
			FROM proyectos P";
			
			$condition_clause = pdo_filters_array_to_string($filters);
			
			$order_by_clause = "ORDER BY prjID DESC";
			$limit_clause = "LIMIT ".($page-1)*$limit.",".$limit;
			
			return $pdo->query($query." ".(is_null($condition_clause)? "" : $condition_clause." ").$order_by_clause." ".$limit_clause);
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
					prjID,
					prjNombre,
					prjCodigo,
					prjHorasEstimadas,
					(
						SELECT COALESCE( 
							SUM(hrDia1) + SUM(hrDia2) + SUM(hrDia3) + SUM(hrDia4) + SUM(hrDia5) +
							SUM(hrDia6) + SUM(hrDia7) + SUM(hrDia8) + SUM(hrDia9) + SUM(hrDia10) +
							SUM(hrDia11) + SUM(hrDia12) + SUM(hrDia13) + SUM(hrDia14) + SUM(hrDia15) +
							SUM(hrDia16) + SUM(hrDia17) + SUM(hrDia18) + SUM(hrDia19) + SUM(hrDia20) +
							SUM(hrDia21) + SUM(hrDia22) + SUM(hrDia23) + SUM(hrDia24) + SUM(hrDia25) +
							SUM(hrDia26) + SUM(hrDia27) + SUM(hrDia28) + SUM(hrDia29) + SUM(hrDia30) + SUM(hrDia31)
						,0) FROM horas H WHERE H.prjID = P.prjID AND H.usrID = ".$usrID."
						UNION SELECT 0
						LIMIT 1
					) AS hrsUsuarioProyecto,
					cltID,
					(SELECT cltNombre FROM clientes C WHERE C.cltID = P.cltID) AS 'cltNombre',
					(SELECT usrNombre FROM usuarios U WHERE U.usrID = P.usrIDJefe) AS 'usrNombreJefe'
				FROM proyectos P 
				WHERE usrIDJefe = ".$usrID." 
				OR EXISTS ( 
					SELECT 1 FROM proyectos_usuarios PU 
					WHERE PU.usrID = ".$usrID." AND PU.prjID = P.prjID 
				)
				
				ORDER BY prjID DESC"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_proy_from_user($pdo = null, $usrID = 0){
        if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($usrID) && $usrID > 0){
		
			return $pdo->query(
				"SELECT
				prjID,
				prjNombre,
				prjCodigo,
				(
						SELECT COALESCE( 
							SUM(hrDia1) + SUM(hrDia2) + SUM(hrDia3) + SUM(hrDia4) + SUM(hrDia5) +
							SUM(hrDia6) + SUM(hrDia7) + SUM(hrDia8) + SUM(hrDia9) + SUM(hrDia10) +
							SUM(hrDia11) + SUM(hrDia12) + SUM(hrDia13) + SUM(hrDia14) + SUM(hrDia15) +
							SUM(hrDia16) + SUM(hrDia17) + SUM(hrDia18) + SUM(hrDia19) + SUM(hrDia20) +
							SUM(hrDia21) + SUM(hrDia22) + SUM(hrDia23) + SUM(hrDia24) + SUM(hrDia25) +
							SUM(hrDia26) + SUM(hrDia27) + SUM(hrDia28) + SUM(hrDia29) + SUM(hrDia30) + SUM(hrDia31)
						,0) FROM horas H WHERE H.prjID = P.prjID AND H.usrID = ".$usrID."
						UNION SELECT 0
						LIMIT 1
					) AS hrsUsuarioProyecto,
				cltID,
				(SELECT cltNombre FROM clientes C WHERE C.cltID = P.cltID) AS 'cltNombre',
				(SELECT usrNombre FROM usuarios U WHERE U.usrID = P.usrIDJefe) AS 'usrNombreJefe'
			FROM proyectos P 
			where prjEstadoProyecto != 'Cerrado'
			and EXISTS ( 
				SELECT 1 FROM proyectos_usuarios PU 
				WHERE PU.usrID = ".$usrID." AND PU.prjID = P.prjID 
			)
			ORDER BY prjID desc"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
    public function select_etapas($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT 
					etapaID,  
					etapaNombre  
				FROM etapas
				ORDER BY etapaID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_jefes($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT
					usrID,
					usrNombre,
					usrMail
				FROM usuarios
				WHERE tpUsrID = 2 AND usrEstID=1
				ORDER BY usrNombre"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_comercial($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT usrID,
				usrNombre,
				usrMail
				FROM usuarios
				WHERE (vprID = 3 AND (usrFechaDesvinculacion = '0000-00-00' or usrFechaDesvinculacion is null)
				or gerID in (11,12,13,14))
				ORDER BY usrNombre"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_gerentes($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"SELECT
					usrID,
					usrNombre,
					usrMail
				FROM usuarios
				WHERE tpUsrID = 3
				ORDER BY usrNombre"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_colaboradores($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT  
					usrID,
					(SELECT usrNombre FROM usuarios U WHERE U.usrID = P.usrID) AS usrNombre,
					(
						SELECT 
							(SUM(hrDia1) + SUM(hrDia2) + SUM(hrDia3) + SUM(hrDia4) + SUM(hrDia5) + 
							SUM(hrDia6) + SUM(hrDia7) + SUM(hrDia8) + SUM(hrDia9) + SUM(hrDia10) + 
							SUM(hrDia11) + SUM(hrDia12) + SUM(hrDia13) + SUM(hrDia14) + SUM(hrDia15) + 
							SUM(hrDia16) + SUM(hrDia17) + SUM(hrDia18) + SUM(hrDia19) + SUM(hrDia20) + 
							SUM(hrDia21) + SUM(hrDia22) + SUM(hrDia23) + SUM(hrDia24) + SUM(hrDia25) + 
							SUM(hrDia26) + SUM(hrDia27) + SUM(hrDia28) + SUM(hrDia29) + SUM(hrDia30) + SUM(hrDia31)) 
						FROM horas H 
						WHERE 
							H.usrID = P.usrID AND 
							H.prjID = P.prjID  
						GROUP BY usrID, prjID   
						UNION SELECT 0
						LIMIT 1) AS usrSumHoras
				FROM (
						SELECT prjID, usrID FROM proyectos_usuarios WHERE prjID = ".$prjID." 
			 			
					) P 
				WHERE usrID IS NOT NULL 
				ORDER BY usrNombre"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
    public function select_count_colaboradores($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(usrID)
				FROM proyectos_usuarios P 
				WHERE prjID = ".$prjID." 
				LIMIT 1"
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_non_colaboradores($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT
					usrID,
					usrNombre
				FROM usuarios U 
				WHERE NOT EXISTS (
					SELECT 1
					FROM proyectos_usuarios PU
					WHERE  
						PU.prjID = ".$prjID." AND
						PU.usrID = U.usrID
				) AND NOT EXISTS (
					SELECT 1
					FROM proyectos P
					WHERE P.prjID = ".$prjID." AND P.usrIDJefe = U.usrID
				)
				ORDER BY 1"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_planeacion($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					prjPlnHrsID, 
					etapaID, 
					(SELECT etapaNombre FROM etapas E WHERE E.etapaID = P.etapaID) AS etapaNombre, 
					prjPlnHrs 
				FROM proyectos_planeacion P 
				WHERE prjID = ".$prjID." 
				ORDER BY prjPlnHrsID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_usuarios($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
				    pjrUsrID,
					prjID,
					usrID,
					usrNombre, 
					prjUsrFechaInicioAsignacion, 
					prjUsrFechaFinAsignacion, 
					prjUsrPorcentajeAsignacion 
				FROM proyectos_usuarios 
				WHERE prjID = ".$prjID." 
				ORDER BY cmbID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    
    public function select_cambios($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					cmbID, 
					cmbNombre, 
					cmbFecha, 
					cmbDescripcion, 
					cmbHoras 
				FROM proyectos_cambios 
				WHERE prjID = ".$prjID." 
				ORDER BY cmbID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
    public function select_count_cambios($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(cmbID) 
				FROM proyectos_cambios 
				WHERE prjID = ".$prjID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
    public function select_hitos($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					htID, 
					htDescripcion, 
					htFechaTermino,
					htFechaProvision,
					htFechaConformidad,
					htPorcentaje,
					htUF,
					monID,
					htFactura,
					htFechaFacturacion,
					(SELECT monCodigo FROM monedas M WHERE M.monID = P.monID) AS 'monCodigo',
					htEstID,
					(SELECT htEstDescripcion FROM hitos_estados HE WHERE HE.htEstID = P.htEstID) AS 'htEstDescripcion'
				FROM proyectos_hitos P 
				WHERE prjID = ".$prjID." 
				ORDER BY htID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
	public function select_count_hitos($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(htID) 
				FROM proyectos_hitos 
				WHERE prjID = ".$prjID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}

	public function select_estimacion($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					estID, 
					estRol, 
					estSeniority,
					estMes,
					estSemana,
					estFechaInicio,
					estFechaFin,
					estHoras,
					cgcID,
					(SELECT cgcNombre FROM cargo_generico C WHERE C.cgcID = P.cgcID) AS 'cgcNombre',
					bnsID,
					(SELECT bnsCodigo FROM bandas_salariales B WHERE B.bnsID = P.bnsID) AS 'bnsCodigo'
				FROM proyectos_estimacion P 
				WHERE prjID = ".$prjID." 
				ORDER BY estID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }


	public function select_count_estimacion($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(estID) 
				FROM proyectos_estimacion
				WHERE prjID = ".$prjID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}

	public function select_bandas_salariales($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			
			return $pdo->query(
				"SELECT 
					bnsID, 
					bnsCodigo, 
					bnsNombre
					FROM bandas_salariales
					ORDER BY bnsID"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

    
	public function select_count_fechas($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(prjFechaInicio) 
				FROM proyectos
				WHERE prjID = ".$prjID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}

    public function select_avances($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					advID, 
					advFecha, 
					advDescripcion, 
					advPorcEsperado, 
					advPorcReal 
				FROM proyectos_avances U 
				WHERE prjID = ".$prjID." 
				ORDER BY advFecha"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
	
    public function select_count_avances($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(advID)
				FROM proyectos_avances U 
				WHERE prjID = ".$prjID." "
			);

			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
	
	public function select_estados_proy($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"select 'No iniciado' as prjEstado from DUAL 
				union
				select 'En curso' as prjEstado from DUAL 
				union 
				select 'Cerrado' as prjEstado from DUAL
				union 
				select 'Fact. Pend.' as prjEstado from DUAL
				union 
				select 'Suspendi' as prjEstado from DUAL
				union 
				select 'Abierto' as prjEstado from DUAL"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function select_estados_facturacion_proy($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"select 'Facturable' as prjFlagFacturable from DUAL
                union
                select 'No Facturable / Inversión' as prjFlagFacturable from DUAL
                union
                select 'No Facturable / Legal' as prjFlagFacturable from DUAL
                union
                select 'No Facturable / Operación' as prjFlagFacturable from DUAL
                union
                select 'No Facturable / Sin Costo Empresa' as prjFlagFacturable from DUAL
                union
                select 'No Aplica' as prjFlagFacturable from DUAL"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
	
	public function select_estados_hitos($pdo = null){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO"){
			return $pdo->query(
				"select htEstID, htEstDescripcion from hitos_estados"
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

	public function select_bitacoras($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			return $pdo->query(
				"SELECT 
					bitID, 
					prjID,
					bitFecha, 
					bitDescripcion, 
					bitHora 
				FROM proyectos_bitacoras U 
				WHERE prjID = ".$prjID." 
				ORDER BY bitFecha"
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}

	public function select_count_bitacoras($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0){
			
			$statement = $pdo->query(
				"SELECT COUNT(bitID) 
				FROM proyectos_bitacoras 
				WHERE prjID = ".$prjID
			);
			$count = intval($statement->fetchColumn());
			$statement->closeCursor();
			return $count;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
		
    public function insert($pdo = null, $datos = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 ){
			
			$columnas_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			
			if (count($columnas_invalidas) === 0) {	
				$prjID = $pdo->insert("proyectos", $datos);
				
				//Esto debajo también puede ser un trigger SQL
				if (is_numeric($prjID) && $prjID != 0) { 
					$pdo->insert(
						"proyectos_cambios", 
					  	[ 
							"prjID" => $prjID, 
							"cmbNombre" => "Planeación Inicial", 
							"cmbFecha" => "CURRENT_TIME"
						]
					);
				}
				return $prjID;
			}
			else {
				throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_invalidas)."'.");
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
	public function update($pdo = null, $datos = [], $filtros = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_array($datos) && sizeof($datos) > 0 && 
			is_array($filtros) && sizeof($filtros) > 0){
				
			if (isset($datos["prjID"])) { unset($datos["prjID"]); }
			$columnas_datos_invalidas = array_diff(array_keys($datos), self::COLUMNAS);
			$columnas_filtros_invalidas = array_diff(array_keys($filtros), self::COLUMNAS);
			
			if (count($columnas_datos_invalidas) === 0 && count($columnas_filtros_invalidas) === 0) {	
				return $pdo->update("proyectos", $datos, $filtros);
			}
			else {
				$mensaje_excepcion = "";
				if (count($columnas_datos_invalidas) > 0) {
					$mensaje_excepcion = "Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>";
				}
				
				if (count($columnas_filtros_invalidas) > 0) {
					$mensaje_excepcion .= "Las siguientes columnas de filtrado son erróneas: '".implode("', '", $columnas_filtros_invalidas)."'.";
				}
				
				throw new Exception($mensaje_excepcion);
			}
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
  }
    
	//POR HACER: ALGORITMO EFICIENTE DE ACTUALIZACION
	public function update_colaboradores($pdo = null, $prjID = 0, $colaboradores = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			((is_array($colaboradores) && count($colaboradores) > 0) || is_bool($colaboradores))) {
				
			$pdo->deleteFrom( 
				"proyectos_usuarios", 
				[ "prjID" => $prjID ] 
			);
			$inserciones = 0;
			
			if (is_array($colaboradores)){
				foreach ($colaboradores as $usrID) {
					$inserted_id = $pdo->insert( 
						"proyectos_usuarios", 
						[ 
							"prjID" => $prjID, 
							"usrID" => $usrID	
						] 
					);
					
					if ($inserted_id > 0)  {
						$inserciones++;
					}
				}
			}
			
			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}

	public function update_proveedores($pdo = null, $prjID = 0, $proveedores = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			((is_array($proveedores) && count($proveedores) > 0) || is_bool($proveedores))) {
				
			$pdo->deleteFrom( 
				"proyectos_proveedores", 
				[ "prjID" => $prjID ] 
			);
			$inserciones = 0;
			
			if (is_array($proveedores)){
				foreach ($proveedores as $usrID) {
					$inserted_id = $pdo->insert( 
						"proyectos_proveedores", 
						[ 
							"prjID" => $prjID, 
							"usrID" => $usrID	
						] 
					);
					
					if ($inserted_id > 0)  {
						$inserciones++;
					}
				}
			}
			
			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
	
	public function update_planeacion_inicial($pdo = null, $prjID = 0, $planeacion = []){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			is_array($planeacion) && sizeof($planeacion) > 0) {
				
				$total_horas = 0;
				foreach ($planeacion as $i => $val){
					$inserted_id = $pdo->insert(
						"proyectos_planeacion",
						[ 
							"prjID" => $prjID, 
							"etapaID" => $i+1,
							"prjPlnHrs" => $val 
						]
					);
					if ($inserted_id > 0) {
						$total_horas += $val;
					}
				}
				
				//esto podría ser un trigger
				$pdo->update(
					"proyectos_cambios",
					[ "cmbHoras" => $total_horas ],
					[ 
						"prjID" => $prjID, 
						"cmbNombre" => "Planeación Inicial" 
					]
				);
				
				$pdo->update(
					"proyectos",
					[ "prjHorasEstimadas" => $total_horas ],
					[ "prjID" => $prjID ]
				);
				
				return $total_horas;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
    
	public function update_cambios($pdo = null, $prjID = 0, $cambios = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
				is_numeric($prjID) && $prjID > 0 &&
				is_array($cambios)) {
			
			$pdo->deleteFrom( 
				"proyectos_cambios", 
				[	
					"prjID" => $prjID, 
					"cmbNombre" => ["NOT EQUALS", "Planeación Inicial"]	
				]
			);

			$columnas_tabla_cambios = ["cmbID", "cmbNombre", "cmbHoras"];
			$inserciones = 0;
			foreach ($cambios as $i => $cambio) {
				if (is_array($cambio)){
					$columnas_datos_invalidas = array_diff(array_keys($cambio), $columnas_tabla_cambios);
					
					if (count($columnas_datos_invalidas) === 0){
						$cambio["prjID"] = $prjID;
						$insert_id = $pdo->insert(
							"proyectos_cambios",
							$cambio
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar un control de cambio entrante.");
				}
			}
			
			$q_prjHorasEstimadas = $pdo->query(
				"SELECT SUM(cmbHoras) 
				FROM proyectos_cambios 
				WHERE prjID = ".$prjID." 
				GROUP BY prjID 
				UNION SELECT 0 
				LIMIT 1"
			);
			$prjHorasEstimadas = intval($q_prjHorasEstimadas->fetchColumn());
			$q_prjHorasEstimadas->closeCursor();
			unset($q_prjHorasEstimadas);
			
			$pdo->update(
				"proyectos", 
			  [ "prjHorasEstimadas" => $prjHorasEstimadas],
			  [ "prjID" => $prjID ]
			);
			
			return $inserciones;	
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}
    
	public function update_hitos($pdo = null, $prjID = 0, $hitos = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			is_array($hitos)) {
			
			$pdo->deleteFrom( 
				"proyectos_hitos", 
			  [	"prjID" => $prjID ]
			);
			
			$columnas_tabla_hitos = ["htID", "htDescripcion", "htFechaTermino","htFechaProvision","htFechaConformidad", "htPorcentaje", "htUF", "monID", "htEstID","htFactura", "htFechaFacturacion"];
			$inserciones = 0;
			foreach ($hitos as $i => $hito) {
				if (is_array($hito)){
					$columnas_datos_invalidas = array_diff(array_keys($hito), $columnas_tabla_hitos);
					
					if (count($columnas_datos_invalidas) === 0){
						$hito["prjID"] = $prjID;
						$insert_id = $pdo->insert(
							"proyectos_hitos",
							$hito
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar un hito entrante.");
				}
			}

			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function update_estimacion($pdo = null, $prjID = 0, $estimacion = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			is_array($estimacion)) {
			
			$pdo->deleteFrom( 
				"proyectos_estimacion", 
			  [	"prjID" => $prjID ]
			);
			
			$columnas_tabla_estimacion = ["estID", "cgcID", "estRol","estSeniority","bnsID","estMes", "estSemana", "estFechaInicio", "estFechaFin", "estHoras"];
			$inserciones = 0;
			foreach ($estimacion as $i => $estimacion) {
				if (is_array($estimacion)){
					$columnas_datos_invalidas = array_diff(array_keys($estimacion), $columnas_tabla_estimacion);
					
					if (count($columnas_datos_invalidas) === 0){
						$estimacion["prjID"] = $prjID;
						$insert_id = $pdo->insert(
							"proyectos_estimacion",
							$estimacion
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar una estimacion entrante.");
				}
			}

			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }
    
	public function update_avances($pdo = null, $prjID = 0, $avances = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			is_array($avances)) {
			
			$pdo->deleteFrom( 
				"proyectos_avances", 
			  [	"prjID" => $prjID ]
			);
			
			$columnas_tabla_avances = ["advID", "advDescripcion", "advFecha", "advPorcReal", "advPorcEsperado"];
			$inserciones = 0;
			foreach ($avances as $i => $avance) {
				if (is_array($avance)){
					
					$columnas_datos_invalidas = array_diff(array_keys($avance), $columnas_tabla_avances);
					
					if (count($columnas_datos_invalidas) === 0){
						$avance["prjID"] = $prjID;
						$insert_id = $pdo->insert(
							"proyectos_avances",
							$avance
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar un avance entrante.");
				}
			}

			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	public function update_bitacoras($pdo = null, $prjID = 0, $bitacoras = []){  
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 &&
			is_array($bitacoras)) {
			
			$pdo->deleteFrom( 
				"proyectos_bitacoras", 
			  [	"prjID" => $prjID ]
			);
			
			$columnas_tabla_bitacoras = ["bitID", "prjID", "bitFecha", "bitDescripcion", "bitHora"];
			$inserciones = 0;
			foreach ($bitacoras as $i => $bitacora) {
				if (is_array($bitacora)){
					$columnas_datos_invalidas = array_diff(array_keys($bitacora), $columnas_tabla_bitacoras);
					
					if (count($columnas_datos_invalidas) === 0){
						$bitacora["prjID"] = $prjID;
						$insert_id = $pdo->insert(
							"proyectos_bitacoras",
							$bitacora
						);
						$inserciones++;
					}
					else {
						throw new Exception("Las siguientes columnas de ingreso son erróneas: '".implode("', '", $columnas_datos_invalidas)."'.<br/>");
					}
				}
				else {
					throw new Exception("Fallo al validar una bitacora entrante.");
				}
			}

			return $inserciones;
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
    }

	
    public function remove($pdo = null, $prjID = 0){
		if (is_object($pdo) && get_class($pdo)==="InterfazPDO" &&
			is_numeric($prjID) && $prjID > 0 ){
			
			return $pdo->deleteFrom(
				"proyectos", 
			  [ "prjID" => $prjID ]
			);
		}
		else {
			throw new Exception("Fallo al validar los parámetros.");
		}
	}  
}