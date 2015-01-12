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
			
			// Products
			$role->add_cap( 'read_pxp_product');
			$role->add_cap( 'read_private_pxp_products' );
			$role->add_cap( 'edit_pxp_product' );
			$role->add_cap( 'edit_pxp_products' );
			$role->add_cap( 'edit_others_pxp_products' );
			$role->add_cap( 'edit_published_pxp_products' );
			$role->add_cap( 'publish_pxp_products' );
			$role->add_cap( 'delete_pxp_product' );
			$role->add_cap( 'delete_pxp_products' );
			$role->add_cap( 'delete_others_pxp_products' );
			$role->add_cap( 'delete_private_pxp_products' );
			$role->add_cap( 'delete_published_pxp_products' );
			
			if( $role->name == 'pxp_product_author' )
				continue;
			
			$post_types = array( 'order', 'credit_block', 'promo_code', 'adjustment' );
			
			foreach( $post_types as $post_type )
			{
				$role->add_cap( 'read_pxp_' . $post_type );
				$role->add_cap( 'read_private_pxp_' . $post_type . 's' );
				$role->add_cap( 'edit_pxp_' . $post_type );
				$role->add_cap( 'edit_pxp_' . $post_type . 's' );
				$role->add_cap( 'edit_others_pxp_' . $post_type .'s' );
				$role->add_cap( 'edit_published_pxp_' . $post_type  . 's' );
				$role->add_cap( 'publish_pxp_' . $post_type .'s' );
				$role->add_cap( 'delete_pxp_' . $post_type );
				$role->add_cap( 'delete_pxp_' . $post_type . 's' );
				$role->add_cap( 'delete_others_pxp_' . $post_type . 's' );
				$role->add_cap( 'delete_private_pxp_' . $post_type . 's' );
				$role->add_cap( 'delete_published_pxp_' . $post_type . 's' );
			}
		}
	}
}

}

return new PXP_Roles();

?>