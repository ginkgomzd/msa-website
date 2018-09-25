<?php
/*
Plugin Name: HTML Scraper plugin
Description: A plugin for scraping html data from site feature
Author: MSA
Version: 1.0
*/

add_action('admin_menu', 'scrape_plugin_setup_menu');

function scrape_plugin_setup_menu(){
    add_menu_page(
        __('Scrape Pages'),// the page title
        __('Scraper'),//menu title
        'manage_options',//capability 
        'scraper',//menu slug/handle this is what you need!!!
        'scrape_page',//callback function
        'dashicons-archive',//icon_url,
        '4'//position
    );

}

function scrape_page(){
    
    //defining page template folder
    $dir = plugin_dir_path( __FILE__ ). '/';
    load_template( $dir . 'scraper.php' );
   
}

?>
