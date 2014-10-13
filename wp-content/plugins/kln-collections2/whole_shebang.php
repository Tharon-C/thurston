<?php
/*
*    Plugin Name: The Whole Shebang
*    Description: DO NOT RUN THIS PROGRAM
*    Version: 1.0
*    Author: Kellan Cummings
*    Author URI: localhost
*    License: GPLv2
*/


add_action('admin_menu', 'kbc_settings_menu', $priority=1);
add_action('admin_init', 'init_whole_shebang');
add_action('admin_footer', 'wshe_ajax');
add_action('wp_ajax_wshe_ajax_request', 'wshe_ajax_callback' );
add_action('wp_ajax_get_selected_collection_and_category_terms', 'get_selected_products_callback');
add_action('admin_enqueue_scripts', 'enqueue_admin_product_js');
add_action('wp_ajax_update_spec_taxonomy_metadata', 'update_spec_taxonomy_metadata' );

function init_whole_shebang() {
	add_action( 'admin_post_save_kbc_options', 'process_kbc_options' );
}

function enqueue_admin_product_js() {
	wp_enqueue_script('kc-product-admin-js', plugins_url().'/kln-collections/js/kc-product-admin.js');
	wp_enqueue_style('kc-admin-css', plugins_url().'/kln-collections/css/kc-admin.css');
}

function kbc_complex_menu() {

    ?> 
    <div id="kbc-general" class="wrap">
        <h2>Whole Shebang</h2>
    
        <?php
    //Checks to see if settings have been saved and if so, prints this message
        if (isset($_GET['message']) && $_GET['message'] == '1' ) { ?>
        <div id='message' class='updated fade'><p><strong>Settings
Saved</strong></p></div>

    <?php } ?>

	<?php
	//add_product_image_metadata(33);
	//add_product_spec_option(WP_CONTENT_DIR.'/uploads/LAMINATES/', 'laminates');
	//add_product_spec_option(WP_CONTENT_DIR.'/uploads/PAINTS/', 'paints');
	//add_product_spec_option(WP_CONTENT_DIR.'/uploads/DRAWERPULLS/', 'drawer-pulls');
	//add_product_image_metadata(33);
	//add_product_spec_image(WP_CONTENT_DIR, '/uploads/LAMINATES/', 'laminates');
	//add_product_spec_image(WP_CONTENT_DIR, '/uploads/PAINTS/', 'paints');
	//add_product_spec_image(WP_CONTENT_DIR, '/uploads/DRAWERPULLS/', 'drawer-pulls');
	?>


        <form method="post" action="" id="add-from-spread" class="product-data-form">
            <h3>Add Product Information From Spreadsheet</h3>
            <input type="hidden" name="callback" value="add_products_from_spreadsheet" />
            <input type="text" name="userfile" id="userfile-afs" value="/new-items.txt" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>

        <form method="post" action="" id="update-from-spread" class="product-data-form">
            <h3>Update Product Information From Spreadsheet</h3>
            <input type="hidden" name="callback" value="update_products_from_spreadsheet" />
            <input type="text" name="userfile" id="userfile-ufs" value="/import-items.txt" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>

        <form method="post" action="" id="add-from-dir" class="product-data-form">
            <h3>Review and Add Items From Directory:</h3>
            <input type="hidden" name="callback" value="add_products_from_directory" />
            <input type="text" name="userfile" id="userfile-afd" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>
        
        <form method="post" action="" class="find-and-replace-form" id="far-products">
            <h3>Find and Replace Products</h3>
            <input type="hidden" name="callback" value="find_and_replace_product_information" />
            <input type="text" name="find_product_name" value="" />
			<input type="text" name="replace_product_name" value="" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>

        <form method="post" action="" class="find-and-replace-form" id="far-taxonomies">
            <h3>Find and Replace Taxonomy Data</h3>
            <input type="hidden" name="callback" value="find_and_replace_taxonomy_data" />
            <input type="text" name="find_product_name" value="" />
			<input type="text" name="replace_product_name" value="" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>
        
        <form method="post" action="" class="find-and-replace-metadata-form" id="far-metadata">
            <h3>Find and Replace Metadata</h3>
            <input type="hidden" name="callback" value="find_and_replace_metadata" />
            <input type="text" name="find_metadata_key" value="" />
            <input type="text" name="find_metadata_value" value="" />
			<input type="text" name="replace_metadata_value" value="" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>
        
        <form method="post" action="" class="find-and-replace-form" id="atsm-taxonomies">
            <h3>Add Taxonomy Spec Metadata</h3>
            <input type="hidden" name="callback" value="add_spec_taxonomy_metadata" />
            <input type="text" name="find_product_name" value="" />
			<input type="text" name="replace_product_name" value="" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>
        
        <form method="post" action="" class="complex-config-form" id="complex-taxonomies">
            <h3>Update Taxonomies</h3>

			<label>Select Taxonomy</label>
            <select name="term-modifier"> <?php
			$terms = get_terms('kln_collection', 'orderby=count&hide_empty=0&hierarchical=0&parent=0');
            foreach($terms as $term) { ?>
                <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
            <? } ?>
            	<option value="0" selected="selected">None</option>
            </select>
            
            <label>Select Category</label>
			<?php $terms = get_terms('subcategory', 'orderby=count&hide_empty=0&hierarchical=0&parent=0');?>
			<select name="subcategory-modifier" value="None"> <?php
            foreach($terms as $term) { ?>
                <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
            <? } ?>
            	<option value="0" selected="selected">None</option>
            </select>

			<?php echo get_product_checkboxes(); ?>            
            <br><br>

			<?php $taxonomies = get_taxonomies(); ?>
            <select name="taxonomy">
			<?php foreach($taxonomies as $taxonomy) { ?>
					<option value="<?php echo $taxonomy; ?>"><?php echo $taxonomy; ?></option>
			<? } ?>
            	<option value="metadata">metadata</option>
            </select>
            
			<?php foreach($taxonomies as $taxonomy) { ?>
                <select name="<?php echo $taxonomy; ?>-term" class="term" value="None" id="<?php echo $taxonomy; ?>" style="display:none"> <?php
				$terms = get_terms($taxonomy, 'orderby=count&hide_empty=0&hierarchical=0&parent=0');
				foreach($terms as $term) { ?>
					<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
				<? } ?>
				<option value="1">Add All</option>
				<option value="0">No Change</option>
                </select>
            <?php } ?>
            
            <input type="text" name="metadata-key" style="display:none">
            <input type="text" name="metadata-value" style="display:none">

			<select name="operation" value="Do Nothing">
				<option value="-1">Remove</option>
                <option value="1">Add</option> 
           		<option value="0">Do Nothing</option>            	
            </select>

            <input type="hidden" name="callback" value="update_spec_taxonomy_metadata" />
            <?php wp_nonce_field( 'kbc' ); ?>
            <br />  
            <input type="submit" value="START" class="button-primary" />
        </form>
        
    </div>
    <div class="ws-content-area"></div>
<?php
}



function admin_product_ajax_callback() { ?>
	<select name="product">
	<?php foreach($products as $product) { ?>
		<option value="<?php echo $product->ID; ?>"><?php echo $product->post_name; ?></option>
	
	<? } ?>
	</select>

<?php }

