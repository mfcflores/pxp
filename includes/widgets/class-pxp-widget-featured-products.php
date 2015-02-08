<?php
/**
 *	Manage plugin widget featured products.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Widgets
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Widget_Featured_Products' ) )
{

class PXP_Widget_Featured_Products extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */ 
	public function __construct()
	{
		parent::__construct(
			'pxp_widget_featured_products', // Base ID
			__( 'PXP - Featured Products' ), // Name
			array( 'description' => __( 'Display list of featured products.' ), ) // Args
		);
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */ 	
	public function widget( $args, $instance )
	{
		if ( isset( $instance[ 'product_display' ] ) )
			$product_display = $instance[ 'product_display' ];
		else 
			$product_display = 10;	
		
		$args = array(
			'posts_per_page'	=> $product_display,
			'post_type'			=> 'pxp_products',
			'post_status'		=> 'publish',
			'meta_key'			=> '_product_featured',
			'meta_value'			=> 1,
			'orderby'			=> 'rand'
		);
		
		$query = new WP_Query( $args );
?>
		<div id="pxp-searchproduct" class="widget widget_pxp_widget_search">
			<h3><?php _e( 'Featured Products' ); ?></h3>
			<ul class="product_list_widget">
<?php
			if( $query->have_posts() ):
				while( $query->have_posts() ):
					$query->the_post();
					global $post;
					
					$post_id 	= $post->ID;
					$price 		= get_post_meta( $post_id, '_product_price', true );
?>
					<li>
						<div class="col-left">
<?php
						if ( has_post_thumbnail() ):
							$thumbnail_url 	= wp_get_attachment_thumb_url( get_post_thumbnail_id( $post_id ), 'thumbnail' ); 
							$thumbnail_title	= get_the_title( get_post_thumbnail_id( $post_id ) );
						
							echo '<img alt="' . $thumbnail_title . '" class="featured-image" src="' . $thumbnail_url . '" />';
							
						endif;
?>
						</div>
						<div class="col-left desc">
							<p><a href="<?php echo get_permalink( $post_id ); ?>" title="<?php _e( $post->post_title ); ?>"><?php _e( $post->post_title ); ?></a></p>
							<p><strong><?php _e( $price . ' Credits' ); ?></strong></p>
						</div>
						<br class="clear">
					</li>
<?php
				endwhile;
			endif;
?>
			</ul>
		</div>
<?php
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */ 
	public function form( $instance )
	{
		if ( isset( $instance[ 'product_display' ] ) )
			$product_display = $instance[ 'product_display' ];
		else 
			$product_display = 10;
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'product_display' ); ?>"><?php _e( 'Featured Products:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'product_display' ); ?>" name="<?php echo $this->get_field_name( 'product_display' ); ?>" placeholder="Number of products to display" type="text" value="<?php echo esc_attr( $product_display ); ?>">
		</p> 
<?php	
	}
	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */ 
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		
		$instance['product_display'] = strip_tags( $new_instance['product_display'] );
		
		return $instance;
	}
}

}

?>