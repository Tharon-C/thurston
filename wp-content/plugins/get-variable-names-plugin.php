<?php

    
add_action ('admin_footer', 'get_my_variable_names');

function get_my_variable_names () { 


echo'<style>.plugin_style {background-color: black; color: white; z-index: 200; position: relative;}</style><div class="plugin_style"><br>';
echo '__FILE__: '.__FILE__.'<br>';
echo 'basename(__FILE__): '.basename(__FILE__).'<br>';    
echo 'dirname(__FILE__): '.dirname(__FILE__).'<br>';    
echo 'admin_url(): '.admin_url().'<br>';
echo 'content_url(): '.content_url().'<br>';
echo 'plugins_url(): '.plugins_url().'<br>';
echo 'home_url(): '.home_url().'<br>';
$upload_dir = wp_upload_dir();
echo '$upload_dir[\'url\'] = wp_upload_dir(): '.$upload_dir['url'].'<br>';
echo '$upload_dir[\'baseurl\'] = wp_upload_dir(): '.$upload_dir['baseurl'].'<br>';
echo 'wp_upload_dir("baseurl"): '.wp_upload_dir("baseurl").'<br>';
echo '$_SERVER["SCRIPT_NAME"]: '.$_SERVER["SCRIPT_NAME"].'<br>';
echo '$_SERVER["REQUEST_URI"]: '.$_SERVER["REQUEST_URI"].'<br>';
echo '$_SERVER["SERVER_NAME"]: '.$_SERVER["SERVER_NAME"].'<br>';
echo '$_SERVER["SERVER_PORT"]: '.$_SERVER["SERVER_PORT"].'<br>';    
echo '$_SERVER["HTTPS"]: '.$_SERVER["HTTPS"].'<br>';
echo 'site_url(): '.site_url().'<br>';
echo 'Current URL: http'.(!$_SERVER["HTTPS"] ? '': 's').'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
echo '<br></div>';
}

?>