<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Cart Page
 */
?>

<h1 class="page-title">Cart</h1>
 
<form method="post">
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
			<tr class="">
				<td class="pxp_product-thumbnail ac">
					<img width="90" height="90" src="http://pixelpartnershq.com/wp-content/uploads/2014/05/CUPS-1-TEMPLATE-90x90.jpg" class="attachment-shop_thumbnail wp-post-image" alt="Copyright 2014 My Paper Cups. All Rights Reserved.">
				</td>

				<td class="pxp_product-name">
					<a href="#">Custom Paper Cup Design</a>
					<dl class="variation">
						<dt class="variation-JobTitleReference">Job Title/Reference:</dt>
						<dd class="variation-JobTitleReference"><p>E</p>
						</dd>
					</dl>
				</td>

				<td class="pxp_product-price ar">
					60 Credits
				</td>

				<td class="pxp_product-quantity ar">
					1
				</td>

				<td class="pxp_product-total-price ar">
					60 Credits
				</td>

				<td class="pxp_product-remove">
					<a href="#">x</a>
				</td>
			</tr>
					
			<tr class="pxp_promo-code">
				<td colspan="6" valign="middle">
					<label for="promo_code">Promo Code:</label> <input type="text" name="promo_code" class="input-text" id="coupon_code" value="" placeholder="" /> 
					<input type="submit" class="pxp_button" name="apply_coupon" value="Apply Promo Code" /> 
				</td>
			</tr>
		</tbody>
	</table>
</form>


<div class="cart_summary">
	<table>
		<tr class="remaining-credits">
			<th>Remaining Balance</th>
			<td>1234 Credits</td>
		</tr>
	</table>
	
	<table cellspacing="0">
		<tr class="cart-total">
			<th>Cart Total</th>
			<td>85 Credits</td>
		</tr>
		<tr class="promo-code">
			<th>Promo Code</th>
			<td>-10 Credits</td>
		</tr>
		<tr>
			<th>Total Price</th>
			<td>75 Credits</td>
		</tr>
	</table>
</div>

<div class="cart-buttons">
	<input type="submit" class="button" name="update_cart" value="Update" /> <input type="submit" class="checkout-button button alt wc-forward" name="proceed" value="Place Order" />
</div>