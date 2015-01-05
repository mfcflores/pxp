<?php
/**
 *	Manage plugin roles.
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

if( !class_exists( 'PXP_Roles' ) )
{

class PXP_Roles
{
	public function __construct()
	{
		// Add capabilities to roles
		add_action( 'admin_init', array( $this, 'pxp_add_role_caps' ), 999 );
	}
	
	/**
	 * Add capabilities to plugin defined roles.
	 */
	public function pxp_add_role_caps()
	{
		// Add the roles you'd like to administer the custom post types
		$roles = array( 'pxp_project_manager', 'pxp_product_author', 'administrator' );
		
		// Loop through each role and assign capabilities
		foreach($roles as $role) 
		{ 
		    $role = get_role($role);
			
			$role->add_cap( 'read' );
			$role->add_cap( 'read_pxp_product');
			$role->add_cap( 'read_private_pxp_products' );
			$role->add_cap( 'edit_pxp_product' );
			$role->add_cap( 'edit_pxp_products' );
			$role->add_cap( 'edit_others_pxp_products' );
			$role->add_cap( 'edit_published_pxp_products' );
			$role->add_cap( 'publish_pxp_products' );
			$role->add_cap( 'delete_others_pxp_products' );
			$role->add_cap( 'delete_private_pxp_products' );
			$role->add_cap( 'delete_published_pxp_products' );
		}
	}
}

}

return new PXP_Roles();