<?php 

$military = get_term_by('slug', 'military', 'kln_collection');
$ecoflex = get_term_by('slug', 'eco-flex', 'kln_collection');
$ironwood2g = get_term_by('slug', 'ironwood-2g', 'kln_collection');
$seating = get_term_by('slug', 'seating', 'kln_collection');

define("MILITARY", $military->term_id);
define("ECOFLEX", $ecoflex->term_id);
define("IRONWOOD2G", $ironwood2g->term_id);
define("SEATING", $seating->term_id);

//echo "Military: ".MILITARY.'<br>';
//echo "Ironwood 2G: ".IRONWOOD2G.'<br>';
//echo "Ecoflex: ".ECOFLEX.'<br>';
//echo "Seating: ".SEATING.'<br>';



class KLNProduct {
							
	var $title;
	var $short_title;
	var $collection;
	var $collection_id;
	var $content;
	var $category;
	var $term_id;
	var $id;
	
	function __construct($id) {
		$this->id = $id;
		$this->title = get_the_title($id);
		preg_match('#.*\s(.*?)$#', $this->title, $matches);
		$this->short_title = $matches[1];
		preg_match('#^(.*?)\s#', $this->title, $matches);
		
				
		$categories = wp_get_post_terms(intval($id), 'kln_collection');
		$parent_id = $categories[0]->parent;
		$this->term_id = $categories->term_id;
		if ($parent_id) {
			do {
				$categories = get_term(intval($parent_id), 'kln_collection');
				$this->term_id = $categories->term_id;
				$parent_id = $categories->parent;
			} while ($parent_id);
		}
		
		if (!$this->collection)
			$this->collection = $this->title;
			
		if(get_the_content($this->id)) {
			$this->content = get_the_content($this->id);
		} else {
			$this->content = get_post_meta($this->id, "product_description", true);	
		}
	}
	