function wshe_ajax(){
	$ll = wp_upload_dir();
	$uploads_dir = $ll['basedir']; ?>
	
	<script>
    var uploads_url = "<?php echo $uploads_dir ;?>";
	jQuery(document).ready(function($) {
     
		$('.complex-config-form').submit(function(event) {
			event.preventDefault();
			event.stopPropagation();

			var callback_data = $(this).find("input[name='callback']").val();
			var term_mod = $(this).find("select[name='term-modifier']").val();
			var subcategory_mod = $(this).find("select[name='subcategory-modifier']").val();			
			var product = $(this).find("select[name='product']").val();			
			var taxonomy = $(this).find("select[name='taxonomy']").val();			
			var term = $(this).find("select[name='" + taxonomy + "-term']").val();			
			var metadata_key = $(this).find("input[name='metadata-key']").val();			
			var metadata_value = $(this).find("input[name='metadata-value']").val();			
			
			var operation = $(this).find("select[name='operation']").val();			
			console.log('term_mod: ' + term_mod);
			console.log('subcategory_mod: ' + subcategory_mod);
			console.log('product: ' + product);
			console.log('taxonomy: ' + taxonomy);
			console.log('term: ' + term);
			console.log('operation: ' + operation);
			
			var exclusions = '';
			$('[name="product"]').each(function() {
				if ($(this).is(':checked')) {
					exclusions += $(this).val() + "|";
				}
			});
			console.log(exclusions);			
			$.post(
                ajaxurl, 
				{
					action: callback_data,
					term_mod: term_mod,
					subcategory_mod: subcategory_mod,
					product: product,
					taxonomy: taxonomy,
					term_change: term,
					operation: operation,
					metadata_key: metadata_key,
					metadata_value: metadata_value,
					exclusions: exclusions
				},
				function(data){
					console.log(data);
					//$('.ws-content-area').replaceWith('<div class="ws-content-area">' + data + '</div>');
				}
            ).error(function(data) {
				console.log("there was a problem");
			});

		});
		
		$('.find-and-replace-form').submit(function(event) {
			callback_data = $(this).find("input[name='callback']").val();
			findproduct = $(this).find("input[name='find_product_name']").val();
			replaceproduct = $(this).find("input[name='replace_product_name']").val();			
			
			event.preventDefault();
			event.stopPropagation();
			
			$.post(
                ajaxurl, 
				{
					action: 'wshe_ajax_request',
					callback: callback_data,
					find_product: findproduct,
					replace_product: replaceproduct
				},
				function(data){
					$('.ws-content-area').replaceWith('<div class="ws-content-area">' + data + '</div>');
				}
            ).error(function(data) {
				console.log("there was a problem");
			});

		});
		
		$('.find-and-replace-metadata-form').submit(function(event) {
			callback_data = $(this).find("input[name='callback']").val();
			metadatakey = $(this).find("input[name='find_metadata_key']").val();
			findmetadata = $(this).find("input[name='find_metadata_value']").val();
			replacemetadata = $(this).find("input[name='replace_metadata_value']").val();			
			
			event.preventDefault();
			event.stopPropagation();
			
			$.post(
                ajaxurl, 
				{
					action: 'update_spec_taxonomy_metadata',
					callback: callback_data,
					find_key: metadatakey,
					find_value: findmetadata,
					replace_value: replacemetadata
				},
				function(data){
					$('.ws-content-area').replaceWith('<div class="ws-content-area">' + data + '</div>');
				}
            ).error(function(data) {
				console.log("there was a problem");
			});

		});
		
		$('.product-data-form').submit( function(event){
            callback_data = $(this).find("input[name='callback']").val();
			userfile = uploads_url + $(this).find("input[name='userfile']").val();				
			
			event.preventDefault();
			event.stopPropagation();
			
			$.post(
                ajaxurl, 
				{
					action: 'wshe_ajax_request',
					callback: callback_data,
					user_file: userfile,
				},
				function(data){
					$('.ws-content-area').replaceWith('<div class="ws-content-area">' + data + '</div>');
				}
            ).error(function(data) {
				console.log("there was a problem");
			});

		});
        
    });
    </script> <?php
}

function set_wordpress_post_meta($post_id, $meta, $content) {
	//echo $meta.': '.$content.'<br>';
	add_post_meta($post_id, $meta, trim($content), true);		
}

function set_wordpress_post_variable($post_id, $key, $value) {
	//echo $key.': '.$value.'<br>';
	wp_update_post(array('ID'=>$post_id, $key=>$value));
}

function set_wordpress_post_meta_array($post_id, $meta, $content, $delimiter = ';') {
	$options = explode($delimiter, $content);
	if ($meta && $content) {
		delete_post_meta($post_id, $meta);
		foreach ($options as $option) {
			$option = trim($option);
			//echo 'Option: '.$option.'<br>';
			add_post_meta($post_id, $meta, $option, false);
		}
	}
}

function set_wordpress_post_pdf_meta($post_id, $meta, $content) {
	$uploads = wp_upload_dir();
	$upload_dir = $uploads['basedir'];
	$content = $upload_dir.'/SPECSHEETS/'.rawurlencode($content).'.pdf';
	//echo $meta.': '.$content.'<br>';
	add_post_meta($post_id, $meta, trim($content), true);		
}

function skip_this_function($id, $meta, $content) {
	//echo $meta.': '.$content.' Skipped<br>';
}

function find_and_replace_product_information($find, $replace) {

	$products = get_posts(array('posts_per_page' => -1, 'post_type' => 'kln_products'));
	foreach ($products as $product) {
		$collection = stripos($product->post_title, $find);
		if ( $collection !== false && $collection === 0) {
			echo $product->post_title.'<br>';
			echo $product->post_name.'<br>';
			echo $product->ID.'<br>';

			$title = preg_replace('/'.$find.'/i', $replace, $product->post_title);
			$name = preg_replace('/'.$find.'/i', $replace, $product->post_name);
			$args = array('ID'=>$product->ID, 'post_name'=>$name, 'post_title'=>$title);
			
			echo $title.'<br>';
			echo $name.'<br><br>';

			//uncomment to run this plugin.
			wp_update_post($args);
		}
	}
	exit;
}

function  find_and_replace_taxonomy_data($find, $replace) {
	$terms = get_terms('kln_collection');
	foreach ($terms as $term) {
		if (stripos($term->name, $find) !== false || stripos($term->slug, $find) !== false ) { 
			echo 'Name: '.$term->name.', ';
			echo 'Slug: '.$term->slug.', ';
			echo 'ID: '.$term->term_id.'<br>';
			
			$slug = preg_replace('/'.$find.'/i', $replace, $term->slug);
			$name = preg_replace('/'.$find.'/i', $replace, $term->name);
			
			echo 'Name: '.$name.', ';
			echo 'Slug: '.$slug.'<br><br>';
			
			
			$args = array('slug'=>$slug, 'name'=>$name);
			wp_update_term($term->term_id, 'kln_collection', $args); 
		}
	}
}

function  find_and_replace_metadata($key, $find, $replace) {
	echo "$key $find $replace";
	$products = get_posts(array('post_type'=>'kln_products', 'posts_per_page'=>-1));
	foreach($products as $product) {
		$product_meta = get_post_meta($product->ID, $key);
		//print_r($product_meta);
		if (is_array($product_meta)) {
			foreach($product_meta as $datum) {
				if ($datum == $find) {
					echo 'Multiple '.$datum;
					echo '<br>';
					update_post_meta($product->ID, $key, $replace, $find);
				}
			}
		} else {
			echo 'Single: '.$product_meta;
			echo '<br>';
			update_post_meta($product->ID, $key, $replace, $find);
		}
	}
	exit;
}

function add_product_spec_option($dirpath, $option_name) {
	echo $option_name.'<br>';
	$dir = scandir($dirpath);
	$dir = array_slice($dir, 2);
	$dir = preg_replace('#\.[a-zA-Z]{3}#', '', $dir);
	add_option($option_name, $dir);
	print_r(get_option($option_name));	
}


