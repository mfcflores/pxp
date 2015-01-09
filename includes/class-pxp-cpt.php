<?php
/**
 *	Initiate plugin custom post types.
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

if( !class_exists( 'PXP_Cpt' ) )
{

class PXP_Cpt
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'pxp_register_post_types' ), 5 );
		add_action( 'init', array( $this, 'pxp_register_taxonomies' ), 5 );
		
		add_action( 'add_meta_boxes', array( $this, 'pxp_add_meta_boxes' ) );
	}
	
	/**
	 * Register PixelPartners Custom Post Types
	 */
	public function pxp_register_post_types()
	{
		$pxp_cpt = array(
			'product'	=> 'Product',
			'order'	=> 'Order',
			'transaction'	=> 'Transaction'
		);
		
		foreach($pxp_cpt as $key => $value)
		{
			$labels = array(
				'name'               => _x( $value .'s', 'post type general name', 'excavations' ),
				'singular_name'      => _x( $value, 'post type singular name' ),
				'menu_name'          => _x( $value . 's', 'admin menu' ),
				'name_admin_bar'     => _x( $value, 'add new on admin bar' ),
				'add_new'            => _x( 'Add New ' . $value, $key ),
				'add_new_item'       => __( 'Add New ' . $value ),
				'new_item'           => __( 'New ' . $value ),
				'edit_item'          => __( 'Edit ' . $value ),
				'view_item'          => __( 'View ' . $value ),
				'all_items'          => __( 'All ' . $value ),
				'search_items'       => __( 'Search ' . $value ),
				'parent_item_colon'  => __( 'Parent ' . $value . ':' ),
				'not_found'          => __( 'No ' . $key . ' found.' ),
				'not_found_in_trash' => __( 'No ' . $key . ' found in Trash.' ),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => false,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			);
			
			if( $key == "order" )
			{
				$args['supports'] = false;
			}
			
			if( $key == "product" )
			{
				$args['public']				= true;
				$args['capability_type']	= array( 'pxp_' . $key, 'pxp_' . $key . 's' );
				$args['map_meta_cap']		= true;
			}

			$post_type = 'pxp_' . $key . 's';
			
			register_post_type($post_type, $args);
		}
	}
	
	/**
	 * Register Custom Taxonomies.
	 */
	public function pxp_register_taxonomies()
	{	
		$labels = array(
			'name'                       => _x( 'Product Categories', 'taxonomy general name' ),
			'singular_name'              => _x( 'Product Category', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Categories' ),
			'popular_items'              => __( 'Popular Category' ),
			'all_items'                  => __( 'All Categories' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Category' ),
			'update_item'                => __( 'Update Category' ),
			'add_new_item'               => __( 'Add New Category' ),
			'new_item_name'              => __( 'New Category Name' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove category' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags' ),
			'not_found'                  => __( 'No category found.' ),
			'menu_name'                  => __( 'Product Category' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'product_category' ),
		);

		register_taxonomy( 'pxp_product_categories', 'pxp_products', $args );
		
		$labels = array(
			'name'                       => _x( 'Product Tags', 'taxonomy general name' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Tags' ),
			'popular_items'              => __( 'Popular Tag' ),
			'all_items'                  => __( 'All Tags' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag' ),
			'update_item'                => __( 'Update Tag' ),
			'add_new_item'               => __( 'Add New Tag' ),
			'new_item_name'              => __( 'New Category Name' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove tag' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags' ),
			'not_found'                  => __( 'No tag found.' ),
			'menu_name'                  => __( 'Product Tags' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'product_tags' ),
		);

		register_taxonomy( 'pxp_product_tags', 'pxp_products', $args );
	}
	
	/**
	 * Initiate meta boxes of post types.
	 */
	public function pxp_add_meta_boxes()
	{
		// PXP Orders Meta Boxes
		add_meta_box( 'pxp_orders_general', __( 'General Details' ), 'PXP_Orders::pxp_orders_general_box' , 'pxp_orders', 'normal' );
		add_meta_box( 'pxp_orders_billing', __( 'Billing Details' ), 'PXP_Orders::pxp_orders_billing_box' , 'pxp_orders', 'normal' );
		add_meta_box( 'pxp_orders_items', __( 'Order Items' ), 'PXP_Orders::pxp_orders_items_box' , 'pxp_orders', 'normal' );
		
		// PXP Products Meta Boxes
		add_meta_box( 'pxp_product_details', __( 'Product Details' ), 'PXP_Products::pxp_product_details_box' , 'pxp_products', 'normal' );
		add_meta_box( 'pxp_product_gallery', __( 'Product Gallery' ), 'PXP_Products::pxp_product_gallery_box' , 'pxp_products', 'side' );
	}
}

}

return new PXP_Cpt();

?>