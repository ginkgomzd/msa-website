<?php
/*
Plugin Name: Feed Parser plugin
Description: A plugin for questionary feature
Author: MSA
Version: 1.0
*/
?>


   
<?php

add_action('admin_menu', 'questions_plugin_setup_menu');

add_action( 'init', function() {
    add_rewrite_endpoint( 'changelog', EP_PERMALINK );
} );
add_action( 'template_redirect', function() {
    global $wp_query;
    if ( ! is_singular( 'product' ) || ! isset( $wp_query->query_vars['changelog'] ) ) {
        return;
    }
    echo "TEST";
    die;
} );
function questions_plugin_setup_menu(){

    add_menu_page(
        __('Feeder Pages'),// the page title
        __('Feed Parser'),//menu title
        'manage_options',//capability 
        'feeder',//menu slug/handle this is what you need!!!
        'feeder_page',//callback function
        'dashicons-menu',//icon_url,
        '3'//position
    );
    add_submenu_page(
        'feeder',
        'Legislations', //page title
        'Legislations', //menu title
        'manage_options', //capability,
        'legislations',//menu slug
        'legislation_page' //callback function
    );
        
    add_submenu_page(
        'feeder',
        'Hearings', //page title
        'Hearings', //menu title
        'manage_options', //capability,
        'hearings',//menu slug
        'hearing_page' //callback function
    );

    add_submenu_page(
        'feeder',
        'Manage Bills',
        'Manage Bills',
        'manage_options',
        'manage_bills',
        'manage_bills_page'
    );

}


function manage_bills_page(){
    load_template(plugin_dir_path(__FILE__) . 'msa-feed-parser-master/manage_bills.php');
}

function feeder_page(){
    
    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). 'msa-feed-parser-master/';

    load_template( $dir . 'index.php' );
   
}

function hearing_page(){

    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). 'msa-feed-parser-master/';

    load_template( $dir . 'hearing.php' );
      
}
function legislation_page(){

    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). 'msa-feed-parser-master/';

    load_template( $dir . 'leg.php' );
      
}


?>
