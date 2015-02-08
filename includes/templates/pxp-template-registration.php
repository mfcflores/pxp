<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Registration Page
 */
?>

<h1 class="page-title">Register</h1>

<?php 
		global $message;

		echo apply_filters( 'pxp_notification_filter', $message ); 
	?>

<div id="register-area">			
	
	<form class="form" method="post" action="#">
	<?php 
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pxp_registration', 'pxp_registration_nonce' );
	?>
		<div class="rowgroup">
			<h2>Your Info</h2>

			<div class="input-group">		
				<input name="user_email" type="text" placeholder="Email" autofocus />
				<br>
				<input name="first_name" type="text" class="input-text" placeholder="First Name" />
				<input name="last_name" type="text" class="input-text" placeholder="Last Name" />
				<br>			
				<input name="phone" type="tel" class="input-text" placeholder="Phone">
				<input name="skype" type="text" class="input-text" placeholder="Skype Name">
			</div>
		</div>
		
		
		<h2>Company Info</h2>

		<div class="input-group">
			<input name="company_name" type="text" placeholder="Company/Trading Name" />
			<input name="company_site" type="text" placeholder="Company Website" />			
			<input name="addrs1" type="text" class="full-size" placeholder="Street Address" size="100" />

			<input name="addrs2" type="text" class="full-size" placeholder="Address Line 2" size="100" />

			<input name="city" type="text" placeholder="City" />
			<input name="zip" type="text" placeholder="Zip/Postal Code" />

			<input name="country" type="text" placeholder="Country" />
		<br>
		</div>
		<div class="input-left">		
			<span class="title">Operating System</span><br>
			<input name="operating_system" type="radio" value="Windows"> Windows <br>
			<input name="operating_system" type="radio" value="Mac"> Mac <br>
			<input name="operating_system" type="radio" value="Both"> Both <br>
			<input name="operating_system" type="radio" value="Other"> Other 
		</div>
		
		<div class="input-right">
			<span class="title">Preferred Design Software</span><br>
			<input name="adobe" type="checkbox" value="Adobe Creative Suite"> Adobe Creative Suite<br>
			<input name="corel" type="checkbox" value="Corel"> Corel<br>
			<input name="other" type="checkbox" value="Other"> Other <input name="other_desc" type="text" class="clean"/><br>
			<span class="title">Adobe Creative Suite Version</span><br>
			<input name="adobe_version" type="text" class="clean"/>
		</div>
		
		<br style="clear:both;">
		<span class="title">Professional Association</span>
		<br>
		<textarea name="professional_association"></textarea>
		<br>
		<span class="title">How long have you been a Marketing Design Professional?</span>
		<br>
		<select name="marketing_design" class="clean">
			<option value="< 1 Year">Less than 1 Year</option>
			<option value="1-5 Years">1-5 Years</option>
			<option value="10+ Years">10+ Years</option>
		</select>
		<br>
		<p><input type="submit" value="REGISTER" class="button"></p>
	</form>
</div>	<!-- end Register Area -->