	function get_product() {
		$tax = get_the_taxonomies();
		$product_path = str_replace(array('.', ': '), array('', ' / '.$this->collection.' / '), strtoupper($tax['kln_collection']));
		$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' =>'any', 'post_parent' => $this->id ); 
		$attachments = get_posts( $args );
		
		$post_classes = '';
		foreach(get_post_class($this->id) as $class) {
			$post_classes .= $class.' ';
		}
		
		$post_classes = trim($post_classes);
		
		$image_ids = get_post_meta($this->id, 'featured-images', true);
		
		if (!$image_ids) {
			$brother_image = substr($attachments[0]->post_title, 1);
			$show_it = apply_filters( 'the_title' , $attachments[0]->guid);
		} else {
			$featured_image = get_post($image_ids[0]);
			$show_it = apply_filters( 'the_title' , $featured_image->guid);
		}
		
		if (!$show_it) {
			preg_match('/src="(.*?)"/i', get_the_post_thumbnail($this->id), $matches);
			$show_it = $matches[1];
		}

		
		$specs = get_post_meta($this->id, "product_spec");
		$options = get_post_meta($this->id, "product_option");
		$meta = get_post_meta($this->id);
		$gsa = ($meta['gsa'][0] == 'true') ? true : false;
		$upload_dir = wp_upload_dir('basename');

		//print_r($meta);
		$product .= 
			'<div id="switcher-frame">'.
			'<div id="product-window">';
		
		if ($show_it) {
			$product .=	
				'<div class="product-img-wrap">';
		
			$product .= ($gsa == true) ? '<img src="'.$upload_dir['url'].'/gsa-check.png" class="gsa-logo">' : '';
			$product .= '<img src="'.$show_it.'" class="featured-product">';
			$product .= ($meta["img_note"][0]) ? '<span class="img-footnote">* '.$meta["img_note"][0].'</span></div>' : '</div>';
		}
		
		$product .='<article id="content-area post-'.$this->id.'" class="'.$post_classes.'">
			<header class="entry-header">'.
				'<div class="content-tab" id="content-tab-1">'.
					'Features'.
				'</div>'.
				'<div class="content-tab" id="content-tab-2">Specs</div>'.
				'<div class="content-tab" id="content-tab-3">Color/Finish</div>'.                			
				'<div class="content-tab" id="content-tab-4">Options</div>'.                			
			'</header>'.					
				'<div class="entry-content">'.
					'<div id="content-area-1" class="content-area">'.
						'<p class="options-text overview">'.
							$this->content.
						'</p>';
						
		if ($specs[0] != "") {
			$product .= '<strong> Features Include: </strong>'.
				'<ul class="options-ul overview">';
			
			foreach($specs as $spec) {
            	$product .= '<li class="options-bullet">'.$spec.'</li>';
            }
 	        $product .= '</ul>';
		}
		
		$product .= 
		'</div>'.
			'<div id="content-area-2" class="content-area" style="display:none">'.
				'<p id="specs-text"></p>'.
				'<caption class="specs-headnote">'.$meta["dimensions_headnote"][0].'</caption>'.
				'<table class="specs-table">'.
					'<thead class="specs-thead"><tr><th></th><th>Width</th><th>Height</th><th>Depth</th></tr>';
		


									
		$product .= '</tr></thead>';
		$product .= '<caption class="specs-footer">'.$meta["dimensions_footnote"][0].'</caption>';
		$product .= '<tbody class="specs-tbody">';
		
		for ($i = 1; $i <= 4; $i++) {
			if ($meta["dimensions_style".$i]) {
				$product .= '<tr><td><strong>'.$meta["dimensions_style".$i][0].'</strong></td>';
				$product .= '<td>'.$meta["dimensions_width".$i][0].'</td>';
				$product .= '<td>'.$meta["dimensions_height".$i][0].'</td>';
				$product .= '<td>'.$meta["dimensions_depth".$i][0].'</td></tr>';
			} elseif ($meta["dimensions_type".$i]) {
				$product .= '<tr><td><strong>'.$meta["dimensions_type".$i][0].'</strong></td>';
				$product .= '<td>'.$meta["dimensions_width".$i][0].'</td>';
				$product .= '<td>'.$meta["dimensions_height".$i][0].'</td>';
				$product .= '<td>'.$meta["dimensions_depth".$i][0].'</td></tr>';
			}
			
		}
	
		$product .='</tbody>
				</table>
				<a class="button-1" target="_blank" href="'.content_url().'/uploads/'.preg_replace('#.*?\/uploads\/#', '', $meta['spec_sheet_url'][0]).'">Spec Sheet</a>
			</div>';


		$product .= '<div id="content-area-3" class="content-area" style="display:none">
					<div class="h5-head-wrap">
						<h5 class="options-h5 selecte-your-options-h5">SELECT YOUR OPTIONS BELOW:</h5>
						<h5 class="options-h5 your-selections-h5">YOUR SELECTIONS:</h5>
					</div>';
		
		$product .= '<div class="laminates-and-paints-div clear">
						<div class="laminates-and-paints-swatches">';
		
		$product .=  $laminate_results = $this->get_swatches('laminates');
		$product .=  $finish_results = $this->get_swatches('finish');
		$product .=  $paint_results = $this->get_swatches('paints');
		
		$product .= 	'</div>
						<div class="laminates-and-paints-selected clear">';				
		
		if ($laminate_results) { 
			$product .=		'<div class="laminate-selected">
								<div class="laminates-selected-in selected-in"></div>
								<div class="laminates-selected-out selected-out"></div>
								<span class="selected-text">LAMINATE</span>
							</div>';
		}

		if ($finish_results) { 
			$product .=		'<div class="finish-selected">
								<div class="finish-selected-in selected-in"></div>
								<div class="finish-selected-out selected-out"></div>
								<span class="selected-text">Finish</span>
							</div>';
		}

		if ($paint_results) { 
			$product .=		'<div class="paint-selected">
								<div class="paint-selected-in selected-in"></div>
								<div class="paint-selected-out selected-out"></div>
								<span class="selected-text">PAINT</span>
							</div>';
		}
		$product .=			'</div>
					</div>';

		$product .= '<div class="drawer-pulls-div clear">
						<div class="drawer-pulls-swatches">';
		
		$product .=  $door_results = $this->get_swatches('drawer-pulls');
			$product .= '</div>';
		if ($door_results) { 
			$product .=	'<div class="pull-selected">
							<div class="pull-selected-in selected-in"></div>
							<div class="pull-selected-out selected-out"></div>
							<span class="selected-text">DRAWER PULL</span>
						</div>';
		}
		$product .=	'</div>
				</div>';
		

		$product .= '<div id="content-area-4" class="content-area" style="display:none">';
		
		if ($specs[0] != "") {
			$product .= '<strong> Additional Options Include: </strong>'.
				'<ul class="options-ul overview">';
			
			foreach($options as $option) {
            	$product .= '<li class="options-bullet">'.$option.'</li>';
            }
 	        $product .= '</ul>';
		}
					
		$product .= '</div>'.		
		'</article>'. 	
	'</div>';
	
		if (!$paint_results && !$laminate_results && !$door_results && !$finish_results) {
			$product .= '<style>#content-tab-3 {display: none;}</style>';
		}

		$product .= '<div id="sidebar-right">';
		
		if ($image_ids) {
			foreach ($image_ids as $image_id) {
				$attachment = get_post($image_id);
				$show_it = apply_filters( 'the_title' , $attachment->guid );
				$product .= '<div class="thumbnail">'.
					'<img src="'.$show_it.'" class="thumbnail-image" id="'.get_the_title($this->ID).'">'.
				'</div>';
			}		
		} elseif ($attachments) {
			foreach ( $attachments as $attachment ) {
				
				if (substr($attachment->post_title, 1) == $brother_image) {	
					$show_it = apply_filters( 'the_title' , $attachment->guid );
					$product .= '<div class="thumbnail">'.
						'<img src="'.$show_it.'" class="thumbnail-image" id="'.get_the_title($this->ID).'">'.
					'</div>';
				}
			}
		} elseif($show_it) {
			$product .= '<div class="thumbnail">'.
					'<img src="'.$show_it.'" class="thumbnail-image" id="'.get_the_title($this->ID).'">'.
				'</div>';
		}
		
		$product .= '</div>'.
			'</div>';

		return $product;
	}
	
	function get_swatches($taxonomy) {		
		global $wpdb;
		
		$wpdb->query($wpdb->prepare('SELECT * FROM wp_terms wt 
		INNER JOIN wp_term_taxonomy wtt ON wt.term_id = wtt.term_id
		INNER JOIN wp_term_relationships wtr ON wtt.term_taxonomy_id = wtr.term_taxonomy_id 
		INNER JOIN wp_posts wp ON wtr.object_id = wp.ID	
		WHERE wp.ID = %d AND wtt.taxonomy = %s', $this->id, $taxonomy));
		
		$results = $wpdb->last_result;
		$error = $wpdb->last_error;
		$last_query = $wpdb->last_query;
		$tax_name = strtoupper($taxonomy);

		if ($results) {	
			$swatch .= '<div class="options-div">'.
				'<span><strong>'.$tax_name.':</strong></span><span id="'.$taxonomy.'-name" class="tax-name"></span><ul class="swatches-ul clear">';
			foreach ($results as $result) {
				$swatch .= '<li class="swatches-li">'.$result->description.'</li>';		
			}
			$swatch .= '</ul></div>';
		}
		return $swatch;
	}

}