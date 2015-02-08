<?php
/**
 *	Manages Plugin Shopping Cart.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Front-end
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Cart' ) )
{

class PXP_Cart
{
	private $cart_contents = array();
	
	private $promo_code_contents = array();
	
	private $cart_total_price = 0; // Total price of cart excluding promo code.
	
	private $promo_code_price = 0;
	
	private $total_price = 0; // Total price of cart inclusion of promo code.
	
	private $wp_session;
	
	public function __construct()
	{
		wp_session_start();
		
		$this->wp_session = WP_Session::get_instance(); 
		
		// Set the contents in cart.
		$this->update_cart_contents();
		
		// Get products frontend
		add_action( 'wp_ajax_pxp_ajax_add_cart', array( $this, 'pxp_ajax_add_cart' ) );
		add_action( 'wp_ajax_noprov_pxp_ajax_add_cart', array( $this, 'pxp_ajax_add_cart' ) );
	}
	
	/**
	 * Get list of item contents in Cart session.
	 *
	 * @return Array $cart
	 */
	public function get_cart()
	{
		$cart_contents = $this->cart_contents;
		
		$cart = array();
	
		print_r($cart_contents);
	
		$cart_page = get_option( 'pxp_cart_page' ); // Post ID of Cart Page.
	
		if( !$this->is_cart_empty() ) :
			foreach( $cart_contents as $item ) :
				// Check Product ID if exists in Cart.
				if( !array_key_exists( $item['ID'], $cart ) ) :
					$featured_img_url 	= wp_get_attachment_thumb_url( get_post_thumbnail_id( $item['post_id'] ) ); 

					$encrypt_key 	= pxp_encrypt_decrypt_key( 'encrypt', 'product-' . $item['ID'] );
					$wp_nonce		= wp_create_nonce( 'pxp-product' );
					$remove_url 	= rtrim( get_permalink( $cart_page ), "/" ) . '?remove_item=' . $encrypt_key . '&_wpnonce=' . $wp_nonce;
				
					$product_price = get_post_meta( $item['post_id'], '_product_price', true );

					// Gravity Forms
					$gform = ( isset( $item['gform'] ) ) ? $item['gform'] : NULL;
				
					$cart[ $item['ID'] ] = array(
						'ID'			=> $item['ID'],
						'post_id'		=> $item['post_id'],
						'name'			=> $item['name'],
						'price'			=> $product_price,
						'total_price'	=> $product_price,
						'attachment_id'	=> get_post_thumbnail_id( $item['post_id'] ),
						'featured_img'	=> $featured_img_url,
						'quantity'		=> 1,
						'gform'			=> $gform,
						'remove_url'	=> $remove_url
					);
				else:
					$cart[ $item['ID'] ]['total_price'] += $product_price;
					$cart[ $item['ID'] ]['quantity']++;
				endif;
		
				$this->cart_total_price += $item['price'];
		
			endforeach;
		endif;

		return $cart;
	}
	
	/**
	 * Get list of items order.
	 *
	 * @return Array $cart
	 */
	public function get_cart_checkout()
	{
		$cart_contents = $this->cart_contents;
		
		$cart = array();
	
		if( !$this->is_cart_empty() ) :
			foreach( $cart_contents as $item ) :
				// Check Product ID if exists in Cart.
				if( !array_key_exists( $item['ID'], $cart ) ) :
					// Gravity Forms
					$gform = ( isset( $item['gform'] ) ) ? $item['gform'] : NULL;
				
					$cart[ $item['ID'] ] = array(
						'ID'			=> $item['ID'],
						'post_id'		=> $item['post_id'],
						'name'			=> $item['name'],
						'price'			=> $item['price'],
						'total_price'	=> $item['price'],
						'quantity'		=> 1,
						'gform'			=> $gform,
					);
				else:
					$cart[ $item['ID'] ]['total_price'] += $item['price'];
					$cart[ $item['ID'] ]['quantity']++;
				endif;
		
				$this->cart_total_price += $item['price'];
		
			endforeach;
		endif;

		return $cart;
	}
	
	/**
	 * Get list of promo codes applied in cart.
	 *
	 * @return Array $promo_codes
	 */
	public function get_promo_code()
	{		
		$this->promo_code_price = $this->promo_code_contents['amount'];
	
		return $this->promo_code_contents;
	}
	
	/**
	 * Update cart contents.
	 */
	public function update_cart_contents()
	{
		$this->set_cart_contents();
		
		$this->set_promo_code_contents();
	}
	
	/**
	 * Set product contents in cart.
	 */
	public function set_cart_contents()
	{
		if( $this->is_cart_set() ) :
			$this->cart_contents = $this->wp_session['pxp_cart']->toArray();
		endif;
	}
	
	/**
	 * Set promo code contents in cart.
	 */
	public function set_promo_code_contents()
	{		
		if( $this->is_promo_codes_set() ) :
			$this->promo_code_contents = $this->wp_session['pxp_cart_promo_code']->toArray();
		else : 
			$this->promo_code_contents = NULL;
		endif;
	}
	
	/**
	 * Add item to session cart.
	 *
	 * @param Int $post_id The Post ID.
	 */
	public function add_cart( $post_id )
	{
		global $pxp_products;

		$product = $pxp_products->get_product( $post_id );
		
		// Check if PXP_Cart session is not set.
		if( !isset( $this->wp_session['pxp_cart'] ) ):
			$this->wp_session['pxp_cart'] = array();
		endif;
		
		$this->wp_session['pxp_cart'][] = array(
			'ID'		=> $product->product_id,
			'post_id' 	=> $post_id,
			'name'		=> $product->name,
			'price'		=> $product->price
		);
	}
	
	/**
	 * Add item to session cart with Gravity Forms data.
	 *
	 * @param Int $post_id The Post ID
	 * @param Array $gform Array of data from gravity forms.
	 */
	public function gform_add_cart( $post_id , $gform )
	{
		$pxp_products = new PXP_Products_External( $post_id );
	
		$product = $pxp_products->get_product( $post_id );
		
		// Check if PXP_Cart session is not set.
		if( !isset( $this->wp_session['pxp_cart'] ) ):
			$this->wp_session['pxp_cart'] = array();
		endif;

		$this->wp_session['pxp_cart'][] = array(
			'ID'		=> $product->product_id,
			'post_id' 	=> $post_id,
			'name'		=> $product->name,
			'price'		=> $product->price,
			'gform'		=> $gform
		);
	}
	
	/**
	 * Get total credits of cart excluding promo code.
	 */
	public function get_cart_total_price()
	{
		$cart_contents = $this->cart_contents;
		
		$this->cart_total_price = 0;
		
		if( !$this->is_cart_empty() ) :
			foreach( $cart_contents as $item ) :
				$product_price = get_post_meta( $item['post_id'], '_product_price', true );
				$this->cart_total_price += $product_price;
			endforeach;
		endif;

		
		return $this->cart_total_price;
	}
	
	/**
	 * Get total credits of cart including promo code.
	 */
	public function get_total_price()
	{
		$cart_price	= $this->get_cart_total_price();
		$promo_code	= $this->get_promo_code();
		
		$this->total_price = $cart_price - $promo_code['amount'];

		return $this->total_price;
	}
	
	/**
	 * Check if Product ID exists in cart.
	 *
	 * @param Int $product_id The ID of Product.
	 * @param Array $cart Array List of products in Cart.
	 * @return bool
	 */
	public function pxp_check_product_in_cart( $product_id, $cart )
	{
		foreach( $cart as $item )
		{
			if( $item['ID'] == $product_id )
				return true;
		}
		
		return false;
	}
	
	/**
	 * Use promo code for discount in cart price.
	 *
	 * @param String $promo_code Promo Code used for discount.
	 */
	public function apply_promo_code_cart( $promo_code )
	{
		if( !isset( $_POST['pxp_promo_code_nonce'] ) ) { return 'Something went wrong. Please try again.'; }

		$nonce = $_POST['pxp_promo_code_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_promo_code' ) ) { return 'Something went wrong. Please try again.'; }
		
		// Check if cart is empty and break.
		if( $this->is_cart_empty() )
			return 'Empty cart, unable to add promo code. Return to shop.';
		
		// Check if Promo Codes in cart is not set and assign array value.
		if( !$this->is_promo_codes_set() ):
			$this->wp_session['pxp_cart_promo_code'] = NULL;
		endif;
	
		// Check promo code and get details.
		$apply = PXP_Promo_Codes::pxp_promo_codes_apply( $promo_code );
		
		if( $apply == "not_found" ) :
			return 'Promo Code does not exist.';
		else:
			$promo_code_contents = $this->promo_code_contents;
		
			// Check if promo code is already applied.
			if( isset( $promo_code_contents['promo_code'] ) && $promo_code_contents['promo_code'] == $promo_code ):
				return 'Promo Code already applied.';
			endif;
				
			// Check if promo code is not yet applied.
			if( isset( $promo_code_contents['promo_code'] ) ) :
				return "Sorry, Promo Code '" . $promo_code_contents['promo_code'] . "' has already been applied. Cannot use more than one promo code.";
			endif;
			
			// Assign promo code details to session cart.
			$this->wp_session['pxp_cart_promo_code'] = $apply; 
		
			// Refresh Cart Content
			$this->update_cart_contents();
			
			return 'Promo Code applied successfully';
		endif;
		
		return 'Something went wrong. Please try again.';
	}
	
	/**
	 * Remove the product/item in the cart.
	 * 
	 * @param String $item Encrypted product id.
	 * @param String $nonce 'pxp-product' created nonce for validation.
	 */
	public function remove_item_cart( $item, $nonce )
	{
		if( !wp_verify_nonce( $nonce, 'pxp-product' ) )
			return 'Something went wrong. Unable to remove item.';
		
		$decrypt_key = pxp_encrypt_decrypt_key( 'decrypt', $item );
		
		$product 	= explode( "-", $decrypt_key );
		$product_id	= $product[1];
		
		$cart = $this->cart_contents;

		$product_name = "";
		
		foreach( $cart as $key => $item )
		{
			if( $item['ID'] == $product_id )
			{
				$product_name = $item['name'];
				
				$this->unset_item_cart( $key );
			}
		}
		
		// Refresh Cart Content
		$this->update_cart_contents();
		
		if( $this->item_exists_in_cart( $product_id ) ):
			return 'Product not found in cart.';
		endif;
		
		if( $this->is_cart_empty() ) :
			$this->unset_promo_code();
			
			// Refresh Cart Content
			$this->update_cart_contents();
		endif;
		
		return $product_name . ' has been removed.';
	}
	
	/**
	 * Remove currently applied promo code.
	 *
	 * @param String $promo_code The promo code applied to be removed.
	 *
	 * @return String Update message.
	 */
	public function remove_promo_code( $promo_code )
	{
		if( $this->is_promo_codes_set() ):
			$promo_code_contents = $this->promo_code_contents;
			
			if( $promo_code_contents['promo_code'] == $promo_code ):
				$this->unset_promo_code();
				
				// Update cart.
				$this->update_cart_contents();
				
				return "Promo Code '" . $promo_code . "' has been removed.";
			endif;
		endif;
		
		return "Promo Code '" . $promo_code . "' does not exist.";
	}
	
	/** 
	 * Check if cart is set.
	 *
	 * @return bool
	 */
	public function is_cart_set()
	{
		return ( isset( $this->wp_session['pxp_cart'] ) );
	}
	
	/** 
	 * Check if promo codes is set.
	 *
	 * @return bool
	 */
	public function is_promo_codes_set()
	{
		return ( isset( $this->wp_session['pxp_cart_promo_code'] ) );
	}
	
	/**
	 * Check if cart is empty.
	 *
	 * @return bool
	 */ 
	public function is_cart_empty()
	{
		return ( empty( $this->cart_contents ) );
	}
	
	/**
	 * Check if item is in cart.
	 *
	 * @param Int $item_id Item ID to be checked in session cart.
	 * @return bool
	 */
	public function item_exists_in_cart( $item_id )
	{
		$cart_contents = $this->cart_contents;
		
		foreach( $cart_contents as $item )
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Unset product in session cart.
	 *
	 * @param int $key Array key.
	 */
	public function unset_item_cart( $key )
	{
		unset( $this->wp_session['pxp_cart'][ $key ] );
	}
	
	/**
	 * Unset promo code in session cart.
	 *
	 * @param String $promo Promo Code applied in cart.
	 */
	public function unset_promo_code( $promo_code = '' )
	{
		unset( $this->wp_session['pxp_cart_promo_code'] );
	}
	
	/**
	 * Reset Cart session.
	 */
	public function unset_cart()
	{
		unset( $this->wp_session['pxp_cart'] );
		$this->unset_promo_code();
	}
	
	/**
	 * Reset WP Session.
	 */
	public function pxp_reset_cart()
	{
		wp_session_unset();
	}
	
	/**
	 * AJAX Add product to cart.
	 */
	public function pxp_ajax_add_cart()
	{
		$post_id = $_POST['post_id'];
		
		$shopping_cart_url 	= get_the_permalink( get_option( 'pxp_cart_page' ) );
		
		$this->add_cart( $post_id );
		
		echo json_encode( array( $shopping_cart_url ) );
		
		wp_die();
	}
}

}

?>