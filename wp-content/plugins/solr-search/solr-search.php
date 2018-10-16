<?php
/*
 * Plugin Name: Solr Search
 * Version: 1.0
 * Description: Apache Sorl server search
 * Author: Dragan Markovski
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: solr-search
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Dragan Markovski
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-solr-search.php' );
require_once( 'includes/class-solr-search-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-solr-search-admin-api.php' );
require_once( 'includes/lib/class-solr-search-post-type.php' );
require_once( 'includes/lib/class-solr-search-taxonomy.php' );

/**
 * Returns the main instance of Solr_Search to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Solr_Search
 */

function Solr_Search () {
	$instance = Solr_Search::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Solr_Search_Settings::instance( $instance );
	}

	return $instance;
}

Solr_Search();