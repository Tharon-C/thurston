
jQuery(document).ready(function($) {
	console.log("Document Ready");

	var post_id = $('#post_ID').val();
	
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

	$('.submit-product-specs-metadata').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		var formData = $('.product-specs-ul li input').serialize();
		formData += '&post_id=' + post_id;
		formData += '&action=update_product_spec_metadata'
		console.log(formData);

		$.post(ajaxurl, formData, function(data) { 
			console.log(data);
			for (key in data) {
				var element = "[name='" + key + "']";
				var $input = $(element);
				
				console.log($input);
				$input.animate(
					{"background-color": "#66FF66"}, 
					{duration: 800, 
						specialEasing: "easeInCirc", 
						complete: function() { 
							$('.product-specs-input').animate({"background-color": "#FFFFFF"}, {duration: 800, specialEasing: "easeOutCirc"});
						}
					}
				);			
			}
		}, "json");
		return false;
	});


	$('.add-new').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		var meta = $(this).attr("id").substring(8);
		var $parent = $(this).parent().parent();
		console.log($parent);
		$.post(ajaxurl, {action: 'add_new_meta_data_blank', metakey: meta, post_id: post_id}, function(data) {
			$parent.prepend('<li class="metadata-li"><input class="product-specs-input" name="' + data + '" type="text">');
			//console.log(data);
		});
	});
	
	$('.remove').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		var meta = $(this).attr("id").substring(7);
		//console.log(meta);
		var $parent = $(this).parent();
		//console.log($parent);
		$.post(ajaxurl, {action: 'remove_meta_data_blank', meta_id: meta, post_id: post_id}, function(data) {
			console.log(data)
			$parent.remove();
		});
	});
	
	var dz = new Dropzone("#dropzone-element", {
		url: ajaxurl + "?action=upload_new_custom_product_file&post_id=" + post_id,
		headers: {
			"Accepts": "application/json"
		},
		success: function(file, response, xhr) {
			var data = $.parseJSON(response);
			if (data.success)
				location.reload();
			else
				console.log(data);
		},
		error: function(file, response, xhr) {
			console.log("Error", arguments);
		}
	});

	$('#add-product-image').click(function(event) {
		event.preventDefault();
		event.stopPropagation();


		console.log("Add Product Image", event);
	});

	
	$('#submit-product-image-metadata').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		//var post_id = $('input[name="post_id"]').val();
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