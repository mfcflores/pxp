<?php
/**
 *	Manage Promo Codes Page.
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

if( !class_exists( 'PXP_Promo_Codes' ) )
{

class PXP_Promo_Codes
{
	public function __construct()
	{
		// Add Actions
		add_action( 'save_post', array( $this, 'pxp_promo_codes_save_post' ), 10, 2 );
		add_action('manage_pxp_promo_codes_posts_custom_column', array($this, 'pxp_promo_codes_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_promo_codes_columns', array($this, 'pxp_set_custom_edit_pxp_promo_codes_columns'));
		add_filter('manage_edit-pxp_promo_codes_sortable_columns', array($this, 'pxp_edit_promo_codes_sortable_columns'));
	}
	
	/**
	 * Set Columns of Promo Codes.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_set_custom_edit_pxp_promo_codes_columns( $columns )
	{
		$columns = array(
			'cb' 				=> '<input type="checkbox" />',
			'promo_id' 			=> __( 'ID' ),
			'promo_code' 		=> __( 'Code' ),
			'promo_amount' 		=> __( 'Amount' ),
			'promo_description' => __( 'Description' ),
			'product_id' 		=> __( 'Product ID' ),
			'promo_usage' 		=> __( 'Usage' ),
			'promo_expiration' 	=> __( 'Expiration Date' ),
			
		);
		
		return $columns;
	}
	
	/**
	 * Set values of the columns per promo code.
	 *
	 * @param $columns An array of columns.
	 * @param $post_id Post ID of promo code list.
	 */
	public function pxp_promo_codes_posts_custom_column( $column, $post_id ) 
	{		
		switch ($column) 
		{
			case 'promo_id':
				$promo_id = get_post_meta( $post_id, '_promo_id', true );
				
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
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $promo_id, $row_action );
				
				
				break;
			case 'promo_code':
				$promo_code = get_post_meta( $post_id, '_promo_code', true );
				
				printf( __( '%s', '%s' ), $promo_code );
				break;
			case 'promo_amount':
				$promo_amount = get_post_meta( $post_id, '_promo_amount', true );
				
				printf( __( '%s', '%s' ), $promo_amount );
				break;
			case 'promo_description':
				$promo_description = get_post_meta( $post_id, '_promo_description', true );
				
				printf( __( '%s', '%s' ), $promo_description );
				break;
			case 'product_id':
				$product_id = get_post_meta( $post_id, '_product_id', true );
				
				printf( __( '%s', '%s' ), $product_id );
				break;
			case 'promo_usage':
				$promo_usage = get_post_meta( $post_id, '_promo_usage', true );
				
				printf( __( '%s', '%s' ), $promo_usage );
				break;
			case 'promo_expiration':
				$promo_expiration = get_post_meta( $post_id, '_promo_expiration', true );
				
				printf( __( '%s', '%s' ), $promo_expiration );
				break;
		}
	}
	
	/**
	 * Set columns as sortable.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_edit_promo_codes_sortable_columns( $columns ) {

		$columns['promo_id'] 			= 'promo_id';
		$columns['promo_code'] 			= 'promo_code';
		$columns['promo_amount'] 		= 'promo_amount';
		$columns['promo_description'] 	= 'promo_description';
		$columns['product_id']			= 'product_id';
		$columns['promo_usage'] 		= 'promo_usage';
		$columns['promo_expiration'] 	= 'promo_expiration';
		
		return $columns;
	}
	
	/**
	 * Display Promo Code detials metabox.
	 */
	public static function pxp_promo_codes_general_box()
	{
		global $post_id;
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pxp_promo_codes', 'pxp_promo_codes_nonce' );
		
		$promo_id 			= ( $post_id != NULL ) ? get_post_meta( $post_id, '_promo_id', true ) : get_option( 'pxp_promo_id' );
		$promo_code			= get_post_meta( $post_id, '_promo_code', true);
		$promo_amount		= get_post_meta( $post_id, '_promo_amount', true);
		$promo_description	= get_post_meta( $post_id, '_promo_description', true);
		
		$promo_allowed_products		= get_post_meta( $post_id, '_promo_allowed_products', true);
		$promo_allowed_products		= ( $promo_allowed_products != NULL ) ? explode(",", $promo_allowed_products ): NULL;
		
		$promo_excluded_products	= get_post_meta( $post_id, '_promo_excluded_products', true);
		$promo_excluded_products	= ( $promo_excluded_products != NULL ) ? explode(",", $promo_excluded_products ): NULL;
		
		$promo_allowed_categories	= get_post_meta( $post_id, '_promo_allowed_categories', true);
		$promo_allowed_categories	= ( $promo_allowed_categories != NULL ) ? explode(",", $promo_allowed_categories ): NULL;
		
		$promo_excluded_categories	= get_post_meta( $post_id, '_promo_excluded_categories', true);
		$promo_excluded_categories	= ( $promo_excluded_categories != NULL ) ? explode(",", $promo_excluded_categories ): NULL;
		
		$promo_usage_coupon	= get_post_meta( $post_id, '_promo_usage_coupon', true);
		$promo_usage_user	= get_post_meta( $post_id, '_promo_usage_user', true);
		$promo_expiration	= get_post_meta( $post_id, '_promo_expiration', true);
		
		$product_categories = get_categories(array(
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 0,
			'hierarchical'	=> 1,
			'taxonomy'		=> 'pxp_product_categories',
			'pad_counts'	=> false
		));
?>
		<input name="pxp_admin_a" type="hidden" value="<?php echo ($post_id != NULL) ? "edit_promo_code" : "add_promo_code"; ?>">
		<div id="pxp-tabs" class="pxp-tabs">
			<ul>
				<li><a href="#tabs-1">General Details</a></li>
				<li><a href="#tabs-2">Usage Restriction</a></li>
				<li><a href="#tabs-3">Usage Limits</a></li>
			</ul>
			<div id="tabs-1">
				<table class="form-table pxp_promo_codes">
					<tbody>
						<tr valign="top">
							<th><?php _e( 'Promo ID:' ); ?></th>
							<td>
								<input type="text" name="promo_id" id="pxp_promo_id" value="<?php echo $promo_id; ?>" class="regular-text" readonly />
							</td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Amount:' ); ?></th>
							<td><input type="text" name="promo_amount" id="pxp_promo_amount" value="<?php echo $promo_amount; ?>" class="regular-text" /></td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Expiration Date:' ); ?></th>
							<td><label for="pxp_promo_expiration"><input type="text" name="promo_expiration" id="pxp_promo_expiration" class="regular-text datepicker" value="<?php echo $promo_expiration; ?>" /> <i class="fa fa-calendar"></i></label></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tabs-2">
				<table class="form-table pxp_promo_codes">
					<tbody>
						<tr valign="top">
							<th><?php _e( 'Allowed Products:' ); ?></th>
							<td>
								<ul class="promo_allowed_products search-list-container half-width">
								<?php
								if( !empty( $promo_allowed_products ) ) :
									foreach( $promo_allowed_products as $post_id )
									{
										$product_name 	= get_the_title( $post_id );
										$product_id		= get_post_meta( $post_id, '_product_id', true );
								?>
										<li class="search-list"><span id="promo_allowed_products-<?php echo $product_id; ?>"><?php echo "#" . $product_id . ' - ' . $product_name; ?></span> <i class="promo-remove fa fa-remove"></i><input name="promo_allowed_products[]" type="hidden" value="<?php echo $post_id; ?>"></li>
								<?php
									}
								endif;
								?>
									<li class="search-box"><input type="text" data-name="promo_allowed_products" id="pxp_allowed_products" value="" class="regular-text autocomplete" /></li>
									<div class="clear"></div>
								</ul>
							</td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Excluded Products:' ); ?></th>
							<td>
								<ul class="promo_excluded_products search-list-container half-width">
								<?php
								if( !empty( $promo_excluded_products ) ) :
									foreach( $promo_excluded_products as $post_id )
									{
										$product_name 	= get_the_title( $post_id );
										$product_id		= get_post_meta( $post_id, '_product_id', true );
								?>
										<li class="search-list"><span id="promo_excluded_products-<?php echo $product_id; ?>"><?php echo "#" . $product_id . ' - ' . $product_name; ?></span> <i class="promo-remove fa fa-remove"></i><input name="promo_excluded_products[]" type="hidden" value="<?php echo $post_id; ?>"></li>
								<?php
									}
								endif;
								?>
									<li class="search-box"><input type="text" data-name="promo_excluded_products" id="pxp_promo_excluded_products" value="" class="regular-text autocomplete" /></li>
									<div class="clear"></div>
								</ul>
							</td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Allowed Categories:' ); ?></th>
							<td>
							<?php 
								if( !empty( $product_categories ) ):
							?>
								<ul class="category-list half-width">
							<?php
									foreach( $product_categories as $category )
									{
										$checked = ( in_array( $category->term_id , $promo_allowed_categories ) ) ? "checked" : "";
										
										echo '<li><label for="promo_allowed_categories-' . $category->term_id . '"><input name="promo_allowed_categories[]" id="promo_allowed_categories-' . $category->term_id . '" type="checkbox" value="' . $category->term_id . '" ' . $checked . ' /> ' . $category->name . '</label></li>';
									}
							?>
								</ul>
							<?php
								else:
									_e( '<i class="text-muted">Currently no product category.</i>' );
								endif;
							?>
							</td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Excluded Categories:' ); ?></th>
							<td>
							<?php 
								if( !empty( $product_categories ) ):
							?>
								<ul class="category-list half-width">
							<?php
									foreach( $product_categories as $category )
									{
										$checked = ( in_array( $category->term_id , $promo_excluded_categories ) ) ? "checked" : "";
										
										echo '<li><label for="promo_excluded_categories-' . $category->term_id . '"><input name="promo_excluded_categories[]" id="promo_excluded_categories-' . $category->term_id . '" type="checkbox" value="' . $category->term_id . '" ' . $checked . ' /> ' . $category->name . '</label></li>';
									}
							?>
								</ul>
							<?php
								else:
									_e( '<i class="text-muted">Currently no product category.</i>' );
								endif;
							?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="tabs-3">
				<table class="form-table pxp_promo_codes">
					<tbody>
						<tr valign="top">
							<th><?php _e( 'Usage limit per coupon:' ); ?></th>
							<td>
								<input type="number" name="promo_usage_coupon" id="pxp_promo_usage_coupon" value="<?php echo $promo_usage_coupon; ?>" class="regular-text" />
							</td>
						</tr>
						<tr valign="top">
							<th><?php _e( 'Usage limit per user:' ); ?></th>
							<td><input type="number" name="promo_usage_user" id="pxp_promo_usage_user" value="<?php echo $promo_usage_user; ?>" class="regular-text" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
<?php
	}
	
	/**
	 * Update promo code details
	 * @param int 		$post_id 	The ID of post.
	 * @param WP_Post 	$post 		The post object.
	 */
	public function pxp_promo_codes_save_post( $post_id, $post )
	{
		if( !isset( $_POST['pxp_promo_codes_nonce'] ) ) { return $post_id; }

		$nonce = $_POST['pxp_promo_codes_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_promo_codes' ) ) { return $post_id; }
	
		if( 'page' == $_POST['post_type'] ) 
		{
			if( !current_user_can( 'edit_page', $post_id ) ) { return $post_id; }
		} 
		else 
		{
			if( !current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		}
		
		if( $post->post_type != "pxp_promo_codes" )
		{
			return $post_id;
		}

		$promo_code			= get_the_title();
		$promo_amount		= $_POST['promo_amount'];
		$promo_description	= $_POST['promo_description'];
		
		$promo_allowed_products		= $_POST['promo_allowed_products'];
		$promo_allowed_products		= implode( ",", $promo_allowed_products );
		
		$promo_excluded_products	= $_POST['promo_excluded_products'];
		$promo_excluded_products	= implode( ",", $promo_excluded_products );
		
		$promo_allowed_categories	= $_POST['promo_allowed_categories'];
		$promo_allowed_categories	= implode( ",", $promo_allowed_categories );
		
		$promo_excluded_categories	= $_POST['promo_excluded_categories'];
		$promo_excluded_categories	= implode( ",", $promo_excluded_categories );
		
		$promo_usage_coupon		= $_POST['promo_usage_coupon'];
		$promo_usage_user		= $_POST['promo_usage_user'];
		$promo_expiration		= $_POST['promo_expiration'];

		$data = array(
			'promo_code'				=> $promo_code,
			'promo_amount'				=> $promo_amount,
			'promo_description'			=> esc_textarea( $promo_description ),
			'promo_allowed_products'	=> $promo_allowed_products,
			'promo_excluded_products'	=> $promo_excluded_products,
			'promo_allowed_categories'	=> $promo_allowed_categories,
			'promo_excluded_categories'	=> $promo_excluded_categories,
			'promo_usage_coupon'		=> $promo_usage_coupon,
			'promo_usage_user'			=> $promo_usage_user,
			'promo_expiration'			=> $promo_expiration
		);
		
		// Check if Add or Edit Promo Code
		if( isset( $_POST['pxp_admin_a'] ) )
		{
			if( $_POST['pxp_admin_a'] == "add_promo_code" ) :
				// Get Promo Code ID from options.
				$promo_id = get_option( 'pxp_promo_id' );
				
				update_post_meta( $post_id, '_promo_id', $promo_id );
				
				// Update Promo Code ID option.
				update_option( 'pxp_promo_id', $promo_id + 1 );
			endif;
		}
		
		foreach( $data as $key => $value )
		{
			update_post_meta( $post_id, '_' . $key, $value );
		}
	}
}

}

return new PXP_Promo_Codes();

?>

