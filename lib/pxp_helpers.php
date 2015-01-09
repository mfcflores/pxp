<?php

if(!function_exists('get_restore_post_link')) :

	/*****************************************************
	* Get restore link of post
	*
	* @param $post_id Post ID of POST.
	******************************************************/
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

	/*****************************************************
	* Get Delete Permanent Link of Post
	*
	* @param $post_id Post ID of POST.
	******************************************************/
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