<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 1/26/2019
 * Time: 11:04 PM
 */
include( '../../../../wp-load.php' );
function retrive_data( $entity_type, $client_id, $import_table_id = [] ) {
	global $wpdb;
	if ( empty( $import_table_id ) ) {
		return;
	}
	$import_list = implode( ',', $import_table_id );
	if ( $entity_type === 'legislation' ) {
		return $wpdb->get_results( "SELECT leg.id,state,session,type,number,title,GROUP_CONCAT(DISTINCT pm.pname) as categories, pn.id as priority,hb.id as hidden FROM last_updated AS lu
LEFT JOIN legislation AS leg ON lu.document_id = leg.id
LEFT JOIN profile_match AS pm ON leg.id = pm.entity_id  and pm.entity_type = 'legislation' and pm.client_id = {$client_id}
LEFT JOIN prioritized_bills AS pn ON leg.id = pn.bill_id and pn.entity_type='legislation' and pn.client_id = {$client_id}
LEFT JOIN hidden_bills AS hb ON leg.id = hb.bill_id and hb.entity_type='legislation' and hb.client_id = {$client_id}
WHERE lu.import_table_id IN ({$import_list}) GROUP BY leg.id", OBJECT );
	} else if ( $entity_type === 'hearing' ) {
		return $wpdb->get_results( "SELECT her.id, date,time,house,committee,place,pn.id as priority,hb.id as hidden FROM last_updated AS lu
LEFT JOIN hearing AS her ON lu.document_id = her.id
LEFT JOIN prioritized_bills AS pn ON her.id = pn.bill_id and entity_type='hearing' and client_id = {$client_id}
LEFT JOIN hidden_bills AS hb ON her.id = hb.bill_id and hb.entity_type='hearing' and hb.client_id = {$client_id}
WHERE lu.import_table_id IN ({$import_list}) GROUP BY her.id", OBJECT );
	} else if ( $entity_type === 'regulation' ) {
		return $wpdb->get_results( "SELECT reg.id,state,agency_name,type,state_action_type,description,register_date,register_citation,pn.id as priority,hb.id as hidden FROM last_updated AS lu
LEFT JOIN regulation AS reg ON lu.document_id = reg.id
LEFT JOIN prioritized_bills AS pn ON reg.id = pn.bill_id and entity_type='regulation' and client_id = {$client_id}
LEFT JOIN hidden_bills AS hb ON reg.id = hb.bill_id and hb.entity_type='regulation' and hb.client_id = {$client_id}
WHERE lu.import_table_id IN ({$import_list}) GROUP BY reg.id", OBJECT );
	}
}

function getClientImports( $client_id ) {
	global $wpdb;
	$result = $wpdb->get_results( "SELECT DISTINCT xml_import_timestamp,entity_type,id FROM import_table 
											   WHERE client_id = {$client_id} ORDER BY xml_import_timestamp DESC LIMIT 25", OBJECT );

	return $result;
}


if ( isset( $_POST['client_id'] ) && isset( $_POST['status'] ) ) {
	$client_id = $_POST['client_id'];
	$import_id = $_POST['status'];

	/*switch ( $status ) {
		case 'last_7':
			$out = $wpdb->get_results( "SELECT id,xml_import_timestamp,entity_type FROM import_table 
											   WHERE client_id = {$client_id} AND 
											   DATE_SUB(xml_import_timestamp, INTERVAL 7 day)", OBJECT );
			break;
		case 'last_update':
			$out = $wpdb->get_results( "SELECT id,MAX(xml_import_timestamp),entity_type FROM import_table
									WHERE client_id = {$client_id} 
									GROUP BY entity_type", OBJECT );
			break;
		case 'last_30':
			$out = $wpdb->get_results( "SELECT id,xml_import_timestamp,entity_type FROM import_table 
											   WHERE client_id = {$client_id} AND 
											   DATE_SUB(xml_import_timestamp, INTERVAL 30 day)", OBJECT );
			break;
	}*/
	$out    = $wpdb->get_results( "SELECT id,xml_import_timestamp,entity_type FROM import_table 
											   WHERE client_id = {$client_id} AND id={$import_id}", OBJECT );
	$data   = [ 'regulation' => [], 'hearing' => [], 'legislation' => [], 'import_ids' => [] ];
	$groups = [ 'regulation' => [], 'hearing' => [], 'legislation' => [] ];
	foreach ( $out as $import ) {
		array_push( $groups[ $import->entity_type ], $import->id );
		//$data[ $import->entity_type ] = retrive_data( $import->entity_type, $client_id, $import->id );
		array_push( $data['import_ids'], $import->id );
	}
	//print_r($groups);
	foreach ( $groups as $group => $ids ) {
		$data[ $group ] = retrive_data( $group, $client_id, $ids );
	}
	echo json_encode( $data );
} else if ( isset( $_POST['import_ids'] ) && isset( $_POST['action'] ) ) {
	$action     = $_POST['action'];
	$import_ids = implode( ',', $_POST['import_ids'] );
	$client     = $_POST['client'];
	$update     = $wpdb->query( "UPDATE import_table SET curation_date = NOW() WHERE id IN ({$import_ids})" );
	if ( false === $update ) {
		echo json_encode( [
			'status'  => false,
			"message" => "There was error during approving upload, please contact administrator."
		] );
	} else {
		echo json_encode( [
			'status'  => true,
			"message" => "Successfully approved upload for client, {$client}. Email will be sent when bills are ready in the system."
		] );
	}
	exit();
} else if ( isset( $_POST['action'] ) && $_POST['action'] === 'get_import_comment_for_user' ) {
	$_result = $wpdb->get_row( $wpdb->prepare(
		"SELECT id,comment FROM import_mail_comments 
				WHERE import_table_id = %d 
				AND user_id = %d",$_POST['import_id'],$_POST['user_id']
	), OBJECT );

	if($_result) {
		echo json_encode( ["status"=>true,"comment"=>$_result->comment] );
	}elsE{
		echo json_encode(["status"=>false]);
	}
}else if(isset($_POST['action']) && $_POST['action'] === 'update_import_comment_for_user'){
	$all_users = $_POST['all_users'];
	if($all_users){
		//$wpdb->query($wpdb->prepare("SELECT * FROM "))
	}else {
		$_result = $wpdb->update( 'import_mail_comments', [ "comment" => $_POST['comment'] ], [
			'import_table_id' => $_POST['import_id'],
			'user_id'         => $_POST['user_id']
		] );
		if ( $_result ) {
			echo json_encode( [ "status" => true ] );
		} elsE {
			echo json_encode( [ "status" => false ] );
		}
	}
}else if ( isset( $_POST['client_id'] ) && isset( $_POST['action'] ) ) {
	$action    = $_POST['action'];
	$client_id = $_POST['client_id'];
	switch ( $action ) {
		case 'get_imports':
			echo json_encode( getClientImports( $client_id ) );
			break;
	}
}
