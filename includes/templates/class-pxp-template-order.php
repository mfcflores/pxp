<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Order Page
 */
?>

<h1 class="page-title">Order</h1>
<div id="order-area"> 
	<form method="post">

	<table cellspacing="0" class="pxp_order">
		<thead>
			<tr>
				<th>Product</th>
				<th>Quantity</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Product Name 1</td>
				<td class="ac">1</td>
				<td class="ac">3 Credits</td>
			</tr>
					
			<tr>
				<td>Product Name 2</td>
				<td class="ac">4</td>
				<td class="ac">20 Credits</td>
			</tr>
			<thead>
				<tr>
					<th>Total</th>
					<th></th>
					<th>23 Credits</th>
				</tr>
			</thead>	
		</tbody>
	</table>

	<div class="pxp_billing-address">
		<h2>Billing Address</h2>
		<div class="input-group">
		 Country *  <br> <input type="text"/> 
		</div>
		
		<div class="fl">
			First Name <br> <input type="text"/>      
		</div>
		<div class="fl">
			Last Name  <br> <input type="text"/> 
		</div>
		 <br style="clear:both;">	
		 <div class="input-group">
			 Company Name <br> <input type="text"/> <br>
			 Email Address <br> <input type="text" /> <br>
			 
			 <h2>Additional Information</h2>
			 Order Notes<br>
			 <textarea placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
		 </div>
	</div> 

	<p><input type="submit" value="SUBMIT ORDER" class="button"></p>
	</form>
</div>