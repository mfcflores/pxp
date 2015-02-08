<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Cart Page
 */
?>

<?php
global $cart, $user_ID, $client;

$cart_contents = $cart->get_cart();
$promo_code_contents = $cart->get_promo_code();
?>

<h1 class="page-title"><?php _e( the_title() ); ?></h1>
 
<?php 
	global $post_id, $message;

	echo apply_filters( 'pxp_notification_filter', $message ); 
?>
 
<table cellspacing="0" class="pxp_cart">
	<thead>
		<tr>
			<th class="pxp_product-thumbnail">&nbsp;</th>
			<th class="pxp_product-name">Product Name</th>
			<th class="pxp_product-price">Price</th>
			<th class="pxp_product-quantity">Quantity</th>
			<th class="pxp_product-total-price">Total Price</th>
			<th class="pxp_product-remove">Remove</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if( !empty( $cart_contents ) ) :
			foreach( $cart_contents as $item ):
				$featured_img_title	= get_the_title( $item['attachment_id'] );
	?>
		<tr class="">
			<td class="pxp_product-thumbnail ac">
				<img width="90" height="90" src="<?php echo $item['featured_img']; ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo $featured_img_title; ?>">
			</td>

			<td class="pxp_product-name">
				<a href="<?php echo get_permalink( $item['post_id'] ); ?>"><?php _e( $item['name'] ); ?></a>
			<?php
				if( $item['gform'] != NULL ):
					foreach( $item['gform']['entry'] as $field ):
						if( $field['value'] != NULL ):
			?>
						<dl class="variation">
							<dt class="variation-JobTitleReference"><?php _e( $field['label'] ); ?></dt>
							<dd class="variation-JobTitleReference">
								<p><?php _e( $field['value'] ); ?></p>
							</dd>
						</dl>
			<?php
						endif;
					endforeach;
				endif;
			?>
			</td>

			<td class="pxp_product-price ar"><?php _e( $item['price'] . ' Credits' ); ?></td>

			<td class="pxp_product-quantity ar"><?php _e( $item['quantity'] ); ?></td>

			<td class="pxp_product-total-price ar"><?php _e( $item['total_price'] . ' Credits' ); ?></td>

			<td class="pxp_product-remove">
				<a href="<?php echo $item['remove_url']; ?>">x</a>
			</td>
		</tr>		
	<?php
			endforeach;
		endif;
	?>
	
		<tr class="pxp_promo-code">
			<td colspan="6" valign="middle">
				<form action="<?php echo get_permalink( $post_id ); ?>" method="POST">
				<?php wp_nonce_field( 'pxp_promo_code', 'pxp_promo_code_nonce' ); ?>
					<label for="promo_code">Promo Code:</label> <input type="text" name="promo_code" class=	"input-text" id="promo_code" value="" placeholder="" /> 
					<input type="submit" class="pxp_button" name="apply_promo_code" value="Apply Promo Code" /> 
				</form>
			</td>
		</tr>
	</tbody>
</table>

<div class="cart_summary">
	<table>
		<tr class="remaining-credits">
			<th>Remaining Balance</th>
			<td><?php _e( $client->user_details['user_credits'] . ' Credits' ); ?></td>
		</tr>
	</table>
	
	<table cellspacing="0">
		<tr class="cart-total">
			<th>Cart Total</th>
			<td><?php _e( $cart->get_cart_total_price() . ' Credits' ); ?></td>
		</tr>
	<?php
		if( !empty( $promo_code_contents ) ) : 
	?>
		<tr class="promo-code">
			<th><?php _e( 'Promo Code : ' . $promo_code_contents['promo_code'] ); ?></th>
			<td><?php _e( '-' . $promo_code_contents['amount'] . ' Credits'); ?> <a href="?remove_code=<?php echo $promo_code_contents['promo_code']; ?>">[Remove]</a></td>
		</tr>
	<?php 
		endif;
	?>
		<tr>
			<th>Total Price</th>
			<td><?php _e( $cart->get_total_price() . ' Credits' ); ?></td>
		</tr>
	</table>
</div>

<div class="cart-buttons">
	<form action="<?php echo get_permalink( $post_id ); ?>" method="POST">
		<input type="button" class="button" name="update_cart" value="Update" /> 
		<input type="submit" class="checkout-button button alt wc-forward" name="checkout" value="Proceed to Checkout" />
	</form>
</div>