<?php
/**
 *	Installation related functions and actions.
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

if( !class_exists( 'PXP_Admin_Clients' ) )
{

class PXP_Admin_Clients
{
	public function __construct()
	{
	}
	
	/**
	 * Display Client List Page in Admin Dashboard.
	 */
	public static function pxp_admin_clients()
	{
		$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
?>
		<div class="wrap">
<?php
		if( $action == "add-new" ):
			self::pxp_admin_add_client_display();
		else:
			self::pxp_admin_client_list();
		endif;
?>	
		</div>
<?php
	}
	
	public static function pxp_admin_client_list()
	{
		global $My_WP_List_Table;
		
		$search = ( isset( $_POST['s'] ) ) ? $_POST['s'] : NULL;
	
		$clients = self::pxp_admin_get_clients();
		
		// Set the orders to the table list.
		$My_WP_List_Table->set_item_list( $clients );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		
		<div id="icon-users" class="icon32"><br/></div>
		<h2>
			<?php _e( 'Clients' ); ?>
			<a class="add-new-h2" id="pxp-client-list-add-client" href="<?php echo admin_url('admin.php?page=pxp-clients&action=add-new'); ?>">Add New Client</a>
		</h2>
			
		<ul class="subsubsub">
			<li>
				<a <?php echo ($search == NULL) ? 'class="current"' : NULL; ?> href="<?php echo '?page=' . $_REQUEST['page']; ?>">
					<?php _e( 'All' ); ?> <span class="count">(<span id="all_count">0</span>)</span>
				</a>
			</li>
		</ul>
		
		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="pxp_client-list_filter" method="POST">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			
			<?php $My_WP_List_Table->search_box( 'search', 'search_id' ) ?>
			
			<?php $My_WP_List_Table->display() ?>
		</form>
<?php
	}
	
	public static function pxp_admin_add_client_display()
	{
		if( isset( $_POST['submit'] ) ):
			$create_client = self::pxp_admin_add_client(); // Create client account.
		endif;
		
		// Initiate countries
		$countries = include( PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'countries.php' );
?>
		<div id="icon-users" class="icon32"><br/></div>
		<h2>
			<?php _e( 'Add New Client' ); ?>
		</h2>
		
<?php 
		if( isset( $_POST['submit'] ) ): 
			$status = ( $create_client == "success" ) ? "updated" : "error"; 
			$create_client = ( $create_client == "success" ) ? "Client has been added." : $create_client; 
?>
			<div id="message" class="<?php echo $status; ?> below-h2">
				<p><?php _e( $create_client ); ?></p>
			</div>
<?php 
		endif; 
?>
		
		<form id="pxp-createclient" method="POST" action="">
<?php	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pxp_add_client', 'pxp_add_client_nonce' );
?>
			<table class="form-table">
				<tr>
					<th>
						<label for="user_login"><?php _e( 'Username' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
					</th>
					<td><input id="user_login" type="text" aria-required="true" class="regular-text" value="" name="user_login" /></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="email"><?php _e( 'E-Mail' ); ?><span class="description"></span></label>
					</th>
					<td><input id="email" type="email" value="" class="regular-text" name="email"></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="first_name"><?php _e( 'First Name' ); ?></label>
					</th>
					<td><input id="first_name" type="text" value="" name="first_name" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="last_name"><?php _e( 'Last Name' ); ?></label>
					</th>
					<td><input id="last_name" type="text" value="" name="last_name" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pass1"><?php _e( 'Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
					</th>
					<td><input id="pass1" type="password" autocomplete="off" class="regular-text" name="pass1"></input></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pass2"><?php _e( 'Repeat Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
					</th>
					<td><input id="pass2" type="password" autocomplete="off" class="regular-text" name="pass2"></input>
					<br></br>
					<div id="pass-strength-result" class="short" style="display: block;">Strength indicator</div>
					<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).' ); ?></p></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_skype"><?php _e( 'Skype' ); ?></label>
					</th>
					<td><input type="text" name="pxp_skype" id="pxp_skype" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_phone"><?php _e( 'Phone Number' ); ?></label>
					</th>
					<td><input type="text" name="pxp_phone" id="pxp_phone" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_company_name"><?php _e( 'Company / Trading Name' ); ?></label>
					</th>
					<td><input type="text" name="pxp_company_name" id="pxp_company_name" class="regular-text" /></td>
				</tr>
			</table>	
			
			<h3><?php _e( 'Company Address' ); ?></h3>
			<table class="form-table">
				<tr>
					<th>
						<label for="pxp_street"><?php _e( 'Address Line 1' ); ?></label>
					</th>
					<td><input type="text" name="pxp_street" id="pxp_street" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_street"><?php _e( 'Address Line 2' ); ?></label>
					</th>
					<td><input type="text" name="pxp_address" id="pxp_address" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_street"><?php _e( 'City' ); ?></label>
					</th>
					<td><input type="text" name="pxp_city" id="pxp_city" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_street"><?php _e( 'Zip' ); ?></label>
					</th>
					<td><input type="text" name="pxp_zip" id="pxp_zip" class="regular-text" /></td>
				</tr>
				<tr>
					<th>
						<label for="pxp_street"><?php _e( 'Country' ); ?></label>
					</th>
					<td>
						<select name="pxp_country" id="pxp_country">
							<option value=""><?php _e( 'Select Country' ); ?></option>
						<?php foreach( $countries as $key => $value ): ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
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
						<input type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Windows"> <span><?php _e( 'Windows' ); ?></span><br>
						<input type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Mac"> <span><?php _e( 'Mac' ); ?></span><br>
						<input type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Both"> <span><?php _e( 'Both' ); ?></span><br>
						<input type="radio" name="pxp_operating_system" id="pxp_operating_system" value="Others"> <span><?php _e( 'Others' ); ?></span><br>
						<span class="description"><?php _e( 'Select which Operating System your business uses.' ); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="pxp_adobe"><?php _e( 'Preferred Design Software' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="pxp_adobe" id="pxp_adobe" value="adobe" />
						<span><?php _e( 'Adobe' ); ?></span><br>
						<input type="checkbox" name="pxp_other" id="pxp_other" value="other" />
						<span><?php _e( 'Other' ); ?></span>
						<input type="text" name="pxp_other_desc" id="pxp_other_desc" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="pxp_if_adobe"><?php _e( 'If Adobe - (Adobe Creative Suite Version)' ); ?></label>
					</th>
					<td>
						<input type="text" name="pxp_if_adobe" id="pxp_if_adobe" value="<?php echo ""; ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="pxp_professional_association"><?php _e( 'Professional Association' ); ?></label>
					</th>
					<td>
						<textarea name="pxp_professional_association" rows="6" class="half-width"></textarea>
					</td>
				</tr>
				<tr>
					<th>
						<label for="pxp_marketing_design"><?php _e( 'How long have you been a marketing design professional' ); ?></label>
					</th>
					<td>
						<select name="pxp_marketing_design" id="pxp_marketing_design">
							<option value="1-year">< 1 year</option>
							<option value="1-5-years">1-5 years</option>
							<option value="10-years">10+ years</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">
						<button type="submit" name="submit" id="pxp-addclient" class="button-primary">Save</button>
						<button id="pxp_cancel" value="<?php echo admin_url('admin.php?page=pxp-clients'); ?>" type="button" class="button-primary">Cancel</button>
					</td>
				</tr>
			</table>
		</form>
<?php
	}
	
	public static function pxp_admin_add_client()
	{
		if( !isset( $_POST['pxp_add_client_nonce'] ) ) 
		{ return __( '<strong>ERROR:</strong> Something went wrong. Please try again.' ); exit(); }

		$nonce = $_POST['pxp_add_client_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_add_client' ) ) 
		{ return __( '<strong>ERROR:</strong> Something went wrong. Please try again.' ); exit(); }
		
		if( !isset( $_POST['user_login'] ) ) 
		{ return __( '<strong>ERROR:</strong> Username is required. Please fill in a valid username.' ); exit(); }
	
		if( !validate_username( $_POST['user_login'] ) ) 
		{ return __( '<strong>ERROR:</strong> The username cannot include non-alphanumeric characters.' ); exit(); }
	
		if( !is_email( $_POST['email'] ) ) 
		{ return __( '<strong>ERROR:</strong> You must enter a valid email address.' ); exit(); }
	
		if( username_exists( $_POST['user_login'] ) ) 
		{ return __( '<strong>ERROR:</strong> Username already exists.' ); exit(); }
	
		if( email_exists( $_POST['email'] ) ) 
		{ return __( '<strong>ERROR:</strong> Email address already exists.' ); exit(); }
	
		$user_name 	= $_POST['user_login'];
		$user_email	= $_POST['email'];
		$user_pass	= $_POST['pass1'];
		
		$user_id = wp_create_user( $user_name, $user_pass, $user_email ); // Create user / client.
		
		wp_update_user( array( 'ID' => $user_id, 'role' => 'pxp_client' ) );
		
		$data  = array(
			'first_name'					=> sanitize_text_field( $_POST['first_name'] ),
			'last_name'						=> sanitize_text_field( $_POST['last_name'] ),
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
			'pxp_other'						=> ( isset( $_POST['pxp_other'] ) ) ? 1 : 0,
			'pxp_other_desc'				=> sanitize_text_field( $_POST['pxp_other_desc'] ),
			'pxp_if_adobe'					=> sanitize_text_field( $_POST['pxp_if_adobe'] ),
			'pxp_professional_association'	=> esc_textarea( $_POST['pxp_professional_association'] ),
			'pxp_marketing_design'			=> sanitize_text_field( $_POST['pxp_marketing_design'] ),
			'pxp_user_credits'				=> 0
		);
		
		foreach( $data as $key => $value )
		{
			update_user_meta( $user_id, $key, $value );
		}
		
		return 'success'; exit();
	}
	
	public static function pxp_admin_get_clients()
	{
		$args = array(
			'role'	=> 'pxp_client'
		);
		
		$users = get_users( $args );
		
		$clients = array();
		
		foreach($users as $user)
		{
			$user_info 	= get_userdata( $user->ID );
			$first_name	= $user_info->first_name;
			$last_name 	= $user_info->last_name;
		
			$pxp_company_name	= get_user_meta( $user->ID, 'pxp_company_name', true );
			
			// Address
			$pxp_street			= get_user_meta( $user->ID, 'pxp_street', true );
			$pxp_address		= get_user_meta( $user->ID, 'pxp_address', true );
			$pxp_city			= get_user_meta( $user->ID, 'pxp_city', true );
			$pxp_zip			= get_user_meta( $user->ID, 'pxp_zip', true );
			$pxp_country		= get_user_meta( $user->ID, 'pxp_country', true );
		
			$clients[] = array(
				'ID'     			=> $user->ID,
				'contact_name'		=> $first_name . ' ' . $last_name,
				'company_name'		=> $pxp_company_name,
				'client_email'    	=> $user->user_email,
				'client_address'    => $pxp_street. ', ' . $pxp_city. ', '. $pxp_country 
			);
		}
		
		return $clients;
	}
}

}

return new PXP_Admin_Clients();

?>

