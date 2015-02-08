<?php
/**
 *	Manage checkout orders.
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

if( !class_exists( 'PXP_Checkout' ) )
{

class PXP_Checkout
{
	/**
	 * Process checkout order.
	 */
	public static function checkout_order()
	{
		global $cart, $user_ID, $client;

		if( !isset( $_POST['pxp_checkout_order_nonce'] ) ) 
		{ return __( '<i class="fa fa-close text-red"></i> Something went wrong. Please try again.' ); exit(); }

		$nonce = $_POST['pxp_checkout_order_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_checkout_order' ) ) 
		{ return __( '<i class="fa fa-close text-red"></i> Something went wrong. Please try again.' ); exit(); }
	
		/***** Proceed Order *****/
		$update = NULL;
		
		$orders  		= $cart->get_cart_checkout();
		$promo_code 	= $cart->get_promo_code();
		$total_price	= $cart->get_total_price();
		$user_credits	= get_user_meta( $user_ID, 'pxp_user_credits', true );
		
		$order_email	= "orders@pixelpartnershq.com"; // Email address of Admin Order.
		
		if( $user_credits < $total_price )
		{
			return __( "<i class='fa fa-close text-red'></i> Sorry, you don't have enough credits." ); exit();
		}
		
		$order_id = get_option( 'pxp_order_id' );
		
		$check = check_id_exists( 'pxp_orders', '_order_id', $order_id );
		
		// Check if Order ID already exists.
		while( $check === true )
		{
			// Increase Order ID.
			$order_id++;

			// Check again.
			$check = check_id_exists( 'pxp_orders', '_order_id', $order_id );
		}

		$first_name	= sanitize_text_field( $_POST['first_name'] );
		$last_name 	= sanitize_text_field( $_POST['last_name'] );
		
		$order = array(
			'order_id'		=> $order_id,
			'country'		=> sanitize_text_field( $_POST['country'] ),
			'first_name'	=> $first_name,
			'last_name'		=> $last_name,
			'contact_name'	=> $first_name . ' ' . $last_name,
			'company_name'	=> sanitize_text_field( $_POST['company_name'] ),
			'email'			=> sanitize_text_field( $_POST['email'] ),
			'order_notes'	=> esc_textarea( $_POST['order_notes'] ),
			'orders'		=> $orders,
			'order_total'	=> $total_price,
			'order_date'	=> date( 'Y-m-d H:i:s' ),
			'promo_code'	=> $promo_code,
			'user_id'		=> $user_ID,
			'order_status'	=> 'Processing'
		);
		
		$post_data = array(
			'post_title'	=> 'Order ' . $order_id,
			'post_name'		=> 'order-' . $order_id,
			'post_status'	=> 'publish',
			'post_type'		=> 'pxp_orders'
		);
		
		$post_id = wp_insert_post($post_data);
		
		foreach($order as $key => $value)
		{
			update_post_meta( $post_id, '_' . $key, $value);
		}
		
		update_option( 'pxp_order_id', $order_id + 1 );
		
		$cart->unset_cart();
		
		return 'Checkout successful.';		
	}	
}

}

return new PXP_Checkout();

?>