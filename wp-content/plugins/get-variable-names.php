<?php
/*
*    Plugin Name: Get Variable Names in Admin Footer
*    Description: Prints a list of variable names
*    Version: 1.0
*    Author: Kellan Cummings
*    Author URI: localhost
*    License: GPLv2
*/
    
add_action ('admin_footer', 'get_my_variable_names');

function get_my_variable_names () { 

/*
echo '<strong>$_GET Array: </strong>'; print_r($_GET); echo '<br>';
echo '<strong>add_query_arg(array("page"=>"page", "message"=1), admin_url()): </strong>'.add_query_arg(array("page"=>"page", "message"=>1), admin_url()).'<br>';
echo '<strong>add_query_arg(array("page"=>"page", "message"=>"message")): </strong>'.add_query_arg(array("page"=>"page", "message"=>"message")).'<br>';
echo '<strong>__FILE__: </strong>'.__FILE__.'<br>';
echo 'basename(__FILE__): '.basename(__FILE__).'<br>';    
echo 'dirname(__FILE__): '.dirname(__FILE__).'<br>';    
echo 'admin_url(): '.admin_url().'<br>';
$wp_upload_dir = wp_upload_dir();
echo '$wp_upload_dir[\'path\'] = wp_upload_dir(): '.$wp_upload_dir['path'].'<br>';
echo '$_SERVER["SCRIPT_NAME"]: '.$_SERVER["SCRIPT_NAME"].'<br>';
echo '$_SERVER["REQUEST_URI"]: '.$_SERVER["REQUEST_URI"].'<br>';
echo '$_SERVER["SERVER_NAME"]: '.$_SERVER["SERVER_NAME"].'<br>';
echo '$_SERVER["SERVER_PORT"]: '.$_SERVER["SERVER_PORT"].'<br>';    
echo '$_SERVER["HTTPS"]: '.$_SERVER["HTTPS"].'<br>';
echo 'site_url(): '.site_url().'<br>';


;
echo '<br><br></div>';
*/


echo'<style>.plugin_style {background-color: white; color: blue; z-index: 200; position: relative;} strong {color: black}</style><div class="plugin_style"><br>';

$kc_output = array('$_GET Array'=>$_GET, '__FILE__'=>__FILE__, '$_SERVER["SCRIPT_NAME"]'=>$_SERVER["SCRIPT_NAME"], 'plugins_url()'=>plugins_url(), 'plugins_url(\'file.php\')'=>plugins_url('file.php'), '"http".(!$_SERVER["HTTPS"] ? "": "s")."://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"])'=>'http'.(!$_SERVER["HTTPS"] ? '': 's').'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);

    foreach($kc_output as $key=>$value)
        if (is_array($key))
            foreach ($key as $k=>$v)
                    printf('<strong>%s: </strong>%s, ', $k, $v);
        else
        printf('<strong>%s: </strong>%s<br>', $key, $value);
   	print_r(get_option('iv_options'));
    echo '<br><br></div>';
	

}  

?>