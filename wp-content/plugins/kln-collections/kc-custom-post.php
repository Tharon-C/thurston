<?php
/*
*    Plugin Name: Products Database
*    Description: Adds Support for Custom Products
*    Version: 1.0
*    Author: TaglineGroup
*    License: GPLv2
*/
define('TAXONOMY_TYPE', 'kln_collection');
define('TAXONOMY_NAME', 'KLN Collection');
define('CUSTOM_PAGE_TYPE', 'kln_products');
define('CUSTOME_PAGE_NAME', 'KLN Product');

add_action('wp_enqueue_scripts', 'add_custom_product_scripts');
add_action ('init', 'create_kln_collections_type');
add_action('wp_ajax_get_selected_product', 'get_selected_product');
add_action('wp_ajax_nopriv_get_selected_product', 'get_selected_product');
add_action('wp_ajax_update_product_metadata', 'update_product_metadata');
add_action('wp_ajax_update_product_image_metadata', 'update_product_image_metadata');

add_action('wp_ajax_get_selected_collection', 'get_selected_collection');
add_action('wp_ajax_nopriv_get_selected_collection', 'get_selected_collection');
add_action('admin_init', 'add_custom_product_metaboxes');
add_filter('replace_term_description', 'replace_term_description', 1, 2);
add_filter('postbox_classes_'.CUSTOM_PAGE_TYPE.'_dimensions_meta_box', 'add_metabox_classes');
add_filter('postbox_classes_'.CUSTOM_PAGE_TYPE.'_product_image_metabox', 'add_metabox_classes');
add_filter('template_include', 'kln_template_include', 1);
add_filter('taxonomy_template', 'kln_taxonomy_template_include', 2);

add_action('admin_footer', 'custom_product_admin_ajax_js');
add_action('wp_ajax_custom_product_admin_ajax_callback', 'custom_product_admin_ajax_callback');

$taxonomyType = 'kln_collection';
$taxonomyName = 'KLN Collection';
$customPageType = 'kln_products';
$customPageName = 'KLN Product';

function replace_term_description($description, $title) { 
	$description =  '<h1 class="collections-h1">'.strtoupper($title).'</h1>'.$description;
	return preg_replace('#\<p\>\<\/p\>#', '', $description);
}

function add_custom_product_scripts() { 
    wp_enqueue_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js', array('jquery'), '1.8.6');
	wp_enqueue_script('kc-dropdown', plugins_url().'/kln-collections/js/kc-dropdown.js', array('jquery', 'jquery-ui'));
	wp_enqueue_style('kc-dropdown-css', plugins_url().'/kln-collections/css/kc-dropdown.css');
	wp_localize_script('kc-dropdown', 'data', array('admin_url'=>admin_url('admin-ajax.php'), 'site_url'=>get_site_url()));
}

function get_selected_product() {
	require_once('single-products-class.php');
	$product = new KLNProduct($_POST['id']);
	echo $product->get_product();
	exit;
}

function get_selected_collection() {
	$term = get_term(intval($_POST['id']), 'kln_collection');
	echo apply_filters('replace_term_description', $term->description, $term->name);
	exit;
}

//Runs after WordPress has finished loading but before any headers are sent. 
	
