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
		add_action( 'admin_init', array( $this, 'pxp_orders_admin_init' ) );
		add_action( 'manage_pxp_orders_posts_custom_column', array( $this, 'pxp_orders_posts_custom_column' ), 10, 2 );
		
		// Add Filters
		add_filter( 'manage_edit-pxp_orders_columns', array( $this, 'pxp_set_custom_edit_pxp_orders_columns' ) );
		add_filter( 'manage_edit-pxp_orders_sortable_columns', array( $this, 'pxp_edit_orders_sortable_columns' ) );
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
			'contact_name'	=> __( 'Contact Name' ),
			'company_name'	=> __( 'Company Name' ),
			'order_date' 	=> __( 'Date' ),
			'order_total'	=> __( 'Total' ),
			'order_status'	=> __( 'Status' )
		);
		
		return $columns;
	}
	
	/**
	 * Initialize admin init for orders
	 */
	public function pxp_orders_admin_init()
	{
		// Update Order Status.
		if( isset( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] == "pxp_orders"  && isset( $_REQUEST['id'] ) && isset( $_REQUEST['change_status'] ) ):
			$post_id = $_REQUEST['id'];
			$status = $_REQUEST['change_status'];

			update_post_meta( $post_id, '_order_status', $status);
			
			wp_redirect( admin_url( 'edit.php?post_type=pxp_orders' ) );
			exit();
		endif;
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
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $order_id, $row_action );
				break;
			case 'contact_name':
				$user_id = get_post_meta( $post_id, '_user_id', true );
				$user_info 	= get_userdata( $user_id );
				$first_name	= $user_info->first_name;
				$last_name 	= $user_info->last_name;
				$contact_name	= $first_name . ' ' . $last_name;
		
				// Temporary
				$contact_name = get_post_meta( $post_id, '_contact_name', true );
		
				printf( __( '%s', '%s' ), $contact_name );
				break;
			case 'company_name':
				$user_id 		= get_post_meta( $post_id, '_user_id', true );
				$company_name	= get_user_meta( $user_id, 'pxp_company_name', true );
				
				// Temporary
				$company_name = get_post_meta( $post_id, '_company_name', true );
				
				printf( __( '%s', '%s' ), $company_name );
				break;
			case 'order_date':
				$order_date	= get_post_meta( $post_id, '_order_date', true);
				
				$order_date	= ( $order_date != NULL ) ? date( 'F j, Y g:i A', strtotime( $order_date ) ) : '-';
				
				printf( __( '%s', '%s' ), $order_date );				
				break;
			case 'order_total':
				$order_total = get_post_meta( $post_id, '_order_total', true );
				
				$order_total = ( $order_total != NULL ) ? number_format( $order_total ) : 0;
				
				printf( __( '%s Credits', '%s' ), $order_total );
				break;
			case 'order_status':
				$order_status = get_post_meta( $post_id, '_order_status', true );
				
				$statuses = array( 'Complete', 'Processing', 'Cancelled' );
				$status = "<select class='admin-order_status' name='order_status' data-post-id='{$post_id}'>";
						
				foreach( $statuses as $value ):
					$selected = ( $order_status == $value ) ? "selected" : "";
					$status .= "<option {$selected} value='{$value}'>{$value}</option>";
				endforeach;
				
				$status .= "</select>";
				
				printf( __( '%s' ), $status );
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
		$columns['contact_name'] 	= 'contact_name';
		$columns['company_name'] 	= 'company_name';
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
		$order_date	= ( $order_date != NULL ) ? date( 'F j, Y g:i A', strtotime( $order_date ) ) : '-';
		$order_status 	= get_post_meta( $post_id, '_order_status', true);
		
		$user_id = get_post_meta( $post_id, '_user_id', true );
		$user_info 	= get_userdata( $user_id );

		$contact_name = get_post_meta( $post_id, '_contact_name', true );
		$company_name = get_post_meta( $post_id, '_company_name', true );
		$customer 		= ( $user_id ) ? $contact_name . ' (' . $company_name . ')' : "";
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
					<td><?php _e( $customer ); ?></td>
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
		
		$user_id = get_post_meta( $post_id, '_user_id', true );
		$phone	= get_user_meta( $user_id, 'phone', true );
		
		$email 	= get_post_meta( $post_id, '_email', true);
		$country = get_post_meta( $post_id, '_country', true);
		$countries = get_countries();
		$country = ( $country != NULL) ? $countries[$country] : "-";
		
?>
		<table class="form-table pxp_orders">
			<tbody>
				<tr valign="top">
					<th><?php _e( 'PayPal Email:' ); ?></th>
					<td><?php _e( $email ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Phone:' ); ?></th>
					<td><?php _e( $phone ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'PO No:' ); ?></th>
					<td><?php _e( '' ); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Address:' ); ?></th>
					<td><?php _e( $country ); ?></td>
				</tr>
			</tbody>
		</table>
<?php	
	}
	
	/**
	 * Display Ordered Items metabox.
	 */
	public static function pxp_orders_items_box( $post )
	{
		$post_id = $post->ID;
		
		$orders = get_post_meta( $post_id, '_orders', true );
		$company_name = get_post_meta( $post_id, '_company_name', true );
		
		if( $orders != NULL || !empty( $orders ) ) :
?>
		<table class="form-table pxp_orders">
			<thead>
				<tr valign="top">
					<th></th>
					<td><strong><?php _e( 'Job Reference' ); ?></strong></td>
					<td><strong><?php _e( 'Price' ); ?></strong></td>
				</tr>
			</thead>
			<tbody>
<?php
			foreach( $orders as $order )
			{
				$thumbnail 	= $order['post_id'];
				$price 		= $order['price'];
				$price		= ( $price != NULL ) ? number_format( $price ) : NULL;
				
				if( has_post_thumbnail( $thumbnail ) ): 
					$featured_img_url 	= wp_get_attachment_url( get_post_thumbnail_id( $thumbnail ), 'thumbnail' ); 
					$featured_img_title	= get_the_title( get_post_thumbnail_id( $thumbnail ) );
				endif;
?>
				<tr id="prodict-<?php echo $order['ID']; ?>" class="item <?php echo $order['post_id']; ?>" valign="top">
					<th class="thumbnail">
					<?php if( has_post_thumbnail( $thumbnail ) ): ?>
						<img width="150" height="150" alt="<?php echo $featured_img_title; ?>" title="<?php echo $featured_img_title; ?>" src="<?php echo $featured_img_url; ?>" />
					<?php endif; ?>
					</th>
					<td class="job-reference">
						<p><?php _e( 'Icon set for ' . $company_name ); ?></p>
						<p><strong><?php _e( 'Product Name' ); ?></strong> : <?php _e( $order['name'] ); ?></p>
					<?php 
						if( $order['gform'] != NULL ) :
							foreach( $order['gform']['entry'] as $field ):
								echo '<p><strong>' . $field['label'] . '</strong> : ' . $field['value'] . '</p>';
							endforeach;
						endif; 
					?>
					</td>
					<td class="product-price"><?php _e( $price . ' Credits' ); ?></td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>
<?php
		else:
			echo 'No item was ordered.';
		endif;
	}
}

}

return new PXP_Orders();

?>