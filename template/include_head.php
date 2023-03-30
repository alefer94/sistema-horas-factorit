<?php 

if (isset($usrData)){
	$GLOBALS["USER_SITE_THEME"] = $GLOBALS["SITE_THEMES"][$usrData["usrTemaSitio"]];
}
?>

<meta name="description" content="Gestion de Horas" />
<meta name="author" content="Benjamin La Madrid" />

<!-- Tamaño de la pantalla, ajuste necesario para Bootstrap y páginas responsivas -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- Estándares -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />

<!-- Para Internet Explorer -->
<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />

<!-- Ajustes para aparatos Apple -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link rel="apple-touch-icon" href="ico/76.png" sizes="76x76" />
<link rel="apple-touch-icon" href="ico/120.png" sizes="120x120" />
<link rel="apple-touch-icon" href="ico/152.png" sizes="152x152" />
<link rel="apple-touch-icon" href="pages/ico/60.png" />
	
<!-- Estilos para plugins adjuntos -->
<link rel="stylesheet" type="text/css" href="plugins/pace/pace-theme-flash.css" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrapv3/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrapv3/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="plugins/font-awesome/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="plugins/jquery-scrollbar/jquery.scrollbar.css" media="screen" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-select2/select2.css" media="screen" />
<link rel="stylesheet" type="text/css" href="plugins/bootstrap-select2/select2-bootstrap.css" media="screen" />
<link rel="stylesheet" type="text/css" href="plugins/switchery/css/switchery.min.css" media="screen" />
	
<!-- Estilos de la app -->
<link rel="stylesheet" type="text/css" href="css/pages.css" class="main-stylesheet" />
<link rel="stylesheet" type="text/css" href="css/pages-icons.css" />
<link rel="stylesheet" type="text/css" href="css/themes/<?=$GLOBALS["USER_SITE_THEME"]; ?>.css" />

<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />


<!-- Fixes -->

<!-- Para tablas responsivas en Firefox. 
    Este bug aparentemente fue arreglado desde Septiembre de 2017 (Firefox 53). 
    Mas info en: https://stackoverflow.com/questions/17408815/
    *Si dichas tablas fallan para algún usuario de Firefox que use nuestra app, las lineas debajo deberian ser descomentadas.
    
    <style>
        @-moz-document url-prefix() {
          fieldset { display: table-cell; }
        }
    </style>
-->

<!-- Para versiones viejas de Internet Explorer, mas info en: https://msdn.microsoft.com/es-es/library/ms537512(v=vs.85).aspx -->
<!--[if lte IE 9 ]>
    <link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
<![endif]-->