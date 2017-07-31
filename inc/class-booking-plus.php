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
		//add_action('woocommerce_before_shop_loop_item_title',array( $this, 'show_flat_avaiable_flash' ),10);
				
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

	
	public function show_flat_avaiable_flash(){
		
		if ( class_exists( 'booking_sevice_plus' ) ){
			global $post, $product;
			
			if (  booking_sevice_plus::room_is_bookable( $product->get_id() ) === false ) {
			  echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Available', 'booking-plus' ) . '</span>', $post, $product ); 
			 }

		}else{
			echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Currently not available', 'booking-plus' ) . '</span>', $post, $product ); 
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
		remove_action( 'after_setup_theme', 'storefront_custom_header_setup', 50 );
		remove_action( 'storefront_footer', 'storefront_credit', 20 );
		remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		
	}
	
	
	/**
	 * Enqueue Storefront Styles
	 * @return void
	 */
	public function enqueue_styles() {
		global $storefront_version;

		wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
	}

	public function get_map_marker(){
		// ottengo i dati della categoria woocommerce
		$terms = get_terms( 'product_cat' );
		
		// verifico che non sia vuota
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			
			
			
			foreach ($terms as $term) :
				if( ! is_null ( Booking_Plus_Flat::get_lat($term->term_id)) && class_exists('Booking_Plus_Flat')){
					
					// The $term is an object, so we don't need to specify the $taxonomy.
    
    $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_image( $thumbnail_id , 'thumbnail');
		
					
					
 
    			$term_array[] = array(
					 'title' => $term->name, 
					 'lat' => Booking_Plus_Flat::get_lat($term->term_id), 
					 'lng' => Booking_Plus_Flat::get_lng($term->term_id),
					 'description' => $term->description,
					'link' => esc_url( get_term_link( $term )),
					'img' => $image,
					
					'link_text' => __('Read more', 'woocommerce')
					 );
				}

			endforeach;

		}
		
		
		$group = json_encode($term_array);
		//wp_die( var_dump($group) );
		return $group;
		// {"0":{"a":1,"b":2},"1":{"c":3},"2":{"d":4}}
		
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

		wp_enqueue_style( 'Poppins', '//fonts.googleapis.com/css?family=Poppins:400,700', array( 'storefront-child-style' ) );
		wp_enqueue_script( 'Proximanova', 'https://use.typekit.net/pwh1wmi.js', array( 'storefront-style' ) );
		wp_enqueue_script( 'typekit', get_stylesheet_directory_uri() . '/assets/js/typekit.min.js', array( 'Proximanova'  ), '1.0', true );
		//wp_enqueue_script( 'instafeed', get_stylesheet_directory_uri() . '/assets/js/instafeed.min.js', array(  ), '1.4.1', true );
		//wp_enqueue_script( 'app', get_stylesheet_directory_uri() . '/assets/js/app.js', array( 'instafeed' ), '1.0', true );
		
		//wp_enqueue_style( 'playfair-display', '//fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic', array( 'storefront-style' ) );
		
		if( is_page_template( 'page-templates/template-search.php' ) ){
			
			
		if (class_exists('Booking_Plus_Flat')){
			$handle 			=	'gmaps';
			$src				=	get_stylesheet_directory_uri() . '/assets/js/gmaps.js';
			$dep				=	array('jquery','google-maps');
			$ver 				=	'1.3.3';

			wp_register_script( $handle	, $src, $dep, $ver, true );
			wp_enqueue_script( $handle); 
			
			
		
			$address = Booking_Plus_Flat::return_address();	

			$handle 			=	'theme-map-scripts';
			$src				=	get_stylesheet_directory_uri() . '/assets/js/scripts_map_search.js';
			$dep				=	array('jquery', 'google-maps', 'gmaps');
			$ver 				=	'1';
			$translation_array 	=	array(
										'markers' => json_decode($this->get_map_marker())
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
		
	}
	

}

}

return new Booking_Plus();