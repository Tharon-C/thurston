<?php

function walk_products($post_id, $collection, $title) {

	//set up wp_list_categories parameters
	$args = array(
		'taxonomy' 		=> $collection,
		'title_li' 		=> __($title),
		'hide_empty'    => TRUE,
		'hierarchical'  => TRUE
	);

	$args['walker'] = new Products_Walker($post_id, $args['taxonomy'] );

	$output = wp_list_categories( $args );		//set up the list and walk it with Products_Walker
	if ( $output ) {
		return $output;
	}
}


class Products_Walker extends Walker_Category {
	
	var $db_fields = array('parent' => 'parent', 'id' => 'term_id');
	var $max_depth = 0;
	private $term_name, $term_id, $product_id, $term_class, $taxonomy;


	function __construct( $product_id, $taxonomy )  {
		// fetch the parent id for the post
		$term = get_term($post_id, 'kln_collection');
		$this->product_id = $product_id;
		$this->term_id = $term->term_id;		 
		$this->term_name = $term->name;
		$this->term_class = $term->slug;
		$this->taxonomy = $taxonomy;
	}

	
	//called into action by wp_list_categories(I think)
	function walk( $elements, $max_depth) {

		//if there are additional $args, it gets them by slicing out $elements and $max_depth
		$args = array_slice(func_get_args(), 2);

		//reset the output
		$output = '';

		if ($max_depth < -1) { //invalid parameter {
			echo '<br><strong>max_depth <-1</strong><br>';
			return $output;
		}

		if (empty($elements)) {//nothing to walk {
			echo '<br><strong>Nothing to Walk!</strong><br>';
			return $output;
		}

		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];

		// if max_depth = -1, flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			echo '<br>Max Depth = -1. Flat Display<br>';
			foreach ( $elements as $e )
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			return $output;
		}

		/*
		 * Need to display in hierarchical order.
		 * Separate elements into two buckets: top level and children elements.
		 * Children_elements is two dimensional array, eg.
		 * Children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field ) {
				$top_level_elements[] = $e;
			}
			else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		/*
		 * When none of the elements is top level.
		 * Assume the first one must be root of the sub elements.
		 */
		if ( empty($top_level_elements) ) {

			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		foreach ( $top_level_elements as $e )
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );

		/*
		 * If we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless.
		 */
		if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans )
				foreach( $orphans as $op )
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
		 }
		 return $output;
	}
	

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		$this->current_depth = $depth;		
		
		//if there are no more elements to walk return 0
		if ( !$element )
			return;

		//Sets up the query parameter in the variable $id_field
		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];
		$this->element_id = $element->$id_field;
		$this->element_parent = $element->$parent_field;

		//check to see if additional args have been passed--picks up args from WP_LIST_CATEGORIES
		//if has_children has been set, 
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			//Sets parameter 'has_children' to FALSE if $children_elements is empty
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		}
		
		//Create a merged array of arguments to send to the callback function
		$cb_args = array_merge( array(&$output, $element, $depth), $args, array($element->$id_field));
					
		//calls the user function to establish the first li
		call_user_func_array(array($this, 'start_el'), $cb_args);

//		echo "<br>Function Output: $output";
//		echo '<br>$id: ';
		$id = $element->$id_field;
		
		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			//loop through array of children elements matched to parent id
			foreach( $children_elements[ $id ] as $child ){
					
				//Initiates ::start_lvl
				if ( !isset($newlevel) ) {
					$newlevel = true;
					//start the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args, array($id));
					//calls the new level
					call_user_func_array(array($this, 'start_lvl'), $cb_args);
				}
				//add 1 to the depth and run display elements again with the child
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array($this, 'end_lvl'), $cb_args);
		}

		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array($this, 'end_el'), $cb_args);
		
	}

	//display ul element
	function start_lvl(&$output, $depth=0, $args=array()) {  
		
		$output .= '<ul class="collection-ul">';  
    }  
  
    // Displays end of a level. E.g '</ul>'  
    // @see Walker::end_lvl()  
    function end_lvl(&$output, $depth=0, $args = array()) {  
        $output .= "</ul>";  
    }  
  
    // Displays start of an element. E.g '<li> Item Name'  
    // @see Walker::start_el()  
	
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		
		if ($this->element_parent != 0 && $this->element_parent != $this->product_id) {
			$hide_it = "hide_it";
		}
		
		if ($this->current_depth == 2) {
			$hide_it = "hide_it";
		}
						
		extract($args);
		$cat_name = esc_attr( $category->name );

		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$bebes_kids = get_term_children($this->element_id, $this->taxonomy);

		global $wpdb;
		$wpdb->query($wpdb->prepare('
			SELECT wp.guid, wp.post_name, wp.ID FROM wp_posts wp
			INNER JOIN wp_term_relationships wtr ON wtr.object_id = wp.ID
			INNER JOIN wp_term_taxonomy wtt ON wtr.term_taxonomy_id = wtt.term_taxonomy_id
			WHERE wtt.term_id = %s', $this->element_id));
		
		$result = basename($wpdb->last_result[0]->post_name);
		$post_id = $wpdb->last_result[0]->ID;
		
		if (!empty($bebes_kids)) {
			$link = $cat_name;
			$no_children = false;
		}
		else {
			$link = $cat_name;
			$no_children = 'no-children';
		}

		if ( !empty($show_count) )
			$link .= ' (' . intval($category->count) . ')';

		if ( 'list' == $args['style'] ) {
			$output .= "<li";
			$class = 'cat-item cat-item-' . $category->term_id.' list-depth-'.$this->current_depth.' '.$this->taxonomy;
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
			}
			$post_id = ($this->current_depth == 0 && !$no_children) ? $category->term_id : $post_id;			
			$output .=  ' id="'.$post_id.'" class="'. $class . ' '.$hide_it.' '.$no_children.'"';
			$output .= ">$link";
		} else {
			$output .= "$link<br />";
		}
//		$output .= "<li id=\"$this->dads_new_class\"-menu-item>$this->your_name";

	}  
  
    // Displays end of an element. E.g '</li>'  
    // @see Walker::end_el()  
	function end_el(&$output, $item, $depth=0, $args=array()) {  
        $output .= "</li>";  
	}  

}
?>