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
		// Initiate define constants
		$this->pxp_defines();
	
		if( is_admin() )
		{
			// Initiate included admin files
			$this->pxp_admin_includes();
		}
		
		if( !is_admin() || defined( 'DOING_AJAX' ) )
		{
			// Initiate include front-end files
			$this->pxp_frontend_includes();
		}
		
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
	 * Include necessary admin files.
	 */
	public function pxp_admin_includes()
	{
		// Admin Assets
		include_once( 'includes/admin/class-pxp-admin-assets.php' );
	
		// Install Hook
		include_once( 'includes/class-pxp-install.php' );
		
		// Custom Post Type
		include_once( 'includes/class-pxp-cpt.php' );
		
		// Admin Menus
		include_once( 'includes/class-pxp-admin-menus.php' );
		
		// Plugin roles
		include_once( 'includes/class-pxp-roles.php' );
		
		// Custom Page Templates
		include_once( 'includes/templates/class-pxp-templates.php' );
		
		// Client side page
		include_once( 'includes/clients/class-pxp-clients.php' );
		
		// Include plugin helper functions
		include_once( 'lib/pxp_helpers.php' );
	}
	
	/**
	 * Include necessary front-end files.
	 */
	public function pxp_frontend_includes()
	{
	}
	
	/**
	 * Initiate Plugin Actions.
	 */
	public function pxp_actions()
	{
		add_action( 'wp_head', array( $this, 'pxp_wp_head' ) );
	}
	
	/**
	 * Initiate Plugin Filters.
	 */
	public function pxp_filters()
	{
	}
	
	public function pxp_wp_head()
	{
	}
}

}

if( class_exists( 'PixelPartners' ) )
{	
	new PixelPartners();
}
?>