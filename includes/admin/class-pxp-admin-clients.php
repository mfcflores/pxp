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

if( !class_exists( 'PXP_Admin' ) )
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
		global $My_WP_List_Table;
		
		$search = ( isset( $_POST['s'] ) ) ? $_POST['s'] : NULL;
		
		$clients = self::pxp_admin_get_clients();
		
		// Set the orders to the table list.
		$My_WP_List_Table->set_item_list( $clients );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		<div class="wrap">
			<div id="icon-users" class="icon32"><br/></div>
			<h2>
				<?php _e('Clients'); ?>
				<a class="add-new-h2" id="pxp-client-list-add-client" href="<?php echo admin_url('user-new.php'); ?>">Add New Client</a>
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
		</div>
<?php
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
				'client_address'    => 'Address'
			);
		}
		
		return $clients;
	}
}

}

return new PXP_Admin_Clients();

?>

