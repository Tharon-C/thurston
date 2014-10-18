<!doctype html>

<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		
		<!-- dns prefetch -->
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		
		<!-- meta -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		
		<!-- icons -->
		<link href="<?php echo plugins_url(); ?>/kln-collections/chest.ico" rel="shortcut icon">
		<!-- fonts -->
		<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>			
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		<!-- css + javascript -->
		<?php wp_head(); ?>
        
		<script>
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>
		
		<script>
			$(document).on('click', '.thumbnail-image', function(event) {
				var $newFeature = $(this).attr('src');
				$('img.featured-product').attr('src', $newFeature);
			});
			
			$(document).on('click', '.content-tab', function(event) {
				$('.content-tab').css({borderColor: '#BBB', color: '#BBB'});
				$(this).css({borderColor: 'black', color: 'black'});
			});
			
			$(document).on('click', '#content-tab-1', function(event) {
				$('#specs').hide();
				$('.overview').fadeIn();
			});
			
			$(document).on('click', '#content-tab-2', function(event) {
				$('.overview').hide();
				$('#specs').fadeIn();
			});
		</script>
        
	</head>

	<body <?php body_class(); ?>>	
		<!-- wrapper -->
		<div class="wrapper">
			
			<!-- header -->

			</header>
				<!-- /logo -->

			<!-- /header -->