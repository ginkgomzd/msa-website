<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/15/2018
 * Time: 4:00 PM
 */
/* Template Name: detail_bill_text */
if ( isset( $_GET['id'] ) && isset( $_GET['entity'] ) ) {
	$row = $wpdb->get_row( "SELECT content FROM entity_text WHERE entity_id = '{$_GET['id']}' AND entity_type = '{$_GET['entity']}';", OBJECT );
	if ( $row ) {
		echo $row->content;
	}else{
		echo "<h1>No bill details found</h1>";
	}
}else{
	wp_die(	'<h1>' . __( "Missing ID's for bill content.") . '</h1>',
		500);
}
