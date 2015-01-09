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

if( !class_exists( 'PXP_Admin_Menus' ) )
{

class PXP_Admin_Menus
{
	public function __construct()
	{
		// Add admin menu
		add_action( 'admin_menu', array( $this, 'client_menu' ) );		
		
		// Filter set screen options
		add_filter( 'set-screen-option', array( $this, 'client_table_set_option' ), 10, 3 );
		
		// Include files
		include_once( 'admin/class-pxp-admin-core.php' );
	}
	
	/**
	 * Add admin menus
	 *
	 * @param	global	$pxp_credits		Page hookname
	 * @param	global	$pxp_orders			Page hookname
	 * @param	global	$pxp_transactions	Page hookname
	 *
	 * @return	void
	 */
	public function client_menu()
	{
		global $pxp_credits, $pxp_orders; $pxp_transactions;
		
		$pxp_clients		= add_menu_page( 'PXP Clients', 'PXP Clients', 'create_users', 'pxp-clients', array( $this, 'client_list' ) , 'dashicons-admin-users', 71);
		
		$pxp_credits		= add_users_page( 'Credits', 'Credits', 'read', 'pxp-client-credits', array( $this, 'client_credits') );
		
		$pxp_orders			= add_users_page( 'Orders', 'Orders', 'read', 'pxp-client-orders', array( $this, 'client_order') );
		
		$pxp_transactions 	= add_users_page( 'Transaction History', 'Transaction History', 'read', 'pxp-client-transactions', array( $this, 'client_transactions') );
		
		if( isset( $_GET['page'] ) ):
			switch( $_GET['page'] )
			{
				case 'pxp-clients':
					// Load screen options for Client Orders Page
					add_action("load-$pxp_clients", array(&$this, "client_page_options"));
					break;
				case 'pxp-client-orders':
					// Load screen options for Client Orders Page
					add_action("load-$pxp_orders", array(&$this, "client_page_options"));
					break;
				case 'pxp-client-transactions':
					// Load screen options for Client Transaction Page
					add_action("load-$pxp_transactions", array(&$this, "client_page_options"));
					break;
			}
		endif;
	}
	
	/**
	 * Enable screen options to page for WP List Table.
	 */
	public function client_page_options()
	{
		global $My_WP_List_Table;
	
		$page = $_GET['page'];
		
		switch( $_GET['page'] )
		{
			case 'pxp-clients':
				include_once( 'admin/class-pxp-admin-clients-table.php' );
				
				$option = 'per_page';
				$args = array(
					'label' => 'Clients',
					'default' => 10,
					'option' => 'clients_per_page'
				);
				
				add_screen_option( $option, $args );
				
				// Create an instance of our package class.
				$My_WP_List_Table = new Clients_List_Table();
				break;
			case 'pxp-client-orders':
				include_once( 'clients/class-pxp-orders-table.php' );
				
				$option = 'per_page';
				$args = array(
					'label' => 'Orders',
					'default' => 10,
					'option' => 'client_orders_per_page'
				);
				
				add_screen_option( $option, $args );
				
				// Create an instance of our package class.
				$My_WP_List_Table = new ClientOrder_List_Table();
				break;
			case 'pxp-client-transactions':
				//include_once( 'clients/class-pxp-orders-table.php' );
				
				$option = 'per_page';
				$args = array(
					'label' => 'Orders',
					'default' => 10,
					'option' => 'client_transactions_per_page'
				);
				
				add_screen_option( $option, $args );
				
				// Create an instance of our package class.
				//$My_WP_List_Table = new ClientOrder_List_Table();
				break;
		}
	}
	
	public function client_table_set_option( $status, $option, $value )
	{
		if ( 'clients_per_page' == $option ) 
			return $value;
		
		if ( 'client_orders_per_page' == $option ) 
			return $value;
			
		if ( 'client_transactions_per_page' == $option ) 
			return $value;

		return $status;
	}
	
	/**
	 * Init the Clients Order Page.
	 */
	public function client_credits()
	{
		PXP_Clients::pxp_client_credits();
	}
	
	/**
	 * Init the Clients Order Page.
	 */
	public function client_order()
	{
		PXP_Clients::pxp_client_orders();
	}
	
	/**
	 * Init the Clients Order Page.
	 */
	public function client_transactions()
	{
		PXP_Clients::pxp_client_transactions();
	}
	
	/**
	 * Init the Client Lists in Admin Dashboard.
	 */
	public function client_list()
	{
		PXP_Admin_Core::pxp_admin_clients();
	}
}

}

return new PXP_Admin_Menus();

