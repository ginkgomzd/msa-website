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
        '',//callback function
        'dashicons-networking',//icon_url,
        '5'//position
    );
    
     add_submenu_page(
        'user_management',
        'Clients', //page title
        'Clients', //menu title
        'manage_options', //capability,
        'user_management',//menu slug
        'client_page' //callback function
    );
    
    add_submenu_page(
        'user_management',
        'Clients_users', //page title
        'Clients users', //menu title
        'manage_options', //capability,
        'Clients users',//menu slug
        'user_page' //callback function
    );

}

function client_page(){
    
    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). '/';
    load_template( $dir . 'clients_management.php' );
   
}

function user_page(){
    
    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). '/';
    load_template( $dir . 'management.php' );
   
}

?>
