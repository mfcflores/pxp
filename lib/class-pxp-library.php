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
	
	/**
	 * Include WP Session Class for session handling.
	 * Author: Eric Mann
	 * Author URI: http://eamann.com
	 */
	public function include_wp_session()
	{
		// let users change the session cookie name
		if( ! defined( 'WP_SESSION_COOKIE' ) ) 
		{
			define( 'WP_SESSION_COOKIE', '_wp_session' );
		}

		if( ! class_exists( 'Recursive_ArrayAccess' ) ) 
		{
			include 'WP_Session/class-recursive-arrayaccess.php';
		}

		// Include utilities class
		if( ! class_exists( 'WP_Session_Utils' ) ) 
		{
			include 'WP_Session/class-wp-session-utils.php';
		}

		// Include WP_CLI routines early
		if( defined( 'WP_CLI' ) && WP_CLI ) 
		{
			include 'WP_Session/wp-cli.php';
		}

		// Only include the functionality if it's not pre-defined.
		if( ! class_exists( 'WP_Session' ) ) 
		{
			include 'WP_Session/class-wp-session.php';
			include 'WP_Session/wp-session.php';
		}
	}
	
	/**
	 * Add filter notification message in front-end.
	 *
	 * @param String $message String to use a message.
	 */
	public function pxp_notification_filter( $message = '' )
	{
		if( $message != '' )
		{
			$message = '<div class="pxp message">' . __( $message ) . '</div>';
		}
		
		return $message;
	}
	
	/**
	 * Mail registration data to admin.
	 *
	 * @param Array $data Array data of Registration details.
	 */
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
	
	/**
	 * WP Mail Content Type as HTML.
	 */
	public function set_html_content_type() 
	{
		return 'text/html';
	}
}

}

global $pxp;

$pxp = new PXP_Library();