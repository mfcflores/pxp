<?php
/**
 *	Import scripts to plugin.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin
 *	@package 	PixelPartners/Class
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Frontend_Assets' ) )
{

class PXP_Frontend_Assets
{
	public function __construct()
	{
		add_action( 'wp_head', array( $this, 'pxp_frontend_add_ajaxurl') );
		add_action( 'wp_enqueue_scripts', array( $this, 'pxp_frontend_scripts' ) );
	}
	
	/**
	 * Enqueue & Register scritps and styles.
	 */
	public function pxp_frontend_scripts( $hook )
	{
		global $post;
		
		wp_enqueue_script	( 'jquery', PXP_URL . '/assets/js/jquery.js' );
		
		wp_register_script	( 'jquery-ui', PXP_URL . '/assets/js/jquery-ui.js');
		wp_enqueue_script	( 'jquery-ui' );
		
		wp_register_script	( 'jquery-autocomplete', PXP_URL . '/assets/js/jquery.autocomplete.js');
		wp_enqueue_script	( 'jquery-autocomplete' );
		
		wp_register_style	( 'jquery-ui-style', PXP_URL . '/assets/css/jquery-ui.css');
		wp_enqueue_style	( 'jquery-ui-style' );
		
		wp_register_style	( 'pxp-fontawesome', PXP_URL . '/assets/css/font-awesome.min.css' );
		wp_enqueue_style	( 'pxp-fontawesome' );
		
		wp_register_style	( 'pxp-style', PXP_URL . '/assets/css/style.css' );
		wp_enqueue_style	( 'pxp-style' );
		
		wp_register_script	( 'pxp-script', PXP_URL . '/assets/js/script.js' );
		wp_enqueue_script	( 'pxp-script' );
		
		
	}
	
	public function pxp_frontend_add_ajaxurl()
	{ 
?>
    <script type="text/javascript">
        ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
	</script>
<?php 
	}
	
	/**
	 * Initiate Bootstrap CSS and JS.
	 */
	public function pxp_initiate_bootstrap()
	{
		wp_register_style	( 'pxp-bootstrap-css', PXP_URL . '/assets/css/bootstrap.css' );
		wp_enqueue_style	( 'pxp-bootstrap-css' );
		
		wp_register_style	( 'pxp-bootstrap-theme', PXP_URL . '/assets/css/bootstrap-theme.min.css' );
		wp_enqueue_style	( 'pxp-bootstrap-theme' );
		
		wp_register_script	( 'pxp-bootstrap-js', PXP_URL . '/assets/js/bootstrap.min.js' );
		wp_enqueue_script	( 'pxp-bootstrap-js' );
	}
}

}

new PXP_Frontend_Assets();

?>