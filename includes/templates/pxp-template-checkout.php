<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Order Page
 */
?>

<?php
global $cart, $message;

$cart_contents = $cart->get_cart();
$promo_code_contents = $cart->get_promo_code();
$countries = get_countries();
?>

<h1 class="page-title"><?php the_title(); ?></h1>

<?php echo apply_filters( 'pxp_notification_filter', $message ); ?>

<div id="order-area"> 
	<form method="post" action="<?php echo get_permalink( get_option( 'pxp_checkout_page' ) ); ?>">

	<table cellspacing="0" class="pxp_order">
		<thead>
			<tr>
				<th>Product</th>
				<th>Quantity</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach( $cart_contents as $item ) :
		?>
				<tr>
					<td><?php _e( $item['name'] ); ?></td>
					<td class="ac"><?php _e( $item['quantity'] ); ?></td>
					<td class="ac"><?php _e( $item['total_price'] . ' Credits' ); ?></td>
				</tr>
		<?php
			endforeach;
		?>
			<thead>
			<?php
				if( !empty( $promo_code_contents ) ) : 
			?>				
				<tr>
					<th><?php _e( 'Promo Code : ' ); ?></th>
					<th><?php _e( $promo_code_contents['promo_code'] ); ?></th>
					<th><?php _e( '-' . $promo_code_contents['amount'] . ' Credits'); ?></th>
				</tr>
				
			<?php 
				endif;
			?>
			
				
				<tr>
					<th>Total</th>
					<th></th>
					<th><?php _e( $cart->get_total_price() . ' Credits' ); ?></th>
				</tr>
			</thead>	
		</tbody>
	</table>

	<div class="pxp_billing-address">
		<h2><?php _e( 'Billing Address' ); ?></h2>
		<?php wp_nonce_field( 'pxp_checkout_order', 'pxp_checkout_order_nonce' ); // Add an nonce field so we can check for it later. ?>
		<div class="input-group">
			<p><?php _e( 'Country <span class="text-red">*</span>' ); ?></p>
			<p>
				<select name="country">
		<?php
			foreach( $countries as $key => $country ) :
				_e( '<option value="' . $key . '">' . $country . '</option>' );
			endforeach;
		?>
				</select>
			</p>
		</div>
		
		<div class="fl">
			<label for="first_name">
				<p><?php _e( 'First Name' ); ?></p>
				<p><input type="text" id="first_name" name="first_name" /></p>
			</label>			
		</div>
		<div class="fl">
			<label for="last_name">
				<p><?php _e( 'Last Name' ); ?></p>
				<p><input type="text" id="last_name" name="last_name" /></p>
			</label>
		</div>
		 <br style="clear:both;">	
		 <div class="input-group">
			<label for="company_name">
				<p><?php _e( 'Company Name' ); ?></p>
				<p><input type="text" id="company_name" name="company_name" /></p>
			</label>
			<label for="email">
				<p><?php _e( 'Email Address' ); ?></p>
				<p><input type="text" id="email" name="email" /></p>
			</label>
			 
			 <h2><?php _e( 'Additional Information' ); ?></h2>
			 <label>
				<p><?php _e( 'Order Notes' ); ?></p>
				<p><textarea name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery."></textarea></p>
			</label>
		 </div>
	</div> 

	<p><input type="submit" name="checkout" value="PLACE ORDER" class="button"></p>
	</form>
</div>