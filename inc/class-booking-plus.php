<?php
/**
 * Booking Plus Class
 *
 * @author   WooThemes
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Booking_Plus' ) ) {

class Booking_Plus {
	/**
	 * Setup class.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_child_styles' ), 99 );
		remove_action ('storefront_header', array( $this, 'storefront_secondary_navigation' ), 30 );
		add_action('init', array( $this, 'unhook_functions' ) );
		
		try{
		if( ! class_exists( 'WooCommerce' ) ){
				 throw new Exception( 'woocommerce non installato' );
			}
			elseif ( ! class_exists( 'WC_Bookings' ) ){
				throw new Exception( 'WC Booking non installato' );
			}
			elseif ( ! class_exists( 'SitePress' ) ){
				throw new Exception( 'WPML non installato' );
			}		
		}
		catch (Exception $e){
			return ("Caught Exception {$e->getMessage()}");
		}
	}

	
	public function check_plugins() {
			
			if( ! class_exists( 'WooCommerce' ) ){
				 throw new Exception( 'woocommerce non installato' );
			}
			elseif ( ! class_exists( 'WC_Bookings' ) ){
				throw new Exception( 'WC Booking non installato' );
			}
			elseif ( ! class_exists( 'SitePress' ) ){
				throw new Exception( 'WPML non installato' );
			}		
		
		}
		
// Unhook default Thematic functions
	public function unhook_functions() {
		//remove_action( 'storefront_before_content',	'storefront_header_widget_region',	10 );
		remove_action( 'storefront_header', 'storefront_product_search', 40 );
		remove_action( 'storefront_header', 'storefront_site_branding', 20 );
		add_action( 'storefront_header', 'storefront_site_branding', 43 );
		
		
		
			remove_action( 'storefront_header', 'storefront_header_cart', 		60 );
			remove_action( 'after_setup_theme', 'custom_header_setup' );
			remove_action( 'after_setup_theme', 	'storefront_custom_header_setup', 50 );
			remove_action( 'storefront_footer', 	'storefront_credit', 20 );
		
			add_action( 'storefront_footer', 	'booking_credits', 20 );
			
	}
	
	
	/**
	 * Enqueue Storefront Styles
	 * @return void
	 */
	public function enqueue_styles() {
		global $storefront_version;

		wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
	}

	/**
	 * Enqueue Storechild Styles
	 * @return void
	 */
	public function enqueue_child_styles() {
		global $storefront_version, $boutique_version;

		/**
		 * Styles
		 */
		wp_enqueue_style( 'booking-plus-woocommerce-style', get_stylesheet_directory_uri() . '/assets/sass/woocommerce/woocommerce.css', array('storefront-woocommerce-style'), $storefront_version );
		
		wp_style_add_data( 'storefront-child-style', 'rtl', 'replace' );

		wp_enqueue_style( 'Poppins', '//fonts.googleapis.com/css?family=Poppins:400,700', array( 'storefront-style' ) );
		wp_enqueue_script( 'Proximanova', 'https://use.typekit.net/pwh1wmi.js', array( 'storefront-style' ) );
		wp_enqueue_script( 'typekit', get_stylesheet_directory_uri() . '/assets/js/typekit.js', array( 'Proximanova'  ), '1.0', true );
		//wp_enqueue_script( 'instafeed', get_stylesheet_directory_uri() . '/assets/js/instafeed.min.js', array(  ), '1.4.1', true );
		//wp_enqueue_script( 'app', get_stylesheet_directory_uri() . '/assets/js/app.js', array( 'instafeed' ), '1.0', true );
		
		//wp_enqueue_style( 'playfair-display', '//fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic', array( 'storefront-style' ) );
		
		if( is_page_template( 'page-templates/template-search.php' ) ){
			
			
			
		
			$address = Booking_Plus_Flat::return_address();	

			$handle 			=	'theme-map-scripts';
			$src				=	get_stylesheet_directory_uri() . '/assets/js/scripts_map_search.js';
			$dep				=	array('google-maps');
			$ver 				=	'1';
			$translation_array 	=	array(
										'address1'	=> 'Arena di Verona, piazza Brà Verona',
										'address2'	=> 'Università degli studi di Verona',
										'address2'	=> 'verona',
										'zoom' 		=> 17,
										'map_tag'	=> 'search_map'
									);


			wp_register_script( $handle	, $src, $dep, $ver, true );
			wp_localize_script( $handle , 'script_data', $translation_array );
			//Load custom JS script    
			wp_enqueue_script( $handle); 



		// Load Google Maps API. Make sure to add the callback and add custom-scripts dependency
		wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyCSdGzaaomcoSbkBqU8YLIRHGqGDeyIYnk',  array(  ) ); 
			//wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?key=AIzaSyCSdGzaaomcoSbkBqU8YLIRHGqGDeyIYnk&callback=initMap',  array( 'custom-scripts' ) ); 

		
			
			
		}
		
	}
	
	public function booking_credits (){
		?>
		<div class="site-info">
			<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ) ); ?>
			<?php if ( apply_filters( 'storefront_credit_link', true ) ) { ?>
			
			<?php } ?>
		</div><!-- .site-info -->
		<?php
	
	}

}

}

return new Booking_Plus();