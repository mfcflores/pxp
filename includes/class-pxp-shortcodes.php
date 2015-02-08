<?php
/**
 *	Manage plugin shortcodes.
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

if( !class_exists( 'PXP_Shortcodes' ) )
{

class PXP_Shortcodes
{
	public function __construct()
	{
		$shortcodes = array(
			'registration'		=> __CLASS__ . '::registration',
			'cart'				=> __CLASS__ . '::cart',
			'checkout'			=> __CLASS__ . '::checkout',
			'login'				=> __CLASS__ . '::login',
			'search'			=> __CLASS__ . '::search',
		);
		
		foreach( $shortcodes as $shortcode => $function )
		{
			// Include Shortcode Class.
			include( 'shortcodes/class-pxp-shortcode-' . $shortcode . '.php' );
			
			add_shortcode( 'pxp_' . $shortcode, $function );
		}
	}
	
	public static function registration( $atts, $contents = '' )
	{
		return PXP_Shortcode_Registration::output( $atts );
	}
	
	public static function cart( $atts, $contents = '' )
	{
		return PXP_Shortcode_Cart::output( $atts );
	}
	
	public static function checkout( $atts, $contents = '' )
	{
		return PXP_Shortcode_Checkout::output( $atts );
	}
	
	public static function login( $atts, $contents = '' )
	{
		return PXP_Shortcode_Login::output( $atts );
	}
	
	public static function search( $atts, $contents = '' )
	{
		return PXP_Shortcode_Search::output( $atts );
	}
}

}

return new PXP_Shortcodes();

?>