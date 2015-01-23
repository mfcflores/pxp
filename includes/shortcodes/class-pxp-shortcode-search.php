<?php
/**
 *	Search Shortcode.
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

if( !class_exists( 'PXP_Shortcode_Search' ) )
{

class PXP_Shortcode_Search
{
	public function __construct()
	{
	}
	
	public static function output( $atts )
	{
		pxp_get_template( 'class-pxp-template-search.php' );
	}
}

}

return new PXP_Shortcode_Search();

?>