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

if( !class_exists( 'PXP_Products' ) )
{

class PXP_Products
{
	public function __construct()
	{
		add_action( 'save_post', array( $this, 'pxp_product_save_post' ), 10, 2 );
		
		// Add Actions
		add_action('manage_pxp_products_posts_custom_column', array($this, 'pxp_products_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_products_columns', array($this, 'pxp_set_custom_edit_pxp_products_columns'));
		add_filter('manage_edit-pxp_products_sortable_columns', array($this, 'pxp_edit_products_sortable_columns'));
	}
	
	/**
	* Set Columns of Listings.
	*
	* @param $columns An array of columns.
	*/
	public function pxp_set_custom_edit_pxp_products_columns($columns)
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
	public function pxp_products_posts_custom_column($column, $post_id) 
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
	public function pxp_edit_products_sortable_columns( $columns ) {

		$columns['product_id'] 		= 'product_id';
		$columns['product_name'] 	= 'product_name';
		$columns['product_code'] 	= 'product_code';
		$columns['product_price'] 	= 'product_price';
		$columns['product_category']= 'product_category';
		$columns['product_date'] 	= 'date';
		
		return $columns;
	}
	
	/**
	 * Add capabilities to plugin defined roles.
	 */
	public static function pxp_product_details_box()
	{
		global $post_id;
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pxp_products', 'pxp_products_nonce' );
		
		$product_id 	= ( $post_id != NULL ) ? get_post_meta( $post_id, '_product_id', true ): get_option( 'pxp_product_id' );
		
		$product_code	= get_post_meta( $post_id, '_product_code', true );
		
		$product_price	= get_post_meta( $post_id, '_product_price', true );
		
		$product_featured	= get_post_meta( $post_id, '_product_featured', true );
		
		$product_form	= get_post_meta( $post_id, '_product_form', true);
		
		// Get Gravity Forms list.
		$gf_forms = array();
		if( class_exists('RGFormsModel') ):
			$forms = RGFormsModel::get_forms( null, 'title' );
			
			foreach( $forms as $form ):
			  $gf_forms[] = array(
				'ID'	=> $form->id,
				'title'	=> $form->title
			  );
			endforeach;
		endif;
?>
		<input name="pxp_admin_a" type="hidden" value="<?php echo ($post_id != NULL) ? "edit_product" : "add_product"; ?>">
		<table class="form-table">
			<tr>
				<th>
					<label for="pxp_product_id"><?php _e( 'Product ID' ); ?></label>
				</th>
				<td>
					<input type="text" id="pxp_product_id" value="<?php echo $product_id; ?>" class="regular-text" readonly />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_product_code"><?php _e( 'Product Code' ); ?></label>
				</th>
				<td>
					<input type="text" id="pxp_product_code" name="pxp_product_code" value="<?php echo $product_code; ?>" class="regular-text" readonly />
				</td>
			</tr>
			<tr>
				<th>
					<label for="pxp_product_price"><?php _e( 'Price' ); ?></label>
				</th>
				<td>
					<input type="text" name="product_price" id="pxp_product_price" value="<?php echo $product_price; ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label><?php _e( 'Featured Product' ); ?></label>
				</th>
				<td>
					<input type="radio" value="1" name="product_featured" <?php echo ( $product_featured == 1 ) ? "checked" : ""; ?>></input><span>Yes</span><br>
					<input type="radio" value="0" name="product_featured" <?php echo ( $product_featured == 0 ) ? "checked" : ""; ?>></input><span>No</span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="product_form"><?php _e( 'Product Form' ); ?></label>
				</th>
				<td>
					<select name="product_form" id="product_form">
						<option value="">None</option>
<?php
						if( !empty( $gf_forms) ):
							foreach( $gf_forms as $form ):
								$selected = ( $product_form == $form['ID'] ) ? "selected" : '';
								echo '<option value="' . $form['ID'] . '" ' . $selected . '>' . $form['title'] . '</option>';
							endforeach;
						endif;
?>
					</select>
				</td>
			</tr>
		</table>
<?php
	}
	
	/**
	 * Manage and display image gallery of product.
	 */
	public static function pxp_product_gallery_box()
	{
		global $post;
	
		$product_image_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
?>
		<div id="pxp_product_gallery_container">
			<ul class="pxp_product_gallery">
<?php
		if( $product_image_gallery ):
			$product_image_gallery = explode( ",", $product_image_gallery );
			
			foreach( $product_image_gallery as $attachment_id ):
				$attachment_url		=  wp_get_attachment_url( $attachment_id );
				$attachment_data 	=  wp_get_attachment_metadata( $attachment_id );
?>
				<li class="pxp_product_image col-md-4" id="<?php echo $attachment_id; ?>">
					<img src="<?php echo $attachment_url; ?>" class="col-md-12" title="<?php echo $attachment_data['image_meta']['title']; ?>">
					<ul class="actions">
						<li><a href="#" class="remove" title="Remove Image"><i class="fa fa-remove"></i></a></li>
					</ul>
					<input type="hidden" value="<?php echo $attachment_id; ?>" id="pxp_product_image_gallery" name="pxp_product_image_gallery[]">
				</li>
<?php		
			endforeach;
		endif;
?>
			</ul>
			<div class="clear"></div>
		</div>
		<p class="hide-if-no-js">
			<a href="#" class="pxp_add_product_image"><?php _e( 'Add Images to Product Gallery' ); ?></a>
		</p>
<?php
	}
	
	/*
	 * Save the meta data for Product Post Type when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @param WP_Post $post The post object.
	 */
	public function pxp_product_save_post($post_id, $post)
	{
		if( !isset( $_POST['pxp_products_nonce'] ) ) { return $post_id; }

		$nonce = $_POST['pxp_products_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_products' ) ) { return $post_id; }
	
		if( 'page' == $_POST['post_type'] ) 
		{
			if( !current_user_can( 'edit_page', $post_id ) ) { return $post_id; }
		} 
		else 
		{
			if( !current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		}
		
		if( $post->post_type != "pxp_products" )
		{
			return $post_id;
		}
		
		$product_code			= $_POST['product_code'];
		$product_price			= $_POST['product_price'];
		$product_featured		= $_POST['product_featured'];
		$product_form			= $_POST['product_form'];
		$product_image_gallery  = implode( ",", $_POST['pxp_product_image_gallery'] ); // Image Gallery

		$data = array(
			'product_code'			=> $product_code,
			'product_price'			=> $product_price,
			'product_featured'		=> $product_featured,
			'product_form'			=> $product_form,
			'product_image_gallery'	=> $product_image_gallery
		);
		
		// Check if Add or Edit Product
		if( isset( $_POST['pxp_admin_a'] ) )
		{
			if( $_POST['pxp_admin_a'] == "add_product" ) :
				// Get Product ID from options.
				$product_id = get_option( 'pxp_product_id' );
				
				update_post_meta( $post_id, '_product_id', $product_id );
				
				// Update Product ID option.
				update_option( $product_id + 1 );
			endif;
		}
		
		foreach( $data as $key => $value )
		{
			update_post_meta( $post_id, '_' . $key, $value );
		}
	}
}

}

return new PXP_Products();

?>