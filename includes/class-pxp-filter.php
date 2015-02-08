<?php
/**
 *	List of Added Filters.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Filter
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Filter' ) )
{

class PXP_Filter
{
	/**
	 * Filter submit button of form in Single Product.
	 *
	 * @param String $button String containing the button to be filtered.
	 * @param Object $form Form Object of Gravity Forms.
	 */
	public static function pxp_single_product_gform_submit_button( $button, $form )
	{
		global $post_id;
		
		$product_form = get_post_meta( $post_id , '_product_form', true );
		
		if( $product_form == $form['id'] ) :
			$field = "<p class='ac'>";
			$field .= "<input type='hidden' value='{$post_id}' name='add-to-cart' />";			
			$field .= "<button class='button pxp_product-selected' type='submit' id='gform_submit_button_{$form["id"]}'><span>Add to Cart</span></button>";
			$field .= "</p>";
			
			
			return $field;
		endif;
	
		return $button;
	}
}

}

?>