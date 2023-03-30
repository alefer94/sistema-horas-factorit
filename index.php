<?php 
require __DIR__.'/common/globals.php';
require __DIR__.'/scripts/validateUserSession.php'; //automaticamente lleva al login si no se ha ingresado

$GLOBALS["esUsrJefe"] = ($GLOBALS["USER_PRIVILEGE"] >= 2);
$GLOBALS["esUsrAdmin"] = ($GLOBALS["USER_PRIVILEGE"] >= 3);

$full_page_path = file_exists(__DIR__.'/pages/'.$GLOBALS["CURRENT_PAGE_ID"].'.php')?
					__DIR__.'/pages/'.$GLOBALS["CURRENT_PAGE_ID"].'.php'
				:	__DIR__.'/pages/'.$GLOBALS["SITE_LINKS"][0].'.php';
				
?>

<!DOCTYPE html>
<html>
<head><?php 
	require __DIR__.'/template/include_head.php'; ?>
	<title><?=$GLOBALS["CURRENT_PAGE_TITLE"];?> - <?=$GLOBALS["SITE_TITLE"];?></title>
</head>
<body class="fixed-header bg-white"><?php 
	require __DIR__.'/template/section_sidebar.php';
	require __DIR__.'/template/section_header.php'; 
	include __DIR__.'/template/include_scripts.php'; ?>
	<div class="page-container">
		<div class="page-content-wrapper">
			<?php require $full_page_path; ?>
    	</div>
	</div><?php 
	if (file_exists(__DIR__.'/js/'.$GLOBALS["CURRENT_PAGE_ID"].'.js')) { ?> 
		<script type="text/javascript" src="js/<?=$GLOBALS["CURRENT_PAGE_ID"];?>.js"></script> <?php } ?>
</body>
</html>