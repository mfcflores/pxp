<?php
/**
 *	Manage paypal gateway setting.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin, Paypal
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Admin_Paypal' ) )
{

class PXP_Admin_Paypal
{
	public static function output()
	{
		$paypal_settings = ( get_option( 'pxp_paypal_settings' ) != "" ) ? get_option( 'pxp_paypal_settings' ) : NULL;
		
		$paypal_mode 	= $paypal_settings['paypal_mode'];
		$client_id 		= $paypal_settings['client_id'];
		$client_secret 	= $paypal_settings['client_secret'];
		$debug_mode 	= $paypal_settings['debug_mode'];
		
?>
		<h1><?php _e( 'PayPal Gateway Settings' ); ?></h1>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="validate">
		<?php wp_nonce_field( 'pxp_paypal', 'pxp_paypal_nonce' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="pxp_paypal_mode"><?php _e( 'PayPal Live or Sandbox' ); ?></label>
					</th>
					<td>
						<label><input type="radio" name="pxp_paypal_mode" value=1  <?php echo ( $paypal_mode == 1 ) ? "checked=checked" : ""; ?>> <?php _e( 'Live' ); ?></label> <br>
						<i><?php _e( 'Select this option if you want to use PayPal Live' ); ?></i> <br>
						<label><input type="radio" name="pxp_paypal_mode" value=0  <?php echo ( $paypal_mode == 0 ) ? "checked=checked" : ""; ?>> <?php _e( 'Sandbox' ); ?><i><small><?php _e( 'Test Mode' ); ?></small></i></label> <br>
						<i><?php _e( 'Select this option if you want to use PayPal Sandbox Testing' ); ?></i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pxp_paypal_clientid"><?php _e( 'PayPal Client ID' ); ?></label>
					</th>
					<td>
						<input type="text" required name="pxp_paypal_clientid" id="pxp_paypal_clientid" value="<?php echo $client_id;?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pxp_paypal_clientsecret"><?php _e( 'PayPal Client Secret' ); ?></label>
					</th>
					<td>
						<input type="text" required name="pxp_paypal_clientsecret" id="pxp_paypal_clientsecret" value="<?php echo $client_secret;?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pxp_enable_debug_mode"><?php _e( 'Enable Debugging Mode'); ?></label>
					</th>
					<td>
						<input type="checkbox" value="1" <?php echo ( $debug_mode == 1 ) ? "checked=checked" : ""; ?> name="pxp_enable_debug_mode" id="pxp_enable_debug_mode">
					</td>
				</tr>
				<tr valign="top">
					<th colspan="2">
						<input type="hidden" name="pxp_admin_a" value="update_paypal">
						<input type="submit" name="submit" value="Save" class="button-primary" />
					</th>
				</tr>
			</table>
		</form>
<?php
	}

	/**
	 * Manage paypal gateway settings update.
	 */
	public static function update_paypal_settings()
	{
		if( !isset( $_POST['pxp_paypal_nonce'] ) ) { return 'error'; }

		$nonce = $_POST['pxp_paypal_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_paypal' ) ) { return 'error'; }
		
		$settings = array(
			'paypal_mode'	=> isset( $_POST['pxp_paypal_mode'] ) ? $_POST['pxp_paypal_mode'] : 0,
			'client_id'		=> $_POST['pxp_paypal_clientid'],
			'client_secret'	=> $_POST['pxp_paypal_clientsecret'],
			'debug_mode'	=> isset( $_POST['pxp_enable_debug_mode'] ) ? 1 : 0
		);
		
		update_option( 'pxp_paypal_settings', $settings );
		
		return 'updated_paypal';
	}
}

}

return new PXP_Admin_Paypal();

?>