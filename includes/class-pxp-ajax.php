<?php
/**
 *	Initiate ajax functions.
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

if( !class_exists( 'PXP_Ajax' ) )
{

class PXP_Ajax
{
	public function __construct()
	{
		add_action( 'wp_ajax_pxp_get_products', array( $this, 'pxp_get_products' ) );
	}
	
	public function pxp_get_products() 
	{  
		$products = array();
	
		$args = array(
			'posts_per_page' 	=> -1,
			'post_type'			=> 'pxp_products',
			'orderby'			=> array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
			'meta_key'			=> '_product_id'
			
		);
		
		$query = new WP_Query( $args );
		
		if( $query->have_posts() ):
			while( $query->have_posts() ):
				global $post;
				
				$query->the_post();
				
				$product_name 	= get_the_title();
				$product_id		= get_post_meta( $post->ID, '_product_id', true );
				
				$products[] = array(
					'value'	=> "#" . $product_id . ' - ' . $product_name,
					'data'	=> array(
						'product_id'	=> $product_id,
						'post_id'		=> $post->ID,
						'product_name'	=> $product_name
					)
				);
			endwhile;
		endif;
		
		echo json_encode($products);
		
		wp_die();
	}
}

}

return new PXP_Ajax();

?>