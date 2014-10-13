
var thumbnailClickEventHandler = function(event) {
	event.preventDefault();
	event.stopPropagation();

	var $newFeature = $(this).attr("src");
	$("img.featured-product").attr("src", $newFeature);
};

var contentTabClickEventHandler = function(event) {
	event.preventDefault();
	event.stopPropagation();

	$(".content-tab").css({borderColor: "#BBB", color: "#BBB"});
	$(this).css({borderColor: "black", color: "black"});
	
	var idArray = $(this).attr("id").split("-");
	var id = idArray[2];
	$(".content-area").hide();
	$("#content-area-" + id).fadeIn();		
};

var swatchEasing = "swing";
var swatchDuration = "1000";

var paintSwatchClickEvent = function(event) {
	src = $(this).css("backgroundImage");
		
	$('.paint-selected-in').css({backgroundImage: src});
	$('.paint-selected-in').stop().animate({width: 200}, 1000, "swing", function() {
		$that = $('.paint-selected-out');
		$(this).toggleClass('paint-selected-out paint-selected-in selected-out selected-in');
		$that.toggleClass('paint-selected-out paint-selected-in selected-out selected-in');
		$('.paint-selected-in').css("width", 0);
	});			
	
};

var laminateSwatchClickEvent = function(event) {
	src = $(this).css("backgroundImage");
		
	$('.laminate-selected-in').css({backgroundImage: src});
	$('.laminate-selected-in').stop().animate({width: 200}, 1000, "swing", function() {
		$that = $('.laminate-selected-out');
		$(this).toggleClass('laminate-selected-out laminate-selected-in selected-out selected-in');
		$that.toggleClass('laminate-selected-out laminate-selected-in selected-out selected-in');
		$('.laminate-selected-in').css("width", 0);
	});			
	
}

var pullSwatchClickEvent = function(event) {
	src = $(this).css("backgroundImage");
		
	$('.pull-selected-in').css({backgroundImage: src});
	$('.pull-selected-in').stop().animate({width: 200}, 1000, "swing", function() {
		$that = $('.pull-selected-out');
		$(this).toggleClass('pull-selected-out pull-selected-in selected-out selected-in');
		$that.toggleClass('pull-selected-out pull-selected-in pull-out pull-in');
		$('.pull-selected-in').css("width", 0);
	});			
}

function newItemLoadedEvent()  {
	var src = $('.paints-swatch').eq(0).css("backgroundImage");
	$('.paint-selected-out').css({backgroundImage: src});
	
	src = $('.laminates-swatch').eq(0).css("backgroundImage");
	$('.laminate-selected-out').css({backgroundImage: src});

	src = $('.drawer-pulls-swatch').eq(0).css("backgroundImage");
	$('.pull-selected-out').css({backgroundImage: src});
}


$(document).ready(function() {
	
	newItemLoadedEvent();	
	
	$(".content-tab").on("click", contentTabClickEventHandler);
	$(".thumbnail-image").on("click", thumbnailClickEventHandler);
	
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

			$('#switcher-frame').stop(true, true).fadeOut('slow');
			$.post(
				data.admin_url, 
				{
					action: 'get_selected_product',
					id: $(this).attr('id')
				},
				function(response) {

					$('#switcher-frame').replaceWith(response);
					$('#switcher-frame').stop(true, true).fadeIn();	
					
					newItemLoadedEvent();

					$('.paints-swatch').on("click", paintSwatchClickEvent);
					$('.laminates-swatch').on("click", laminateSwatchClickEvent);
					$('.drawer-pulls-swatch').on("click", pullSwatchClickEvent);
					
					$(".content-tab").on("click", contentTabClickEventHandler);
					$(".thumbnail-image").on("click", thumbnailClickEventHandler);
			});
		}
		
		if ($(this).hasClass('list-depth-0')) {
			var id = $(this).attr('id');
			console.log("ID " + id);

			$('#switcher-frame').stop(true, true).fadeOut('slow');
			
			$.post(
				data.admin_url, 
				{
					action: 'get_selected_collection',
					id: id				
				}, function(response) {
					console.log(response);
					$('#switcher-frame').replaceWith('<div id="switcher-frame"><div class="page-wrapper">' + response + '</div></div>');
					$('#switcher-frame').stop(true, true).fadeIn();	
					
					newItemLoadedEvent();

					$('.paints-swatch').on("click", paintSwatchClickEvent);
					$('.laminates-swatch').on("click", laminateSwatchClickEvent);
					$('.drawer-pulls-swatch').on("click", pullSwatchClickEvent);
					
					$(".content-tab").on("click", contentTabClickEventHandler);
					$(".thumbnail-image").on("click", thumbnailClickEventHandler);
				}
			);
		}
	});
	
	$('.paints-swatch').on("click", paintSwatchClickEvent);
	$('.laminates-swatch').on("click", laminateSwatchClickEvent);
	$('.drawer-pulls-swatch').on("click", pullSwatchClickEvent);
});