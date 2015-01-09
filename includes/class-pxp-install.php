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

if( !class_exists( 'PXP_Install' ) )
{

class PXP_Install
{
	public function __construct()
	{
		// Run function on plugin activation.
		register_activation_hook( PXP_FILE, array( $this, 'add_roles_on_plugin_activation' ) );
		register_activation_hook( PXP_FILE, array( $this, 'add_options_on_plugin_activation' ) );
		
		// Run function on plugin deactivation.
		//register_deactivation_hook( PXP_FILE, array( $this, 'remove_roles_on_plugin_deactivation' ) );
	
		// Show action lins in plugin page.
		add_filter( 'plugin_action_links_' . PXP_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}
	
	/**
	 * Add options on plugin activation.
	 */
	public function add_options_on_plugin_activation()
	{
		add_option( 'pxp_product_id', 1 ); // Set Product ID to 1.
	}
	
	/**
	 * Add roles on plugin activation
	 */
	public function add_roles_on_plugin_activation()
	{
		// Client
		add_role(
			'pxp_client',
			__( 'Client' ),
			array(
				'read'			=> true
			)
		);
		
		// Project Manager
		/**
		 * Capabilities: 
		 * - Add client at admin side
		 * - View list of clients
		 * - View Client Profile
		 * - Create/Add product
		 * - Edit Product
		 * - View list of products
		 * - Adjust client credits (refund/deduct credits)
		 * - Send invoice to client (connected to xero.com?)
		 */
		add_role(
			'pxp_project_manager',
			__( 'Project Manager' ),
			array(
				'read'			=> true, 
				'create_users'	=> true,	// Add Clients
				'edit_users'	=> true, 	// Edit Clients
				'list_users'	=> true,	// View Clients
				'publish_posts'	=> false,	// Add New Product
				'edit_posts' 	=> false,	// Edit Product
				'delete_posts'	=> false, 	// Use false to explicitly deny
			)
		);		
		
		// Product Author
		/**
		 * Capabilities:
		 * - Add / Edit Products
		 */
		add_role(
			'pxp_product_author',
			__( 'Product Author' ),
			array(
				'read'			=> true,  
				'publish_posts'	=> false, 	// Add Product
				'edit_posts'	=> false,	// Edit Product	
				'upload_files' 	=> true,	// Upload media files
				'delete_posts'	=> false, 	// Use false to explicitly deny
			)
		);
	}
	
	/**
	 * Remove roles on plugin deactivation to modify or update capabilities.
	 */
	public function remove_roles_on_plugin_deactivation()
	{
		remove_role('pxp_client');
		remove_role('pxp_project_manager');
		remove_role('pxp_product_author');
	}
	
	/**
	 * Show action links on the plugin page.
	 *
	 * @access 	public
	 * @param	array	$links Plugin action links.
	 * @return 	array
	 */
	public function plugin_action_links( $links )
	{
		$my_links = array(
			'<a href="'. get_admin_url(null, 'options-general.php?page=gpaisr') .'">Settings</a>'
		);
		
		return array_merge( $my_links, $links );
	}
}

}

return new PXP_Install();

?>