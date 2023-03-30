<?php
/**
 * Returns the number of days in a given month and year, taking into account leap years.
 * Corrected by ben at sparkyb dot net
 * @param int $month 
 * @param int $year 
 */
function days_in_month($month, $year){
	// calculate number of days in a month
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}