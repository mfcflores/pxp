<?php
/**
 *	Initiate ajax functions.
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

if( !class_exists( 'PXP_Ajax' ) )
{

class PXP_Ajax
{
	public function __construct()
	{
		// Get products backend
		add_action( 'wp_ajax_pxp_get_products', array( $this, 'pxp_get_products' ) );
		
		// Get products frontend
		add_action( 'wp_ajax_pxp_load_more_product', array( $this, 'pxp_load_more_product' ) );
		add_action( 'wp_ajax_nopriv_pxp_load_more_product', array( $this, 'pxp_load_more_product' ) );
	}
	
	/**
	 * Get List of Products Ajax.
	 */
	public function pxp_get_products() 
	{  
		$products = array();
	
		$args = array(
			'posts_per_page' 	=> -1,
			'post_type'			=> 'pxp_products',
			'orderby'			=> array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
			'meta_key'			=> '_product_id'
			
		);
		
		$query = new WP_Query( $args );
		
		if( $query->have_posts() ):
			while( $query->have_posts() ):
				global $post;
				
				$query->the_post();
				
				$product_name 	= get_the_title();
				$product_id		= get_post_meta( $post->ID, '_product_id', true );
				
				$products[] = array(
					'value'	=> "#" . $product_id . ' - ' . $product_name,
					'data'	=> array(
						'product_id'	=> $product_id,
						'post_id'		=> $post->ID,
						'product_name'	=> $product_name
					)
				);
			endwhile;
		endif;
		
		echo json_encode($products);
		
		wp_die();
	}
	
	/**
	 * Load more products AJAX.
	 */
	public function pxp_load_more_product()
	{
		global $post, $pxp_products;
	
		$offset = $_POST['offset'];

		if( $offset == 0 )
		{
			$starting_product = 12;
			
			$offset += $starting_product;
		}
		
		$per_product = 12;
		
		$args = array(
			'posts_per_page' 	=> $per_product,
			'post_type'   		=> 'pxp_products',
			'post_status'  		=> 'publish',
			'offset'			=> $offset,
			'order'				=> 'DESC',
			'orderby'			=> 'date'
		);
		
		$products = new WP_Query( $args );
		
		// Set has post to false
		$has_post = 0;
		$content = "";
		
		if( $products->have_posts() ):
		
			// Set has post to true
			$has_post = 1;
			
			ob_start();
			
			while( $products->have_posts() ) :
				$products->the_post();
	  
				// Post ID
				$post_id = $post->ID;
				
				// Product Details
				$product = $pxp_products->get_product( $post_id );
				
				$image_id	= get_post_thumbnail_id( $post_id );
				$image_url 	= wp_get_attachment_url( $image_id );
?>
				<li class="pxp_product">
					<a href="<?php echo get_the_permalink( $product->post_id ); ?>">
						<img width="150" height="150" src="<?php echo $image_url; ?>" class="attachment-shop_catalog wp-post-image" alt="Pxp_waterbottle_product" />
						<h3><?php _e( $product->name ); ?></h3>
						<span class="price"><span class="amount"><?php _e( $product->price ); ?>&nbsp;credits</span></span>
					</a>
				</li>
<?php
			endwhile;
			
			$content = ob_get_contents();
			
			ob_get_clean();
		endif;
		
		wp_reset_query();
		wp_reset_postdata();
		
		$offset += $per_product;
		
		echo json_encode(array($has_post, $content, $offset));
		wp_die();
	}
}

}

return new PXP_Ajax();

?>