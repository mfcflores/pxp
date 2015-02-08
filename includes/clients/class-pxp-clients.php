<?php
/**
 *	Client side page.
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

if( !class_exists( 'PXP_Clients' ) )
{

class PXP_Clients
{
	public $countries;

	public function __construct()
	{
		include_once( 'class-pxp-client-credits.php' );
		include_once( 'class-pxp-client-orders.php' );
		include_once( 'class-pxp-client-transactions.php' );
		
		// Display custom fields in profile.
		add_action('show_user_profile', array( $this, 'pxp_client_user_profile' ) );
		add_action('edit_user_profile', array( $this, 'pxp_client_user_profile' ) );
		
		// Update user profile custom fields.
		add_action( 'personal_options_update', array( $this, 'pxp_update_client_user_profile' ) );
		add_action( 'edit_user_profile_update', array( $this, 'pxp_update_client_user_profile' ) );
		
		// Initiate countries
		$this->countries = include( PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'countries.php' );
	}
	
	/**
	 * Show custom fields in user profile.
	 * @param	obj	$user	The user object.
	 * @return	void
	 */
	public function pxp_client_user_profile( $user )
	{
		$pxp_skype 			= get_user_meta( $user->ID, 'pxp_skype', true);
		$pxp_phone			= get_user_meta( $user->ID, 'pxp_phone', true);
		$pxp_company_name	= get_user_meta( $user->ID, 'pxp_company_name', true);
		$pxp_street			= get_user_meta( $user->ID, 'pxp_street', true);
		$pxp_address		= get_user_meta( $user->ID, 'pxp_address', true);
		$pxp_city			= get_user_meta( $user->ID, 'pxp_city', true);
		$pxp_zip			= get_user_meta( $user->ID, 'pxp_zip', true);
		$pxp_country		= get_user_meta( $user->ID, 'pxp_country', true);
		$pxp_operating_system 	= get_user_meta( $user->ID, 'pxp_operating_system', true);
		$pxp_adobe			= get_user_meta( $user->ID, 'pxp_adobe', true);
		$pxp_corel			= get_user_meta( $user->ID, 'pxp_corel', true);
		$pxp_other			= get_user_meta( $user->ID, 'pxp_other', true);
		$pxp_other_desc		= get_user_meta( $user->ID, 'pxp_other_desc', true);
		$pxp_acs_version	= get_user_meta( $user->ID, '_pxp_acs_version', true);
		$pxp_professional_association	= get_user_meta( $user->ID, 'pxp_professional_association', true);
		$pxp_marketing_design	= get_user_meta( $user->ID, 'pxp_marketing_design', true);
		
?>
		<table class="form-table">
			<tr>
				<th>
					<label for="pxp_skype"><?php _e( 'Skype' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_skype" id="pxp_skype" value="<?php echo $pxp_skype; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_phone"><?php _e( 'Phone Number' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_phone" id="pxp_phone" value="<?php echo $pxp_phone; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_company_name"><?php _e( 'Company / Trading Name' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_company_name" id="pxp_company_name" value="<?php echo $pxp_company_name; ?>" class="regular-text" />
				</td>
			</tr>
		</table>	
		
		<h3>Company Address</h3>
		<table class="form-table">
			<tr>
				<th>
					<label for="pxp_street"><?php _e( 'Street' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_street" id="pxp_street" value="<?php echo $pxp_street; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_street"><?php _e( 'Street' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_address" id="pxp_address" value="<?php echo $pxp_address; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_street"><?php _e( 'City' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_city" id="pxp_city" value="<?php echo $pxp_city; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_street"><?php _e( 'Zip' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_zip" id="pxp_zip" value="<?php echo $pxp_zip; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_street"><?php _e( 'Country' ); ?></label>
				</th>
				<td>
					<select name="pxp_country" id="pxp_country">
						<option value="">Select Country</option>
					<?php foreach( $this->countries as $key => $value ): ?>
						<option value="<?php echo $key; ?>" <?php echo ( $pxp_country == $key ) ? "selected" : ""; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		
		<table class="form-table">
			<tr>
				<th>
					<label for="pxp_operating_system"><?php _e( 'Operating System' ); ?></label>
				</th>
				<td>
					<input <?php echo ( $pxp_operating_system == "Windows" ) ? "checked" : ""; ?> type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Windows"> <span><?php _e( 'Windows' ); ?></span><br>
					<input <?php echo ( $pxp_operating_system == "Mac" ) ? "checked" : ""; ?> type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Mac"> <span><?php _e( 'Mac' ); ?></span><br>
					<input <?php echo ( $pxp_operating_system == "Both" ) ? "checked" : ""; ?> type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Both"> <span><?php _e( 'Both' ); ?></span><br>
					<input <?php echo ( $pxp_operating_system == "Others" ) ? "checked" : ""; ?> type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Others"> <span><?php _e( 'Others' ); ?></span><br>
					<span class="description"><?php _e( 'Select which Operating System your business uses.' ); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_adobe"><?php _e( 'Preferred Design Software' ); ?></label>
				</th>
				<td>
					<input <?php echo ( $pxp_adobe == true ) ? "checked" : ""; ?> type="checkbox" name="pxp_adobe" id="pxp_adobe" value="adobe" />
					<span><?php _e( 'Adobe' ); ?></span><br>
					<input <?php echo ( $pxp_corel == true ) ? "checked" : ""; ?> type="checkbox" name="pxp_corel" id="pxp_corel" value="adobe" />
					<span><?php _e( 'Corel' ); ?></span><br>
					<input <?php echo ( $pxp_other == true ) ? "checked" : ""; ?> type="checkbox" name="pxp_other" id="pxp_other" value="other" />
					<span><?php _e( 'Other' ); ?></span>
					<input type="text" name="pxp_other_desc" id="pxp_other_desc" value="<?php echo ( $pxp_other == true ) ? $pxp_other_desc : ""; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_acs_version"><?php _e( 'Adobe Creative Suit Version' ); ?></label>
				</th>
				<td>
					<input type="text" name="pxp_acs_version" id="pxp_acs_version" value="<?php echo $pxp_acs_version; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_professional_association"><?php _e( 'Professional Association' ); ?></label>
				</th>
				<td>
					<textarea name="pxp_professional_association" rows="6" cols="10"><?php echo $pxp_professional_association; ?></textarea>
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_marketing_design"><?php _e( 'How long have you been a marketing design professional' ); ?></label>
				</th>
				<td>
					<select name="pxp_marketing_design" id="pxp_marketing_design">
						<option <?php echo ( $pxp_marketing_design == "1-year" ) ? "selected" : "" ; ?> value="1-year">< 1 year</option>
						<option <?php echo ( $pxp_marketing_design == "1-5-years" ) ? "selected" : "" ; ?> value="1-5-years">1-5 years</option>
						<option <?php echo ( $pxp_marketing_design == "10-years" ) ? "selected" : "" ; ?> value="10-years">10+ years</option>
					</select>
				</td>
			</tr>
		</table>
<?php
	}
	
	/**
	 * Update user custom fields
	 * @param	int	$user_id	User ID
	 * @return 	void
	 */
	public static function pxp_update_client_user_profile( $user_id )
	{
		$data = array(
			'pxp_skype'						=> sanitize_text_field( $_POST['pxp_skype'] ),
			'pxp_phone'						=> sanitize_text_field( $_POST['pxp_phone'] ),
			'pxp_company_name'				=> sanitize_text_field( $_POST['pxp_company_name'] ),
			'pxp_street'					=> sanitize_text_field( $_POST['pxp_street'] ),
			'pxp_address'					=> sanitize_text_field( $_POST['pxp_address'] ),
			'pxp_city'						=> sanitize_text_field( $_POST['pxp_city'] ),
			'pxp_zip'						=> sanitize_text_field( $_POST['pxp_zip'] ),
			'pxp_country'					=> sanitize_text_field( $_POST['pxp_country'] ),
			'pxp_operating_system'			=> sanitize_text_field( $_POST['pxp_operating_system'] ),
			'pxp_adobe'						=> ( isset( $_POST['pxp_adobe'] ) ) ? 1 : 0,
			'pxp_corel'						=> ( isset( $_POST['pxp_corel'] ) ) ? 1 : 0,
			'pxp_other'						=> ( isset( $_POST['pxp_other'] ) ) ? 1 : 0,
			'pxp_other_desc'				=> sanitize_text_field( $_POST['pxp_other_desc'] ),
			'pxp_acs_version'				=> sanitize_text_field( $_POST['pxp_acs_version'] ),
			'pxp_professional_association'	=> esc_textarea( $_POST['pxp_professional_association'] ),
			'pxp_marketing_design'			=> sanitize_text_field( $_POST['pxp_marketing_design'] )
		);
		
		foreach( $data as $key => $value )
		{
			update_user_meta( $user_id, $key, $value );
		}
	}
	
	/**
	 * Display Client Credits Page.
	 */
	public static function pxp_client_credits()
	{
		PXP_Client_Credits::output();
	}
	
	/**
	 * Display Client Orders Page.
	 */
	public static function pxp_client_orders()
	{
		PXP_Client_Orders::output();
	}
	
	/**
	 * Display Client Transaction History Page.
	 */
	public static function pxp_client_transactions()
	{
		PXP_Client_Transactions::output();
	}
}

}

return new PXP_Clients();

?>