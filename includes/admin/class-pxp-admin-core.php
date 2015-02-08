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
		include_once( 'class-pxp-admin-clients.php' );

		//add_action( 'admin_head', array( $this, 'admin_alert_notification' ) );
		add_action( 'admin_notices', array( $this, 'admin_alert_notification' ) );
	}
	
	/**
	 * Setup plugin settings page.
	 */
	public static function pxp_plugin_settings()
	{
		$did_update = ( isset( $_POST['pxp_admin_a'] ) ) ? self::pxp_settings_update( $_POST['pxp_admin_a'] ) : NULL;
?>
		<div class="wrap pxp-settings">
<?php 
			$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';

			self::pxp_settings_tab( $tab );
			
			pxp_admin_notification_message( $did_update );
?>
			<div class="pxp-settings-container">
<?php
				self::pxp_settings_do_tab( $tab );
?>
			</div>
		</div>
<?php
	}

	/**
	 * Generate setting tabs.
	 *
	 * @param String $current Current tab viewing.
	 */
	public static function pxp_settings_tab( $current = 'general' )
	{
		$tabs = array(
			'general'	=> __( 'General' ),
			'paypal'	=> __( 'PayPal Gateway' )
		);
		
		$links = array();
	
		foreach( $tabs as $tab => $name )
		{
			$class = ($tab == $current) ? 'nav-tab nav-tab-active' : 'nav-tab';
			
			$links[] = '<a class="' . $class . '" href="?page=pxp-settings&amp;tab=' . $tab . '">' . $name . '</a>';
		}
		
		echo '<h2 class="nav-tab-wrapper">';
		
		foreach ( $links as $link ):
			echo $link;
		endforeach;
		
		echo '</h2>';
	}
	
	/**
	 * Display contents of current tab active.
	 */
	public static function pxp_settings_do_tab( $tab )
	{
		switch( $tab )
		{
			case 'general':
				self::pxp_general_settings();
				break;
			case 'paypal':
				self::pxp_paypal_settings();
				break;
		}
	}
	
	/**
	 * Do updates to settings.
	 *
	 * @param string $action Settings to update.
	 */
	public static function pxp_settings_update( $action )
	{
		$did_update = "";
		
		switch( $action )
		{
			case 'update_paypal':
				include_once( 'class-pxp-admin-paypal.php' );
				
				$did_update = PXP_Admin_Paypal::update_paypal_settings();
				break;
		}
		
		return $did_update;
	}
	
	/**
	 * Display contents of General Settings.
	 */
	public static function pxp_general_settings()
	{
		echo '<h1>Under Construction</h1>';
	}
	
	/**
	 * Display contents of PayPal Gateway settings.
	 */
	public static function pxp_paypal_settings()
	{
		include_once( 'class-pxp-admin-paypal.php' );
		PXP_Admin_Paypal::output();
	}
	
	/**
	 * Output Admin Clients Page.
	 */
	public static function pxp_admin_clients()
	{
		PXP_Admin_Clients::pxp_admin_clients();
	}
	
	/**
	 * Display notification message to alert missing important settings.
	 */
	public function admin_alert_notification()
	{

		$paypal_settings = ( get_option( 'pxp_paypal_settings' ) != "" ) ? get_option( 'pxp_paypal_settings' ) : NULL;
		
		$client_id 		= $paypal_settings['client_id'];
		$client_secret 	= $paypal_settings['client_secret'];
		
		if( $client_id == "" || $client_secret == "" ):
?>
			<div class="error"><p></i> Configure PayPal Gate Settings <a href="<?php echo admin_url( 'admin.php?page=pxp-settings&tab=paypal' ); ?>">here</a>.</p></div>
<?php	
		endif;
	}
}

}

return new PXP_Admin_Core();

?>