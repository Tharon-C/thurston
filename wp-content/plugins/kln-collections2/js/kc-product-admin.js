jQuery(document).ready(function($) {
	$('select[name=term-modifier]').change(function() {
		var term = $(this).val();
		console.log("Selected Term: " + term);
		$.post(ajaxurl, {
				action: 'get_selected_collection_and_category_terms',
				subcategory_mod: $('select[name=subcategory-modifier]').val(),
				term_mod: $('select[name=term-modifier]').val()
			}, function(data) {
			$('#taxonomy-exceptions').replaceWith(data);
		});
	});
	$('select[name=subcategory-modifier]').change(function() {
		var term = $(this).val();
		console.log("Selected Subcategory: " + term);
		$.post(ajaxurl, {
				action: 'get_selected_collection_and_category_terms',
				subcategory_mod: $('select[name=subcategory-modifier]').val(),
				term_mod: $('select[name=term-modifier]').val()
			}, function(data) {
			$('#taxonomy-exceptions').replaceWith(data);
		});

	});
	$('select[name=product]').change(function() {
		var term = $(this).val();
		
		console.log(term);
	});
	$('select[name=taxonomy]').change(function() {
		var term = $(this).val();
		console.log(term);
		$('.term').hide();
		if (term == "metadata") {
			$('input[name=metadata-value]').show();
			$('input[name=metadata-key]').show();			
		} else {
			$('#' + term).show();
		}
	});

	$('.term').change(function() {
		var term = $(this).val();
		console.log(term);
	});

	$('#submit-dimensions-metadata').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		var formData = $('#dimensions_meta_box .inside input, #dimensions_meta_box .inside textarea, #dimensions_meta_box .inside select').serialize();
		console.log(formData);
		formData += '&action=update_product_metadata'
		console.log(formData);
		$.post(ajaxurl, formData, function(data) { 
			console.log(data);
		});
		return false;
	});

	$('#submit-product-image-metadata').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		var post_id = $('input[name="post_id"]').val();
		var action = $('input[name="product_image_action"]').val();
		var image_ids = '';
		
		$('input[name="featured-product-image"]').each(function() {
			if($(this).attr('checked')) {
				image_ids += $(this).val();
				image_ids += '&';
			}
		});

		//console.log(image_ids);
		//console.log(action);
		//console.log(post_id);
	
		$.post(ajaxurl, {
				post_id: post_id,
				action: action,
				image_ids: image_ids
			}, function(data) { 
			console.log(data);
		});
		return false;
	});


});