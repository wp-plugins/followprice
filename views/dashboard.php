<div class="wrap">
	<?php
	$store_key = get_option('fpr_store_key');
	if(empty($store_key)) {update_option('followprice_activated', false);}else{update_option('followprice_activated', true);}

	$activated = get_option('followprice_activated');
	$activated_count = get_option('followprice_activated_count');
	if ($activated == true) {
		if ($activated_count < 2) {$activated_count++;}
		update_option('followprice_activated_count', $activated_count);
	}
	wp_enqueue_script( 'followprice', plugins_url( '../js/followprice.js', __FILE__ ));

	$site_url = get_site_url();
	$data = array(
		'fpActivated' => $activated,
		'activatedCount' => $activated_count,
		'siteUrl' => $site_url,
		'followpriceEnv' => FOLLOWPRICE_ENV
		);
	wp_localize_script( 'followprice', 'phpVars', $data );

	?>

	<?php include(FOLLOWPRICE__PLUGIN_DIR . 'views/header.php'); ?>
	<h2 class="placeholder">&nbsp;</h2>
</div>

<div class="wrap fp-wrap fp-dashboard-wrap" style="display:none">

	<?php if($activated == true){ ?>

	<div class="fp-block fp-block-white fp-block-dashboard">

			<div class="fp-block-left">
				<div class="fp-block-container">

				<p class="fp-block-text">Click to access your followprice dashboard in a new tab.</p>
				
				<a target="_blank" href="https://followprice.co/business/login" class="fp-button fp-button-primary fp-button-large" type="submit" >Go to my Dashboard<span class="dashicons dashicons-external"></span></a>	
				</div>
			</div><div class="fp-block-right fp-block-right-back"></div>

			
			
	</div>

	<?php 
	}else if($activated == false) { ?>
	<div class="fp-block">
		<h3 class="fp-block-title">To complete the installation please execute the following steps.</h3>
	</div>
	<div class="fp-block fp-block-white">
		<div class="fp-block-container fp-block-container-title">
			<h3 class="fp-block-title">1. Get your store key</h3>
		</div>
		<div class="fp-block-container">
			<p class="fp-block-text">To get a valid store key create a Followprice account with your store domain. If you already have a Followprice account find your store key in your welcome email.</p>
			<form target="_blank" method="POST" action="https://followprice.co/home/register?marketplace=woocommerce"  name="followprice_activate">
				<input class="fp-button fp-button-primary fp-button-large" type="submit" value="Get Store Key">
			</form>
		</div>
	</div>
	<div class="fp-block fp-block-white">
		<div class="fp-block-container fp-block-container-title">
			<h3 class="fp-block-title">2. Enter your store key</h3>
		</div>
		<div class="fp-block-container">
		<p class="fp-block-text">If you already know your store key.</p>
			<form action="options.php" id="fp-settings-key" method="post">
				<?php settings_fields( 'fpr-store-key-group' ); ?>
				<?php $fpr_store_key = get_option('fpr_store_key'); ?>
				<fieldset>
					<input id="key" style="margin-bottom: 13px; width:90%; max-width:550px;display:inline-block;vertical-align:top;" name="fpr_store_key" type="text" value="<?php echo $fpr_store_key; ?>" class="regular-text code"><div class="fp-validation-container"></div>
				</fieldset>
				<input type="submit" style="display:inline-block;vertical-align:top;" name="submit" id="key-submit" class="fp-button fp-button-primary fp-button-green fp-button-large" value="Activate">
			</form>
		</div>
	</div>
	<div class="fp-block">
		<p>If you need help contact us at <a href="mailto:support@followprice.co?Subject=Key%20validation%20help">support@followprice.co</a>.</p>
	</div>
	<?php
}
?>
</div>

