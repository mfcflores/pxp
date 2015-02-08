<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Search Page
 */
?>

<?php
	global $pxp_products;
	
	$products 	= $pxp_products->get_products( -1);
	$search 	= isset( $_REQUEST['search'] ) ? $_REQUEST['search'] : NULL;
?>

<header><h1 class="page-title"><?php _e( 'Search Results' ); ?></h1></header>
<form id="searchproduct" action="<?php echo get_permalink( $search_page ); ?>" method="GET">
	<div id="input-search">
		<input type="text" placeholder="Search for products" name="search" id="search" value="<?php echo $search; ?>">
		<button id="search-sumbit" type="submit"><i class="fa fa-search fa-lg"></i></button>
	</div>
</form>

<?php 
	if( !empty( $products ) ):
?>
		<ul class="pxp_products">
		<?php
			$products = $pxp_products->get_products( 12 ); // Set the number of products.

			foreach( $products as $product ) :
				$image_id	= get_post_thumbnail_id( $product->post_id );
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
			endforeach;
		?>
		</ul>

		<br style="clear:both;">
<?php
	else:
?>
		<div class="title">
			Don't see what you're looking for? <a href="#">Contact Us</a> for a custom quote
		</div>
<?php
	endif;
?>