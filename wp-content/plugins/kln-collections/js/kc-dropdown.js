var activeColor = "cerulean";

var subcategoryClickEventHandler = function(event) {
	var id = $(this).data('category_id');
	console.log(id);
	console.log($(this));
	getProductPage($(this), id);
};

var thumbnailClickEventHandler = function(event) {
	event.preventDefault();
	event.stopPropagation();

	var $newFeature = $(this).attr("src");
	$("img.featured-product").attr("src", $newFeature);
};

var contentTabClickEventHandler = function(event) {
	event.preventDefault();
	event.stopPropagation();

	$(".content-tab").css({borderColor: "#BBB", color: "#BBB", zIndex: 0});
	$(this).css({borderColor: "black", color: "black", zIndex: "1"});
	
	var idArray = $(this).attr("id").split("-");
	var id = idArray[2];
	$(".content-area").hide();
	$("#content-area-" + id).fadeIn();		
};

function swatchClickEvent(type, src, name) {	
	$('#' + type + '-name').text(' ' + name);
	if (type == "drawer-pulls") { 
		type="pull";
	} 


	
	console.log(type);
	
	$('.' + type + '-selected-in').css({backgroundImage: src});
	$('.' + type + '-selected-in').stop().animate({width: 200}, 1000, "swing", function() {
		$that = $('.' + type + '-selected-out');
		$(this).toggleClass(type + '-selected-out ' + type + '-selected-in selected-out selected-in');
		$that.toggleClass(type + '-selected-out ' + type + '-selected-in selected-out selected-in');
		$('.' + type + '-selected-in').css("width", 0);
	});			
}

function newItemLoadedEvent()  {
	
	if ($('.paints-swatch').eq(0).attr('id')) {
		var src = $('.paints-swatch').eq(0).css("backgroundImage");
		$('.paint-selected-out').css({backgroundImage: src});
		var name = $('.paints-swatch').eq(0).attr('id').replace('_', ' ').toUpperCase();
		$('#paints-name').text(' ' + name);
	}

	if ($('.laminates-swatch').eq(0).attr('id')) {
		src = $('.laminates-swatch').eq(0).css("backgroundImage");
		$('.laminates-selected-out').css({backgroundImage: src});
		var name = $('.laminates-swatch').eq(0).attr('id').replace('_', ' ').toUpperCase();
		$('#laminates-name').text(' ' + name);
	}
	
	if ($('.drawer-pulls-swatch').eq(0).attr('id')) {
		src = $('.drawer-pulls-swatch').eq(0).css("backgroundImage");
		$('.pull-selected-out').css({backgroundImage: src});
		var name = $('.drawer-pulls-swatch').eq(0).attr('id').replace('_', ' ').toUpperCase();
		$('#drawer-pulls-name').text(' ' + name);
	}

	if ($('.finish-swatch').eq(0).attr('id')) {
		var src = $('.finish-swatch').eq(0).css("backgroundImage");
		$('.finish-selected-out').css({backgroundImage: src});
		var name = $('.finish-swatch').eq(0).attr('id').replace('_', ' ').toUpperCase();
		$('#finish-name').text(' ' + name);
	}

	loadClickEvents();
	
	$(".content-tab").on("click", contentTabClickEventHandler);
	$(".thumbnail-image").on("click", thumbnailClickEventHandler);
	$(".subcategory-thumbnail").on("click", subcategoryClickEventHandler);
}


$(document).ready(function() {
	
	newItemLoadedEvent();	
	
	$(".content-area").hide();
	$("#content-area-1").show();
	
	var pathname = window.location.pathname;	
  	var $dropDown = $('.menu-item-has-children');
  	
	$dropDown.hover(function() {
		$(this).find('.sub-menu').stop(true, false).animate({height:'toggle'}, 'fast');
	});
		
	$('.cat-item').click(function(event) {

		event.preventDefault();
		event.stopPropagation();
		
		if (!$(this).hasClass('no-children')) {
			$(this).siblings().children().children().slideUp();
			$(this).children().children().slideDown();
			
			var $getSamples;
			var fetchURL = "/?id=" + $(this).attr('id');			

			if ($(this).hasClass('list-depth-0')) {
				$.each(function() {
					var $getSamples = $(this).children().children().attr('id');

				});				
			};
				 
			if ($(this).hasClass('list-depth-1')) {
				var $getSamples = {samples: $(this).children(".list-depth-2").html()};			
			}
			
			$.get(fetchURL, $getSamples, function(data) {
				$('#switcher-window').html(data);
			});
		}
		
		if ($(this).hasClass('no-children')) {
			getProductPage($(this), $(this).attr('id'));
		}
		
		if ($(this).hasClass('list-depth-0')) {
			var id = $(this).attr('id');
			//console.log("ID " + id);
			
			$('#switcher-frame').stop(true, true).fadeOut('slow');
			var action = 'get_selected_';
			action += ($(this).hasClass('kln_collection')) ? 'collection' : 'subcategory';			
			console.log(action);
			$.post(
				data.admin_url, 
				{
					action: action,
					id: id				
				}, function(response) {
					//console.log(response);
					$('#switcher-frame').replaceWith('<div id="switcher-frame"><div class="page-wrapper">' + response + '</div></div>');
					$('#switcher-frame').stop(true, true).fadeIn();	
					
					newItemLoadedEvent();
				}
			);
		}
	});

});

function getProductPage($node, page_id) {
	$('.no-children').each(function() {
		if (!$(this).hasClass('list-depth-0')) {
			$(this).removeClass("cerulean");
		}
	});
	
	$node.addClass(activeColor);
	
	$('#switcher-frame').stop(true, true).fadeOut('slow');
	$.post(
		data.admin_url, 
		{
			action: 'get_selected_product',
			id: page_id
		},
		function(response) {

			$('#switcher-frame').replaceWith(response);
			$('#switcher-frame').stop(true, true).fadeIn();	
			
			newItemLoadedEvent();
	});
}

function loadClickEvents() {
	$('.swatch').each(function() {

		var i = 0;
		var classes = new Array();

		$($(this).attr('class').split(' ')).each(function() { 
			if (this !== '') {
				classes[i++] = this;
			}    
    	});
		
		var type = classes[1].replace("-swatch", "");
		$(this).click(function() {
			swatchClickEvent(type, $(this).css("backgroundImage"), $(this).attr('id').replace(/_/g, ' ').toUpperCase());
		});
	});
}