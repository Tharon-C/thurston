<?php
/*
*    Plugin Name: Import Vitals
*    Description: Allows You to put all vital information into a admin-menu form. Updating the form automatically updates all vital info on web site.
*    Version: 1.0
*    Author: Kellan Cummings
*    Author URI: taglinegroup.com
*    License: GPLv2
*/


//Setup Class

register_activation_hook( __FILE__, 'iv_set_default_options_array');

function iv_set_default_options_array() {
    if (get_option('iv_options') === false ) {
        $new_options['company'] = '';
        $new_options['streetAddress'] = '';
        $new_options['city'] = '';
		$new_options['state'] = '';
		$new_options['zip'] = '';
		$new_options['toll_free'] = '';
		$new_options['phone'] = '';
		$new_options['fax'] = '';
        add_option('iv_options', $new_options);
    }
}

//Create a Settings Menu for Content Filter; 
add_action('admin_menu', 'iv_settings_menu');

function iv_settings_menu() {
    // Create top-level menu item on wp-admin sidebar
    add_options_page('My Vital Information Shortcode', 'My Vitals', 'manage_options', 'iv_main_menu', 'iv_complex_menu');
}

//Add the Menu to change the options in the DB
function iv_complex_menu() {

    // Retrieve plugin configuration options from database
    ?>
     
    <div id="iv-general" class="wrap">
        <h2>Vital Information</h2>
    
        <?php
    //Checks to see if settings have been saved and if so, prints this message
        if (isset($_GET['message']) && $_GET['message'] == '1' ) { ?>
        <div id='message' class='updated fade'><p><strong>Settings
Saved</strong></p></div>

    <?php } ?>

        <form method="post" action="admin-post.php">
            <input type="hidden" name="action" value="save_iv_options" />
            <!-- Adding security through hidden referrer field -->
            <?php wp_nonce_field( 'iv' );
            $options = get_option( 'iv_options' );
            echo '<table>';
            foreach ($options as $key=>$value)
                echo '<tr><td>'.strtoupper(str_replace('_', ' ', $key)).':</td><td><input type="text" name="'.$key.'" value="'.esc_html( $value ).'"></td></tr>'; ?>
            </table>
            <br />
            <input type="submit" value="Submit" class="button-primary"/>
            
        <form method="post" action="admin-post.php"><br /><br />
            <input type="hidden" name="action" value="add_new_iv_options" />
        	<?php wp_nonce_field( 'iv2' );?>
			Your New Option: <input type="text" name="your_new_option" value=""><br /><br />
            <input type="submit" value="Submit" class="button-primary"/>    		
    </div>
<?php
}


//Processing and Storing Plugin Config Data

//Initalize Admin
add_action('admin_init', 'iv_admin_init');

//Call to action variable admin_post_+[hidden form field value from iv_complex_menu()] 
function iv_admin_init () {
    add_action( 'admin_post_save_iv_options', 'process_iv_options' );
    add_action( 'admin_post_add_new_iv_options', 'add_new_iv_options' );
}

function add_new_iv_options() {
    if ( !current_user_can( 'manage_options' ))
        wp_die( 'Not allowed' );
		
	check_admin_referer( 'iv2' );
	$options = get_option( 'iv_options' );
	$new_option = array($_POST['your_new_option']=>'A');
	$options = array_merge($options, $new_option);
	update_option( 'iv_options', $options );
    wp_redirect( add_query_arg( array('page'=>'iv_main_menu', 'message'=>1), admin_url('options-general.php')));
	exit;
}

//Submit iv_complex_menu form
function process_iv_options() {
    // Check that user has proper security level

    if ( !current_user_can( 'manage_options' ))
        wp_die( 'Not allowed' );
    
	// Check that nonce field created in configuration form is present

	
	check_admin_referer( 'iv' );

    // Retrieve original plugin options array
    $options = get_option( 'iv_options' );
    // Cycle through all text form fields and store their values in the options array
    foreach ($options as $key=>$value)
		if ( isset( $_POST[$key] ) ) 
            $options[$key] = sanitize_text_field( $_POST[$key] );
        // Cycle through all check box form fields and set the options array to true or false values based on presence of variables

    //Store Updated Options Array Into Database
    update_option( 'iv_options', $options );
    // Redirect the page to the configuration form that was processed
    //if (__FILE__ == admin_url().
    wp_redirect( add_query_arg( array('page'=>'iv_main_menu', 'message'=>1), admin_url('options-general.php')));
    
     exit;
}


//shortcode for importing and updating vital information [vital
add_shortcode('vitals', 'iv_address');

function iv_address ($atts) {
	$myoptions = get_option('iv_options');
	$returnOption = '';
	if (is_array($atts))
		foreach ($atts as $k=>$v)
			$returnOption .= $myoptions[$v].' ';
	else if (!$atts);
	else
		$returnOption = $myoptions[$v];
	
	return $returnOption;
}
	
	
	// extract(shortcode_atts(array('streetAddress'=>'', 'city'=>'', 'state'=>'', 'zip'=>'', 'phone'=>'', 'fax'=>''), $atts))
	



?>