function add_product_spec_image($dirpath, $subfolder, $option_category) {
	$military = get_term_by('slug', 'military', 'kln_collection');
	$ecoflex = get_term_by('slug', 'eco-flex', 'kln_collection');
	$ironwood2g = get_term_by('slug', 'ironwood-2g', 'kln_collection');
	$seating = get_term_by('slug', 'seating', 'kln_collection');
	
	define("MILITARY", $military->term_id);
	define("ECOFLEX", $ecoflex->term_id);
	define("IRONWOOD2G", $ironwood2g->term_id);
	define("SEATING", $seating->term_id);
	
	
	$dir = scandir($dirpath.$subfolder);
	$dir = array_slice($dir, 2);
	foreach ($dir as $img) {
		$option_name = preg_replace('#\.[a-zA-Z]{3}#', '', $img);
		$filepath = WP_CONTENT_URL.$subfolder.$img;
		
		$wp_filetype = wp_check_filetype($img, null );
		$attachment = array(
			'guid' => $filepath, 
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $option_name,
			'post_content' => '',
			'post_status' => 'inherit'
		);
	  
	 	$thing = get_term_by('slug', strtolower($option_name), $option_category);
		$posty = get_post();
		echo $option_name.'<br>';
		echo $option_category.': '.$thing->name.' ('.$thing->term_id.')<br>';
		
		$collections = array();
				
	  	if ($option_category == 'paints') {
			$collections = array(MILITARY, IRONWOOD2G);
			echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
		} elseif($option_category == 'laminates') {
			if ($option_name == 'MAPLE') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'OAK-QUARTERED') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'WILD-CHERRY') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'OAK-GOLDEN') {
				$collections = array(MILITARY);
				echo 'Military ('.MILITARY.')<br>';
			} elseif ($option_name == 'OAK-SOLAR') {
				$collections = array(MILITARY);
				echo 'Military ('.MILITARY.')<br>';
			} elseif ($option_name == 'MAHOGANY') {
				$collections = array(MILITARY);
				echo 'Military ('.MILITARY.')<br>';
			}

			
		} elseif($option_category == 'drawer-pulls') {
			if ($option_name == 'CNTP') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'STEEL') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'ACNT') {
				$collections = array(MILITARY, IRONWOOD2G);
				echo 'Military ('.MILITARY.'); IRONWOOD-2G ('.IRONWOOD2G.')<br>';
			} elseif ($option_name == 'ASYM') {
				echo 'IRONWOOD2G ('.IRONWOOD2G.')';
				$collections[] = IRONWOOD2G;			
			} elseif ($option_name == 'MOON') {
				echo 'IRONWOOD2G ('.IRONWOOD2G.')';
				$collections[] = IRONWOOD2G;			
			} elseif ($option_name == 'PLNG') {
				echo 'IRONWOOD2G ('.IRONWOOD2G.')';
				$collections[] = IRONWOOD2G;			
			} elseif ($option_name == 'HOOD') {
				echo 'IRONWOOD2G ('.IRONWOOD2G.')';
				$collections[] = IRONWOOD2G;			
			}
		}
		
		
		
		echo '<br>';	  	
		echo $attach_id = wp_insert_attachment( $attachment, $filepath);		
		echo '<br><br>';
	  	
		add_post_meta($attach_id, $option_category, $option_name);
		wp_set_post_terms($attach_id, $thing->term_id, $option_category);
		wp_set_post_terms($attach_id, $collections, 'kln_collection');
		 
		
	}
}

function add_product_image_metadata($post_id, $dirpath) {
	$dir = scandir(WP_CONTENT_DIR.'/uploads/KLNWEBSITE/KLNCHEST/CHEST/3DRAWERCHEST/2G');
	$dir = array_slice($dir, 2);
	foreach ($dir as $img) {
		$filepath = WP_CONTENT_URL.'/uploads/KLNWEBSITE/KLNCHEST/CHEST/3DRAWERCHEST/2G/'.$img;
		
		$wp_filetype = wp_check_filetype($img, null );
		$attachment = array(
			'guid' => $filepath, 
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $name,
			'post_content' => '',
			'post_status' => 'inherit'
		);
	  
		
		echo $attach_id = wp_insert_attachment( $attachment, $filepath, $post_id);
		preg_match('#(\d)_(.*?)_.*?_(.*?)_(.*?)_(.*?)_(.*?)$#', $name, $metadata);
		add_post_meta($attach_id, "laminate", $metadata[6]);
		add_post_meta($attach_id, "paint", $metadata[5]);
		add_post_meta($attach_id, "drawer-pull", $metadata[3]);
		add_post_meta($attach_id, "collection", $metadata[2]);
		add_post_meta($attach_id, "image_no", $metadata[1]);
		
		//require_once( ABSPATH . 'wp-admin/includes/image.php' );
		//$attach_data = wp_generate_attachment_metadata( $attach_id, $filepath);
		//wp_update_attachment_metadata( $attach_id, $attach_data );

	}
}

function get_product_checkboxes($ids = 0) {
	$return = '<fieldset id="taxonomy-exceptions">
		<table>
		<thead><th>Exclude Products</th></thead><tbody><tr>';
	$i = 0;
	
	if ($ids == 0) {
		$products = get_posts(array("post_type"=>"kln_products", "posts_per_page"=>-1));
		foreach($products as $product) {
			$return .= (++$i % 4 == 0) ? '</tr><tr>' : '';
			$return .= '<td><input type="checkbox" name="product" value="'.$product->ID.'">'.$product->post_title.'</td>';
		}
	} else {
		foreach($ids as $id=>$title) {
			$return .= (++$i % 4 == 0) ? '</tr><tr>' : '';
			$return .= '<td><input type="checkbox" name="product" value="'.$id.'">'.$title.'</td>';
		}
	}
    
	$return .= '</tr></tbody></table></fieldset>';
	return $return;
}

function get_selected_products_callback() {

	if ($_POST['term_mod'] && $_POST['subcategory_mod'])  {
		$terms = get_selected_collection_and_category_terms($_POST['term_mod'], $_POST['subcategory_mod']);
	}
	elseif ($_POST['term_mod'] && !$_POST['subcategory_mod']) {
		$terms = get_selected_collection_terms($_POST['term_mod']);
	}
	elseif (!$_POST['term_mod'] && $_POST['subcategory_mod']) {
		$terms = get_selected_category_terms($_POST['subcategory_mod']);
		$ids = array();
	}

	$ids = array();

	foreach ($terms as $term) {
		$ids[$term->ID] = $term->post_title;		
	}	
	echo get_product_checkboxes($ids);	
}



function get_selected_collection_terms($term_mod) {
	global $wpdb;
	$wpdb->query($wpdb->prepare('SELECT * FROM wp_posts wp
		INNER JOIN wp_term_relationships wtr ON wp.ID = wtr.object_id
		INNER JOIN wp_term_taxonomy wtt ON wtt.term_taxonomy_id = wtr.term_taxonomy_id
		INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
		WHERE (
			wtt.term_id IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d)
			OR wtt.parent IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d))', $term_mod, $term_mod));
	return $wpdb->last_result;
}

