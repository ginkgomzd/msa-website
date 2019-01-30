<?php
/*
 * Plugin Name: Solr Search
 * Version: 1.0
 * Description: Apache Solr server search
 * Author: MSA
 * Requires at least: 4.0
 * Tested up to: 5.0
 *
 * Text Domain: solr-search
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Ljubisa Dobric
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'SOLR_SEARCH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once( SOLR_SEARCH . '/vendor/autoload.php' );
// Load plugin class files
require_once( 'includes/class-solr-search.php' );
require_once( 'includes/class-solr-search-settings.php' );

// Load plugin libraries
require_once( 'includes/class-solr-search-admin-api.php' );
require_once( 'classes/class-solr-search-solr-connector.php' );
/**
 * Returns the main instance of Solr_Search to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Solr_Search
 */

function Solr_Search() {
	$instance = Solr_Search::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Solr_Search_Settings::instance( $instance );
	}
	if ( is_null( $instance->solr_client ) ) {
		$instance->solr_client = new Solr_Search_Connector();
	}

	return $instance;
}

Solr_Search();