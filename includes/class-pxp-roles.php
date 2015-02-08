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
		add_action( 'init', array( $this, 'pxp_add_role_caps' ), 999 );
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
			$role->add_cap( 'read_post' );
			$role->add_cap( 'read_private_posts' );
			$role->add_cap( 'edit_post' );
			$role->add_cap( 'edit_posts' );
			$role->add_cap( 'edit_published_posts' );
			$role->add_cap( 'edit_private_posts' );
			$role->add_cap( 'edit_others_posts' );
			$role->add_cap( 'delete_post' );
			$role->add_cap( 'delete_posts' );
			$role->add_cap( 'delete_private_posts' );
			$role->add_cap( 'delete_published_posts' );
			$role->add_cap( 'delete_others_posts' );
			$role->add_cap( 'publish_posts' );

			
			$capability_types = array( 'product', 'order', 'credit_block', 'promo_code', 'adjustment' );

			foreach( $capability_types as $capability_type )
			{
				if( $role->name == 'pxp_product_author' && $capability_type != "product")
					continue;

				$role->add_cap( 'read_pxp_' . $capability_type );
				$role->add_cap( 'read_private_pxp_' . $capability_type . 's' );
				$role->add_cap( 'edit_pxp_' . $capability_type );
				$role->add_cap( 'edit_pxp_' . $capability_type . 's' );
				$role->add_cap( 'edit_others_pxp_' . $capability_type .'s' );
				$role->add_cap( 'edit_published_pxp_' . $capability_type  . 's' );
				$role->add_cap( 'edit_private_pxp_' . $capability_type  . 's' );
				$role->add_cap( 'publish_pxp_' . $capability_type .'s' );
				$role->add_cap( 'delete_pxp_' . $capability_type );
				$role->add_cap( 'delete_pxp_' . $capability_type . 's' );
				$role->add_cap( 'delete_others_pxp_' . $capability_type . 's' );
				$role->add_cap( 'delete_private_pxp_' . $capability_type . 's' );
				$role->add_cap( 'delete_published_pxp_' . $capability_type . 's' );
			}
		}
	}
}

}

return new PXP_Roles();

?>