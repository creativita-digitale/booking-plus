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

function storefront_credit() {
		?>
		<div class="site-info">
			<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ) ); ?>
			<?php if ( apply_filters( 'storefront_credit_link', true ) ) { ?>
			<br /> <?php printf( __( '%1$s designed by %2$s.', 'storefront' ), 'Storefront', '<a href="http://www.woothemes.com" alt="Premium WordPress Themes & Plugins by WooThemes" title="Premium WordPress Themes & Plugins by WooThemes" rel="designer">WooThemes</a>' ); ?>
			<?php } ?>
		</div><!-- .site-info -->
		<?php
	}

require_once( 'inc/class-booking-plus.php' );
require_once( 'inc/class-navigation.php' );
require_once( 'inc/customizer/class-storefront-customizer.php' );