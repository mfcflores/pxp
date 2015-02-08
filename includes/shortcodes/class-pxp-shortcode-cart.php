<?php
/**
 *	Cart Shortcode.
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

if( !class_exists( 'PXP_Shortcode_Cart' ) )
{

class PXP_Shortcode_Cart
{	
	/**
	 * Display cart page.
	 */
	public static function output( $atts )
	{
		global $cart, $message;
		
		// Check if cart is empty.
		if( $cart->is_cart_empty() )
		{
			$message = 'Cart is empty. Please add an item to cart.';
		}
		
		// Process Remove Item
		if( isset( $_REQUEST['remove_item'] ) && isset( $_REQUEST['_wpnonce'] ) ) :
			$item 	= $_REQUEST['remove_item'];	// Product ID
			$nonce	= $_REQUEST['_wpnonce']; 	// PXP Products nonce
			
			$message = self::remove_item( $item, $nonce );
		endif;
		
		// Process Remove Promo Code
		if( isset( $_REQUEST['remove_code'] ) ) :
			$promo_code = $_REQUEST['remove_code'];
			
			$message = self::remove_promo_code( $promo_code );
		endif;
		
		if( isset( $_POST['checkout'] ) ) :
			global $cart, $client;
			
			$total_price = $cart->get_total_price();
			
			if( $cart->is_cart_empty() ) : // Check if cart is empty.
				$message = 'Cart is empty. Please add an item to cart.';
			elseif( $client->user_details['user_credits'] == 0 || $client->user_details['user_credits'] == NULL || $client->user_details['user_credits'] < $total_price ) : // Check if User Credits is 0, is null or less than the total price.
				$message = 'You have insufficient credits.';
			else: // Redirect to Checkout page is successful.
				wp_redirect( get_permalink( get_option( 'pxp_checkout_page' ) ) );
				die();
			endif;
		endif;
		
		// Process Add Promo Code
		if( isset( $_POST['apply_promo_code'] ) ) :
			$promo_code = $_POST['promo_code'];
	
			$message = self::apply_promo_code( $promo_code );
		endif;
		
		pxp_get_template( 'pxp-template-cart.php' );
	}
	
	/**
	 * Remove the product/item in the cart.
	 * 
	 * @param String $item Encrypted product id.
	 * @param String $nonce 'pxp-product' created nonce for validation.
	 *
	 * @return String update message.
	 */
	public static function remove_item( $item, $nonce )
	{
		global $cart;
		
		$update = $cart->remove_item_cart( $item, $nonce );
		
		return $update;
	}
	
	/**
	 * Use promo code for discount in cart price.
	 *
	 * @param String $code Promo Code used for discount.
	 *
	 * @return String update message.
	 */
	public static function apply_promo_code( $code )
	{
		global $cart;
		
		$update = $cart->apply_promo_code_cart( $code );
		
		return $update;
	}
	
	/**
	 * Remove currently applied promo code.
	 *
	 * @param String $code Promo Code used for discount.
	 *
	 * @return String update message.
	 */
	public static function remove_promo_code( $code )
	{
		global $cart;
		
		$update = $cart->remove_promo_code( $code );
		
		return $update;
	}
}

}

return new PXP_Shortcode_Cart();

?>