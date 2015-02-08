<?php
/**
 *	Client Order page.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Client
 *	@package 	PixelPartners/Classes/Client
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Client_Orders' ) )
{

class PXP_Client_Orders
{
	/**
	 * Display Client order page.
	 */
	public static function output()
	{
		$order_id = ( isset( $_GET['order'] ) ) ? $_GET['order'] : NULL;
?>
		<div class="wrap">
<?php
		if( $order_id != NULL ):
			// Output client order details
			self::pxp_client_order_details( $order_id );
		else:
			// Output client orders list.
			self::pxp_client_orders_list();
		endif;
?>
		</div>
<?php
	}
	
	/**
	 * Display Client Order List.
	 */
	public static function pxp_client_orders_list()
	{
		global $My_WP_List_Table;
		
		$search = ( isset( $_POST['s'] ) ) ? $_POST['s'] : NULL;
		$product_page = get_post_type_archive_link( 'pxp_products' );
	
		$orders = self::get_orders();
		
		// Set the orders to the table list.
		$My_WP_List_Table->set_item_list( $orders );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		<div id="icon-users" class="icon32"><br/></div>
		<h2>
			<?php _e('Orders'); ?>
			<a class="add-new-h2" id="pxp-client-add-order" href="<?php echo $product_page; ?>">Add New Order</a>
		</h2>
			
		<ul class="subsubsub">
			<li>
				<a <?php echo ($search == NULL) ? 'class="current"' : NULL; ?> href="<?php echo '?page=' . $_REQUEST['page']; ?>">
					<?php _e( 'All' ); ?> <span class="count">(<span id="all_count">0</span>)</span>
				</a>
			</li>
		</ul>
		
		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="pxp_client_order_filter" method="POST">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			
			<?php $My_WP_List_Table->search_box( 'search', 'search_id' ) ?>
			
			<?php $My_WP_List_Table->display() ?>
		</form>
<?php
	}
	
	/**
	 * Display Client Order Details
	 * @param	int	$order_id	Order done by client.
	 */
	public static function pxp_client_order_details( $order_id )
	{
		$order_id = ( isset( $_GET['order'] ) ) ? $_GET['order'] : NULL;
		$post_id = ( isset( $_GET['post'] ) ) ? $_GET['post'] : NULL;
		$post = get_post( $post_id );
		$user_id = get_post_meta( $post_id, '_user_id', true );
		
		$order_date 	= get_post_meta( $post_id, '_order_date', true);
		$order_date	= ( $order_date != NULL ) ? date( 'F j, Y g:i A', strtotime( $order_date ) ) : '-';
		$order_status 	= get_post_meta( $post_id, '_order_status', true);
		
		$user_info 	= get_userdata( $user_id );

		$contact_name = get_post_meta( $post_id, '_contact_name', true );
		$company_name = get_post_meta( $post_id, '_company_name', true );
		$customer 		= ( $user_id ) ? $contact_name . ' (' . $company_name . ')' : "";
		$phone	= get_user_meta( $user_id, 'phone', true );
		
		$email 	= get_post_meta( $post_id, '_email', true);
		$country = get_post_meta( $post_id, '_country', true);
		$countries = get_countries();
		$country = ( $country != NULL) ? $countries[$country] : "-";
		
		$orders = get_post_meta( $post_id, '_orders', true );
		?>
		<div class="pxp-credit-box">
				<h2>
					<?php _e( 'Order # ' ); _e( $order_id );?>
				</h2>
		</div>
		<div class="pxp-credit-box">
				<h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare. 
				</h4>
		</div>
		<div class="pxp-order">
			<div class="pxp-credit-box">
				<div class="row">
					<h3><?php _e( 'General Details: '); ?></h3>
				</div>
				<div class="row">
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
				</div>
				<div class="row">
					<h3><?php _e( 'Billing Details: '); ?></h3>
				</div>
				<div class="row">
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
				</div>
			</div>
		</div><br>
		<div class="pxp-order">
			<div class="pxp-credit-box">
				<div class="row">
					<h3><?php _e( 'Order Items: '); ?></h3>
				</div>
				<div class="row">
					<table class="form-table pxp_orders">
						<thead>
							<th></th>
							<td><strong><?php _e( 'Job Reference' ); ?></strong></td>
							<td><strong><?php _e( 'Price' ); ?></strong></td>
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
				</div>
			</div>
		</div>
<?php
	}
	
	public static function get_orders()
	{
		global $user_ID;

		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'pxp_orders',
			'orderby'			=> 'date',
			'order'				=> 'desc',
			'meta_key'			=> '_user_id',
			'meta_value'		=> $user_ID
		);
		
		$query = get_posts( $args );

		$orders = array();
		
		foreach( $query as $key => $value )
		{
			$post_id	= $value->ID;
			$order_id	= get_post_meta( $post_id, '_order_id', true );
			$order_date	= get_post_meta( $post_id, '_order_date', true );
			$order_date	= ( $order_date != NULL ) ? date( 'F j, Y g:i A', strtotime( $order_date ) ) : '-';
			
			$order_total	= get_post_meta( $post_id, '_order_total', true );
			$order_total = ( $order_total != NULL ) ? number_format( $order_total ) : 0;
			
			$order_status	= get_post_meta( $post_id, '_order_status', true );
			
			$orders[] = array(
				'ID'			=> $order_id,
				'post_id'		=> $post_id,
				'order_date'	=> $order_date,
				'order_total'	=> $order_total . ' Credits',
				'order_status'	=> $order_status
			);
		}
		
		return $orders;
	}
}

}

return new PXP_Client_Orders();

?>