<?php 
/**
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/validateRequestParameters.php
 * Autor: Benjamin La Madrid
 *
 * Valida que los parámetros requeridos existan dentro de los enviados por una petición, y verifica que éstos contengan un valor.
 * @param string[] $enviados Un array con los parámetros a verificar (usualmente, '$_POST').
 * @param string[] $requeridos Un array con los nombres de los parámetros necesarios para la ejecución.
 * @return bool|array 'true' (bool) si la validación es exitosa. De lo contrario, un array con dos valores: 'false' (bool) y un mensaje (string) detallando la cantidad de parámetros faltantes, cuáles son y cuáles se recibieron.
 */
function validateRequestParameters($enviados, $requeridos){
	if (is_array($enviados) && is_array($requeridos)){
		$array_diferencial = array_diff(
			$requeridos, 
			array_keys($enviados)
		);
		$tmn_requeridos = sizeof($requeridos);
		$tmn_enviados = sizeof($enviados);
		$tmn_diferencial = sizeof($array_diferencial);
		
		$mensaje = "";
		
		if ($tmn_diferencial === 0) { //si todos los parámetros requeridos existen, verificaremos que no estén vacíos
			$vacios = [];
			foreach ($enviados as $llave => $valor){
				if (array_search($llave, $requeridos) && $valor === ""){
					array_push($vacios, $llave);
				}
			}
			
			if (sizeof($vacios) > 0){
				$mensaje = "Los siguientes parámetros fueron recibidos sin contener ningún valor:<br/>";
				$i = 0;
				foreach ($vacios as $nombre) {
					$mensaje .= "'".$nombre."'";
					$i++;
					if ($i < $tmn_enviados) { $mensaje .= ", "; }
					else { $mensaje .= ".<br/>"; }
				}
			}
		}
		else if ($tmn_diferencial > 0) { //si falta algún parámetro...
			if ($tmn_enviados === 0) {
				$mensaje = "No se recibió ninguno de los ".$tmn_requeridos." parámetros necesarios para ejecutar esta acción.";
			}
			else {
				$mensaje = "Los parámetros recibidos son insuficientes. Se recibieron ".$tmn_enviados." de ".$tmn_requeridos.".<br/>";
				
				$mensaje .= "Parámetros recibidos:<br/>";
				$i = 0;
				foreach ($enviados as $nombre => $valor) {
					$mensaje .= "'".$nombre."'";
					$i++;
					if ($i < $tmn_enviados) { $mensaje .= ", "; }
					else { $mensaje .= ".<br/>"; }
				}
				
				$mensaje .= "Parámetros faltantes:<br/>";
				$i = 0;
				foreach ($array_diferencial as $nombre){
					$mensaje .= "'".$nombre."', ";
					$i++;
					if ($i < $tmn_diferencial) { $mensaje .= ", "; }
					else { $mensaje .= ".<br/>"; }
				}
			}
		}
		
		return $mensaje === ""? (true) : ([ false, $mensaje ]);
	}
	else {
		return null;
	}
}