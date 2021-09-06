<?php
/**
 * Plugin Name:  Order Email
 * Plugin URI: http://www.bazarafzar.com
 * Version: 0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function add_expedited_order_woocommerce_email( $email_classes ) {

	// include our custom email class
	require_once( 'includes/class-order-email.php' );

	// add the email class to the list of email classes that WooCommerce loads
	$email_classes['WC_Expedited_Order_Email'] = new WC_Expedited_Order_Email();

	return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'add_expedited_order_woocommerce_email' );
