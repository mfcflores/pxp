<?php
/**
 *	Manage user account.
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

if( !class_exists( 'PXP_Users' ) )
{

class PXP_Users
{
	public $user_details = array(); // Current remaining credits
	
	public function __construct( $user_ID )
	{
		$this->user_details = array(
			'user_credits'	=> $this->get_user_credits( $user_ID )
		);
	}
	
	public function get_user_credits( $user_ID )
	{
		$user_credits = get_user_meta( $user_ID, 'pxp_user_credits', true );
	
		return ( $user_credits != NULL ) ? $user_credits : 0;
	}
	
}

}

?>