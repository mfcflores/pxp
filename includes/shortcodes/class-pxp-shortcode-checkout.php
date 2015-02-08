<?php
/**
 *	Checkout Shortcode.
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

if( !class_exists( 'PXP_Shortcode_Checkout' ) )
{

class PXP_Shortcode_Checkout
{
	/**
	 * Display checkout page.
	 */
	public static function output( $atts )
	{
		global $message;
		
		// Place order.
		if( !empty($_POST) && isset( $_POST['checkout'] ) ):
			$message = PXP_Checkout::checkout_order();
		endif;
		pxp_get_template( 'pxp-template-checkout.php' );
	}
}

}

return new PXP_Shortcode_Checkout();

?>