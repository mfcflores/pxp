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
					<!--<select name="pxp_operating_system">
						<option <?php echo ( $pxp_operating_system == "Windows" ) ? "selected" : ""; ?> value="Windows">Windows</option>
						<option <?php echo ( $pxp_operating_system == "Mac" ) ? "selected" : ""; ?> value="Mac">Mac</option>
						<option <?php echo ( $pxp_operating_system == "Both" ) ? "selected" : ""; ?> value="Both">Both</option>
						<option <?php echo ( $pxp_operating_system == "Others" ) ? "selected" : ""; ?> value="Others">Others</option>
					</select>-->
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
					<input <?php echo ( $pxp_other == true ) ? "checked" : ""; ?> type="checkbox" name="pxp_other" id="pxp_other" value="other" />
					<span><?php _e( 'Other' ); ?></span>
					<input type="text" name="pxp_other_desc" id="pxp_other_desc" value="<?php echo ( $pxp_other == true ) ? $pxp_other_desc : ""; ?>" class="regular-text" />
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
			'pxp_other'						=> ( isset( $_POST['pxp_other'] ) ) ? 1 : 0,
			'pxp_other_desc'				=> sanitize_text_field( $_POST['pxp_other_desc'] ),
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
?>
		<div id="pxp-credits-page">
			<div class="pxp-credit-box">
				<div class="pxp-credit-column50">
					<h1>Purchase Credits</h1>
				</div>
				<div class="pxp-credit-column50 text-right">
					<h3 style="margin-right:15px;">Remaining Balance:&nbsp;<span class="text-red">12345</span> Credits</h3>
				</div>
			</div>
			<div class="pxp-credit-box">
				<h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare. Quisque odio enim, vulputate in felis vel, tincidunt rutrum ex.  Praesent finibus orci non turpis suscipit ornare.
				</h4>
			</div>
			<div class="pxp-credit-box text-center">
				<div class="pxp-credit">
					<div class="row">
						<h3>500 Credits</h3>
					</div>
						<div id="content" class="row">
							<p>$500</p>
							<p>500 Credits</p>
							<p>+5% Credits</p>
						</div>
							<div id="btn" class="row">
								<button type="button" class="btn">Buy</button>
							</div>
				</div>
				<div class="pxp-credit">
					<div class="row">
						<h3>100 Credits</h3>
					</div>
						<div id="content" class="row">
							<p>$100</p>
							<p>100 Credits</p>
							<p>+10 Credits</p>
						</div>
							<div id="btn" class="row">
								<button type="button" class="btn">Buy</button>
							</div>
				</div>
				<div class="pxp-credit">
					<div class="row">
						<h3>Any Amount</h3>
					</div>
						<div id="content" class="row">
							<p>Lorem ipsum dolor </p>
							<p>adipiscing eli</p>
							<p>sit amet</p>
						</div>
							<div id="btn" class="row">
								<button type="button" class="btn">Buy</button>
							</div>
				</div>
			</div>
			<div class="pxp-credit-box">
				<h4 >
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare. Quisque odio enim, vulputate in felis vel, tincidunt rutrum ex. Quisque fringilla accumsan sapien nec gravida. 
				</h4>
			</div>

			<div class="clearfix"></div>
		</div>
<?php
	}
	
	/**
	 * Display Client Orders Page.
	 */
	public static function pxp_client_orders()
	{
		$order_id = ( isset( $_GET['order'] ) ) ? $_GET['order'] : NULL;
?>
		<div class="wrap">
<?php
		if( $order_id != NULL ):
			// Output client order details
			self::pxp_client_order_details( $order_id );
		else:
			// Output client orders list.
			self::pxp_client_orders_list();
		endif;
?>
		</div>
<?php
	}

	/**
	 * Display Client Order List.
	 */
	public static function pxp_client_orders_list()
	{
		global $My_WP_List_Table;
		
		$search = ( isset( $_POST['s'] ) ) ? $_POST['s'] : NULL;
		
		$orders = array(
			array(
				'ID'			=> 1,
				'order_date'	=> 'Sample',
				'order_total'	=> '2',
				'order_status'	=> 'Ordered'
			),
			array(
				'ID'			=> 2,
				'order_date'	=> 'Sample 2',
				'order_total'	=> '1',
				'order_status'	=> 'Cancelled'
			)
		);
		
		// Set the orders to the table list.
		$My_WP_List_Table->set_item_list( $orders );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		<div id="icon-users" class="icon32"><br/></div>
		<h2>
			<?php _e('Orders'); ?>
			<a class="add-new-h2" id="pxp-client-add-order" href="<?php echo admin_url('users.php?page=pxp-client-orders'); ?>">Add New Order</a>
		</h2>
			
		<ul class="subsubsub">
			<li>
				<a <?php echo ($search == NULL) ? 'class="current"' : NULL; ?> href="<?php echo '?page=' . $_REQUEST['page']; ?>">
					<?php _e( 'All' ); ?> <span class="count">(<span id="all_count">0</span>)</span>
				</a>
			</li>
		</ul>
		
		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="pxp_client_order_filter" method="POST">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			
			<?php $My_WP_List_Table->search_box( 'search', 'search_id' ) ?>
			
			<?php $My_WP_List_Table->display() ?>
		</form>
<?php
	}
	
	/**
	 * Display Client Order Details
	 * @param	int	$order_id	Order done by client.
	 */
	public static function pxp_client_order_details( $order_id )
	{
		$order_id = ( isset( $_GET['order'] ) ) ? $_GET['order'] : NULL;

		echo $order_id;
	}
	
	/**
	 * Display Client Transaction History Page.
	 */
	public static function pxp_client_transactions()
	{
		$transaction_id = ( isset( $_GET['transaction'] ) ) ? $_GET['transaction'] : NULL;
?>
		<div class="wrap">
<?php
		if( $transaction_id != NULL ):
			// Output client transactions details
			self::pxp_client_transaction_details( $transaction_id );
		else:
			// Output client transactions list.
			self::pxp_client_transactions_list();
		endif;
?>
		</div>
<?php
	}
	
	/**
	 * Display Client Transaction History Page.
	 */
	public static function pxp_client_transactions_list()
	{
		global $My_WP_List_Table;
		
		$transactions = array(
			array(
				'ID'						=> 1,
				'transaction_description'	=> 'Quisque fringilla accumsan sapien nec gravida. ',
				'transaction_date'			=> 'Sample',
			),
			array(
				'ID'						=> 2,
				'transaction_description'	=> 'Praesent finibus orci non turpis suscipit ornare.',
				'transaction_date'			=> 'Sample 2',
			)
		);
		
		// Set the transactions to the table list.
		$My_WP_List_Table->set_item_list( $transactions );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		<div>
			<h2>Transaction History</h2>
		<div>
		<div>
			<h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare.
			</h4>
		</div>

<?php 
		$My_WP_List_Table->display();	
	}
	
	/**
	 * Display Client Transaction Details
	 * @param	int	$transcation	Transactions done by client.
	 */
	public static function pxp_client_transaction_details( $transaction_id )
	{
		echo $transaction_id;
	}
}

}

return new PXP_Clients();

?>