function create_kln_collections_type() {
 
	global $taxonomyType;
	global $taxonomyName;
	global $customPageType;
	global $customPageName;

	register_taxonomy(
			$taxonomyType,
			$customPageType,
			array(
				'labels'=>array(
					'name'=>$taxonomyName.'s',
					'singular_name'=>$taxonomyName,
					'all_items'=>'All '.$taxonomyName.'s',
					'parent_item'=>'From which '.$taxonomyName
					),
			'show_in_nav_menus'=>TRUE,
			'show_admin_column'=>TRUE,
			'hierarchical'=>TRUE,
			'query_var'=>'collections',
			'rewrite'=>array(
				'with front'=>FALSE,
				'hierarchical'=>TRUE,
				'slug'=>'collections'
				)
			)
		);
	
	$tax_types = array('laminate', 'drawer-pull', 'paint');

	foreach($tax_types as $tax_type) {
		register_taxonomy(
			$tax_type.'s',
			$customPageType,
			array(
				'labels'=>array(
					'name'=>ucfirst($tax_type.'s'),
					'singular_name'=>ucfirst($tax_type),
					'all_items'=>'All '.$tax_type.'s',
					'parent_item'=>'From which '.$tax_type,
					),
			'show_in_nav_menus'=>TRUE,
			'hierarchical'=>TRUE,
			'query_var'=>$tax_type.'s',
			'rewrite'=>array(
				'with front'=>FALSE,
				'hierarchical'=>TRUE,
				'slug'=>$tax_type.'s'
				)
			)
		);
	}


	register_taxonomy(
		'subcategory',
		$customPageType,
		array(
			'labels'=>array(
				'name'=>'Subcategories',
				'singular_name'=>'Subcategory',
				'all_items'=>'All Subcategories'
				),
		'show_in_nav_menus'=>TRUE,
		'show_ui'=>TRUE,
		'show_admin_column'=>TRUE,
		'hierarchical'=>TRUE,
		'has_archive' => TRUE,
		'query_var'=>'categories',
		'rewrite'=>array(
			'with front'=>FALSE,
			'hierarchical'=>TRUE,
			'slug'=>'categories'
			)
		)
	);
	
	register_post_type( $customPageType, 
		array(	
		'labels' => array(		//specifies strings to be used when managing post type
			'name' => $customPageName.'s',
			'singular_name' => $customPageName,
			'add_new' => 'Add New',
			'add_new_item' => 'Add New '.$customPageName,
			'edit' => 'Edit',
			'edit_item' => 'Edit '.$customPageName,
			'new_item' => 'New '.$customPageName,
			'view' => 'View',
			'view_item' => 'View '.$customPageName,
			'search_items' => 'Search '.$customPageName.'s',
			'not_found' => 'No '.$customPageName.'s Found',
			'not_found_in_trash' => 'No '.$customPageName.'s found in Trash'),
		'public' => true,
		'menu_position' => 20,	
		'hierarchical'=> TRUE,
		'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'),
		'taxonomies' => array('subcategory', 'kln_collection', 'laminates', 'drawer-pulls', 'paints'),
		'menu_icon' => plugins_url( '/chest.ico', __FILE__ ),
		'has_archive' => true,
		'query_var'=>'products',
		'rewrite' => array(
			'slug'=>'products',
			'with_front'=>false) //takes out /blog/ from address
		)
	);
}

function add_custom_product_metaboxes () {
	global $customPageType;
	$id = 'dimensions_meta_box';
	$title = 'Dimensions';
	$callback = 'display_dimensions_metabox';
	$the_post_type = $customPageType;
	$context = 'normal'; // The part of the page where the edit screen section should be shown 			('normal', 'advanced', or 'side'). 

	add_meta_box( $id, $title, $callback, $the_post_type, $context);

	$id = 'product_image_metabox';
	$title = 'Images';
	$callback = 'display_product_image_metabox';
	$the_post_type = $customPageType;
	$context = 'normal'; // The part of the page where the edit screen section should be shown 			('normal', 'advanced', or 'side'). 

	add_meta_box( $id, $title, $callback, $the_post_type, $context);

}

function add_metabox_classes($classes) {
	array_push($classes, 'product_metadata_box');
	return $classes;
} 

function update_product_metadata() {
	$post_id = intval($_POST['post_id']);
	print_r($_POST);
	unset($_POST['action']);	
	unset($_POST['post_id']);
	
	foreach ($_POST as $key=>$value) {
		$post_meta = update_post_meta($post_id, $key, $value);
	}
}

function update_product_image_metadata() {
	$image_ids = array_slice(array_reverse(explode('&', $_POST['image_ids'])), 1);
	$post_meta = update_post_meta($_POST['post_id'], 'featured-images', $image_ids);

	$images = get_post_meta($_POST['post_id'], 'featured-images');
	print_r($images);
}


