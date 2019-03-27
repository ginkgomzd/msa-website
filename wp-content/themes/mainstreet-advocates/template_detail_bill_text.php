<?php
/* Template Name: detail_bill_text */
if ( isset( $_GET['id'] ) && isset( $_GET['entity'] ) ) {
	$row = $wpdb->get_row( "SELECT content,text_type FROM entity_text WHERE entity_id = '{$_GET['id']}' AND entity_type = '{$_GET['entity']}';", OBJECT );
	if ( $row ) {
		if ( $row->text_type === 'pdf' ) {
			header("Content-type: application/pdf");
			header('Content-Transfer-Encoding: binary');
			print readfile($row->content);;
			exit(0);
		} else {
			echo  file_get_contents($row->content);
		}
	} else {
		echo "<h1>No bill details found</h1>";
	}
} else {
	wp_die( '<h1>' . __( "Missing ID's for bill content." ) . '</h1>',
		500 );
}
