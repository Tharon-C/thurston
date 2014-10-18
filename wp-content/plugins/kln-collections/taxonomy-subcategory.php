<?php require_once(WP_PLUGIN_DIR.'/kln-collections/products-walker.php'); ?>
<?php get_header(); ?>

	<div id="product-page-wrap" class="alfa-wrap">

        <!-- sidebar -->
        <aside class="walker-wrap sidebar" role="complementary">
        	
	
            <?php
			$term_id = get_queried_object()->term_id; //Get ID of current term
			walk_products($term_id, 'subcategory', 'Categories');
            ?>
                    
        </aside>
        <!-- /sidebar -->                   

        <div id="switcher-frame">
        	<div class="page-wrapper">
                <?php echo apply_filters('replace_term_description', term_description(), single_term_title('', false)); ?>
			</div>
        </div>
        
	</div>
 <?php get_footer(); ?>