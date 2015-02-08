<?php
/**
 *	Manage plugin widget Search.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Widgets
 *	@package 	PixelPartners/Widget/Search
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Widget_Search' ) )
{

class PXP_Widget_Search extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */ 
	public function __construct()
	{
		parent::__construct(
			'pxp_widget_search', // Base ID
			__( 'PXP - Product Search' ), // Name
			array( 'description' => __( 'Display product search form.' ), ) // Args
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
		$search_page = get_option( 'pxp_search_page' );
?>
		<div id="pxp-searchproduct" class="widget widget_pxp_widget_search">
			<h3><?php _e( 'Search Products' ); ?></h3>
			<form id="searchproduct" action="<?php echo get_permalink( $search_page ); ?>" method="GET">
				<div id="input-search">
					<input type="text" placeholder="Search for products" name="search" id="search">
					<button id="search-sumbit" type="submit"><i class="fa fa-search fa-lg"></i></button>
				</div>
			</form>
		</div>
<?php
	}
}

}

?>