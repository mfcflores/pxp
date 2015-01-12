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
				
				if ( attachment.id ) {

					pxp_product_images.append('<li class="pxp_product_image col-md-4" id="' + attachment.id + '"><img src="' + attachment.url + '" class="col-md-12"><ul class="actions"><li><a href="#" class="remove" title="Remove Image"><i class="fa fa-remove"></i></a></li></ul><input type="hidden" id="pxp_product_image_gallery" name="pxp_product_image_gallery[]" value="' + attachment.id + '"></li>');
				}
			});

			pxp_remove_product();
		});

		// Finally, open the modal.
		pxp_product_gallery.open();
	});

	pxp_remove_product();
	
	function pxp_remove_product() {
		$(".pxp_product_image").on("click", ".remove", function(e) {
			e.preventDefault();
			
			$(this).closest(".pxp_product_image").remove();
		});
	}
	
	$("#pxp_product_gallery_container .pxp_product_gallery").sortable({
		items: 'li.pxp_product_image',
		cursor: 'move',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'ui-product-sort-placeholder',
		start:function(event,ui){
			ui.placeholder.height(ui.item.height());
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		}
	});
});
