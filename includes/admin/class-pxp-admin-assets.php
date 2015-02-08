<?php
/**
 *	Import scripts to plugin.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin
 *	@package 	PixelPartners/Class
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Admin_Assets' ) )
{

class PXP_Admin_Assets
{
	public function __construct()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'pxp_admin_scripts' ) );
	}
	
	/**
	 * Enqueue & Register scritps and styles.
	 */
	public function pxp_admin_scripts( $hook )
	{
		global $post;
		
		wp_enqueue_script	( 'jquery', PXP_URL . '/assets/js/jquery.js' );
		
		wp_register_script	( 'jquery-ui', PXP_URL . '/assets/js/jquery-ui.js');
		wp_enqueue_script	( 'jquery-ui' );
		
		wp_register_script	( 'jquery-validation', PXP_URL . '/assets/js/jquery.validate.min.js');
		wp_enqueue_script	( 'jquery-validation' );
		
		wp_register_script	( 'jquery-autocomplete', PXP_URL . '/assets/js/jquery.autocomplete.js');
		wp_enqueue_script	( 'jquery-autocomplete' );
		
		wp_register_style	( 'jquery-ui-style', PXP_URL . '/assets/css/jquery-ui.css');
		wp_enqueue_style	( 'jquery-ui-style' );
		
		if( isset( $post ) && $post->post_type == "pxp_products" )
		{
			//$this->pxp_initiate_bootstrap( $hook );
			
			wp_enqueue_media();
		}
		
		wp_enqueue_script	( 'password-strength-meter' );
		
		wp_register_style	( 'pxp-fontawesome', PXP_URL . '/assets/css/font-awesome.min.css' );
		wp_enqueue_style	( 'pxp-fontawesome' );
		
		wp_register_style	( 'pxp-style', PXP_URL . '/assets/css/admin.css' );
		wp_enqueue_style	( 'pxp-style' );
		
		wp_register_script	( 'pxp-script', PXP_URL . '/assets/js/admin.js' );
		wp_enqueue_script	( 'pxp-script' );
		
		wp_localize_script	( 'pxp-script', 'admin_url', admin_url() );
	}
	
	/**
	 * Initiate Bootstrap CSS and JS.
	 */
	public function pxp_initiate_bootstrap()
	{
		wp_register_style	( 'pxp-bootstrap-css', PXP_URL . '/assets/css/bootstrap.css' );
		wp_enqueue_style	( 'pxp-bootstrap-css' );
		
		wp_register_style	( 'pxp-bootstrap-theme', PXP_URL . '/assets/css/bootstrap-theme.min.css' );
		wp_enqueue_style	( 'pxp-bootstrap-theme' );
		
		wp_register_script	( 'pxp-bootstrap-js', PXP_URL . '/assets/js/bootstrap.min.js' );
		wp_enqueue_script	( 'pxp-bootstrap-js' );
	}
}

}

new PXP_Admin_Assets();

?>