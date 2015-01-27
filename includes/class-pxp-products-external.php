<?php
/**
 *	Manage Products of Plugin
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

if( !class_exists( 'PXP_Products_External' ) )
{

class PXP_Products_External
{
	// WP Post Object
	public $post;
	
	public function __construct()
	{
	}
	
	/**
	 * Get PXP Products
	 *
	 * @param Int $per_page The number of products per page.
	 */
	public function get_products( $per_page = -1 )
	{	
		$args = array(
			'posts_per_page' 	=> $per_page,
			'post_type'			=> 'pxp_products',
			'post_status'		=> 'publish',
			'order'				=> 'DESC',
			'orderby'			=> 'date'
		);
		
		$query = new WP_Query( $args );
		
		$product_list = array();
		
		if( $query->have_posts() )
		{
			while( $query->have_posts() )
			{
				$query->the_post();
				
				global $post;
				
				$post_id = $post->ID;
				
				$product_list[] = (object) array(
					'post_id'		=> $post_id,
					'product_id'	=> get_post_meta( $post_id, '_product_id', true ),
					'name'			=> get_the_title( $post_id ),
					'description'	=> get_the_content(),
					'code'			=> get_post_meta( $post_id, '_product_code', true ),
					'price'			=> get_post_meta( $post_id, '_product_price', true ),
					'featured'		=> get_post_meta( $post_id, '_product_featured', true ),
					'form'			=> get_post_meta( $post_id, '_product_form', true ),
					'image_gallery'	=> get_post_meta( $post_id, '_product_image_gallery', true )
				);
			}			
		}
		
		return (object) $product_list;
	}
	
	/**
	 * Get single porduct
	 *
	 * @param Int $post_id The Post ID.
	 * @return Array Product Data.
	 */
	public function get_product( $post_id )
	{
		$product = (object) array(
			'post_id'		=> $post_id,
			'product_id'	=> get_post_meta( $post_id, '_product_id', true ),
			'name'			=> get_the_title( $post_id ),
			'description'	=> get_the_content(),
			'code'			=> get_post_meta( $post_id, '_product_code', true ),
			'price'			=> get_post_meta( $post_id, '_product_price', true ),
			'featured'		=> get_post_meta( $post_id, '_product_featured', true ),
			'form'			=> get_post_meta( $post_id, '_product_form', true ),
			'image_gallery'	=> get_post_meta( $post_id, '_product_image_gallery', true )
		);
		
		return $product;
	}
}

}

?>