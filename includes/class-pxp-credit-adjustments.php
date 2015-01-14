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
		add_action('manage_pxp_adjustments_posts_custom_column', array($this, 'pxp_credit_adjustments_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_adjustments_columns', array($this, 'pxp_set_custom_edit_pxp_credit_adjustments_columns'));
		add_filter('manage_edit-pxp_adjustments_sortable_columns', array($this, 'pxp_edit_credit_adjustments_sortable_columns'));
	}
	
	/**
	 * Set Columns of Listings.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_set_custom_edit_pxp_credit_adjustments_columns( $columns )
	{
		$columns = array(
			'cb' 									=> '<input type="checkbox" />',
			'credit_adjustment_id' 	=> __( 'ID' ),
			'date' 								=> __( 'Date' ),
			'order_number' 				=> __( 'Order Number' ),
			'job_reference' 				=> __( 'Job Reference' ),
			'contact_name'				=> __( 'Contact Name' ),
			'amount'							=> __( 'Amount' ),
			'description'						=> __( 'Description' ),
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
			case 'credit_adjustment_id':
				$credit_adjustment_id = get_post_meta( $post_id, '_credit_adjustment_id', true );
				
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
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $credit_adjustment_id, $row_action );
				
				
				break;
			case 'date':
				$date = get_post_meta( $post_id, '_date', true );
				
				printf( __( '%s', '%s' ), $date );
				break;
			case 'order_number':
				$order_number = get_post_meta( $post_id, '_order_number', true );
				
				printf( __( '%s', '%s' ), $order_number );
				break;
			case 'job_reference':
				$job_reference = get_post_meta( $post_id, '_job_reference', true );
				
				printf( __( '%s', '%s' ), $job_reference );
				break;
			case 'contact_name':
				$contact_name = get_post_meta( $post_id, '_contact_name', true );
				
				printf( __( '%s', '%s' ), $contact_name );
				break;
			case 'amount':
				
				$amount = get_post_meta( $post_id, '_amount', true );
				
				printf( __( '%s', '%s' ), $amount );
				break;
			case 'description':
				$description = get_post_meta( $post_id, '_description', true );
				
				printf( __( '%s', '%s' ), $description );
				break;
		}
	}
	
	/**
	 * Set columns as sortable.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_edit_credit_adjustments_sortable_columns( $columns ) {

		$columns['credit_adjustment_id'] 		= 'credit_adjustment_id';
		$columns['date'] 									= 'date';
		$columns['order_number'] 					= 'order_number';
		$columns['job_reference'] 					= 'job_reference';
		$columns['contact_name'] 					= 'contact_name';
		$columns['amount'] 							= 'amount';
		$columns['description'] 						= 'description';

		
		return $columns;
	}
/**
	 * Display Credit Adjustment details metebox.
 */
 public static function pxp_credit_adjustments_general_box()
 {
		global $post_id;
  
		//Add an nonce field so we can check it later.
		wp_nonce_field('pxp_credit_adjustments', 'pxp_credit_adjustments_nonce');
  
		$credit_adjustment_id 		= get_post_meta( $post_id, '_credit_adjustment_id', true);
		$date									= get_post_meta( $post_id, '_date', true);
		$order_number					= get_post_meta( $post_id, '_order_number', true);
		$job_reference 					= get_post_meta( $post_id, '_job_reference', true);
		$contact_name					= get_post_meta( $post_id, '_contact_name', true);
		$amount								= get_post_meta( $post_id, '_amount', true);
		$description						= get_post_meta( $post_id, '_description', true);
		
  
  
  
 
 ?>
	<table class="form-table pxp_credit_adjustments">
		<tbody>
		<!--	<tr valign="top">
				<th><?//php _e( 'ID:' ); ?></th>
					<td><input type="text" name="credit_adjustment_id" id="credit_adjustment_id" value="<?php //echo $credit_adjustment_id; ?>" class="regular-text" readonly /></td>
			</tr>
			<tr valign="top">
					<th><?//php _e( 'Date:' ); ?></th>
					<td><input type="text" name="date" id="date" class="regular-text datepicker"  readonly/></td>
			</tr> !-->
				<tr valign="top">
				<th><?php _e( 'Contact Name:' ); ?></th>
					<td><input type="text" name="contact_name" id="contact_name" value="<?php echo $contact_name; ?>" class="regular-text" /></td>
			</tr>
				<tr valign="top">
				<th><?php _e( 'Order Number:' ); ?></th>
					<td><input type="text" name="order_number" id="order_number" value="<?php echo $order_number; ?>" class="regular-text" /></td>
			</tr>
				<tr valign="top">
				<th><?php _e( 'Job Reference:' ); ?></th>
					<td><input type="text" name="job_reference" id="job_reference" value="<?php echo $job_reference; ?>" class="regular-text" /></td>
			</tr>
				<tr valign="top">
				<th><?php _e( 'Amount:' ); ?></th>
					<td><input type="text" name="amount" id="amount" value="<?php echo $amount; ?>" class="regular-text" /></td>
			</tr>
				<tr valign="top">
				<th><?php _e( 'Description:' ); ?></th>
					<td><input type="text" name="description" id="description" value="<?php echo $description; ?>" class="regular-text" /></td>
			</tr>
		</tbody>
	</table>
 <?php
 }
 
}

}

return new PXP_Credit_Adjustments();

?>

