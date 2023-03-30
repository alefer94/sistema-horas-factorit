<?php

/** Convierte un array asociativo de filtros en un cláusula WHERE de SQL
 * EL array puede tener 2 formatos:
 * ["columna" => "dato"] para comparaciones "igual a", o 
 * ["columna" => ["CONDICION", valor]] para otras condiciones.
 * Las condiciones soportadas son: 
 *    EQUALS - igual a
 *    NOT EQUALS - no igual a
 *    LESS - menor que
 *    MORE - mayor que
 *    LIKE - contiene
 *    LIKE_BEGIN - comienza con
 *    LIKE_END - termina con
 *    IN - es uno de ('valor' debe ser un array)
 */
function pdo_filters_array_to_string($filters){
    
    $filters_count = count($filters);
	$condition_clause = null;
    
    if ($filters_count>0) {
        $condition_clause = "WHERE ";
        $filter_i = 0;
        foreach ($filters as $col => $col_detail){
            $filter_i++;
            if (is_array($col_detail) ) {
                $condition_type = $col_detail[0];
                $val = $col_detail[1];
                switch ($condition_type){
                    case "EQUALS": 		
                        if (is_null($val)) { 
                            $condition_clause .= $col." IS NULL"; } 
                        else { 
                            $condition_clause .= $col." = '".$val."'"; }
                        break;
                    case "NOT EQUALS": 		
                        if (is_null($val)) { 
                            $condition_clause .= $col." IS NOT NULL";
                        }
                        else {
                            $condition_clause .= $col." <> '".$val."'";
                        }
                        break;
                    case "LESS": 		$condition_clause .= $col." < ".$val.""; break;
                    case "MORE": 		$condition_clause .= $col." > ".$val.""; break;
                    case "LIKE": 		$condition_clause .= $col." LIKE '%".$val."%'"; break;
                    case "LIKE_BEGIN": 	$condition_clause .= $col." LIKE '".$val."%'"; break;
                    case "LIKE_END": 	$condition_clause .= $col." LIKE '%".$val."'"; break;
                    case "IN":			if (is_array($val)) { $condition_clause .= $col." IN ('".implode("', '", $val).")"; break; }
                    default: 			$condition_clause .= $col." = '".$val."'"; break;
                }
            }
            else if (is_null($col_detail)) {
                $condition_clause .= $col." IS NULL";
            }
            else {
                $condition_clause .= $col." = '".$col_detail."'";
            }
            
            if ($filter_i < $filters_count){
                $condition_clause .= " AND ";
            }
        }
    }

    return $condition_clause;
}