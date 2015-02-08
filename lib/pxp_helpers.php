<?php
/**
 *	PixelPartners Plugin admin functions
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Core
 *	@package 	PixelPartners/Lib
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}


/**
 * Create plugin page.
 *
 * @param string $option	WP Option
 * @param string $name		Page Slug
 * @param string $title		Page Title
 * @param string $content	Page Content
 */
function pxp_create_page( $option = '', $name = '', $page_title = '', $page_content = '')
{
	global $wpdb;
	
	$option_value = get_option( $option );

	// If post ID is greater than 0 and page exist.
	if ( $option_value > 0 && get_post( $option_value ) )
		return -1;

	$page_found = null;
	
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_name = %s LIMIT 1;", $name ) );
	}

	if ( $page_found ) {
		if ( ! $option_value ) {
			update_option( $option, $page_found );
		}

		return $page_found;
	}

	$page_data = array(
		'post_status'       => 'publish',
		'post_type'         => 'page',
		'post_author'       => 1,
		'post_name'         => $name,
		'post_title'        => $page_title,
		'post_content'      => $page_content,
		'comment_status'    => 'closed'
	);
	
	$page_id = wp_insert_post( $page_data );

	// Update meta "_wp_page_template" to use custom page template of plugin.
	update_post_meta( $page_id, '_wp_page_template', 'pxp-page-template.php' );
	
	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}

/**
 * Get page template.
 *
 * @param string $template File location of the page template.
 */
function pxp_get_template( $template )
{
	include_once( PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template );
}

if(!function_exists('get_restore_post_link')) :

	/*
	 * Get restore link of post
	 *
	 * @param $post_id Post ID of POST.
	 */
	function get_restore_post_link($post_id)
	{
		if( !$post_id || !is_numeric( $post_id ) ) {
			return false;
		}
		
		$_wpnonce = wp_create_nonce( 'untrash-post_' . $post_id );
		
		$url = admin_url( 'post.php?post=' . $post_id . '&action=untrash&_wpnonce=' . $_wpnonce );
		
		return $url; 
	}

endif;

if(!function_exists('get_delete_permanent_post_link')) :

	/*
 	 * Get Delete Permanent Link of Post
	 *
	 * @param $post_id Post ID of POST.
	 */
	function get_delete_permanent_post_link($post_id)
	{
		if( !$post_id || !is_numeric( $post_id ) ) {
			return false;
		}
		
		$_wpnonce = wp_create_nonce( 'delete-post_' . $post_id );
		
		$url = admin_url( 'post.php?post=' . $post_id . '&action=delete&_wpnonce=' . $_wpnonce );
		
		return $url; 
	}

endif;

if(!function_exists('pxp_encrypt_decrypt_key')) :
	function pxp_encrypt_decrypt_key($action, $text)
	{
		include_once( 'class-encryption.php' );
		
		$encryption = new Encryption;
		
		switch($action)
		{
			case 'encrypt':
				return $encryption->encode( $text );
				break;
			case 'decrypt':
				return $encryption->decode( $text );
				break;
		}
	}
endif; 

/**
 * Return array list of countries.
 */
function get_countries()
{
	return include( PXP_FILE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'countries.php' );
}

/**
 * Check if id exists.
 *
 * @param String $post_type The post type to check ID.
 * @param String $key ID key.
 * @param String $value ID value.
 * @return bool
 */
function check_id_exists( $post_type, $key, $value )
{
	$args = array(
		'posts_per_page'	=> 1,
		'post_type'			=> $post_type,
		'meta_key'			=> $key,
		'meta_value'		=> $value,
		'meta_compare'		=> '='
	);
	
	$query = new WP_Query( $args );
	
	if( $query->found_posts > 0 )
		return true;
	else
		return false;
}

/**
 * Display notification message.
 *
 * @param string $action Notification to display.
 */
function pxp_admin_notification_message( $action )
{
	$message 	= "Something went wrong, please try again.";
	$status 	= "error";
	
	switch( $action )
	{
		case 'updated_paypal':
			$message 	= "PayPal Gateway Setting updated.";
			$status 	= "updated";
			break;
		case 'credit_block_checkout_success':
			$message 	= "Your transaction is successful.";
			$status 	= "updated";
			break;
		case 'credit_block_checkout_cancelled':
			$message 	= "Transaction has been cancelled.";
			$status 	= "cancelled";
			break;
	}
	
	if( $action != "" ) :
?>
		<div id="message" class="<?php echo $status; ?> below-h2">
			<p><?php _e( $message ); ?></p>
		</div>
<?php
	endif;
}

/**
 * Return session to array.
 *
 * @param string $key Array key for session to return.
 * @return Array.
 */
function pxp_get_session( $key )
{
	global $pxp_session;
	
	if( isset( $pxp_session['credit_blocks_checkout'] ) ):
		return $pxp_session['credit_blocks_checkout']->toArray();
	endif;
	
	return false; 
}

?>