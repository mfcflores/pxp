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

?>