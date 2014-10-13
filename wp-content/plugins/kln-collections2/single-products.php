<?php /* Template Name: Product Template */ ?>
<?php require_once(WP_PLUGIN_DIR.'/kln-collections/products-walker.php'); ?>
<?php require_once('single-products-class.php'); ?>
<?php $product = new KLNProduct(get_the_ID()); ?>
<?php get_header(); ?>

	<div id="product-page-wrap" class="alfa-wrap">

        <!-- sidebar -->
        <aside class="walker-wrap sidebar" role="complementary">

            <?php 	
            $product_id = get_queried_object()->term_id; //Get ID of current term
			$term = current(get_the_terms(get_the_id(), 'kln_collection'));
			walk_products($terms->term_id, 'kln_collection', 'Collections');
            ?>
                    
        </aside>
        <!-- /sidebar -->                   
		<?php echo $product->get_product(); ?>
	</div>
 <?php get_footer(); ?>
 