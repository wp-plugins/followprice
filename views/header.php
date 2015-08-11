<?php
wp_register_style( 'followprice-style', plugins_url('../css/followprice-style.css', __FILE__) );
wp_enqueue_style( 'followprice-style' );
?>

<div class="fp-title-div">
	<img class="fp-logo" src='<?php echo FOLLOWPRICE__PLUGIN_URL . "img/full-logo.svg"; ?>'>
	<ul class="fp-nav">
		<li class="fp-nav-li">
			<p class="fp-button fp-button-header fp-button-first"><?php if ($activated == false) {echo 'ACTIVATION';} else {echo 'DASHBOARD';} ?></p>
		</li>
		<li class="fp-nav-li">
			<p class="fp-button fp-button-header fp-button-second" selected="true">SETTINGS</p>
		</li>
	</ul>
</div>
