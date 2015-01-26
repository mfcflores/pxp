jQuery(document).ready(function($) {
	/**
	 * Load more item to Product List.
	 */
	var product_offset = 0;
	var load_more_product = false;
	var starting_products = $("input[name='starting_product']").val();
	$("#load_more").on("click", function(e) {
		e.preventDefault();
		
		if(load_more_product)
			return false;
		
		//load_more_product = true;
		
		$.ajax({
			url: ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				action: 'pxp_load_more_product',
				offset: product_offset
			},
			beforeSend: function() {
			
				$("#load_more").html('<i class="fa fa-refresh fa-spin"></i>');
			},
			success: function(data) {
				//console.log(data);
				
				product_offset = data[2];
				
				if(data[0] == 1) {
					$("#load_more").html("Load More");
				
					$("ul.pxp_products").append( data[1] );
				}
				else {
					$("#load_more").html("Load More");
					$("#load_more").hide();
				}
				
				load_more_product = false;
			}
		});
	});
});