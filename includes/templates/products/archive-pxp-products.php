<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Product Page
 */

get_header(); ?>

<div id="content" class="page">
	<div class="col-full">
		<section id="main" class="fullwidth"> 
 
		<?php 
			global $pxp_products;
		?>

		<h1 class="page-title">Products</h1>

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

		<div class="load-more"><input type="hidden" name="starting_product" value="12"/><button type="button" id="load_more" class="button" name="load_more">Load More</button></div>

		</section><!-- /#main -->   
	</div><!-- /.col-full -->
</div><!-- /#content -->

<?php 
	get_footer();