function get_selected_collection_and_category_terms($term_mod, $subcategory_mod) {
	global $wpdb;
	$wpdb->query($wpdb->prepare('SELECT * FROM wp_posts wp
		INNER JOIN wp_term_relationships wtr ON wp.ID = wtr.object_id
		INNER JOIN wp_term_taxonomy wtt ON wtt.term_taxonomy_id = wtr.term_taxonomy_id
		INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
		WHERE (
			wtt.term_id IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
			OR wtt.parent IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
		)
		AND wp.ID IN (
			SELECT wp.ID FROM wp_posts wp
			INNER JOIN wp_term_relationships wtr ON wp.id = wtr.object_id
			INNER JOIN wp_term_taxonomy wtt ON wtt.term_taxonomy_id = wtr.term_taxonomy_id
			INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
			WHERE (
			wtt.term_id IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
			OR wtt.parent IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
		))', $term_mod, $term_mod, $subcategory_mod, $subcategory_mod));

	return $wpdb->last_result;
}

function get_selected_category_terms($subcategory_mod) {
	global $wpdb;
	$wpdb->query($wpdb->prepare('SELECT * FROM wp_posts wp
			INNER JOIN wp_term_relationships wtr ON wp.id = wtr.object_id
			INNER JOIN wp_term_taxonomy wtt ON wtt.term_taxonomy_id = wtr.term_taxonomy_id
			INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
			WHERE (
			wtt.term_id IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
			OR wtt.parent IN (
				SELECT wtt.term_id FROM wp_term_taxonomy wtt
				INNER JOIN wp_terms wt ON wtt.term_id = wt.term_id
				WHERE wtt.parent = %d
			)
		)', $subcategory_mod, $subcategory_mod));
	return $wpdb->last_result;
}

function update_spec_taxonomy_metadata () {
	extract($_POST);
	global $wpdb;

	$exclusions = explode('|', $exclusions);
	$exclusions = array_slice(array_reverse($exclusions), 1);
	print_r($exclusions);
	
	if ($term_mod && !$subcategory_mod) { 
		$result = get_selected_category_terms($term_mod); 
	} elseif ($term_mod && $subcategory_mod) {
		$result = get_selected_collection_and_category_terms($term_mod, $subcategory_mod);
	} elseif ($subcategory_mod && !$term_mod) {
		$result = get_selected_category_terms($subcategory_mod);
	} 
		
	//print_r($result);
		
	if ($metadata_key && $metadata_value) {
		foreach ($result as $product_id) {
			$post_terms = update_post_meta(intval($product_id->ID), $metadata_key, $metadata_value);
			//print_r($post_terms);
		}
	} else {
		foreach ($result as $product_id) {
		//echo 'Product ID: ';
		//echo $product_id->ID;
		//echo '<br>';
			if (in_array($product_id->ID, $exclusions)) {
				echo "$product_id->ID excluded<br>";
				continue;
			}

			if ($term_change == 1) {
				$all_terms = array();
				$terms = get_terms($taxonomy, 'orderby=count&hide_empty=0&hierarchical=0&parent=0');
				foreach($terms as $term) {
					$all_terms[] = intval($term->term_id);
				}
				$post_terms = wp_set_post_terms(intval($product_id->ID), $all_terms, $taxonomy, true);
				print_r($post_terms);
			} elseif ($operation == 0) {
				echo 'do nothing';
			} else {
				$post_terms = wp_set_post_terms(intval($product_id->ID), array(intval($term_change)), $taxonomy, true);
				print_r($post_terms);
		
			}		
		}
	}
	


	exit;
}

function add_spec_taxonomy_metadata($key, $value) {
	$products = get_posts(array('post_type'=>'kln_products', 'posts_per_page'=>-1));
	foreach($products as $product) {
		$terms = wp_get_post_terms($product->ID, 'kln_collection');
		echo '<strong>Name: '.$product->post_name.'</strong>';
		
		echo '<br><strong>Category Term: </strong>';
		$subcategory['child'] = $terms[0]->slug;
		$subcategory['child_name'] = ucwords($terms[0]->name);
		$subcategory['child_id'] = $terms[0]->term_id;
		
		if($terms[0]->parent) {
			$parent = get_term(intval($terms[0]->parent), 'kln_collection');
			if($parent->parent) {
				echo '<strong>SUbcategory Slug: </strong>';
				$grandparent = get_term(intval($parent->parent), 'kln_collection');
				$subcategory['collection_id'] = $grandparent->term_id;
				$subcategory['collection_name'] = $grandparent->name;
				if ($position = strpos($parent->slug, '-')) {
					echo $subcategory['slug'] = substr($parent->slug, 0, $position);
				} else {
					echo $subcategory['slug'] = $parent->slug;
				}
				echo '<br>Name: ';
				echo $subcategory['name'] = preg_replace('/_/', ' ', $subcategory['slug']);			
				if (!$subcategory['slug']) {
					//print_r($grandparent);
				} 
				
				if ($grandparent->parent) {
					$greatgrandparent = get_term(intval($greatgrandparent->parent), 'kln_collection');
				}
				
			} else {
				//print_r($terms);
				echo '<strong>Term Parent Slug: </strong>';
				$subcategory['collection_id'] = $terms[0]->parent;
				$subcategory['collection_name'] = $terms[0]->name;
				if ($position = strpos($terms[0]->slug, '-')) {
					echo $subcategory['slug'] = substr($terms[0]->slug, 0, $position); 
				} else {
					echo $subcategory['slug'] = $terms[0]->slug;
				}
				$subcategory['name'] = preg_replace('/_/', ' ', $subcategory['slug']);			
			}	
			echo '<br>Name: ';
			echo $subcategory['name'] = ucwords($subcategory['name']);

			$subcategory['id'] = get_term_by('name', $subcategory['name'], 'subcategory');
			$subcategory['id-child'] = get_term_by('name', $subcategory['child_name'], 'subcategory');
			
			echo '<br>id: ';
			echo $subcategory['id'] = $subcategory['id']->term_id;
			echo $subcategory['id-child'] = $subcategory['id-child']->term_id;
			
			echo '<br>Collection: ';
			echo $subcategory['collection_name'] = ucwords($subcategory['collection_name']);
			
			if (!$subcategory['id']) {
				$submission = $subcategory_id = wp_insert_term(
					$subcategory['name'], 
					'subcategory', 
					array(
						'parent'=>0, 
						'slug'=>$subcategory['slug']
					)
				);
				$subcategory['id'] = $submission['term_id'];
			}
			
			if(!$subcategory['id-child']) {
				$child = wp_insert_term(
					$subcategory['child_name'], 
					'subcategory', 
					array(
						'parent'=>$subcategory['id'], 
						'slug'=>$subcategory['child'].'-'.$subcategory['collection_name']
					)
				);
				$subcategory['id-child'] = $child['term_id'];
			}
			
			$collection = wp_insert_term(
				$subcategory['collection_name'], 
				'subcategory', 
				array(
					'parent'=>$subcategory['id-child'], 
					'slug'=>$subcategory['collection_name'].'-'.$subcategory['slug']
				)
			);		
			$post_terms = wp_set_post_terms($product->ID, array($collection['term_id']), 'subcategory', true);
			print_r($post_terms);
		}
		echo '<br><br>';
	}
}

function wshe_ajax_callback() {
	extract($_POST);	
	if ($user_file) {
		call_user_func($callback, $user_file);
	} elseif ($find_product) {
		call_user_func($callback, $find_product, $replace_product);
	} elseif ($find_key) {
		call_user_func($callback, $find_key, $find_value, $replace_value);
	} elseif ($operation) {
		call_user_func($callback, $term_mod, $subcategory_mod, $product, $taxonomy, $term, $operation);
	}
}

function add_products_from_directory($dir) {

	$product = array(
		"category"=>"",
		"subcategory"=>"",
		"collection"=>"",
		"image"=>"",
		"product"=>""
	);
	
	$directories = scandir($dir);
	foreach ($directories as $key=>$value) {
		if ($value == "." || $value == ".." )
			continue;
		//echo $value.'<br>';
		$product['category'] = $value;
		if(!stristr($value, '.')) {
			$subdir = $dir.'/'.$value;
			$subdirectories = scandir($subdir);
			
			foreach ($subdirectories as $subkey=>$subvalue) {
				if ($subvalue == "." || $subvalue == ".." )
					continue;
				//echo $subvalue."<br>";	
				$product['subcategory'] = $subvalue;

				if(!stristr($subvalue, '.')) {
					$subsubdir = $subdir.'/'.$subvalue;
					$subsubdirectories = scandir($subsubdir);
					
					foreach ($subsubdirectories as $subsubkey=>$subsubvalue) {
						if ($subsubvalue == "." || $subsubvalue == ".." )
							continue;
						//echo $subsubvalue."<br>";
						$product['collection'] = $subsubvalue;
						if(!stristr($subsubvalue, '.')) {
							$subsubsubdir = $subsubdir.'/'.$subsubvalue;
							$subsubsubdirectories = scandir($subsubdir.'/'.$subsubvalue);
							
							foreach ($subsubsubdirectories as $subsubsubkey=>$subsubsubvalue) {
								if ($subsubsubvalue == "." || $subsubsubvalue == ".." || $subsubsubvalue == "Thumbs.db")
									continue;
								//echo $subsubsubvalue."<br>";
								$product['image'] = $subsubsubvalue;
								$product['directory'] = $subsubsubdir;
								
								$kln_product = new kln_product($product);
								
								if(!stristr($subsubsubvalue, '.')) {
									$subsubsubsubdir = $subsubsubdir.'/'.$subsubsubvalue;
									$subsubsubsubdirectories = scandir($subsubsubdir.'/'.$subsubsubvalue);
									foreach ($subsubsubsubdirectories as $subsubsubsubkey=>$subsubsubsubvalue) {
										if ($subsubsubsubvalue == "." || $subsubsubsubvalue == ".." )
											continue;
										//echo $subsubsubsubvalue."<br>";	
									}
								}
							}
						}
					}
				}
			}
		}
	}

	die();
}


function add_products_from_spreadsheet($text) {

	$file = fopen('/'.$text, 'r');	
	$line = fgetcsv($file, 0, "\t");
	for ($i = 0; $i <= count($line) - 1; $i++) {
		$pair = split('\|', $line[$i]);
		if (!$pair[1]) {
			$pair[1] = 'skip_this_function';
		}
		$product_functions[$pair[0]] = $pair[1]; 
		
		//echo $pair[0]." => ".$product_functions[$pair[0]]."<br>";
	}
	
	do {
		$new_content = fgetcsv($file, 0, "\t");
		//print_r($new_content);
		$new_product = new kln_product('process_spreadsheet_data', $new_content, $product_functions);		
	} while(!feof($file));
	
	fclose($file);
	die();
}


function update_products_from_spreadsheet($text) {
	
	$products = get_posts(array('posts_per_page' => -1, 'post_type' => 'kln_products'));

	$file = fopen('/'.$text, 'r');
	$id = 0;
	
	$line = fgetcsv($file, 0, "\t");
	for ($i = 0; $i <= count($line) - 1; $i++) {
		$pair = split('\|', $line[$i]);
		if (!$pair[1]) {
			$pair[1] = 'skip_this_function';
		}
		$functions[$pair[0]] = $pair[1]; 
		
		echo $pair[0]." => ".$functions[$pair[0]]."<br>";
	}
	
	do {
		$content = fgetcsv($file, 0, "\t");
		if ($content[0] != null) {
			for ($i = 0; $i <= count($products); $i++) {
				if (preg_match('#'.$content[0].'#', $products[$i]->post_name)) {
					$ii = 0; 
					$id = $products[$i]->ID;
					echo '<br><strong>'.$content[0].', id: '.$id.'</strong><br>';
					//set_wordpress_taxonomy($id);
					foreach ($functions as $meta=>$function) {
						call_user_func($function, $id, $meta, $content[$ii++]);
					}
				} 
			} 
		} else {
			echo $content[0].' is a new product!';
		}
		
		if ($id == 0) {
			echo 'No match for '.$content[0];
		}
		echo '<br>';
		$id = 0;

	} while(!feof($file));
	fclose($file);
}



function kbc_settings_menu() {
    // Create top-level menu item on wp-admin sidebar
	
	$submenu='Whole Shebang';
	$capabilities='manage_options';
	$function_callback='kbc_complex_menu';
	
	add_menu_page('Whole Shebang Activation', $submenu, $capabilities, 'kbc_main_menu', $function_callback);

}


class kln_product {

	private $pdf_file_dir = '/SPECSHEETS/';
	private $uploads_dir;
	private $image_folder = '/PRODUCTS/';

	public $image_basename; //basename of file
	public $image_filepath; //the full image url
	public $image_post_title; //same as basename without extension
	public $product_path;
	
	public $thumbnail_ref; //used to define particular color-laminate-top thumbnails
	
	public $product_id; //ID of product, either fetched with wp_insert_post or db query
	
	public $kln_collection;
	
	public $product_cat;
	public $product_subcat;
	public $kln_product;
	
	public $unique_products; //array of unique objects
	public $kln_product_name;
	public $kln_product_title;
	
	public $product_parent;
	public $parental_id;
	
	public $lam_op;
	public $paint_op;
	public $top_op;
	
	public $term_slug;
	public $term_name;
	public $term_parent_id;
	
	public $product_meta;
	public $product_spec;
	public $product_option;
	public $post_content;
	public $display_name;
	
	public $term_id;
	
	public $taxonomy = array();
	
	
	function set_field($field, $content) {
		$this->{$field} = $content;
		//echo '<strong>'.$field.'</strong>: '.$this->{$field}.'<br>';
	}
	
	function set_meta($field, $content) {
		$this->product_meta[$field] = $content;
		//echo '<strong>'.$field.'</strong>: '.$this->product_meta[$field].'<br>';
	}

	function set_meta_tag_for_gsa_pdf($field, $content) {
		$content = $this->pdf_file_dir.rawurlencode($content).'.pdf';
		$this->product_meta[$field] = $content;
		//echo '<strong>'.$field.'</strong>: '.$this->product_meta[$field].'<br>';
	}

	function set_meta_tag_for_education_pdf($field, $content) {
		$content = $this->pdf_file_dir.rawurlencode($content).'.pdf';
		$this->product_meta[$field] = $content;
		//echo '<strong>'.$field.'</strong>: '.$this->product_meta[$field].'<br>';
	}


	function set_array_field($field, $content, $delimiter = ';') {
		$options = explode($delimiter, $content);
		foreach ($options as $option) {
			$option = trim($option);
			$this->{$field}[] = $option;
		}
		foreach ($this->{$field} as $f) {
			//echo '<strong>'.$field.'</strong>: '.$f.'<br>';
		}					
	}
	
	function set_image($image_no, $filename) {
		if (!$filename) {
			return 0;
		} else {
			$this->image_basename[] = $filename;
			$filepath = $this->uploads_dir.$this->image_folder.$filename;
			$this->image_filepath[] = $filepath; 
			$post_title = preg_replace( '/\.[^.]+$/', '', $filename);
			$this->image_post_title[] = $post_title;
			preg_match('#((\d)_(.*?)_(.*?)_(.*?))\.*$#', $post_title, $ops);
			if ($ops[3] == "SEATING") {
			
			} else {
				$this->parse_product_options($ops[5]);
				$thumb = $thumbnail_no.'-'.$this->lam_op.'_'.$this->paint_op.'_'.$this->top_op;
				$this->thumbnail_ref[] = preg_replace(array("#_$#", "#__#", "#-_#"), array("", "_", "-"), $thumb);
			}
		}
	}
	
	function set_taxonomy($field, $content) {
		$this->{$field} = $content;
		//echo '<strong>'.$field.'</strong>: '.$this->{$field}.'<br>';
		$content = preg_replace(array('#\s#', '#\'#'), array(' ', ''), strtolower($content));

		$this->term_slug = preg_replace(array('#\s#', '#\'#'), array('-', ''), $content);
		$this->term_name = $content;
		//echo '<strong>Term Slug</strong>: '.$this->term_slug.'<br>';
		//echo '<strong>Content: '.$content.'</strong><br>';
		
		global $wpdb;
		$wpdb->query($wpdb->prepare('SELECT * FROM wp_terms WHERE name LIKE %s', $content));
		$all_terms = $wpdb->last_result;

		foreach($all_terms as $term) {
			$wpdb->query($wpdb->prepare('SELECT parent FROM wp_term_taxonomy WHERE term_id = %s', $term->term_id));
			$parent = $wpdb->last_result;
			$parent = $parent[0]->parent;
			//echo "Parent: ".$parent.'<br>';
			//print_r($parent);
			if ($parent != 0 && array_search($parent, $this->taxonomy) !== false) {
				$this->taxonomy[] = $term->term_id;
				//echo "Taxonomy 1: ".$this->taxonomy[0].'<br>';
				//echo "Taxonomy 2: ".$this->taxonomy[1].'<br>';
				//echo "Taxonomy 3: ".$this->taxonomy[2].'<br>';
				return 0;
					//wp_set_object_terms($id, $term-ID, 'kln_collection', );
				
			} elseif ($parent == 0 && !$this->taxonomy) {
				
				//echo $content.' in '.$term->name.' no parent<br>';
				$this->taxonomy[] = $term->term_id;
				//echo 'Top Item in Taxonomy: '.$term->name.', (id) '.$term->term_id.'<br>';
				return 0;
			} elseif($parent != 0) {
				//add new term here with parent as last object in taxonomy array
				//echo 'Has a Parent, but not this one.<br>';
				continue;
			}
		}
		if ($content) {
			$grandparent = end($this->taxonomy);
			//echo 'Adding a New Term '.$content.', (id) new id, (parent id)'.$grandparent.', (name) '.$content.'<br>';
			$this->taxonomy[] = wp_insert_term($content, 'kln_collection', array('parent'=>$grandparent, 'slug'=>$this->term_slug	));
		}
	}
	
	function get_product_terms() {
		$all_terms = get_terms('kln_collection');
		echo 'NAME, SLUG, ID, PARENT';
		foreach($all_terms as $term) {
			echo '<br>';
			echo $term->name.', ';
			echo $term->slug.', ';
			echo $term->term_id.', ';
			echo $term->parent;
			echo '<br>';
		}
	}
	

	function process_spreadsheet_data($content, $functions) {
		foreach ($functions as $meta=>$function) {
			$this->{$function}($meta, $content[$ii++]);
		}
	}
	
	function __construct() {
		
		$ll = wp_upload_dir();
		$this->uploads_dir = $ll['baseurl'];
		$num_args = func_num_args();

		if ($num_args == 1) {
			$the_product = func_get_arg(0);
			
			$cat = $the_product['category'];
			$subcat = $the_product['subcategory'];
			$collection = $the_product['collection'];
			$image = $the_product['image'];
			$dir = $the_product['directory'];
			
			$this->product_cat = $cat;
	
			$filepath = preg_replace('#.*?(/wp-content/)#', '\1', $dir);
	
			$this->image_filepath = $filepath.'/'.$image;
			$this->kln_collection = $this->parse_product_collection($collection, $this->image_filepath);
			
			$this->image_basename = $image;
			
			//0 is the pagename, 2 is the thumbnail no, 3 is collection, 4 is the name, 5 options
			preg_match('#((\d)_(.*?)_(.*?)_(.*?)).(JPG|jpg)#', $image, $ops);
			//echo $ops[0];
			//echo ' ';
			
			$this->image_post_title = $ops[1];
			$thumbnail_no = $ops[2];
			
			$name = $ops[4];
			$this->kln_product = $this->parse_product_name($name);
			$this->parse_product_options($ops[5]);
			/*
			$option = explode('_', $options);
			
			if (count($option) == 1){
				$this->paint_op = $option;
			}
			
			if (count($option) == 2){
				$this->lam_op = $option[0];
				$this->paint_op = $option[1];
				$this->top_op = null;		 			
			}
			*/
			
			$this->thumbnail_ref = $thumbnail_no.'-'.$this->lam_op.'_'.$this->paint_op.'_'.$this->top_op;
			$this->thumbnail_ref = preg_replace(array("#_$#", "#__#", "#-_#"), array("", "_", "-"), $this->thumbnail_ref);
			$this->image_post_title = preg_replace( '/\.[^.]+$/', '', $this->image_basename);

			$this->kln_product_title = strtoupper(preg_replace('#-#', ' ', $this->kln_collection)).' '.$this->kln_product;
			$this->kln_product_name = strtolower(preg_replace('#\s#', '-', $this->kln_collection).'-'.preg_replace('#\s#', '-', $this->kln_product));
			$this->term_slug = strtolower($this->kln_product_name).'s'; 
			$this->term_name = strtolower($this->product_cat);
			
			$parent_term = get_term_by('slug', $this->term_name, 'kln_collection');
			
			$this->term_parent_id = $parent_term->term_id; 
			
			if (!$this->term_parent_id) {
				wp_insert_term(strtolower($this->product_cat), 'kln_collection');
			}

		} elseif ($num_args == 3) {
		
			//ob_start();
			
			$callback = func_get_arg(0);
			$content = func_get_arg(1);
			$class_functions = func_get_arg(2);
			
			$this->{$callback}($content, $class_functions);
			
			//ob_end_clean();
			$this->kln_product_name = strtolower(preg_replace('#\s#', '-', $this->kln_collection.'-'.$this->kln_product));
			$this->term_parent_id = end($this->taxonomy);			
			$this->kln_product_title = strtoupper($this->kln_collection.' '.$this->kln_product);
			//$this->review_product();
			
			$this->post_product();
		
			//$ops = func_get_arg(0);
			//$cats = func_get_arg(1);
			//$relative_path = func_get_arg(2);
			//$subcats = func_get_arg(3);
			
			//$this->image_basename = preg_replace('#.*/#', '', $ops[0]);
			
			//$this->thumbnail_ref = $ops[1].'-'.$ops[3];
			//$this->kln_collection = $this->parse_product_collection($cats[2], $relative_path);
			//$this->kln_product = $this->parse_product_name($ops[2]);
			//$this->product_cat = strtolower($cats[1]);
		
			//$this->parse_product_options($ops[3]);
			//$this->product_path = $relative_path;

		}
		

			
		//$this->review_product();
		//$this->get_taxonomy_data();
		//$this->post_product();
	}	
	
	function review_product() {
		//set the name (slug) of the product and the title (h1) of the product
		echo '<br><strong>PRODUCT:</strong> ';
		echo $this->kln_product;
		echo '<br><strong>TITLE:</strong> ';
		echo $this->kln_product_title;
		echo '<br><strong>NAME:</strong> ';
		echo $this->kln_product_name;
		echo '<br><strong>COLLECTION:</strong> ';
		echo $this->kln_collection;
		echo '<br><strong>CATEGORY:</strong> ';
		echo $this->product_cat;

		if (is_array($this->lam_op)) {
			foreach ($this->lam_op as $lam) {
				echo '<br><strong>LAMINATE:</strong> ';
				echo $lam;
			}
		} else {
			echo '<br><strong>LAMINATE:</strong> ';
			echo $this->lam_op;
		}
		
		if (is_array($this->paint_op)) {
			foreach ($this->paint_op as $paint) {
				echo '<br><strong>PAINT:</strong> ';
				echo $paint;
			}
		} else {
			echo '<br><strong>PAINT:</strong> ';
			echo $this->paint_op;
		}

		if (is_array($this->top_op)) {
			foreach ($this->top_op as $top) {
				echo '<br><strong>TOP:</strong> ';
				echo $top;
			}
		} else {
			echo '<br><strong>TOP:</strong> ';
			echo $this->top_op;
		}
		
		if (is_array($this->image_thumbnail_ref)) {
			foreach($this->image_thumbnail_ref as $ithumbnail) {
				echo '<br><strong>Image Thumbnail:</strong> ';
				echo $ithumbnail;
			}
		} else {
				echo '<br><strong>Image Thumbnail:</strong> ';
				echo $this->image_thumbnail_ref;		
		}
		
		if (is_array($this->image_filepath)) {
			foreach($this->image_filepath as $ifilepath) {
				echo '<br><strong>Image Filepath:</strong> ';
				echo $ifilepath;
			}
		} else {
				echo '<br><strong>Image Filepath:</strong> ';
				echo $this->image_filepath;		
		}
			
		if (is_array($this->image_post_title)) {
			foreach($this->image_post_title as $iptitle) {
				echo '<br><strong>Image Post Title:</strong> ';
				echo $iptitle;
			}
		} else {
				echo '<br><strong>Image Post Title:</strong> ';
				echo $this->image_post_title;				
		}

	
		echo '<br><strong>CATEGORY-PARENT-ID:</strong> ';
		echo $this->term_parent_id;
		echo '<br><strong>CATEGORY_TERM_SLUG:</strong> ';
		echo $this->term_slug;

		echo '<br><strong>CATEGORY_TERM_NAME:</strong> ';
		echo $this->term_name;

		echo '<br><strong>POST_CONTENT:</strong> ';
		echo $this->post_content;
		
		foreach($this->taxonomy as $tax) {
			echo '<br><strong>TAXONOMY:</strong> ';
			echo $tax;
		}
		foreach($this->product_meta as $key=>$value) {
			echo '<br><strong>'.$key.'</strong> ';
			echo $value;
		}

		echo '<br><br>';
	}

	function post_product() {
	
		//Query the DB to see if there is 
		$query_args = array(
			'post_type'			=>	'kln_products',
			'pagename'			=>	$this->kln_product_name,
			'kln_collections'	=>	$this->kln_collection,
		);
		
		global $wpdb;	
	
		//define mysql query
		$is_it_unique = "SELECT ID FROM wp_posts WHERE post_title = %s";
		$wpdb->query($wpdb->prepare($is_it_unique, $this->kln_product_title), OBJECT_K);
		$this->product_id = $wpdb->last_result[0]->ID;
	
		if ($wpdb->num_rows) { 
			echo '<br><strong>This Item Has Already Been Posted</strong><br>';
			$this->product_meta();
			$this->product_attachment_meta();
			return 0;
		}
	
		
		$post_product = array(
			//  'ID'             => [ <post id> ] //Are you updating an existing post?
			//  'menu_order'     => [ <order> ] //If new post is a page, it sets the order in which it should appear in the tabs.
			'comment_status' => 	'closed',
			//  'ping_status'    => [ 'closed' | 'open' ] // 'closed' means pingbacks or trackbacks turned off
			//  'pinged'         => [ ? ] //?
			//  'post_author'    => [ <user ID> ] //The user ID number of the author.
			'post_content'   => 	$this->post_content,
			//  'post_excerpt'   => [ <an excerpt> ] //For all your post excerpt needs.
			'post_name'      => 	$this->kln_product_name, //post slug
			'post_status'    => 	'publish',  //'draft' | 'pending'| 'future' | 'private' | 'custom_registered_status' ] //Set the status of the new post.
			'post_title'     => 	$this->kln_product_title, //The title of your post.
			'post_type'      => 	'kln_products'
		);
	
		$this->product_id = wp_insert_post($post_product);
		echo '<strong>Added A New Item! Time to Post Your Meta Data</strong><br>';
	
		foreach ($this->taxonomy as $tax) {
			$term_id = wp_set_object_terms($this->product_id, intval($tax), 'kln_collection', false);
		}
		
		$this->product_meta();
		$this->product_attachment_meta();	
	}
		
	function product_meta() {
		echo 'Unique ID is: '.$this->product_id.'<br>';
		
		echo "LAM<br>";
		if ($this->lam_op) {
			foreach($this->lam_op as $value) {
				if ($value) {		
					echo $value.'<br>';
					add_post_meta($this->product_id, $value, $value, true);
				}
			}
		}
		echo "TOP<br>";
		if ($this->top_op) {
			foreach($this->top_op as $value) {
				if ($value) {		
					echo $value.'<br>';
					add_post_meta($this->product_id, $value, $value, true);
				}
			}
		}
		echo "PAINT<br>";
		if ($this->paint_op) {
			foreach($this->paint_op as $value) {
				if ($value) {		
					echo $value.'<br>';
					add_post_meta($this->product_id, $value, $value, true);
				}
			}
		}
		echo "REF<br>";		
		/*
		if ($this->thumbnail_ref) {
			foreach($this->thumbnail_ref as $key=>$value) {
				if ($value) {		
					echo $key.': '.$value.'<br>';
					//add_post_meta($this->product_id, $value, $value, false);
				}
			}
		}
		*/
		echo "META<br>";		
		foreach($this->product_meta as $key=>$value) {
			if ($value) {		
				echo $key.': '.$value.'<br>';
				add_post_meta($this->product_id, $key, $value, true);
			}
		}
		
		if ($this->product_spec) {
			foreach($this->product_spec as $value) {
				if ($value) {		
					echo 'Product Spec: '.$value.'<br>';
					add_post_meta($this->product_id, 'product_spec', $value, false);
				}
			}
		}
		if ($this->product_option) {
			foreach($this->product_option as $value) {
				if ($value) {		
					echo 'Product Option: '.$value.'<br>';
					add_post_meta($this->product_id, 'product_option', $value, false);
				}			
			}
		}
	}	
	
	function product_attachment_meta () {
	
		//Check to See
		global $wpdb;
		$searchstring = "SELECT ID FROM wp_posts WHERE post_title = %s";
		
		for ($i=0; $i < count($this->image_post_title); $i++) {
			
			$wpdb->query($wpdb->prepare($searchstring, $this->image_post_title[$i]), OBJECT_K);
			echo "image title: ".$this->image_post_title[$i].'<br>';
			echo "image basename: ".$this->image_basename[$i].'<br>';
			echo "iamge filepath: ".$this->image_filepath[$i].'<br>';
			
			if ($wpdb->num_rows) {
				echo '<br><strong>IMAGE ALREADY LISTED<br></strong>';
				continue;
			}
		
			$wp_filetype = wp_check_filetype($this->image_basename[$i], null );
			
			$attachment = array(
				'guid' => $this->image_filepath[$i], 
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => $this->image_post_title[$i],
				'post_content' => '',
				'post_status' => 'inherit'
			);
		  
			
			echo $attach_id = wp_insert_attachment( $attachment, $this->image_filepath[$i], $this->product_id);
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $this->image_filepath[$i]);
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}
	}		

	function parse_product_collection($kln_collection, $product_path) {
		if (stristr($product_path, '2G')) {
			$this->parental_id = 7;
			return 'Ironwood 2G';
		}
		
		elseif (stristr($product_path, 'steel')) {
			$this->parental_id = 9;
			return 'Steel';
		}
		
		elseif (stristr($product_path, 'base')) {
			$this->parental_id = 5;
			return 'Eco-Flex';
		}
		
		elseif (stristr($product_path, 'zero')) {
			$this->parental_id = 11;
			return 'Net-Zero';
		}
	}
	
	function parse_product_options($ops) {
		if (preg_match('#(.*?)_(.*?)_(.*)#', $ops, $matches))  {
			$this->paint_op[] = $this->kln_paint($matches[1]);
			$this->lam_op[] = $this->kln_paint($matches[2]);
			$this->top_op[] = $this->kln_paint($matches[3]);
			return;
		}
	
		elseif (preg_match('#(.*?)_(.*)#', $ops, $matches)) {
			$this->lam_op[] = $this->kln_paint($matches[1]);
			$this->paint_op[] = $this->kln_paint($matches[2]);
		}
	
		else { 
			echo "PAINT: ";
			echo $this->paint_op[] = $this->kln_paint($ops);
			echo "<br>";
		}
	}
	
	function kln_paint($ops) {
		
		//Get Paint
		if (stristr($ops, 'CHER'))
			 return 'WILD-CHERRY';			
		if (stristr($ops, 'OAK'))
			 return 'OAK';
		if (stristr($ops, 'STEEL'))
			 return 'STAINED-STEEL';			
		if (stristr($ops, 'ECL'))
			 return 'TOTAL-ECLIPSE';			
		if (stristr($ops, 'DUNES'))
			 return 'DUNES';			
		if (stristr($ops, 'MOSS'))
			 return 'MOSS';			
		if (stristr($ops, 'SUBSIL'))
			 return 'SUBURBAN-SILVER';			
		if (stristr($ops, 'SCLL'))
			 return 'SCALLOP';			
		if (stristr($ops, 'HSHPUP'))
			 return 'HUSH-PUPPY';			
		if (stristr($ops, 'GRYDAY'))
			 return 'GRAY-DAY';			
		if (stristr($ops, 'FOGHRN'))
			 return 'FOGHORN';			
		if (stristr($ops, 'BLUWNG'))
			 return 'BLUE-WING';			
		if (stristr($ops, 'MYSNHT'))
			 return 'MYSTIC-NIGHT';	
		else
			return $ops;
		
		
	}
	
	function parse_product_name($product_name) {
		
		
		echo "Product Name: <br>".$product_name;
		if (preg_match('#(DESK)/(.*?)/(.*?)/#', $product_name, $matches)) {
			$this->product_cat = $matches[1];
			return $matches[3];
		}
		elseif (preg_match('#4DRAWER#', $product_name))
			return 'FOUR DRAWER CHEST';
		elseif (preg_match('#5DRAWER#', $product_name))
			return 'FIVE DRAWER CHEST';
		elseif (preg_match('#CARREL#', $product_name))
			return 'CARREL';
		elseif (preg_match('#WARDROBE_.*?_2D#', $product_name))
			return 'TWO DRAWER WARDROBE';	
		elseif (preg_match('#WARDROBE#', $product_name))
			return 'WARDROBE';
		elseif (preg_match('#2D.*?W#', $product_name))
			return 'TWO DRAWER STACKABLE CHEST';
		elseif (preg_match('#3DWR_ASYM#', $product_name))
			return 'THREE DRAWER CHEST: ASYMMETRICAL';
		elseif (preg_match('#3DWR_ACNT#', $product_name))
			return 'THREE DRAWER CHEST: ACNT';
		elseif (preg_match('#3DWR_CNTP#', $product_name))
			return 'THREE DRAWER CHEST: CNTP';
		elseif (preg_match('#3DWR_MOON#', $product_name))
			return 'THREE DRAWER CHEST: MOON';
		elseif (preg_match('#3DWR_PLNG#', $product_name))
			return 'THREE DRAWER CHEST: PLNG';
		elseif (preg_match('#3DWR_STND#', $product_name))
			return 'THREE DRAWER CHEST: STAINED STEEL';
		elseif (preg_match('#3DRAWER#', $product_name))
			return 'SMALL THREE DRAWER CHEST';
		elseif (preg_match('#TV#', $product_name))
			return 'TV STAND';
		elseif (preg_match('#NIGHTSTAND#', $product_name))
			return 'NIGHTSTAND';
		elseif (preg_match('#DECK#', $product_name)) {
			return str_replace('-', ' ', $product_name);
		}
		else
			return $product_name;
	}
}



//load directory text file
function load_whole_shebang() {
	//load from file
	
	
	$whole_shebang = file(plugins_url().'/foldercontents.txt');

	//puts all original images paths into an array;
	$relative_path = $whole_shebang;

	//clean up #1: erases names of first two irrelevant directories
	$whole_shebang = preg_replace('#KLN.*?/KLN.*?(/|$)#', '', $whole_shebang);

	//BEGIN LOOP
	for ($k=0; $k<=600; $k++) {

		//get the category
	echo '<strong>NEW ITEM</strong><br>';
	echo '<br>$whole_shebang[$k]<br>';
	echo $whole_shebang[$k];
	echo '<br><br>';
	echo '<br>$relative_path[$k]<br>';
	echo $relative_path[$k];
	echo '<br><br>';

		//cats 0 will be the filepath, 1 will be category, 2: collection, 3:subcat 
		preg_match('#(.*?)/(.*?)/#', $whole_shebang[$k], $cats); 
	
		//cats 0 will be the filepath, 1 will be category, 2: collection, 3:subcat 
		preg_match('#(.*?)/(.*?)/(.*?)/#', $whole_shebang[$k], $subcats); 

		//ops 1: thumbnail no, 2:short product name, 3:paint option
		preg_match('#.*/(\d)_.*?_(.*?)_(.*?)\.JPG#', $whole_shebang[$k], $ops);

		//Checking the Chests--1: thumbnail, 2: product, 3:ops
		preg_match('#.*/(\d)_.*?_(3DWR_....)_(.*?)\.JPG#', $whole_shebang[$k], $nops);

		if ($nops) $ops = $nops;

	echo '<br>$cats<br>';
	var_dump($subcats);
	echo '<br><br>';
		
	echo '<br>$subcats<br>';
	var_dump($cats);
	echo '<br><br>';
	
	echo '<br>$ops<br>';
	var_dump($ops);
	echo '<br><br>';	
	
	
		if (!$ops)
			continue;
		
		else
			$kln_product[$k] = new kln_product($ops, $cats, $relative_path[$k], $subcats);
	}

}

//Submit kbc_complex_menu form
function process_kbc_options() {
    // Check that user has proper security level
    if ( !current_user_can( 'manage_options' ))
        wp_die( 'Not allowed' );
    // Check that nonce field created in configuration form is present
    check_admin_referer( 'kbc' );

	//load_whole_shebang();
    wp_redirect( add_query_arg( array('page'=>'kbc_main_menu', 'message'=>1), admin_url('options-general.php')));
    
    exit;
}


function new_strat () {
	global $wpdb;
	$is_it_unique = "SELECT post_title FROM wp_posts WHERE post_type = %s";
	$results = $wpdb->query($wpdb->prepare($is_it_unique, 'kln_products'), OBJECT_K);
	echo '<br>';
	echo $wpdb->last_query;
	echo '<br>';			
	var_dump ($results); var_dump($wpdb->last_results);
	
}
?>