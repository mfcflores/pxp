<?php
/**
 *	Manage Orders page.
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

if( !class_exists( 'PXP_Orders' ) )
{

class PXP_Orders
{
	public function __construct()
	{
		// Add Actions
		add_action('manage_pxp_orders_posts_custom_column', array($this, 'pxp_orders_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_orders_columns', array($this, 'pxp_set_custom_edit_pxp_orders_columns'));
		add_filter('manage_edit-pxp_orders_sortable_columns', array($this, 'pxp_edit_orders_sortable_columns'));
	}
	
	/**
	* Set Columns of Listings.
	*
	* @param $columns An array of columns.
	*/
	public function pxp_set_custom_edit_pxp_orders_columns($columns)
	{
		
		$columns = array(
			'cb' 			=> '<input type="checkbox" />',
			'order_id' 		=> __( 'ID' ),
			'order_date' 	=> __( 'Date' ),
			'order_total'	=> __( 'Total' ),
			'order_status'	=> __( 'Status' )
		);
		
		return $columns;
	}
	
	/**
	* Set values of the columns per listing.
	*
	* @param $columns An array of columns.
	* @param $post_id Post ID of listing list.
	*/
	public function pxp_orders_posts_custom_column($column, $post_id) 
	{		
		switch ($column) 
		{
			case 'order_id':
				$order_id = get_post_meta( $post_id, '_order_id', true );
				
				printf( __( '%s', '%s' ), $order_id );
				break;
			case 'order_date':
				$order_date = get_the_date( 'm/d/Y' );
				
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
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $order_date, $row_action );
				
				break;
			case 'order_total':
				$order_total = get_post_meta( $post_id, '_order_total', true );
				
				printf( __( '%s', '%s' ), $order_total );
				break;
			case 'order_status':
				$order_status = get_post_meta( $post_id, '_order_status', true );
				
				printf( __( '%s', '%s' ), $order_status );
				break;
		}
	}
	
	/**
	 * Set columns as sortable.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_edit_orders_sortable_columns( $columns ) {

		$columns['order_id'] 		= 'order_id';
		$columns['order_date'] 		= 'order_date';
		$columns['order_total'] 	= 'order_total';
		$columns['order_status'] 	= 'order_status';
		
		return $columns;
	}
	
	/**
	 * Display General Details metabox.
	 */
	public static function pxp_orders_general_box()
	{
		global $post_id;
		
		$order_date 	= get_post_meta( $post_id, '_order_date', true);
		$order_status 	= get_post_meta( $post_id, '_order_status', true);
?>
		<table class="form-table pxp_orders">
			<tbody>
				<tr valign="top">
					<th><?php _e( 'Order Date:' ); ?></th>
					<td><?php _e( $order_date ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Order Status:' ); ?></th>
					<td><?php _e( $order_status ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Customer:' ); ?></th>
					<td><?php _e( '' ); ?></td>
				</tr>
			</tbody>
		</table>
<?php	
	}
	
	/**
	 * Display Billing Details metabox.
	 */
	public static function pxp_orders_billing_box()
	{
		global $post_id;
		
		$paypal_email 	= get_post_meta( $post_id, '_paypal_email', true);
?>
		<table class="form-table pxp_orders">
			<tbody>
				<tr valign="top">
					<th><?php _e( 'PayPal Email:' ); ?></th>
					<td><?php _e( $paypal_email ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Phone:' ); ?></th>
					<td><?php _e( '' ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'PO No:' ); ?></th>
					<td><?php _e( '' ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Address:' ); ?></th>
					<td><?php _e( '' ); ?></td>
				</tr>
			</tbody>
		</table>
<?php	
	}
	
	/**
	 * Display Ordered Items metabox.
	 */
	public static function pxp_orders_items_box()
	{
	}
}

}

return new PXP_Orders();