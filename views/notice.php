<?php
wp_register_style( 'followprice-style', plugins_url('../css/followprice-style.css', __FILE__) );
wp_enqueue_style( 'followprice-style' );
?>

<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
	<form name="followprice_activate" action="<?php echo admin_url('admin.php?page=followprice-menu'); ?>" method="POST">
		<div class="followprice_activate">
			<img class="fp_logo" src='<?php echo FOLLOWPRICE__PLUGIN_URL . "img/full-logo-white.svg"; ?>'>
			<div class="fa_button_container" onclick="document.followprice_activate.submit();">
				<div class="fp-button fp-button-primary fp-button-green fp-button-large">Activate your Followprice account</div>
			</div>
			<div class="fa_description">Activate your account to complete the installation.</div>
		</div>
	</form>
</div>