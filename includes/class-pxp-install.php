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
		register_activation_hook( PXP_FILE, array( $this, 'pxp_install' ) );
		
		// Run function on plugin deactivation.
		register_deactivation_hook( PXP_FILE, array( $this, 'pxp_deactivate' ) );
	
		// Show action lins in plugin page.
		add_filter( 'plugin_action_links_' . PXP_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
	}
	
	/**
	 * Run functions on activation.
	 */
	public function pxp_install()
	{
		// Create important pages on activation.
		$this->create_pxp_pages();
		
		// Add options on activation.
		$this->add_options_on_plugin_activation();
		
		// Add roles on activation.
		$this->add_roles_on_plugin_activation();
	}
	
	/**
	 * Run functions on deactivation.
	 */
	public function pxp_deactivate()
	{
		// Remove caps and roles.
		//$this->remove_roles_on_plugin_deactivation();
	}
	
	/**
	 * Add options on plugin activation.
	 */
	public function add_options_on_plugin_activation()
	{
		add_option( 'pxp_product_id', 1 );	// Set Product ID to 1.
		add_option( 'pxp_order_id', 1 );	// Set Order ID to 1.
		add_option( 'pxp_promo_id', 1 );	// Set Promo ID to 1. 
		add_option( 'pxp_credit_block_id', 1 );	// Set Promo ID to 1. 
	}
	
	/**
	 * Add roles on plugin activation
	 */
	public function add_roles_on_plugin_activation()
	{
		global $wp_roles;
		
		if ( class_exists( 'WP_Roles' ) ) 
		{
			if ( ! isset( $wp_roles ) ) 
			{
				$wp_roles = new WP_Roles();
			}
		}
		
		if ( is_object( $wp_roles ) ) 
		{
			// Client
			add_role( 'pxp_client',	__( 'Client' ),	array( 'read' => true ) );
			
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
			add_role( 'pxp_project_manager', __( 'Project Manager' ), array(
				'read'			=> true, 
				'create_users'	=> true,	// Add Clients
				'edit_users'	=> true, 	// Edit Clients
				'list_users'	=> true,	// View Clients
				'publish_posts'	=> false,	// Add New Product
				'edit_posts' 	=> false,	// Edit Product
				'delete_posts'	=> false, 	// Use false to explicitly deny
			) );		
			
			// Product Author
			/**
			 * Capabilities:
			 * - Add / Edit Products
			 */
			add_role( 'pxp_product_author',	__( 'Product Author' ),	array(
				'read'			=> true,  
				'publish_posts'	=> false, 	// Add Product
				'edit_posts'	=> false,	// Edit Product	
				'upload_files' 	=> true,	// Upload media files
				'delete_posts'	=> false, 	// Use false to explicitly deny
			) );
		}
	}
	
	/**
	 * Get capabilities for PixelPartners.
	 *
	 * @return array
	 */
	public function get_pxp_capabilities()
	{
		$capabilities = array();
		
		$capability_types = array( 'pxp_product', 'pxp_order', 'pxp_credit_block', 'pxp_promo_code', 'pxp_adjustment' );
		
		foreach( $capability_types as $capability_type )
		{
			$capabilities[ $capability_type ] = array(
				'read_' . $capability_type,
				'read_private_' . $capability_type . 's',
				'edit_' . $capability_type,
				'edit_' . $capability_type . 's',
				'edit_others_' . $capability_type .'s',
				'edit_published_' . $capability_type  . 's',
				'edit_private_' . $capability_type  . 's',
				'publish_' . $capability_type .'s',
				'delete_' . $capability_type,
				'delete_' . $capability_type . 's',
				'delete_others_' . $capability_type . 's',
				'delete_private_' . $capability_type . 's',
				'delete_published_' . $capability_type . 's'
			);
		}
		
		return $capabilities;
	}
	
	/**
	 * Remove roles on plugin deactivation to modify or update capabilities.
	 */
	public function remove_roles_on_plugin_deactivation()
	{
		global $wp_roles;
		
		if ( class_exists( 'WP_Roles' ) ) 
		{
			if ( ! isset( $wp_roles ) ) 
			{
				$wp_roles = new WP_Roles();
			}
		}
		
		if ( is_object( $wp_roles ) ) 
		{
			$capabilities = $this->get_pxp_capabilities();
			
			foreach( $capabilities as $key => $capability )
			{
				foreach( $capability as $cap )
				{
					if( $key == "pxp_product" ):
						$wp_roles->remove_cap( 'pxp_product_author', $cap );
					endif;
					
					$wp_roles->remove_cap( 'pxp_project_manager', $cap );
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}
			
			remove_role( 'pxp_client' );
			remove_role( 'pxp_project_manager' );
			remove_role( 'pxp_product_author' );
		}
	}
	
	/**
	 * Create important pages of plugin when installed.
	 */
	public function create_pxp_pages()
	{
		$pages = array(
			'registration'	=> array(
				'name'		=> 'registration',
				'title'		=> 'Registration',
				'content'	=> '[pxp_registration]'
			),
			'cart'			=> array(
				'name'		=> 'cart',
				'title'		=> 'Cart',
				'content'	=> '[pxp_cart]'
			),
			'checkout'		=> array(
				'name'		=> 'checkout',
				'title'		=> 'Checkout',
				'content'	=> '[pxp_checkout]'
			),
			'login'			=>array(
				'name'		=> 'login', 
				'title'		=> 'Login',
				'content'	=> '[pxp_login]'
			),
			'search'		=> array(
				'name'		=> 'search',
				'title'		=> 'Search',
				'content'	=> '[pxp_search]'
			)
		);
		
		foreach( $pages as $key => $page )
		{
			pxp_create_page( 'pxp_' . $key . '_page', $page['name'], $page['title'], $page['content'] );
		}
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