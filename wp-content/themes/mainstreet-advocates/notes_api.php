<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/23/2018
 * Time: 10:16 PM
 */
include( '../../../wp-load.php' );

// dont allow access to visitors
if ( ! is_user_logged_in() ) {
	auth_redirect();
}else{
	$user = MSAvalidateUserRole();
}
if ( isset( $_POST['action'] ) ) {
	if ( $_POST['action'] === "add" && isset( $_POST['note_text'] ) ) {
		$client_id = null;
		if ( isset( $_POST['client_id'] ) ) {
			$client_id = $_POST['client_id'];
		}
		$note = $user->createNewNote( sanitize_textarea_field( $_POST['note_text'] ), $_POST['id'], $_POST['type'],$client_id);
		if ( isset( $note->inserted_id ) ) {
			echo json_encode( [
				'status'           => true,
				'action'           => 'insert',
				'insert_id'        => $note->inserted_id,
				"insert_user"      => $note->user->user_nicename,
				'insert_timestamp' => date_format( $note->note_timestamp, 'Y-m-d H:i:s' )
			] );
		} else {
			echo json_encode( [ 'status' => false, 'action' => 'insert' ] );
		}
	} else if ( $_POST['action'] === "remove" ) {
		$result = $user->deleteNote( $_POST['bill_id'], $_POST['note_id'], $_POST['type'] );
		echo json_encode( [ 'status' => ( $result ) ? true : false, 'action' => 'remove' ] );
	} else if ( $_POST['action'] === "edit" ) {
		$result = $user->editNote( $_POST['note_text'], $_POST['id'], $_POST['type'] );
		if ( $result !== false ) {
			echo json_encode( [
				'status'         => true,
				'action'         => 'edit',
				"edit_user"    => $result->user->user_nicename,
				'note_timestamp' => $result->note_timestamp
			] );
		} else {
			echo json_encode( [ 'status' => false, 'action' => 'edit' ] );
		}
	}
}

//before editing check if note belongs to this user
