<div id="header" class="header full-width no-padding b-b b-blue">
	<!-- START MOBILE CONTROLS -->
	<div class="container-fluid">
		<!-- LEFT SIDE -->
		<div class="pull-left full-height visible-sm visible-xs">
			<!-- START ACTION BAR -->
			<div class="header-inner">
				<a href="#"
					class="btn-link toggle-sidebar visible-sm-inline-block visible-xs-inline-block padding-20"
					data-toggle="sidebar"> <span class="icon-set menu-hambuger"></span>
				</a>
			</div>
			<!-- END ACTION BAR -->
		</div>
		<div class="pull-center hidden-md hidden-lg">
			<div class="header-inner">
				<div class="brand inline">
					<img src="img/logo.png" alt="logo"
						data-src="img/logo.png"
						data-src-retina="img/logo_2x.png" width="78" height="22">
				</div>
			</div>
		</div>
	</div>
	<!-- END MOBILE CONTROLS -->
	
	<div class="pull-left full-right sm-table hidden-xs hidden-sm">
		<div class="header-inner">
			<div class="brand inline">
				<img src="img/logo.png" alt="logo"
					data-src="img/logo.png"
					data-src-retina="img/logo_2x.png" width="78" height="22">
			</div>
		</div>
	</div>
	
	<div class="pull-center hidden-xs hidden-sm text-center padding-10">
			<b class="font-montserrat" style="font-size: 1.25em"><?=$GLOBALS["SITE_TITLE"];?></b>
			<p class="small"><?=$GLOBALS["CURRENT_PAGE_TITLE"];?></p>
	</div>
	
	<div class="pull-right full-height hidden-xs hidden-sm no-margin no-padding">
		<div id="header-navigation" class="full-height no-margin no-padding text-right"><?php 
			foreach ($GLOBALS["SITE_LINKS"] as $url => $item) {
				if (is_array($item) && $GLOBALS["USER_PRIVILEGE"] >= $item["nvl_restriccion"]){
		  ?><a id="navlink-<?=strtolower($item["titulo"]);?>" href="<?=$url; ?>" class="btn b-rad-0 b-l b-grey full-height no-padding no-margin" style="width: 2.5rem !important" data-toggle="tooltip" data-placement="bottom" title="<?=$item["titulo"]; ?>" <?=($url!==$GLOBALS["CURRENT_PAGE_ID"]?"":"disabled"); ?>><?php 
			  ?><span class="m-t-15 <?=$item["ico_clases"]; ?>" style="font-size: 1.5rem"></span><?php
		  ?></a><?php 
				}
			}
	  ?></div>
	</div>
</div>