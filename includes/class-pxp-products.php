<?php
/**
 *	Manage Products of Plugin
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

if( !class_exists( 'PXP_Products' ) )
{

class PXP_Products
{
	public function __construct()
	{
	}
	
	/**
	 * Add capabilities to plugin defined roles.
	 */
	public static function pxp_product_details_box()
	{
?>
		<table class="form-table">
			<tr>
				<th>
					<label for=""><?php _e( 'Product ID' ); ?></label>
				</th>
				<td>
					<input type="text" name="" id="" value="" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for=""><?php _e( 'Price' ); ?></label>
				</th>
				<td>
					<input type="text" name="" id="" value="" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for=""><?php _e( 'Featured Product' ); ?></label>
				</th>
				<td>
					<input type="text" name="" id="" value="" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for=""><?php _e( 'Tags' ); ?></label>
				</th>
				<td>
					<input type="text" name="" id="" value="" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for=""><?php _e( 'Product Form' ); ?></label>
				</th>
				<td>
					<input type="text" name="" id="" value="" class="regular-text" />
				</td>
			</tr>
		</table>
<?php
	}
	
	/**
	 * Manage and display image gallery of product.
	 */
	public static function pxp_product_gallery_box()
	{
		global $post;
		
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $post->ID
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				echo apply_filters('the_title', $attachment->post_title);
				the_attachment_link($attachment->ID, false);
			}
		}
	?>
		<div id="pxp_product_gallery_container">
			<ul class="pxp_product_gallery">
			</ul>
			<div class="clear"></div>
			<input type="hidden" value="" id="pxp_product_image_gallery" name="pxp_product_image_gallery">
		</div>
		<p class="hide-if-no-js">
			<a href="#" class="pxp_add_product_image"><?php _e( 'Add Images to Product Gallery' ); ?></a>
		</p>
	<?php
	}
}

}

return new PXP_Products();