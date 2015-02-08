<?php
/**
 *	Search Shortcode.
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

if( !class_exists( 'PXP_Shortcode_Search' ) )
{

class PXP_Shortcode_Search
{
	public function __construct()
	{
		add_filter( 'posts_clauses', array( &$this, 'pxp_post_clauses' ), 10, 2 );		
	}
	
	/**
	 * Display search shortcode using search template.
	 */
	public static function output( $atts )
	{
		pxp_get_template( 'pxp-template-search.php' );
	}
	
	/**
	 * Filter query post clauses. Add search LIKE query.
	 *
	 * @param array $pieces Query clauses.
	 * @param array $query WP_Query object.
	 *
	 * @return array $pieces
	 */
	public function pxp_post_clauses( $pieces, $query )
	{
		global $wpdb;
		
		$post_type = $query->get('post_type');
		$search_page = get_option( 'pxp_search_page' );
			
		// Check if in Admin Dashboard and Post Type is 'exca_reports' and not main query.
		if( !is_admin() && $post_type == 'pxp_products' && is_page( $search_page ) && !$query->is_main_query() )
		{
			if( isset( $_REQUEST['search'] ) && !isset( $query->query['meta_key'] ) )
			{
				$search = $_REQUEST['search'];
				
				$pieces['where'] .= " AND {$wpdb->posts}.post_title LIKE '%{$search}%'";
			}
		}
		
		return $pieces;
	}

}

}

return new PXP_Shortcode_Search();

?>