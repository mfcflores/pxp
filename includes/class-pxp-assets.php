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

if( !class_exists( 'PXP_Scripts' ) )
{

class PXP_Scripts
{
	public function __construct()
	{
	}
	
	/**
	 * Enqueue admin scripts.
	 */
	public function pxp_admin_enqueue()
	{	
		add_action( 'admin_enqueue_scripts', array( $this, 'pxp_admin_scripts' ) );
		
		echo PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js';
		
	}
	
	/**
	 * Enqueue & Register scritps and styles.
	 */
	public function pxp_admin_scripts()
	{
		wp_enqueue_script( 'jquery' );
		
		wp_enqueue_script( 'main-script', PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'script.js' );
	}
	
	
	
}

}

?>