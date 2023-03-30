<?php 
/*
 * Sistema: Gestión de Horas
 * Cliente: FactorIT
 * Archivo fuente: common/repairPOST.php
 * Autor: tmk-php@infeline.org
 * 
 * Lee los campos recibidos en una petición POST y en el archivo "raw" del POST.
 * Con esto reconstruye los arrays perdidos.
 * Esta función fue obtenida de la sección de comentarios en http://php.net/manual/en/language.variables.external.php
 * @param array $data El array $_POST
 * @return array Un array $_POST reparado.
 */
function repairPost($data) {
	// combine rawpost and $_POST ($data) to rebuild broken arrays in $_POST
	$rawpost = "&".file_get_contents("php://input");
	while(list($key,$value)= each($data)) {
		$pos = preg_match_all("/&".$key."=([^&]*)/i",$rawpost, $regs, PREG_PATTERN_ORDER);       
		if((!is_array($value)) && ($pos > 1)) {
			$qform[$key] = array();
			for($i = 0; $i < $pos; $i++) {
				$qform[$key][$i] = urldecode($regs[1][$i]);
			}
		} else {
			$qform[$key] = $value;
		}
	}
	return $qform;
}
?> 