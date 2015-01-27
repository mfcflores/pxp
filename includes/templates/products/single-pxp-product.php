<?php
/**
 * Template Name: Selected Product Page
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

get_header(); 

global $post, $pxp_products;

$post_id 		= $post->ID;
$product_id		= get_post_meta( $post_id, '_product_id', true );
$code			= get_post_meta( $post_id, '_product_code', true );
$price			= get_post_meta( $post_id, '_product_price', true );
$featured		= get_post_meta( $post_id, '_product_featured', true );
$form			= get_post_meta( $post_id, '_product_form', true );
$image_gallery	= get_post_meta( $post_id, '_product_image_gallery', true );
$image_gallery	= explode( ",", $image_gallery );
?>

<div id="content" class="page">
	<div class="col-full">
		<section id="main" class="fullwidth">
		<?php if( have_posts() ): the_post(); ?>
			<div class="pxp_product-image">
			<?php 
				if( has_post_thumbnail( $post_id ) ): 
					$featured_img_url 	= wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); 
					$featured_img_title	= get_the_title( get_post_thumbnail_id( $post->ID ) );
			?>
					<a class="fancybox" data-fancybox-group="product_gallery" title="<?php echo $featured_img_title; ?>" href="<?php echo $featured_img_url; ?>">
						<img class="big featured" width="300" height="300" src="<?php echo $featured_img_url;?>" />
					</a>
			<?php
				endif;
			
				foreach( $image_gallery as $attachment_id ):
					$attachment_url		= wp_get_attachment_url( $attachment_id );
					$attachment_title	= get_the_title( $attachment_id );
			?>
					<a class="fancybox" data-fancybox-group="product_gallery" href="<?php echo $attachment_url; ?>" title="<?php echo $attachment_title; ?>">
						<img class="thumb" width="100" height="100" src="<?php echo $attachment_url; ?>">
					</a>
			<?php
				endforeach;
			?>
			</div>

			<div class="product-title">
				<h1><?php the_title(); ?></h1>
				<h3><?php _e( $price . ' Credits' ); ?></h3>
			</div>				

			<br style="clear:both;">

			<div class="pxp_tabs">
				<ul class="tabs">
					<li class="description_tab">
						<a href="#tab-description">Description</a>
					</li>
				</ul>

				<div class="panel entry-content" id="tab-description">
					
				  <h2>Product Description</h2>

					<p><?php echo the_content(); ?></p>
					
					<div style="background: #f5f5f5; padding: 10px;">
						
						<h3>PRODUCTION NOTES</h3>
						
						<h4>About Project Assets</h4>
						
						<p><b>For first-time customers:</b> You will receive instructions from your Project Manager on how to supply the content and images so we can complete the project.</p>
						<p><b>For existing customers:</b> Please create a new folder inside your PXP folder for your new project and upload the project assets to the new folder. Use Job Title/Reference to identify the folder.</p>
						
						<h4>About Multiple Versions</h4>
						<p>If your project require multiple versions/variations of a design (e.g. for a product rollout), please provide proper information in the creative brief. Additional charges will be discussed prior to start of project.</p>
					</div>
				</div>
			</div>

			<div class="design-form">
			<?php  
				if( function_exists( 'gravity_form' ) ):
					gravity_form( $form, false, false, false, '', false );
				endif;
			?>
			</div>
			
		<?php else: ?>
			<h1>Product Not Found.</h1>
		<?php endif; ?>
		</section><!-- /#main -->   
	</div><!-- /.col-full -->
</div><!-- /#content -->

<?php
get_footer();
?>