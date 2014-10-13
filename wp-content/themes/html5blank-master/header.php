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
			conditionizr();
		}()
		</script>
		
	</head>

	<body <?php body_class(); ?>>	
		<!-- wrapper -->
		<?php if (!is_front_page()) { ?> <div id="navspan"></div><?php } ?>
                
        <div class="wrapper">
			<header class="header clear" role="banner">
				<div class="toll-free">Toll-Free: 800.624.9101</div>
				<div class="logo"> 
					<a href="<?php echo home_url(); ?>">
						<img src="<?php echo content_url(); ?>/uploads/2013/10/cropped-KLNpng.fw_.png" alt="Logo" class="logo-img">
					</a>
				</div>
				<? if (is_front_page()) { ?>
				<nav class="nav" role="navigation">
					<?php kln_homepage_nav(); ?>
                </nav>
				<?php } else { ?>
                <nav class="blackbar" role="navigation">
					<?php kln_products_nav('collections-nav', 'header');?>
				</nav>
				<?php } ?>
			</header>