function display_dimensions_metabox( $this_product ) {
	$specs = get_post_custom($this_product->ID);  
	//print_r($specs);
	?>
        <input type="hidden" name="post_id" value="<?php echo $this_product->ID; ?>" /> 
        <label>Headnote</label>
     	<textarea><?php echo $specs['dimensions_headnote'][0]; ?></textarea>
        <table>
        	<thead>
            	<th>Style</th>
                <th>Width</th>
            	<th>Height</th>
            	<th>Depth</th>
            </thead>
				<tbody>
            	<tr>
                	<td><input type="text" name="dimensions_style1" value="<?php echo htmlspecialchars($specs['dimensions_type1'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_width1" value="<?php echo htmlspecialchars($specs['dimensions_width1'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_height1" value="<?php echo htmlspecialchars($specs['dimensions_height1'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_depth1" value="<?php echo htmlspecialchars($specs['dimensions_depth1'][0]); ?>"></td>
                </tr>
                <tr>
                	<td><input type="text" name="dimensions_style2" value="<?php echo htmlspecialchars($specs['dimensions_type2'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_width2" value="<?php echo htmlspecialchars($specs['dimensions_width2'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_height2" value="<?php echo htmlspecialchars($specs['dimensions_height2'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_depth2" value="<?php echo htmlspecialchars($specs['dimensions_depth2'][0]); ?>"></td>
                </tr>
                <tr>
					<td><input type="text" name="dimensions_style3" value="<?php echo htmlspecialchars($specs['dimensions_type3'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_width3" value="<?php echo htmlspecialchars($specs['dimensions_width3'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_height3" value="<?php echo htmlspecialchars($specs['dimensions_height3'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_depth3" value="<?php echo htmlspecialchars($specs['dimensions_depth3'][0]); ?>"></td>
                </tr>
                <tr>
					<td><input type="text" name="dimensions_style4" value="<?php echo htmlspecialchars($specs['dimensions_type4'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_width4" value="<?php echo htmlspecialchars($specs['dimensions_width4'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_height4" value="<?php echo htmlspecialchars($specs['dimensions_height4'][0]); ?>"></td>
                	<td><input type="text" name="dimensions_depth4" value="<?php echo htmlspecialchars($specs['dimensions_depth4'][0]); ?>"></td>
                </tr>
            </tbody>  
        </table>
    	<label>Footnote</label>
     	<textarea><?php echo $specs['dimensions_footnote'][0]; ?></textarea>
		<button id="submit-dimensions-metadata" class="submit-metadata">Update</button>
<?php }

function display_product_image_metabox( $this_product ) { ?>					
   	
    <!-- <label>Add New Image</label> 
    <input type="file" name="file" id="featured-image-file">
   	<br> -->
 	<?php echo display_custom_product_images(); ?>
    <input type="hidden" name="post_id" value="<?php echo $this_product->ID; ?>" /> 
    <input type="hidden" name="product_image_action" value="update_product_image_metadata" />
 
	<button id="submit-product-image-metadata" class="submit-metadata">Update</button>
<?php } 

function display_custom_product_images() {
	global $post;
	$image_ids = get_post_meta($post->ID, 'featured-images', true);
	$args = array( 'post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' =>'any', 'post_parent' => $post->ID ); 
	$attachments = get_posts( $args );
	$return = '<div class="featured-images-wrap">';
	if ( $attachments ) {
		foreach ($attachments as $attachment) {
		$return .= '
			<div class="featured-image-wrap">
				<img src="'.plugins_url().'/kln-collections/img/x-mark.png" class="featured-x-mark">
				<img src="'.apply_filters( 'the_title' , $attachment->guid).'" class="featured-product-image">
				<fieldset class="featured-product-checkbox">
					<input type="checkbox" 
						name="featured-product-image" 
						value="'.$attachment->ID.'"';
		$return .= ((is_array($image_ids) && in_array($attachment->ID, $image_ids))) ? 'checked="checked">' : '>';
		$return .= 'Display Image
				</fieldset>
			</div>';
		}
	}
	$return .= '</div>';
	return $return;	
}


function kln_taxonomy_template_include ($template_path) {
	$taxonomy = get_queried_object()->taxonomy;
	if ($taxonomy == 'subcategory') {
		//$template_path = plugin_dir_path(__FILE__).'/taxonomy-subcategory.php';
	}
	return $template_path;
}