<div class="wrap fp-wrap fp-settings-wrap" style="display:none;">
	<?php if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="updated">
		<p><strong><?php _e('Settings saved.') ?></strong></p>
	</div>
	<?php } ?>
	<form id="settings-form" method="post" action="options.php">
		<?php settings_fields( 'fpr-settings-group' ); ?>
		<?php $fpr_options = get_option('fpr_options'); ?>


		<div class="fp-block fp-block-white">

			<div class="fp-block-container fp-block-container-title">
				<h3 class="fp-block-title">General</h3>
			</div>

			<div class="fp-block-container">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="fpr_options[button_toggle]">Enable Followprice:</label></th>
						<td><input name="fpr_options[button_toggle]" type="checkbox" value="1" <?php checked( isset($fpr_options['button_toggle']) ); ?> />Enable<p class="description">Enable the Followprice button in all pages.</p></td>
					</tr>
				</table>
			</div>

		</div>


		<div class="fp-block fp-block-white">

			<div class="fp-block-container fp-block-container-title">
				<h3 class="fp-block-title">Product Page</h3>
			</div>

			<div class="fp-block-container">
				<table class="form-table">
					<tr>
						<th scope="row"><label for="fpr_options[position]">Position:</label></th>
						<td><fieldset>
							<input name="fpr_options[position]" type="radio"  value="0" <?php if ( $fpr_options['position'] == 0 ) echo ' checked="checked"'; ?> >
							<label>Below the price</label>
							<br>
							<input name="fpr_options[position]" type="radio"  value="1" <?php if ( $fpr_options['position'] == 1 ) echo ' checked="checked"'; ?> >
							<label>Below the title</label>
							<br>
							<input name="fpr_options[position]" type="radio"  value="2" <?php if ( $fpr_options['position'] == 2 ) echo ' checked="checked"'; ?> >
							<label>Below "add to cart"</label>
						</fieldset></td>
					</tr>
					<tr>
						<th scope="row"><label for="fpr_options[allignment]">Alignment:</label></th>
						<td>
							<select name="fpr_options[allignment]" id="allignment">
								<option value="0" <?php if ($fpr_options['allignment'] == 0) echo 'selected="selected"';?> >Default</option>
								<option value="1" <?php if ($fpr_options['allignment'] == 1) echo 'selected="selected"';?> >Left</option>
								<option value="2" <?php if ($fpr_options['allignment'] == 2) echo 'selected="selected"';?> >Right</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<p>Is your Followprice button is too close to other elements in your page? Add margins to create some space.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fpr_options[margin][top]">Margins (px):</label></th>
						<td>
							<p style="display:inline;">Top:</p><input name="fpr_options[margin][top]" type="text" id="margin-top" value="<?php echo $fpr_options['margin']['top']; ?>" style="width:50px;"/>
							<p style="display:inline;">Right:</p><input name="fpr_options[margin][right]" type="text" id="margin-right" value="<?php echo $fpr_options['margin']['right']; ?>" style="width:50px;"/>
							<p style="display:inline;">Bottom:</p><input name="fpr_options[margin][bottom]" type="text" id="margin-right" value="<?php echo $fpr_options['margin']['bottom']; ?>" style="width:50px;"/>
							<p style="display:inline;">Left:</p><input name="fpr_options[margin][left]" type="text" id="margin-right" value="<?php echo $fpr_options['margin']['left']; ?>" style="width:50px;"/>

						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fpr_options[css]">Custom CSS:</label></th>
						<td><textarea name="fpr_options[css]" type="text" id="custom-css"><?php echo $fpr_options['css']; ?></textarea></td>
					</tr>
				</table>
			</div>

		</div>


		<div class="fp-block fp-block-white">

			<div class="fp-block-container fp-block-container-title">
				<h3 class="fp-block-title">List Page</h3>
			</div>

			<div class="fp-block-container">
				<table class="form-table">
					<tr>
					<th scope="row"><label for="fpr_options[list_toggle]">Show List View:</label></th>
						<td><input name="fpr_options[list_toggle]" type="checkbox" value="1" <?php checked( isset($fpr_options['list_toggle']) ); ?> >Enable<p class="description">Enable the Followprice button in list pages.</p></td>
					</tr>
					<tr>
						<th scope="row"><label for="fpr_options[allignment_list]">Allignment:</label></th>
						<td>
							<select name="fpr_options[allignment_list]" id="allignment">
								<option value="0" <?php if ($fpr_options['allignment_list'] == 0) echo 'selected="selected"';?> >Default</option>
								<option value="1" <?php if ($fpr_options['allignment_list'] == 1) echo 'selected="selected"';?> >Left</option>
								<option value="2" <?php if ($fpr_options['allignment_list'] == 2) echo 'selected="selected"';?> >Right</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td>
							<p>Create space between the button and surrounding elements by adding margins.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fpr_options[margin_list][top]">Margins (px):</label></th>
						<td>
							<p style="display:inline;">Top:</p><input name="fpr_options[margin_list][top]" type="text" id="margin-top" value="<?php echo $fpr_options['margin_list']['top']; ?>" style="width:50px;"/>
							<p style="display:inline;">Right:</p><input name="fpr_options[margin_list][right]" type="text" id="margin-right" value="<?php echo $fpr_options['margin_list']['right']; ?>" style="width:50px;"/>
							<p style="display:inline;">Bottom:</p><input name="fpr_options[margin_list][bottom]" type="text" id="margin-right" value="<?php echo $fpr_options['margin_list']['bottom']; ?>" style="width:50px;"/>
							<p style="display:inline;">Left:</p><input name="fpr_options[margin_list][left]" type="text" id="margin-right" value="<?php echo $fpr_options['margin_list']['left']; ?>" style="width:50px;"/>
						</td>
					</tr>
				</table>
			</div>

		</div>

		<div class="fp-block">
		<input type="submit" name="submit" id="settings-submit" class="fp-button fp-button-primary fp-button-large fp-button-footer" value="Save Changes">
	</div>

	</form>

	<?php if($activated == true){ ?>

	<p>Need to change your store key? <a class="fp-block-toggle">click here</a>.</p>

	<div class="fp-block fp-block-white fp-block-key" style="display:none">

		<div class="fp-block-container fp-block-container-title">
			<h3 class="fp-block-title">Store Key</h3>
		</div>

		<div class="fp-block-container">
			<p class="fp-block-text">If you entered an incorrect store key, you can change it here.</p>
			<form action="options.php" method="post" id="fp-settings-key">
				<?php settings_fields( 'fpr-store-key-group' ); ?>
				<?php $fpr_store_key = get_option('fpr_store_key'); ?>
				<input id="key" style="margin-bottom: 13px; width:90%; max-width:550px;display:inline-block;vertical-align:top;" name="fpr_store_key" type="text" value="<?php echo $fpr_store_key; ?>" class="regular-text code"><div class="fp-validation-container"></div>
				<input type="submit" style="display:inline-block;vertical-align:top;" name="submit" id="key-submit" class="button button-secondary fp-settings-key" value="Validate Store key">
			</form>
		</div>
	</div>
	<?php } ?>

</div>