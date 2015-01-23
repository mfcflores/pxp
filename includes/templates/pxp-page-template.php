<?php
/**
 *	Custom page template of plugin.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Template
 *	@package 	PixelPartners/Includes/Template
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}
get_header(); ?>

<div id="content" class="page">
	<div class="col-full">
		<section id="main" class="fullwidth"> 	
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
			
				the_content();
				
			endwhile;
		?>
		</section><!-- /#main -->   
	</div><!-- /.col-full -->
</div><!-- /#content -->

<?php
get_footer();
