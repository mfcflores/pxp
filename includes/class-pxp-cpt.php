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
		add_action( 'init', array( $this, 'pxp_register_credit_post_types' ), 5 );
		add_action( 'init', array( $this, 'pxp_register_taxonomies' ), 5 );
		
		add_filter( 'wp_insert_post_data', array( $this, 'pxp_filter_insert_post_data' ), 99, 2 );
		
		// Hide Add Menu in custom post types.
		add_action( 'admin_menu', array( $this, 'pxp_hide_add_menu' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'pxp_add_meta_boxes' ) );
		
		
		// CPT: Orders
		include_once( 'class-pxp-orders.php' );
		
		// CPT: Products
		include_once( 'class-pxp-products.php' );
		
		// CPT: Credit Blocks
		include_once( 'class-pxp-credit-blocks.php' );
		
		// CPT: Promo Codes
		include_once( 'class-pxp-promo-codes.php' );
		
		// CPT: Credit Adjustments
		include_once( 'class-pxp-credit-adjustments.php' );
	}
	
	/**
	 * Register plugin custom post types.
	 */
	public function pxp_register_post_types()
	{
		$pxp_cpt = array(
			'product'		=> 'Product',
			'order'			=> 'Order',
			'transaction'	=> 'Transaction',
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
				$args['public']				= true;
				$args['capability_type']	= array( 'pxp_' . $key, 'pxp_' . $key . 's' );
				$args['map_meta_cap']		= true;
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
	 * Register plugin credits related custom post types.
	 */
	public function pxp_register_credit_post_types()
	{
		$pxp_cpt = array(
			'credit_block'					=> 'Credit Block',
			'promo_code'					=> 'Promo Code',
			'adjustment'	   					=> 'Credit Adjustment',
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
				'all_items'          => __( $value ),
				'search_items'       => __( 'Search ' . $value ),
				'parent_item_colon'  => __( 'Parent ' . $value . ':' ),
				'not_found'          => __( 'No ' . $value . ' found.' ),
				'not_found_in_trash' => __( 'No ' . $value . ' found in Trash.' ),
			);

			if( $key == "credit_block" )
			{
				$labels['menu_name'] = "Credits";
			}
			
			$args = array(
				'labels'             	=> $labels,
				'public'             	=> true,
				'publicly_queryable' 	=> true,
				'show_ui'            	=> true,
				'show_in_menu'       	=> true,
				'query_var'          	=> true,
				'rewrite'            	=> false,
				'capability_type'    	=> array( 'pxp_' . $key, 'pxp_' . $key . 's' ),
				'has_archive'        	=> true,
				'hierarchical'       	=> false,
				'menu_position'      	=> null,
				'supports'           	=> false,
				'map_meta_cap'			=> true
			);
			
			if( $key != "credit_block" )
			{
				$args['show_in_menu'] = 'edit.php?post_type=pxp_credit_blocks';
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
	 * Hide add menu in custom post types.
	 */
	function pxp_hide_add_menu()
	{
		global $submenu;
		
		unset($submenu['edit.php?post_type=pxp_credit_blocks'][10]);
		unset($submenu['edit.php?post_type=pxp_promo_codes'][10]);
		unset($submenu['edit.php?post_type=pxp_adjustments'][10]);
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
		
		// PXP Credit Blocks Meta Boxes
		add_meta_box( 'pxp_credit_blocks_general', __( 'Credit Block Details' ), 'PXP_Credit_Blocks::pxp_credit_blocks_general_box' , 'pxp_credit_blocks', 'normal' );
		
		// PXP Promo Codes Meta Boxes
		add_meta_box( 'pxp_promo_codes_general', __( 'Promo Code Details' ), 'PXP_Promo_Codes::pxp_promo_codes_general_box' , 'pxp_promo_codes', 'normal' );
		
		//PXP Credit Adjustments Meta Boxes
		add_meta_box( 'pxp_credit_adjustments_general', __( 'Credit Adjustments Details' ), 'PXP_Credit_Adjustments::pxp_credit_adjustments_general_box' , 'pxp_adjustments', 'normal' );
	}
	
	/*
	 * Filter during add new post.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @param WP_Post $post The post object.
	 */
	public function pxp_filter_insert_post_data($data, $postarr)
	{
		global $post_id;
		
		// If it is our form has not been submitted, so we dont want to do anything
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		
		switch($data['post_type'])
		{
			case 'pxp_credit_blocks':
				$date = date( 'Y-m-d' );
				$data['post_title'] = "Credit Block - " . $date;
				$data['post_name'] 	= "credit-block-" . $date;
				break;
			case 'pxp_promo_codes':
				$date = date( 'Y-m-d' );
				$data['post_title'] = "Promo Code - " . $date;
				$data['post_name'] 	= "promo-code-" . $date;
				break;
			case 'pxp_adjustments':
				$date = date( 'Y-m-d' );
				$data['post_title'] = "Credit Adjustment - " . $date;
				$data['post_name'] 	= "credit-adjustment-" . $date;
				break;
		}
		
		return $data;
	}
}

}

return new PXP_Cpt();

?>