jQuery(document).ready(function($) {
	
	$(".validate").validate();
	
	$(".datepicker").datepicker({
		dateFormat: 'mm/dd/yy'
	});

	$(".number").ForceNumericOnly();
	
	$("#post").validate();
	
	$( "#pxp-tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
	$( "#pxp-tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	
	$(".tooltip").tooltip();
	
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
	
	$('.search-list-container').click(function(e) {
		// Target only parent.
		if (e.target == this) { 
           $(this).find(".search-box .autocomplete").focus();
        }
	});
	
	$('.search-list-container').on( 'click', '.promo-remove', function(e) {
		e.preventDefault();
		$(this).parent().remove();
	});
	
	$(".admin-order_status").change(function(e) {
		var status = $(this).val();
		
		var post_id 	= $(this).data('post-id'); 
		var pxp_order 	= admin_url + "edit.php?post_type=pxp_orders&id=" + post_id + "&change_status=" + status;
		
		window.location = pxp_order;
	});
	
	// Get Products
	var products = product_list();
	function product_list() {
		var pxp_products = [];
		
		$.ajax({
			url: ajaxurl,
			global: false,
			async: false,
			type: "POST",
			dataType: "JSON",
			data: {
				action : "pxp_get_products"
			},
			success: function( data ){
				pxp_products = data;
			}
		});
		
		return pxp_products;
	}
	
	/**
	 * Initiate auto complete after window load.
	 * @var products Product List.
	 */
	$(window).load( function() {
		// Initialize autocomplete with custom appendTo:
		$('.promo_allowed_products .autocomplete').autocomplete({
			lookup: products,
			minChars: 1,
			appendTo: '.promo_allowed_products',
			triggerSelectOnValidInput: false,
			onSelect: function( suggestion ) {
				var input_name = $(this).data('name');
				$('<li class="search-list"><span id="promo_allowed_products-' + suggestion.data.product_id + '">' + suggestion.value + '</span> <i class="promo-remove fa fa-remove"></i><input name="promo_allowed_products[]" type="hidden" value="' + suggestion.data.post_id + '"></li>').insertBefore( $(this).parent() );
			}
		});
		
		$('.promo_excluded_products .autocomplete').autocomplete({
			lookup: products,
			minChars: 1,
			appendTo: '.promo_excluded_products',
			triggerSelectOnValidInput: false,
			onSelect: function( suggestion ) {
				var input_name = $(this).data('name');
				$('<li class="search-list"><span id="promo_excluded_products-' + suggestion.data.product_id + '">' + suggestion.value + '</span> <i class="promo-remove fa fa-remove"></i><input name="promo_excluded_products[]" type="hidden" value="' + suggestion.data.post_id + '"></li>').insertBefore( $(this).parent() );
			}
		});
	});
	

	// Binding to trigger checkPasswordStrength
    $( '#pxp-createclient' ).on( 'keyup', 'input[name=pass1], input[name=pass2]',
        function( event ) {
            checkPasswordStrength(
                $('input[name=pass1]'),         // First password field
                $('input[name=pass2]'), 		// Second password field
                $('#pass-strength-result'),     // Strength meter
                $('button[type=submit]'),       // Submit button
                []     // Blacklisted words
            );
        }
    );

	function checkPasswordStrength( $pass1, $pass2, $strengthResult, $submitButton, blacklistArray ) {
        var pass1 = $pass1.val();
		var pass2 = $pass2.val();
	 
		// Reset the form & meter
		//$submitButton.attr( 'disabled', 'disabled' );
		$strengthResult.removeClass( 'short bad good strong' );
	 
		// Extend our blacklist array with those from the inputs & site data
		blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )
	 
		// Get the password strength
		var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );
	 
		// Add the strength meter results
		switch ( strength ) {
	 
			case 2:
				$strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
				break;
	 
			case 3:
				$strengthResult.addClass( 'good' ).html( pwsL10n.good );
				break;
	 
			case 4:
				$strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
				break;
	 
			case 5:
				$strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
				break;
	 
			default:
				$strengthResult.addClass( 'short' ).html( pwsL10n.short );
	 
		}
	 
		// The meter function returns a result even if pass2 is empty,
		// enable only the submit button if the password is strong and
		// both passwords are filled up
		if ( '' !== pass2.trim() ) {
			$submitButton.removeAttr( 'disabled' );
		}
	 
		return strength;
	}
	
	(function($) {
		$.fn.setCursorPosition = function(pos) {
			if ($(this).get(0).setSelectionRange) {
				$(this).get(0).setSelectionRange(pos, pos);
			} else if ($(this).get(0).createTextRange) {
				var range = $(this).get(0).createTextRange();
				range.collapse(true);
				range.moveEnd('character', pos);
				range.moveStart('character', pos);
				range.select();
			}
		}
	}(jQuery));
});

// Numeric only control handler
jQuery.fn.ForceNumericOnly = function() {
  return this.each(function()
  {
  jQuery(this).keydown(function(e)
  {
    var key = e.charCode || e.keyCode || 0;
    // allow backspace, tab, delete, arrows, numbers
          // and keypad numbers ONLY
    return (
      key == 8 ||
      key == 9 ||
      key == 46 ||
      (key >= 37 && key <= 40) ||
      (key >= 48 && key <= 57) ||
      (key >= 96 && key <= 105));
  });
  });
};// JavaScript Document

function number_format (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}


