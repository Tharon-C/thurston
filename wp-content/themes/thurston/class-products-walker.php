<?php

class KLN_Products_Walker extends Walker_Nav_Menu {

//	public $db_fields;
//	$db_fields = array('parent'=>'', 'id'=>'');
	
	function __construct () {
	}

	function start_el(&$output, $item, $depth=0, $args=array()) {  
    	if( 0 == $depth )  
        	return;  
		parent::start_el(&$output, $item, $depth, $args);  
	}  
  
	function end_el(&$output, $item, $depth=0, $args=array()) {  
		if( 0 == $depth )  
        	return;
        parent::end_el(&$output, $item, $depth, $args);  
	}  

	
}


class Walker_Simple_Example extends Walker {  
  
    // Set the properties of the element which give the ID of the current item and its parent  
    var $db_fields = array( 'parent' => 'parent_id', 'id' => 'object_id' );  
  
    // Displays start of a level. E.g '<ul>'  
    // @see Walker::start_lvl()  
    function start_lvl(&$output, $depth=0, $args=array()) {  
        $output .= "\n<ul>\n";  
    }  
  
    // Displays end of a level. E.g '</ul>'  
    // @see Walker::end_lvl()  
    function end_lvl(&$output, $depth=0, $args=array()) {  
        $output .= "</ul>\n";  
    }  
  
    // Displays start of an element. E.g '<li> Item Name'  
    // @see Walker::start_el()  
    function start_el(&$output, $item, $depth=0, $args=array()) {  
        $output .= "<li>".esc_attr($item->label);  
    }  
  
    // Displays end of an element. E.g '</li>'  
    // @see Walker::end_el()  
    function end_el(&$output, $item, $depth=0, $args=array()) {  
        $output .= "</li>\n";  
    }  
} 

 
$elements=array(); // Array of elements  
echo Walker_Simple_Example::walk($elements);  

show_product_walker();

function show_product_walker() {
	
	wp_list_categories(
	array(
	'show_option_all'    => '',
	'orderby'            => 'name',
	'order'              => 'ASC',
	'style'              => 'list',
	'show_count'         => 0,
	'hide_empty'         => 1,
	'use_desc_for_title' => 1,
	'child_of'           => 0,
	'feed'               => '',
	'feed_type'          => '',
	'feed_image'         => '',
	'exclude'            => '',
	'exclude_tree'       => '',
	'include'            => '',
	'hierarchical'       => 1,
	'title_li'           => __( 'Collections' ),
	'show_option_none'   => __('No categories'),
	'number'             => null,
	'echo'               => 1,
	'depth'              => 0,
	'current_category'   => 0,
	'pad_counts'         => 0,
	'taxonomy'           => 'kln_collection',
	'walker'             => new description_walker())
	);
}
	

class description_walker extends Walker_Nav_Menu
{
      function start_el(&$output, $item, $depth, $args)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $prepend = '<strong>';
           $append = '</strong>';
           $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
                     $description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            $item_output .= $description.$args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}

?>
