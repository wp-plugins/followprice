<?php
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Plugin Name: Followprice
 * Plugin URI: http://www.followprice.co
 * Description: Displays a button for customers to follow your products and get notified once the price drops.
 * Version: 1.1.0
 * Author: Followprice
 * Author URI: http://www.followprice.co
 * License: GPLv2 or later
 */

/*  Copyright 2015  FOLLOWPRICE  (email : daniel.moreira@followprice.co)
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    define( 'PLUGIN_VER' , '1.1.0');

    define( 'PLATFORM_VER' , get_bloginfo('version'));
    define( 'PLATFORM_NAME' , 'wordpress');

    function get_woo_version() {
        // If get_plugins() isn't available, require it
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';

        // If the plugin version number is set, return it 
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];

        } else {
            // Otherwise return null
            return NULL;
        }
    }

    define( 'PLATFORM_DEP_NAME' , 'woocommerce');
    define( 'PLATFORM_DEP_VER' , get_woo_version());

    define( 'FOLLOWPRICE__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    define( 'FOLLOWPRICE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    define( 'FOLLOWPRICE_PRODUCTION' , 'https://followprice.co');
    define( 'FOLLOWPRICE_STAGING' , 'http://sites.followprice.co:1337');

    define( 'FOLLOWPRICE_ENV' , FOLLOWPRICE_PRODUCTION);

    //Initiallize settings and redirect when plugin activates
    register_activation_hook( __FILE__, 'plugin_activated' );
    add_action('admin_init', 'activate_redirect');

    function plugin_activated() {
        register_setting('fpr-settings-group', 'fpr_options');
        register_setting('fpr-store-key-group', 'fpr_store_key');
            $fpr_options = array (
                'button_toggle' => 1,
                'position' => 2,
                'allignment' => 0,
                'margin' => array(
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0
                    ),
                'css' => '',
                'list_toggle' => null,
                'button_style_nocounter' => 1,
                'button_style_iconlink' => 1,
                'button_style_notext' => 1,
                'button_style_stacked' => null,
                'allignment_list' => 0,
                'margin_list' => array(
                        'top' => 0,
                        'right' => 0,
                        'bottom' => 0,
                        'left' => 0
                    ),
            );
        $fpr_store_key = '';
        update_option('fpr_options', $fpr_options);
        if (!(get_option('fpr_store_key'))) {update_option('fpr_store_key', $fpr_store_key);};
        add_option('followprice_activation_redirect', true);
        add_option('followprice_activated', false);
        add_option('followprice_activated_count', 0);
        add_option('product_page_priorities', array( 'key_price' => 11, 'key_title' => 6, 'key_cart' => 31,));
    }

    function activate_redirect() {
        if (get_option('followprice_activation_redirect', false)) {
            delete_option('followprice_activation_redirect');
            wp_redirect("admin.php?page=followprice-menu");
        }
    }

    // Admin page include
    include( plugin_dir_path( __FILE__ ) . 'config.php');

    //Get product information and div
    function get_button_div($containerclass, $buttonclass){
        $product_id = get_the_ID();
        $product_obj = new WC_Product( $product_id );
        $post_obj = $product_obj->post;
        $url = get_permalink( $product_id );
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
        $currency = get_woocommerce_currency();
        
        // categories
        global $post;
        $categories = wp_get_object_terms($post->ID, 'product_cat');
        if (!empty($categories)){
            $subcategory = array_pop($categories)->name;
        }
        if (!empty($categories)){
            $category = array_pop($categories)->name;
        }
        if (!isset($category)) {
            $category = "";
        }
        if (!isset($subcategory)) {
            $subcategory = "";
        }

        // stock
        $stock = (int)$product_obj->stock;
        
        if ($product_obj->stock_status == "instock") {
            $availability = 1;
        } else if ($product_obj->stock_status == "outofstock") {
            $availability = 0;
        }

        // campaign dates
        $sale_price_dates_from  = ( $date = get_post_meta( $product_id, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
        $sale_price_dates_to    = ( $date = get_post_meta( $product_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';


        $fpr_options = get_option('fpr_options');
        $button_style = "";
        if ($buttonclass == "button-list") {
            $button_style .= "no-full-text,"; 
        }

        $fpr_store_key = get_option('fpr_store_key');
        $fp_title = htmlspecialchars($post_obj->post_title);
        $category = htmlspecialchars($category);
        $subcategory = htmlspecialchars($subcategory);
        if ($buttonclass == "button-list") {
            $button_type = "list";
        } else {
            $button_type = "single";
        }
    	echo "<div class='$containerclass'>
                <div class='fp-button $buttonclass'
                data-store-key='$fpr_store_key'
                data-style='$button_style'
                data-type='$button_type'
                data-product-title='$fp_title'
                data-product-id='$product_obj->id'
                data-product-url='$url'
                data-product-image='$image[0]'
                data-product-price='$product_obj->price'
                data-product-old-price='$product_obj->regular_price'
                data-product-currency='$currency'
                data-product-campaign-start='$sale_price_dates_from'
                data-product-campaign-end='$sale_price_dates_to'
                data-product-availability ='$availability'
                data-product-stock='$stock'
                data-product-category='$category'
                data-product-subcategory='$subcategory'>
                </div>
            </div>
            ";
    }

    //Product Page div
    function fpr_follow_button() {
        $containerclass = "followprice-container";
        $buttonclass = "button-product";
        get_button_div($containerclass, $buttonclass);
    }

    //List Page div
    function fpr_follow_button_list(){
        $containerclass = "followprice-container-list";
        $buttonclass = "button-list";
        get_button_div($containerclass, $buttonclass);
    }

    //Button script
    function fpr_button_script() {
        wp_enqueue_script( 'button-script', plugins_url( '/js/button-script.js', __FILE__ ));
        $fpr_store_lang = get_bloginfo( 'language' );
        $dataToBePassed = array(
            'store_lang' => $fpr_store_lang,
            'followpriceEnv' => FOLLOWPRICE_ENV,
            'pluginVer' => PLUGIN_VER,
            'platformVer' => PLATFORM_VER,
            'platformName' => PLATFORM_NAME,
            'platformDepVer' => PLATFORM_DEP_VER,
            'platformDepName' => PLATFORM_DEP_NAME,
        );
        wp_localize_script( 'button-script', 'fpBtnVars', $dataToBePassed );
    }

    //Button CSS
    function fpr_button_css() {
        $options = get_option('fpr_options');
        $margin = $options['margin'];
        if ($options['allignment'] == 0) {
            $allignment = "";
        } else if ($options['allignment'] == 1) {
            $allignment = "float:left;";
        } else if ($options['allignment'] == 2) {
            $allignment = "float:right;";
        }
        echo "<style>
            #followprice-container{
                width:100%;
                display:inline-block;
            }
            .button-product {
                margin-top: $margin[top]px;
                margin-right: $margin[right]px;
                margin-bottom: $margin[bottom]px;
                margin-left: $margin[left]px;
                $allignment 
            }
            $options[css]
            </style>";
    }

    function fpr_button_list_css() {
        $options = get_option('fpr_options');
        $margin_list = $options['margin_list'];
        if ($options['allignment_list'] == 0) {
            $allignment_list = "";
        } else if ($options['allignment_list'] == 1) {
            $allignment_list = "float:left;";
        } else if ($options['allignment_list'] == 2) {
            $allignment_list = "float:right;";
        }
        echo "<style>
            #followprice-container-list{
                width:100%;
                display:inline-block;
            }
            .button-list {
                margin-top: $margin_list[top]px;
                margin-right: $margin_list[right]px;
                margin-bottom: $margin_list[bottom]px;
                margin-left: $margin_list[left]px;
                $allignment_list
            }
            $options[css]
            </style>";
    }

    //Conversions
    function conversion_check( $order ) {
        $order_id = $order->get_order_number();
        $items = $order->get_items();
        $coupons = $order->get_used_coupons();
        $coupon = $coupons[0];
        // $coupon_obj = new WC_Coupon( $coupon );
        
        echo '<script name ="fp-conversions"
            data-total="' . $order->get_total() . '"
            data-order-id="' . $order->get_order_number() . '"
            data-user-id="' . $order->get_user_id() . '"
            data-coupon-code="' . $coupon . '"
            ';
        $counter = 0;
        foreach( $items as $item_id => $item ) {
            $product_id = $item['product_id'];
            // $url = get_permalink( $product_id );
            $product_obj = new WC_Product( $product_id );
            // echo 'data-products-' . $counter . '-url="' . $url . '"
            // ';
            echo 'data-products-' . $counter . '-id="' . $product_id . '"
            data-products-' . $counter . '-price="' . $product_obj->price .'"
            ';
            $counter++;
        }
        echo 'src="' . FOLLOWPRICE_ENV . '/fp-conversions.js" async></script>';
    }

    add_action( 'woocommerce_order_details_after_order_table', 'conversion_check' );

    //Button toggle
    $fpr_options = get_option('fpr_options');
    if (isset($fpr_options['button_toggle'])) {

        //Follow button
        //Get hook priorities to determine where to position the button
        function fpr_get_priorities() {
            $priorities = $GLOBALS['wp_filter']['woocommerce_single_product_summary'];
            $priority_results = array();
            foreach ($priorities as $priority => $array) {
                foreach($array as $key => $value){
                    if($key == 'woocommerce_template_single_price'){
                        $priority_results['key_price'] = $priority;
                    }
                    if($key == 'woocommerce_template_single_title'){
                        $priority_results['key_title'] = $priority;
                    }
                    if($key == 'woocommerce_template_single_add_to_cart'){
                        $priority_results['key_cart'] = $priority;
                    }
                }
            }
            update_option('product_page_priorities', $priority_results);
        }
        $priority_results = get_option('product_page_priorities');
        if ($fpr_options['position'] == 0) {
            $button_pos = ($priority_results['key_price'] + 1);
        } else if ($fpr_options['position'] == 1) {
            $button_pos = ($priority_results['key_title'] + 1);
        } else {
            $button_pos = ($priority_results['key_cart'] + 1);
        }
        add_action ( 'woocommerce_single_product_summary', 'fpr_get_priorities');
        add_action ( 'woocommerce_single_product_summary', 'fpr_follow_button', $button_pos, 1);

        // Single Product CSS
        add_action ( 'woocommerce_after_single_product', 'fpr_button_css' );

        if (isset($fpr_options['list_toggle'])) {
            // List buttons
            add_action ( 'woocommerce_after_shop_loop_item', 'fpr_follow_button_list', 40);

            // List page CSS
            add_action ( 'wp_footer', 'fpr_button_list_css' );
        }

        // Button script
        add_action ( 'wp_footer', 'fpr_button_script' );
    }

}