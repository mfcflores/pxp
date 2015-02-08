<?php
/**
 *	Manage plugin widget product categories.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Widgets
 *	@package 	PixelPartners/Widget/ProductCategories
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Widget_Product_Categories' ) )
{

class PXP_Widget_Product_Categories extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */ 
	public function __construct()
	{
		parent::__construct(
			'pxp_widget_product_categories', // Base ID
			__( 'PXP - Product Categories' ), // Name
			array( 'description' => __( 'Display list of product categories.' ), ) // Args
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
		$args = array(
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 0,
			'hierarchical'	=> 1,
			'taxonomy'		=> 'pxp_product_categories',
			'pad_counts'	=> false
		);
		
		$product_categories = get_categories( $args );
		
		echo '<div id="pxp-productcategories" class="widget widget_pxp_widget_product_categories">';
		
		echo '<h3>' .  __( 'Featured Products' ) . '</h3>';
		
		echo '<ul>';
		
		foreach( $product_categories as $category ) :
			$category_link = get_term_link($category);
?>	
			<li>
				<a href="<?php echo $category_link; ?>" title="<?php printf( __( "View all products in %s" ), $category->name ); ?>"><?php echo $category->name; ?></a>
			</i>
<?php
		endforeach;
		
		echo '</ul></div>';
	}
}

}

?>