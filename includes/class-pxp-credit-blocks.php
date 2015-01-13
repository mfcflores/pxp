<?php
/**
 *	Manage Credit Blocks Page.
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

if( !class_exists( 'PXP_Credit_Blocks' ) )
{

class PXP_Credit_Blocks
{
	public function __construct()
	{
		// Add Actions
		add_action( 'save_post', array( $this, 'pxp_credit_blocks_save_post' ), 10, 2 );
		add_action('manage_pxp_credit_blocks_posts_custom_column', array($this, 'pxp_credit_blocks_posts_custom_column'), 10, 2);
		
		// Add Filters
		add_filter('manage_edit-pxp_credit_blocks_columns', array($this, 'pxp_set_custom_edit_pxp_credit_blocks_columns'));
		add_filter('manage_edit-pxp_credit_blocks_sortable_columns', array($this, 'pxp_edit_credit_blocks_sortable_columns'));
	}
	
	/**
	 * Set Columns of Credit Blocks.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_set_custom_edit_pxp_credit_blocks_columns( $columns )
	{
		$columns = array(
			'cb' 			=> '<input type="checkbox" />',
			'credit_amount' => __( 'Amount' ),
			'credit_bonus' 	=> __( 'Bonus' ),
		);
		
		return $columns;
	}
	
	/**
	 * Set values of the columns per Credit Blocks.
	 *
	 * @param $columns An array of columns.
	 * @param $post_id Post ID of credit block list.
	 */
	public function pxp_credit_blocks_posts_custom_column( $column, $post_id ) 
	{		
		switch ($column) 
		{
			case 'credit_amount':
				$credit_amount = get_post_meta( $post_id, '_credit_amount', true );
				
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
					</div>';
				}
				
				printf( __( '<a href="%s"><strong>%s</strong></a> %s', '%s' ), $edit, $credit_amount, $row_action );
				break;
			case 'credit_bonus':
				$credit_bonus = get_post_meta( $post_id, '_credit_bonus', true );
				
				printf( __( '%s', '%s' ), $credit_bonus . '%' );
				break;
		}
	}
	
	/**
	 * Set columns as sortable.
	 *
	 * @param $columns An array of columns.
	 */
	public function pxp_edit_credit_blocks_sortable_columns( $columns ) 
	{
		$columns['credit_amount'] 	= 'credit_amount';
		$columns['credit_bonus'] 	= 'credit_bonus';
		
		return $columns;
	}
	
	/**
	 * Display Credit Blocks details metebox.
	 */
	public static function pxp_credit_blocks_general_box()
	{
		global $post_id;
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pxp_credit_blocks', 'pxp_credit_blocks_nonce' );
		
		$credit_amount 	= get_post_meta( $post_id, '_credit_amount', true);
		$credit_bonus	= get_post_meta( $post_id, '_credit_bonus', true);
?>
		<table class="form-table pxp_credit_blocks">
			<tbody>
				<tr valign="top">
					<th><?php _e( 'Amount:' ); ?></th>
					<td><input type="text" name="credit_amount" id="credit_amount" value="<?php echo $credit_amount; ?>" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e( 'Bonus:' ); ?></th>
					<td><input type="text" name="credit_bonus" id="credit_bonus" value="<?php echo $credit_bonus; ?>" class="regular-text" /></td>
				</tr>
			</tbody>
		</table>
<?php
	}
	
	/**
	 * Update credit blocks details.
	 * @param int 		$post_id 	The ID of post.
	 * @param WP_Post 	$post 		The post object.
	 */
	public function pxp_credit_blocks_save_post( $post_id, $post )
	{
		if( !isset( $_POST['pxp_credit_blocks_nonce'] ) ) { return $post_id; }

		$nonce = $_POST['pxp_credit_blocks_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_credit_blocks' ) ) { return $post_id; }
	
		if( 'page' == $_POST['post_type'] ) 
		{
			if( !current_user_can( 'edit_page', $post_id ) ) { return $post_id; }
		} 
		else 
		{
			if( !current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		}
		
		if( $post->post_type != "pxp_credit_blocks" )
		{
			return $post_id;
		}
		
		
		
		$credit_amount	= $_POST['credit_amount'];
		$credit_bonus	= $_POST['credit_bonus'];

		$data = array(
			'credit_amount'	=> $credit_amount,
			'credit_bonus'	=> $credit_bonus,
		);
		
		foreach( $data as $key => $value )
		{
			update_post_meta( $post_id, '_' . $key, $value );
		}
	}
}

}

return new PXP_Credit_Blocks();

?>

