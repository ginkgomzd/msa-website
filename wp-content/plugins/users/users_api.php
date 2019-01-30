<?php

require( '../../../wp-load.php' );

if ( isset( $_POST['client_id'] ) and ! isset( $_POST['user_id'] ) ) {
	$client_id    = $_POST['client_id'];
	$msa = new MSAUser();
	echo json_encode( $msa->getClientUsers($client_id,['ID','user_login'] ));
} else if ( isset( $_POST['user_id'] )) {
	$msa_user = new MSAUser($_POST['user_id']);
	$settings = $msa_user->getUserSettings(true);
	echo json_encode($settings);
}
?>