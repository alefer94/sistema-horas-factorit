<?php 
setlocale(LC_TIME, "");
date_default_timezone_set("America/Santiago");

$GLOBALS["ROOT_DIRECTORY"] = "/factorit_gestion_horas";
$GLOBALS["SITE_TITLE"] = "Gestión de Horas";

$GLOBALS["SITE_LINKS"] = [
	0 => "inicio", 
	"inicio" => [
		"titulo" => "Inicio", 
		"ico_clases" => "pg pg-home", 
		"nvl_restriccion" => 1, 
		"descripcion" => ""
	],
	"horas" => [
		"titulo" => "Mis Horas", 
		"ico_clases" => "fa fa-pencil-square-o", 
		"nvl_restriccion" => 1, 
		"descripcion" => "Aquí ud. maneja las horas que ha trabajado por cada mes, proyecto, etapa y día. Cada día tiene un máximo de 8 horas humanas grabables."
	],
	"adm_clientes" => [
		"titulo" => "Clientes", 
		"ico_clases" => "fa fa-coffee", 
		"nvl_restriccion" => 1, 
		"descripcion" => "Administra los clientes de FactorIT. Todo cliente es asociado a uno o varios proyectos, y no puede haber un proyecto sin un cliente previamente establecido."
	],
	"adm_proyectos" => [
		"titulo" => "Proyectos", 
		"ico_clases" => "fa fa-suitcase", 
		"nvl_restriccion" => 1, 
		"descripcion" => "Lista los proyectos de FactorIT registrados en este sistema, y admite una gestión más profunda a través de una vista detallada, visible al hacer clic en un botón <span class='badge badge-info b-rad-0'><span class='fa fa-ellipsis-h'></span></span>"
	],
	"adm_usuarios" => [
		"titulo" => "Usuarios", 
		"ico_clases" => "fa fa-group", 
		"nvl_restriccion" => 3, 
		"descripcion" => "Visualiza los usuarios. Si un usuario pierde u olvida su clave, desde aquí se puede restituir con un botón <span class='badge badge-success b-rad-0'><span class='fa fa-key'></span></span>"
	],
	"adm_skill" => [
		"titulo" => "Skill", 
		"ico_clases" => "fa fa-list-ul", 
		"nvl_restriccion" => 2, 
		"descripcion" => "Visualiza los skill. la cual nos señala su nombre y nivel"
	],
	"adm_usuarios_skill" => [
		"titulo" => "Usuarios Skill", 
		"ico_clases" => "fa fa-wrench", 
		"nvl_restriccion" => 1, 
		"descripcion" => "Aqui podra vizualizar las skill que tiene el usuario y skill"
	],
	"adm_feriados" => [
		"titulo" => "Feriados", 
		"ico_clases" => "fa fa-calendar", 
		"nvl_restriccion" => 1, 
		"descripcion" => "En esta sección se administran los feriados por año. Éstos infieren directamente en el ingreso de horas y el cálculo de las horas hábiles de cada mes."
	],
	"perfil" => [
		"titulo" => "Mi Perfil", 
		"ico_clases" => "fa fa-user", 
		"nvl_restriccion" => 1, 
		"descripcion" => "Aquí puede ajustar preferencias de su cuenta de usuario, y ver detalles sobre ella."
	],
	"logout" => [
		"titulo" => "Cerrar Sesión", 
		"ico_clases" => "fa fa-sign-out", 
		"nvl_restriccion" => 1, 
		"descripcion" => ""
	],
	

];

//archivos .css, que deben existir en css/themes
$GLOBALS["SITE_THEMES"] = [
	"abstract",
	"calendar",
	"corporate",
	"retro",
	"simple",
	"unlax",
	"vibes"
];

$array_request_uri = explode("/", $_SERVER["REQUEST_URI"]);
$GLOBALS["CURRENT_PAGE_ID"] = !empty(end($array_request_uri))? end($array_request_uri) : $GLOBALS["SITE_LINKS"][0];

$GLOBALS["USER_ID"] = -1;
$GLOBALS["USER_NAME"] = "";
$GLOBALS["USER_MAIL"] = "";
$GLOBALS["USER_RUT"] = "";
$GLOBALS["USER_PRIVILEGE"] = -1;
$GLOBALS["USER_SITE_THEME"] = $GLOBALS["SITE_THEMES"][2];

foreach ($GLOBALS["SITE_LINKS"] as $url => $sitio){
	if  ($url === $GLOBALS["CURRENT_PAGE_ID"]) {
		$GLOBALS["CURRENT_PAGE_TITLE"] = $sitio["titulo"];
		break;
	}
}

unset($array_request_uri);