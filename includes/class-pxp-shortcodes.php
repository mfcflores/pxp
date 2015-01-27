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
			'order'				=> __CLASS__ . '::order',
			'login'				=> __CLASS__ . '::login',
			'search'			=> __CLASS__ . '::search',
			'product'			=> __CLASS__ . '::product',
			'selected_product'	=> __CLASS__ . '::selected_product'
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
	
	public static function order( $atts, $contents = '' )
	{
		return PXP_Shortcode_Order::output( $atts );
	}
	
	public static function login( $atts, $contents = '' )
	{
		return PXP_Shortcode_Login::output( $atts );
	}
	
	public static function search( $atts, $contents = '' )
	{
		return PXP_Shortcode_Search::output( $atts );
	}
	
	public static function product( $atts, $contents = '' )
	{
		return PXP_Shortcode_Product::output( $atts );
	}
	
	public static function selected_product( $atts, $contents = '' )
	{
		return PXP_Shortcode_Selected_Product::output( $atts );
	}
}

}

return new PXP_Shortcodes();

?>