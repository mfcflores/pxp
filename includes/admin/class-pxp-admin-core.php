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

if( !class_exists( 'PXP_Admin_Core' ) )
{

class PXP_Admin_Core
{
	public function __construct()
	{
	}
	
	/**
	 * Output Admin Clients Page.
	 */
	public static function pxp_admin_clients()
	{
		include_once( 'class-pxp-admin-clients.php' );
		
		PXP_Admin_Clients::pxp_admin_clients();
	}
}

}

return new PXP_Admin_Core();

?>