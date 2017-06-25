<?php
/**
 * Boutique engine room
 *
 * @package boutique
 */

/**
 * Set the theme version number as a global variable
 */
$theme				= wp_get_theme( 'booking-plus' );
$Booking_plus_version	= $theme['Version'];

$theme				= wp_get_theme( 'storefront' );
$storefront_version	= $theme['Version'];


require_once( 'inc/class-booking-plus.php' );
require_once( 'inc/class-navigation.php' );
require_once( 'inc/customizer/class-storefront-customizer.php' );

add_action( 'after_setup_theme', 'silvercareone_lang_setup' );
	function silvercareone_lang_setup() {
    load_child_theme_textdomain( 'booking-plus', get_stylesheet_directory() . '/languages' );
}