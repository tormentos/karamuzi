<?php
$theme = wp_get_theme();
define('DM_VERSION', $theme->get('Version'));
define('DM_TEMPLATEPATH', get_template_directory());
// Implement TGMPA plugin registrar
require_once get_parent_theme_file_path('/inc/tgm/tgm-plugin-registration.php');
// Implement Server PHP Checker
require_once get_parent_theme_file_path('/inc/admin/check.php');
// Implement Main Functions
require_once get_parent_theme_file_path("inc/init.php");
// Add your own codes here
add_action("wp_ajax_get_my_option", "accesspress_parallax_get_my_option");

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/options-framework/' );




add_filter('posts_clauses', 'order_by_stock_status');
function order_by_stock_status($posts_clauses) {
    global $wpdb;
    // only change query on WooCommerce loops
    if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {
        $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
        $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
        $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
    }
    return $posts_clauses;
}

// show discount percentage on product card

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

add_action( 'woocommerce_before_shop_loop_item_title', 'mt_show_sale_percentage_loop' ,10);
 
function mt_show_sale_percentage_loop() {
 
global $product;
 echo "<div class=''>10% تخفیف</div>"; 
if ( $product->is_on_sale() ) {

if(preg_match('/(آریا)|(اریا)|(aria)|(arya)/', $product->get_name() , $matches, PREG_OFFSET_CAPTURE)){
echo "<div class='price-discount'><span>10% تخفیف</span></div>"; 
}
}}



add_action( 'init', 'my_init_function');
function my_init_function() {
add_action( 'woocommerce_after_shop_loop_item', 'bbloomer_show_free_shipping_loop', 5 );
 
function bbloomer_show_free_shipping_loop() {
   echo '<div class="price-discount">Free Shipping</div>';
}
}


