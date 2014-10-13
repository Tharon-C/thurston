<?php
/*
*    Plugin Name: Taxonomy Designer
*    Version: 1.0
*    Author: Kellan Cummings
*    Author URI: localhost
*/
define("GIVEN_TERM", 'subcategory');
add_action('wp_ajax_taxonomy_designer', 'taxonomy_designer_callback');
add_action('admin_menu', function() {
	$submenu='Taxonomy Designer';
	$capabilities='manage_options';
	$function_callback='create_taxonomy_designer_settings_menu';
	add_options_page('Custom Taxonomy Data', $submenu, $capabilities, 'taxonomy_designer_menu', $function_callback);
});



function create_taxonomy_designer_settings_menu() { ?>
	<h2>Taxonomy Designer</h2>
	<?php if(isset($_GET['message'])) {
		echo $_GET['message'].'<br><br>';
	} ?>

    <form class="add-new-fader-form" method="post" 
        enctype="multipart/form-data" 
        action="<?php echo admin_url('admin-post.php'); ?>">        
 		<?php //wp_nonce_field('process_taxonomy_changes', 'klkn_tax'); ?>
		<input type="hidden" name="action" value="process_taxonomy_changes">
		
 	    	<?php echo_custom_taxonomies(); ?>
        <input type="submit" name="submit" value="Submit">
		
    </form>	
<?php }

function taxonomy_designer_callback() {
}

function echo_custom_taxonomies() {
	$terms = get_terms(GIVEN_TERM, 'orderby=count&hide_empty=0&hierarchical=0&parent=0');
	echo '<ul class="tier-1-taxonomy-ul">';
	foreach($terms as $term) {
		echo '<li class="tier-1-taxonomy-li" id="'.$term->term_id.'"><button></button><input type="text" name="'.$term->term_id.'" value="'.$term->name.'">';
		$children = get_terms(GIVEN_TERM, 'orderby=count&hide_empty=0&hierarchical=0&child_of='.$term->parent);
		if ($children) {
			echo '<ul class="tier-2-taxonomy-ul">';
			foreach($children as $child) {
				echo '<li class="tier-2-taxonomy-li" id="'.$child->term_id.'"><button></button><input type="text" name="'.$child->term_id.'" value="'.$child->name.'">';
				$grandchildren = get_terms(GIVEN_TERM, 'orderby=count&hide_empty=1&hierarchical=0&parent='.$child->parent);
				if ($grandchildren) {
					echo '<ul class="tier-3-taxonomy-ul">';	
					foreach ($grandchildren as $grandchild) {
						echo '<li class="tier-3-taxonomy-li" id="'.$grandchild->term_id.'"><button></button><input type="text" name="'.$grandchild->term_id.'" value="'.$grandchild->name.'"></li>';
					}
					echo '</ul></li>';
				} else { echo '</li>'; }
			} 
			echo '</ul></li>';
		} else { echo '</li>'; }
	}
	echo '</ul>';
}