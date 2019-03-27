<?php

/**
 * 
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


// Do something here if plugin is being uninstalled.
delete_option( 'wpt_solr_search_core_prefix' );
delete_option( 'wpt_solr_search_server_enable' );
delete_option( 'wpt_solr_search_server_host' );
delete_option( 'wpt_solr_search_server_pass' );
delete_option( 'wpt_solr_search_server_port' );
delete_option( 'wpt_solr_search_server_username' );