function kln_template_include ($template_path) {
	global $customPageType;
	if (get_post_type()=='kln_products') {
		if(is_single()) {
			$template_path = plugin_dir_path(__FILE__).'single-products.php';
		}
	}
	return $template_path;
}

function custom_product_admin_ajax_js() { ?>
	<script>
	jQuery('document').ready(function($) {
		var featuredXMarkClickEvent = function(event) {
			event.preventDefault;
			event.stopPropagation;		
			var id = jQuery(this).parent().find('[name="featured-product-image"]').val();
			console.log(id);
			var data = {action: 'custom_product_admin_ajax_callback', attachment_id: id};
			if (confirm('Are you sure you want to delete this item?')) {
				jQuery.post(ajaxurl, data, function(response) {
					jQuery('.featured-images-wrap').replaceWith(response);					
					jQuery('.featured-x-mark').on("click", featuredXMarkClickEvent);
				});
			}
		};

		jQuery('.featured-x-mark').on("click", featuredXMarkClickEvent);		
	});
	</script>
<?php }

function custom_product_admin_ajax_callback() {
	$id = $_POST['attachment_id'];
	if ($id) {
		//print_r(get_post($id));
		$deleted = wp_delete_post($id);
	}
	if ($deleted) echo '<h5>Successfully Deleted Post: '.$id.'</h5>'.display_custom_product_images();
	else echo '<h5>There was a problem deleting your file.</h5>'.display_custom_product_images();
	exit;
}

function upload_new_custom_product_file() {
	if ( !current_user_can( 'manage_options' ))
       wp_die( 'Not allowed' );
	
	$uploads_path = WP_CONTENT_DIR.'/uploads';
	$products_folder = '/custom_products/';
	$pathname = $uploads_path.$products_folder;

 	if (!is_dir($pathname)) {
		mkdir($pathname);
	}

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	
	if ($_FILES['file']['error'] == 4 && $_POST['attachment_id']) {
		$message = 'No Attachment Found!';
	} elseif ($_FILES["file"]["error"] > 0) {
  		$message = "Error: " . $_FILES["file"]["error"] . "<br>";
  	} elseif((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& in_array($extension, $allowedExts)) {
		
		$filepath = $pathname.$_FILES["file"]["name"];
		$file_has_been_moved = move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
      	
		if ($file_has_been_moved) {
			$metadata_has_been_set = update_custom_product_image_metadata($filepath);
			
			if($metadata_has_been_set) {
				$message = "Upload: " . $_FILES["file"]["name"] . "<br>"
					."Type: " . $_FILES["file"]["type"] . "<br>"
					."Size: " . ($_FILES["file"]["size"] / 1024). " kB<br>"
					."Stored in: ".$_FILES["file"]["tmp_name"]
					."Stored in: ".$filepath;
			} else {
				$message = "There was an error processing your image MetaData!";
			}
			
		} else {
			$message = "Error Moving File into New Directory!";
		}
	} else {
  		$message = "Invalid File Type! :".$_FILES["file"]["name"] . "<br>"
					."Type: " . $_FILES["file"]["type"] . "<br>"
					."Size: " . ($_FILES["file"]["size"] / 1024). " kB<br>"
					."Stored in: ".$_FILES["file"]["tmp_name"];
  	}
	echo $message;
}

function update_custom_product_image_metadata($filepath) {
	$upload_dir = wp_upload_dir();
	$image_basename = preg_replace(array('#.*\/#'), array(''), $filepath);
	$guid = $upload_dir['baseurl'].'/custom_products/'.$image_basename;	

	$wp_filetype = wp_check_filetype($filepath, null);
	$attachment = array(
		'guid' => $guid, 
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => $title,
		'post_name' =>  $title,		
		'post_status' => 'inherit'
	);
	global $post;
	$attach_id = wp_insert_attachment($attachment, $filepath, $post->ID);
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata($attach_id, $filepath);
	$updated_attachment_metadata = wp_update_attachment_metadata($attach_id, $attach_data );

	if ($updated_attachment_metadata) {
		return "Successfully Uploded Metadata";
	} else {
		return 0;
	}
}