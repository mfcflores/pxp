<?php
/**
 *	Client Transaction page.
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

if( !class_exists( 'PXP_Client_Transactions' ) )
{

class PXP_Client_Transactions
{
	/**
	 * Display Client transaction page.
	 */
	public static function output()
	{
		$transaction_id = ( isset( $_GET['transaction'] ) ) ? $_GET['transaction'] : NULL;
?>
		<div class="wrap">
<?php
		if( $transaction_id != NULL ):
			// Output client transactions details
			self::pxp_client_transaction_details( $transaction_id );
		else:
			// Output client transactions list.
			self::pxp_client_transactions_list();
		endif;
?>
		</div>
<?php
	}
	
	/**
	 * Display Client Transaction History Page.
	 */
	public static function pxp_client_transactions_list()
	{
		global $My_WP_List_Table;
		
		$transactions = self::get_transactions();
		
		// Set the transactions to the table list.
		$My_WP_List_Table->set_item_list( $transactions );
		
		// Fetch, prepare, sort, and filter our data.
		$My_WP_List_Table->prepare_items();
?>
		<div>
			<h2>Transaction History</h2>
		<div>
		<div>
			<h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare.
			</h4>
		</div>

<?php 
		$My_WP_List_Table->display();	
	}
	
	/**
	 * Display Client Transaction Details
	 * @param	int	$transcation	Transactions done by client.
	 */
	public static function pxp_client_transaction_details( $transaction_id )
	{
	}
	
	/**
	 * Get transaction history by client.
	 *
	 * @return array $transaction Array of transaction history.
	 */
	public static function get_transactions()
	{
		global $user_ID;
		
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'pxp_transactions',
			'post_status'		=> 'private',
			'order'				=> 'date',
			'orderby'			=> 'ASC',
			'author'			=> $user_ID
		);

		$query = get_posts( $args );
		
		$transactions = array();
		
		foreach( $query as $transaction )
		{
			$transactions[] = array(
				'ID'						=> $transaction->ID,
				'transaction_description'	=> $transaction->post_content,
				'transaction_date'			=> date( 'F j, Y g:i A', strtotime( $transaction->post_date ) ),
			);
		}
		
		return $transactions;
	}
}

}

return new PXP_Client_Transactions();

?>