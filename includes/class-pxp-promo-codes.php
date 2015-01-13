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
		//add_action( 'save_post', array( $this, 'pxp_promo_codes_save_post' ), 10, 2 );
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
				$product_price = get_post_meta( $post_id, '_promo_usage', true );
				
				printf( __( '%s', '%s' ), $promo_usage );
				break;
			case 'promo_expiration':
				$product_price = get_post_meta( $post_id, '_promo_expiration', true );
				
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
		
		$promo_id 			= get_post_meta( $post_id, '_promo_id', true);
		$promo_code			= get_post_meta( $post_id, '_promo_code', true);
		$promo_amount		= get_post_meta( $post_id, '_promo_amount', true);
		$promo_description	= get_post_meta( $post_id, '_promo_description', true);
		$product_id			= get_post_meta( $post_id, '_product_id', true);
		$promo_usage		= get_post_meta( $post_id, '_promo_usage', true);
		$promo_expiration	= get_post_meta( $post_id, '_promo_expiration', true);
?>
		<table class="form-table pxp_promo_codes">
			<tbody>
				<tr valign="top">
					<th><?php _e( 'Promo ID:' ); ?></th>
					<td><input type="text" name="promo_id" id="promo_id" value="<?php echo $promo_id; ?>" class="regular-text" readonly /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Promo Code:' ); ?></th>
					<td><input type="text" name="promo_code" id="promo_code" value="<?php echo $promo_code; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Description:' ); ?></th>
					<td><textarea rows="10" class="half-width"><?php _e ( $promo_description ); ?></textarea></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Amount:' ); ?></th>
					<td><input type="text" name="promo_amount" id="promo_amount" value="<?php echo $promo_amount; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Expiration Date:' ); ?></th>
					<td><input type="text" name="promo_expiration" id="promo_expiration" class="regular-text datepicker" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Allowed Products:' ); ?></th>
					<td><input type="text" name="credit_bonus" id="credit_bonus" value="" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Excluded Products:' ); ?></th>
					<td><input type="text" name="credit_bonus" id="credit_bonus" value="" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Usage:' ); ?></th>
					<td><input type="text" name="promo_usage" id="promo_usage" value="<?php echo $promo_usage; ?>" class="regular-text" /></td>
				</tr>
			</tbody>
		</table>
<?php
	}
}

}

return new PXP_Promo_Codes();

?>

