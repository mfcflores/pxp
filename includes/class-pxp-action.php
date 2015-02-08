<?php
/**
 *	List of Added Actions.
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

if( !class_exists( 'PXP_Action' ) )
{

class PXP_Action
{
	/**
	 * Redirect user to Cart page after form submit.
	 *
	 * @param Object $entry Entry Object entered by user.
	 * @param Object $form Form Object from Gravity Forms.
	 */
	public static function pxp_redirect_after_gform_submit( $entry, $form )
	{
		global $cart;
		
		$cart_page = get_option( 'pxp_cart_page' );
		
		$add_to_cart = $_POST['add-to-cart'];
		
		// Assigned Entry ID and Entry Values.
		$product_form = array( 'ID' => $entry['id'], 'entry' => array() );
		
		foreach( $form['fields'] as $field )
		{
			if( $field['label'] != NULL )
			{
				$value = $entry[ $field['id'] ];
				
				// Check if value is serialized.
				if( is_serialized( $value ) )
					$value = unserialize( $value );
				
				$product_form['entry'][] = array(
					'id'	=> $field['id'],
					'label' => $field['label'],
					'value' => $value
				);
			}
		}
		
		// Add item to cart with gform.
		$cart->gform_add_cart( $add_to_cart, $product_form );

		// Redirect to Cart page after add item to cart.
		wp_redirect( get_permalink( $cart_page ) );
		
		die();
	}
	
	/**
	 * Redirect user when visiting specific page.
	 */
	public static function pxp_template_redirect()
	{
		global $cart;
		
		// Get Cart page post_id.
		$cart_page = get_option( 'pxp_cart_page' );
		
		// Get Checkout page post_id.
		$checkout_page = get_option( 'pxp_checkout_page' );

		if( is_page( $cart_page ) && ! is_user_logged_in() )
		{
			// Get Login page post id.
			$login_page = get_option( 'pxp_login_page' );
			
			wp_redirect( get_permalink( $login_page ) );        
			exit();   
		}
		
		if( is_page( $checkout_page ) && $cart->is_cart_empty() )
		{
			wp_redirect( get_permalink( $cart_page ) );        
			exit();   
		}
	}
	
	/**
	 * Register pxp plugin widgets.
	 */
	public static function register_pxp_widget()
	{
		register_widget( 'PXP_Widget_Featured_Products' );
		register_widget( 'PXP_Widget_Search' );
		register_widget( 'PXP_Widget_Product_Categories' );
	}
}

}

?>