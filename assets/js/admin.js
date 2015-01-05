jQuery(document).ready(function($) {
	/**
	 * Add image to product gallery.
	 */
	var pxp_product_gallery;
	var pxp_product_image_gallery_id = $('#pxp_product_image_gallery');
	var pxp_product_images = $('#pxp_product_gallery_container ul.pxp_product_gallery');
	$(".pxp_add_product_image").on("click", function(e) {
		var attachment_ids = pxp_product_image_gallery_id.val();

		e.preventDefault();

		// If the media frame already exists, reopen it.
		if ( pxp_product_gallery ) {
			pxp_product_gallery.open();
			return;
		}

		// Create the media frame.
		pxp_product_gallery = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: 'Add Images to Product Gallery',
			button: {
				text: 'Add to gallery',
			},
			states : [
				new wp.media.controller.Library({
					title: 'Add Images to Product Gallery',
					filterable : 'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		pxp_product_gallery.on( 'select', function() {

			var selection = pxp_product_gallery.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();
				
				attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment_ids;
				
				pxp_product_images.append('<li class="pxp_product_image col-md-12" id="' + attachment.id + '"><img src="' + attachment.url + '" class="col-md-12"></li>');
				
				console.log(attachment);
			});

			pxp_product_image_gallery_id.val( attachment_ids );
		});

		// Finally, open the modal.
		pxp_product_gallery.open();
	});
});