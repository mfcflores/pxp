<?php
/**
 *	Login Shortcode.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Shortcode_Login' ) )
{

class PXP_Shortcode_Login
{
	public function __construct()
	{
	}
	
	public static function output( $atts )
	{
		pxp_get_template( 'class-pxp-template-login.php' );
	}
}

}

return new PXP_Shortcode_Login();

?>