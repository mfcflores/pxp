<?php
/*
Plugin Name: Pixel Partners
Plugin URI:  http://illumedia.net
Description: Pixel Partners Commerce
Version:     1.0.0
Author:      Mark Francis C. Flores & Illumedia Outsourcing
Author URI:  http://illumedia.net
License:     GPLv2
*/

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PixelPartners' ) )
{

/**
 * Main PixelPartners Class
 *
 * @class PixelPartners
 * @since 1.0.0
 */
final class PixelPartners
{
	/**
	 * Plugin version
	 * @var string $version Plugin version. 
	 */
	public $version = '1.0.0';
	
	public function __construct()
	{	
		global $cart, $pxp_session;
		
		// Initiate define constants
		$this->pxp_defines();
	
		// Initiate includes Admin & Front-end
		$this->pxp_includes();
	
		if( is_admin() )
		{
			// Initiate included admin files
			$this->pxp_admin_includes();
		}
		
		if( !is_admin() || defined( 'DOING_AJAX' ) )
		{
			// Initiate include front-end files
			$this->pxp_frontend_includes();
			
			$cart = new PXP_Cart();
		}
		
		// Start session
		wp_session_start();
		$pxp_session = WP_Session::get_instance();
		
		// Add Actions
		$this->pxp_actions();
		
		// Add Filters
		$this->pxp_filters();
	}
	
	/**
	 * Define constant variables.
	 */
	public function pxp_defines()
	{
		$siteurl = get_option('siteurl');
		
		define( 'PXP_FILE', __FILE__ );
		define( 'PXP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); 
		define( 'PXP_FOLDER', dirname( PXP_PLUGIN_BASENAME ) ); 
		define( 'PXP_URL', $siteurl . '/wp-content/plugins/' . PXP_FOLDER ); 
		define( 'PXP_FILE_PATH', dirname( __FILE__ ) );
		define( 'PXP_DIR_NAME', basename( PXP_FILE_PATH ) );
		define( 'PXP_VERSION', $this->version );
	}
	
	/**
	 * Initiate general include files.
	 */
	public function pxp_includes()
	{
		global $pxp, $pxp_products;
		
		// Include user manage class
		include_once( 'includes/class-pxp-users.php' );
		
		// Plugin Library
		include_once( 'lib/class-pxp-library.php' );
		
		// Custom Post Type
		include_once( 'includes/class-pxp-cpt.php' );
		
		// Include plugin helper functions
		include_once( 'lib/pxp_helpers.php' );
		
		// Custom Page Templates
		include_once( 'includes/templates/class-pxp-templates.php' );
		
		// Add Plugin shortcodes
		include_once( 'includes/class-pxp-shortcodes.php' );
		
		// Include Ajax functions
		include_once( 'includes/class-pxp-ajax.php' );
		
		// Add Plugin shortcodes
		include_once( 'includes/class-pxp-products-external.php' );
		
		// Initiate plugin widget.
		include_once( 'includes/widgets/class-pxp-widget-featured-products.php' );
		include_once( 'includes/widgets/class-pxp-widget-search.php' );
		include_once( 'includes/widgets/class-pxp-widget-product-categories.php' );
		
		// Include WP Session Class by Eric Mann
		$pxp->include_wp_session();
	}
	
	/**
	 * Include necessary admin files.
	 */
	public function pxp_admin_includes()
	{	
		// Admin Assets
		include_once( 'includes/admin/class-pxp-admin-assets.php' );
	
		// Install Hook
		include_once( 'includes/class-pxp-install.php' );
		
		// Admin Menus
		include_once( 'includes/class-pxp-admin-menus.php' );
		
		// Plugin roles
		include_once( 'includes/class-pxp-roles.php' );
		
		// Custom Page Templates
		include_once( 'includes/templates/class-pxp-templates.php' );
		
		// Client side page
		include_once( 'includes/clients/class-pxp-clients.php' );
	}
	
	/**
	 * Include necessary front-end files.
	 */
	public function pxp_frontend_includes()
	{
		// Front-end Assets
		include_once( 'includes/class-pxp-frontend-assets.php' );
		
		// Initiate Plugin Shopping Cart.
		include_once( 'includes/class-pxp-cart.php' );
		
		// Initiate Plugin Checkout Order.
		include_once( 'includes/class-pxp-checkout.php' );
	}
	
	/**
	 * Initiate Plugin Actions.
	 */
	public function pxp_actions()
	{
		// Fire plugin actions.
		include_once( 'includes/class-pxp-action.php' );
		
		// Redirect after form submission
		add_action('gform_after_submission', 'PXP_Action::pxp_redirect_after_gform_submit', 10, 2);
		
		// Register plugin widget.
		add_action( 'widgets_init', 'PXP_Action::register_pxp_widget' );
		
		// Redirect User when template visit.
		add_action( 'template_redirect', 'PXP_Action::pxp_template_redirect' );
		
		add_action( 'init', array( $this, 'pxp_init' ) );
	}
	
	/**
	 * Initiate Plugin Filters.
	 */
	public function pxp_filters()
	{
		global $pxp;
		
		// Fire plugin filters.
		include_once( 'includes/class-pxp-filter.php' );
		
		// Filter Gravity Form Submit Button
		add_filter("gform_submit_button", "PXP_Filter::pxp_single_product_gform_submit_button", 10, 2);
		
		add_filter( 'pxp_notification_filter', array( $pxp, 'pxp_notification_filter' ) );
	}
	
	/**
	 * Initiate WP Init action.
	 */
	public function pxp_init()
	{
		global $user_ID, $client;
		
		if( is_user_logged_in() ):
			// Initiate client account details.
			$client = new PXP_Users( $user_ID );
		else:
		
		endif;
	}
}

}

if( class_exists( 'PixelPartners' ) )
{	
	new PixelPartners();
}
?>