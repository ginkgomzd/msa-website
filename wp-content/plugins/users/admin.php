<?php
/*
Plugin Name: User management plugin
Description: A plugin for user - profile match management
Author: MSA
Version: 1.0
*/

add_action('admin_menu', 'user_plugin_setup_menu');

function user_plugin_setup_menu(){
    add_menu_page(
        __('User Pages'),// the page title
        __('Users Management'),//menu title
        'manage_options',//capability 
        'user_management',//menu slug/handle this is what you need!!!
        'user_page',//callback function
        'dashicons-networking',//icon_url,
        '5'//position
    );

}

function user_page(){
    
    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). '/';
    load_template( $dir . 'management.php' );
   
}

?>
