<?php
/**
 *	Registration Shortcode.
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

if( !class_exists( 'PXP_Shortcode_Registration' ) )
{

class PXP_Shortcode_Registration
{
	public function __construct()
	{
			
	}
	
	public static function output( $atts )
	{
		global $message;
		
		if( isset( $_POST ) && !empty( $_POST ) ) :
			$message = self::send_registration();
		endif;
		
		pxp_get_template( 'class-pxp-template-registration.php' );
	}
	
	public static function send_registration()
	{
		global $pxp;
		
		if( !isset( $_POST['pxp_registration_nonce'] ) ) 
		{ return __( '<strong class="text-red">ERROR:</strong> Something went wrong. Please try again.' ); exit(); }

		$nonce = $_POST['pxp_registration_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_registration' ) ) 
		{ return __( '<strong class="text-red">ERROR:</strong> Something went wrong. Please try again.' ); exit(); }
	
		if( $_POST['user_email'] == "" || !is_email( $_POST['user_email'] ) )
		{ return __( '<strong class="text-red">ERROR:</strong> Enter a valid email address.' ); exit(); }
	
		$user_email	= $_POST['user_email'];
	
		$data  = array(
			'user_email'				=> $user_email,
			'first_name'				=> sanitize_text_field( $_POST['first_name'] ),
			'last_name'					=> sanitize_text_field( $_POST['last_name'] ),
			'phone'						=> sanitize_text_field( $_POST['phone'] ),
			'skype'						=> sanitize_text_field( $_POST['skype'] ),
			'company_name'				=> sanitize_text_field( $_POST['company_name'] ),
			'company_site'				=> sanitize_text_field( $_POST['company_site'] ),
			'addrs1'					=> sanitize_text_field( $_POST['addrs1'] ),
			'addrs2'					=> sanitize_text_field( $_POST['addrs2'] ),
			'city'						=> sanitize_text_field( $_POST['city'] ),
			'zip'						=> sanitize_text_field( $_POST['zip'] ),
			'country'					=> sanitize_text_field( $_POST['country'] ),
			'operating_system'			=> ( isset( $_POST['operating_system'] ) ) ? sanitize_text_field( $_POST['operating_system'] ) : "",
			'adobe'						=> ( isset( $_POST['adobe'] ) ) ? "Yes" : "No",
			'other'						=> ( isset( $_POST['other'] ) ) ? "Yes" : "No",
			'other_desc'				=> sanitize_text_field( $_POST['other_desc'] ),
			'adobe_version'				=> sanitize_text_field( $_POST['adobe_version'] ),
			'professional_association'	=> esc_textarea( $_POST['professional_association'] ),
			'marketing_design'			=> sanitize_text_field( $_POST['marketing_design'] ),
		);
		
		// Send registration mail to Admin.
		$pxp->pxp_send_mail_registration( $data );

		return __( 'Your registration has been sent.' ); 
		
		exit();
	}
}

}

return new PXP_Shortcode_Registration();

?>