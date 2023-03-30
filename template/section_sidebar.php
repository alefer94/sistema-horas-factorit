<?php require_once __DIR__.'/../scripts/validateUserSession.php'; ?>

<div class="page-sidebar no-padding no-overflow hidden-md hidden-lg" data-pages="sidebar">
	<div id="appMenu" class="sidebar-overlay-slide from-top"></div>
	<div class="sidebar-menu full-height full-width flex-container auto-overflow">
		<ul class="menu-items full-height full-width no-padding m-auto no-overflow">
		<?php 
		foreach ($GLOBALS["SITE_LINKS"] as $url => $item) {
			if (is_array($item) && $GLOBALS["USER_PRIVILEGE"] >= $item["nvl_restriccion"]){
		?>
			<li class="m-t-5 m-r-0 v-align-middle no-padding full-width">
				<a id="sidelink-<?=strtolower($item["titulo"]);?>" href="<?=$url; ?>" class="detailed no-margin p-l-30 p-r-0 full-width">
					<span class="icon-thumbnail no-padding">
    					<i class="<?=$item["ico_clases"]; ?>"></i>
    				</span>
					<span class="title m-t-5"><?=$item["titulo"]; ?></span>
				</a>
			</li>
		<?php 
			} 
		}
		?>
		</ul>
		<div class="clearfix"></div>
	</div>
</div>