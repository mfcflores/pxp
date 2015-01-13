<?php
/**
 *	Manage Credit Adjustments Page.
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

if( !class_exists( 'PXP_Credit_Adjustments' ) )
{

class PXP_Credit_Adjustments
{
	public function __construct()
	{
		// Add Actions
		//add_action( 'save_post', array( $this, 'pxp_credit_adjustment_save_post' ), 10, 2 );
		add_action('manage_pxp_credit_adjustments_posts_custom_column', array($this, 'pxp_credit_adjustments_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_credit_adjustments_columns', array($this, 'pxp_set_custom_edit_pxp_credits_adjustment_columns'));
		add_filter('manage_edit-pxp_credit_adjustments_sortable_columns', array($this, 'pxp_edit_credit_adjustments_sortable_columns'));
	}
	
	/**
	 * Set Columns of Listings.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_set_custom_edit_pxp_credit_adjustments_columns( $columns )
	{
		$columns = array(
			'cb' 			=> '<input type="checkbox" />',
			'product_id' 	=> __( 'ID' ),
			'product_image' => __( 'Image' ),
			'product_name' 	=> __( 'Product Name' ),
			'product_code' 	=> __( 'Product Code' ),
			'product_price'	=> __( 'Price' ),
			'product_category'	=> __( 'Category' ),
			'product_date'	=> __( 'Date' )
		);
		
		return $columns;
	}
	
	/**
	 * Set values of the columns per listing.
	 *
	 * @param $columns An array of columns.
	 * @param $post_id Post ID of listing list.
	 */
	public function pxp_credit_adjustments_posts_custom_column( $column, $post_id ) 
	{		
		switch ($column) 
		{
			case 'product_id':
				$product_id = get_post_meta( $post_id, '_product_id', true );
				
				$edit 		= get_edit_post_link( $post_id );
				$trash 		= get_delete_post_link( $post_id );
				$view 		= get_permalink( $post_id );
				$restore 	= get_restore_post_link( $post_id );
				$delete 	= get_delete_permanent_post_link( $post_id );
				$post_status = ( isset( $_GET['post_status'] ) ) ? $_GET['post_status'] : NULL;
				
				if($post_status == "trash")
				{
					$row_action = '<div class="row-actions">
						<span class="edit">
							<a title="Restore this item from the Trash" href="' . $restore . '">Restore</a> | 
						</span>
						<span class="trash">
							<a class="submitdelete" href="' . $delete . '" title="Move this item to the Trash">Delete Permanently</a>
						</span>
					</div>';
				}
				else
				{
					$row_action = '<div class="row-actions">
						<span class="edit">
							<a title="Edit this item" href="' . $edit . '">Edit</a> | 
						</span>
						<span class="trash">
							<a class="submitdelete" href="' . $trash . '" title="Move this item to the Trash">Trash</a> | 
						</span>
						<span class="view">
							<a title="Edit this item" href="' . $view . '">View</a>
						</span>
					</div>';
				}
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $product_id, $row_action );
				
				
				break;
			case 'product_image':
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail();
				} 
				break;
			case 'product_name':
				$product_name = get_the_title();
				
				printf( __( '%s', '%s' ), $product_name );
				break;
			case 'product_code':
				$product_code = get_post_meta( $post_id, '_product_code', true );
				
				printf( __( '%s', '%s' ), $product_code );
				break;
			case 'product_price':
				$product_price = get_post_meta( $post_id, '_product_price', true );
				
				printf( __( '%s', '%s' ), $product_price );
				break;
			case 'product_category':
				$args = array(
					'orderby' 	=> 'name', 
					'order' 	=> 'ASC', 
					'fields' 	=> 'all'
				);
				
				$terms = wp_get_post_terms( $post_id, 'pxp_product_categories', $args );
				
				$product_categories = array();
				
				foreach( $terms as $term ):
					$product_categories[] = '<a href="' . admin_url( 'edit.php?pxp_product_categories=' . $term->slug . '&post_type=pxp_products' ) . '">' . $term->name . '</a>';
				endforeach;
				
				$product_categories = implode( ", ", $product_categories );
				
				printf( __( '%s', '%s' ), $product_categories );
				break;
			case 'product_date':
				$product_date = get_the_date( 'm/d/Y' );
				
				printf( __( '%s', '%s' ), $product_date );
				break;
		}
	}
	
	/**
	 * Set columns as sortable.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_edit_credit_adjustments_sortable_columns( $columns ) {

		$columns['product_id'] 		= 'product_id';
		$columns['product_name'] 	= 'product_name';
		$columns['product_code'] 	= 'product_code';
		$columns['product_price'] 	= 'product_price';
		$columns['product_category']= 'product_category';
		$columns['product_date'] 	= 'date';
		
		return $columns;
	}
}

}

return new PXP_Credit_Adjustments();

?>

