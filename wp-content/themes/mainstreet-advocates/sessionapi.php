<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/14/2018
 * Time: 10:04 AM
 */

include( '../../../wp-load.php' );
global $wpdb;
if (isset($_GET['action'])and $_GET['action'] === 'list') {
	$data = $wpdb->get_results( "SELECT * FROM session_info", OBJECT );

	$data = array(
		'sEcho'                => 1,
		"iTotalRecordes"       => count( $data ),
		'iTotalDisplayRecords' => count( $data ),
		'aaData'               => $data
	);

	echo json_encode( $data );
}else if (isset($_POST['action'] ) && $_POST['action'] == 'query'){
	$session = $_POST['session'];
	$data = $wpdb->get_results( "SELECT DISTINCT(state) FROM legislation WHERE session = '{$session}' AND state NOT IN (SELECT session_state FROM session_info WHERE session_year = '{$session}')", OBJECT );
	echo json_encode( $data );
}