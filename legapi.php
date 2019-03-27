<?php // Include the library
include( '../../../wp-load.php' );
global $wpdb;

//$user_id=get_current_user_id();
//$client = get_user_meta( $user_id,'company', true );
$user = MSAvalidateUserRole();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$client_id = null;
	if ( isset( $_POST['client_id'] ) ) {
		$client_id = $_POST['client_id'];
	}
	if ( isset( $_POST['action'] ) && $_POST['action'] === 'priority' ) {
		$user->reindexSolrCore();
		$result = $user->updateBillPriority( $_POST['id'], $_POST['type'], $_POST['status'], $client_id );
		echo json_encode( array( 'status' => ( $result === true ) ? true : false ) );
	}else if (isset($_POST['action']) && $_POST['action'] === 'hide'){
		$result = $user->hideBill($_POST['id'],$_POST['type'],$_POST['status'],$client_id);
		echo json_encode( array( 'status' => ( $result === true ) ? true : false ) );
	} elseif ( isset( $_POST['searchFilter'] ) ) {
		echo json_encode( $user->typeheadSuggestion( $_POST['searchFilter'] ) );
	}
} else if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
	$state    = '';
	$category = null;
	$search   = null;
	if ( isset( $_GET['cat'] ) ) {
		$cat = $_GET['cat'];
	} else {
		$cat = '%';
	}
	if ( isset( $_GET['text_filter'] ) ) {
		$search = $_GET['text_filter'];
	}
	if ( isset( $_GET['federal'] ) ) {
		if ( $_GET['federal'] === '*' ) {
			$state = '';
		} else {
			$state = $_GET['federal'];
		};
	}
	if ( isset( $_GET['category'] ) && $_GET['category'] !== '' ) {
		$category = $_GET['category'];
	}
	if ( isset( $_GET['draw'] ) ) {
		$draw = $_GET['draw'];
	}
	if ( $cat === 'legislation' ) {
		if ( $user->solr_active ) {
			$end_result = $user->getLegislationsSolr( $category, $state, $search, $draw, $_GET['start'], $_GET );
		} else {
			$end_result = $user->getLegislations( $category, $state, $draw, $_GET['start'], $_GET );
		}

	} else if ( $cat === 'regulation' ) {
		if ( $user->solr_active ) {
			$end_result = $user->getRegulationsSolr( $category, $state, $search, $draw, $_GET['start'], $_GET );
		} else {
			$end_result = $user->getRegulations( $category, $state );
		}

	} else if ( $cat === 'hearing' ) {
		if ( $user->solr_active ) {
			$end_result = $user->getHearingsSolr( $category, $state, $search, $draw, $_GET['start'], $_GET );
		} else {
			$end_result = $user->getHearings( $category );
		}
	}

	echo json_encode( $end_result );
}


?>