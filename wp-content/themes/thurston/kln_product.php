<?php while ( have_posts() ) : the_post(); ?>
	<?php
    $title = get_the_title();
    preg_match('#.*\s(.*?)$#', $title, $matches);
    $short_title = $matches[1];
    preg_match('#^(.*?)\s#', $title, $matches);
    $collection = $matches[1];
    if (!$collection)
        $collection = $title;
?>

				
			
			<div id="product-window">
				<div id="product-path">
                   	<?php $tax = get_the_taxonomies();
					echo str_replace(array('.', ': '), array('', ' / '.$collection.' / '), strtoupper($tax['kln_collection']));
					?>                
                </div>
				<?php
				$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' =>'any', 'post_parent' => $post->ID ); 
				$attachments = get_posts( $args );
				$brother_image = substr($attachments[0]->post_title, 1);
					
				if ( $attachments ) ?>
					<img src="<?php echo $show_it = apply_filters( 'the_title' , $attachments[0]->guid); ?>" class="featured-product">
			
            	<article id="content-area post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<div class="content-tab" id="content-tab-1"> <?php echo $short_title; ?></div>
						<div class="content-tab" id="content-tab-2">Specs</div>
						<div class="content-tab" id="content-tab-3">Options</div>                			
					</header>
					<div class="entry-content">
                    	<p class="options-text overview">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent purus sapien, volutpat ac libero ut, accumsan pretium lectus. Aenean imperdiet ultricies mi, a luctus elit. Proin at tellus eu neque cursus facilisis non vitae velit. Nulla facilisi. Duis lacus odio, sagittis ac sagittis sed, mattis ac erat. Donec sed aliquet turpis. Cras imperdiet fermentum neque sit amet pretium. Vestibulum iaculis blandit turpis id luctus. Nam ut urna ut elit tincidunt ultricies. Nulla euismod lobortis mi sit amet ultricies. Sed viverra, nisl nec elementum venenatis, ligula magna tristique massa, eu posuere justo mi at enim.</p>
						<ul class="options-ul overview">
							<li class="options-bullet">Donec at condimentum quam</li>
							<li class="options-bullet">et iaculis nibh</li>
							<li class="options-bullet">Suspendisse condimentum enim</li>
							<li class="options-bullet">nec placerat mattis.</li> 
							</ul>
						<div id="specs">
							<? $product_specs = get_post_custom($this_product->ID);
							$product_specs = array_slice(array_reverse($product_specs), 2); ?>
							<p id="specs-text">
								<?php foreach ($product_specs as $k=>$v) { 
									if (is_array($v)) {
										foreach ($v as $ke=>$va)  { ?>
											<?php echo strtoupper(str_replace('_', ' ', str_replace('op', 'option', $k))).' '; ?>

										<?php
										}
									}
								} ?>
							</p>
						</div>
				                    
						<?php the_content(); ?>
						<?php wp_get_archives('type=alpha'); ?>
					</div>		
				</article> 	
			</div> 
			
			<div id="sidebar-right"> <?php
				
				if ( $attachments ) {
					foreach ( $attachments as $attachment ) {
						if (substr($attachment->post_title, 1) == $brother_image) { ?>	
							<div class="thumbnail">
								<img src="<?php echo $show_it = apply_filters( 'the_title' , $attachment->guid ); ?>" class="thumbnail-image" id="<?php the_title(); ?>">				
							</div> <?php 
						}
					}
				} ?>
			</div>	
	<?php endwhile;?>