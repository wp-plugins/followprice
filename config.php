<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action( 'admin_menu', 'my_plugin_menu' );

// Create admin menus
function my_plugin_menu() {
	add_menu_page( 'Followprice Dashboard', 'Followprice', 'manage_options', 'followprice-menu', 'followprice_dashboard', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA4Ny4yMTEgODcuMjEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDg3LjIxMSA4Ny4yMSIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZmlsbD0iIzI4QThFMCIgZD0iTTg0LjYwNCw0Mi40NDZMNDQuNzI4LDIuNTcyYy0yLjg2Ny0yLjg2Ny02LjUwOC0yLjU2My02LjUwOC0yLjU2M0w4LjQ3LDAuMDNjMCwwLTMuMjExLTAuMTUzLTUuOTAyLDIuNTM4UzAuMDI5LDguNDcyLDAuMDI5LDguNDcyTDAuMDA5LDM4LjIyYzAsMC0wLjMwNywzLjY0LDIuNTYzLDYuNTExbDM5Ljg3MywzOS44NzRjMy40NzQsMy40NzMsOS4xMDcsMy40NzMsMTIuNTc4LDBsMjkuNTgyLTI5LjU4Qzg4LjA4MSw1MS41NTIsODguMDc3LDQ1LjkyMSw4NC42MDQsNDIuNDQ2eiBNMTIuOTIxLDI1LjIxM2MtMy4zOTMtMy4zOTQtMy4zOTYtOC44OTcsMC0xMi4yOWMzLjM5My0zLjM5NSw4Ljg5Ni0zLjM5NSwxMi4yOTEsMGMzLjM5NSwzLjM5MywzLjM5NSw4Ljg5NiwwLDEyLjI5QzIxLjgxOCwyOC42MSwxNi4zMTQsMjguNjEsMTIuOTIxLDI1LjIxM3ogTTcxLjY3MSw1MS4zMDhMNTEuMzA0LDcxLjY3M2MtMS41OSwxLjU5Mi00LjE3NCwxLjU5Mi01Ljc2NCwwYy0xLjU5My0xLjU5NC0xLjU5My00LjE3NCwwLTUuNzY2bDEzLjQwNi0xMy40MDZIMjguMDY4Yy0yLjI1MiwwLjAwMi00LjA3Ni0xLjgyNi00LjA3Ni00LjA3NmMwLTIuMjUyLDEuODI0LTQuMDc5LDQuMDc2LTQuMDc3bDMwLjg3OC0wLjAwMkw0NS41NDgsMzAuOTQ4Yy0xLjU5My0xLjU5My0xLjU5My00LjE3NCwwLTUuNzY3YzEuNTkyLTEuNTkxLDQuMTcyLTEuNTkyLDUuNzY0LDBMNzEuNjcxLDQ1LjU0YzAuNzM2LDAuNzM2LDEuMTkzLDEuNzU4LDEuMTkzLDIuODg1QzcyLjg2NCw0OS41NDgsNzIuNDA5LDUwLjU2OSw3MS42NzEsNTEuMzA4eiIvPjwvZz48L3N2Zz4=');

	add_action( 'admin_init', 'register_settings' );
}

// Register admin options
function register_settings() {
    register_setting('fpr-settings-group', 'fpr_options');
    register_setting('fpr-store-key-group', 'fpr_store_key');
}

function followprice_dashboard() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include(FOLLOWPRICE__PLUGIN_DIR . 'views/dashboard.php');
}

//plugins page notice
if ( ($pagenow == 'plugins.php') && (get_option('followprice_activated') == false) ) {
	function display_notice() {
		include(FOLLOWPRICE__PLUGIN_DIR . 'views/notice.php');
	}
	add_action( 'admin_notices', 'display_notice' );
}

?>