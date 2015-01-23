<?php
/**
 *	Initiate PXP Plugin libraries.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin
 *	@package 	PixelPartners/Library
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Library' ) )
{

class PXP_Library
{
	public function __construct()
	{
		
	}
	
	public function pxp_notification_filter( $message = '' )
	{
		if( $message != '' )
		{
			$message = '<div class="message">' . __( $message ) . '</div>';
		}
		
		return $message;
	}
	
	public function pxp_send_mail_registration( $data )
	{
		$site_name		= get_option('blogname');
		$admin_email	= get_site_option('admin_email');
		$user_email		= $data['user_email'];
		
		$first_name 	= $data['first_name'];
		$last_name 		= $data['last_name'];
		
		$subject 	= "New Registration";
		
		$headers[] 	= 'From: ' . $site_name .  ' <' . $admin_email . '>';
		
		ob_start();

		echo '<html><body><table>';
		
		foreach( $data as $key => $value )
		{
			$label = explode( "_", $key );
			
			foreach( $label as $label_key => $label_value )
			{
				$label[$label_key] = ucfirst( $label_value );
			}
			
			$label = implode( $label, " " );
			
			echo '<tr style="border: 1px solid #F0F0F0;"><th style="padding: 5px 10px; text-align: left;">' . $label . ' :</th><td style="text-align: left;">' . $value . '</td></tr>';
		}
		
		echo '</table></body></html>';
		
		$message = ob_get_contents();
		
		ob_get_clean();
	
		add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
	
		wp_mail( $user_email, $subject, $message, $headers );
		
		remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
	}
	
	public function set_html_content_type() 
	{
		return 'text/html';
	}
}

}

global $pxp;

$pxp = new PXP_Library();