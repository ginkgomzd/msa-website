<?php // Include the library
include( '../../../wp-load.php' ); 
global $wpdb;

$user_id=get_current_user_id();
$client = get_user_meta( $user_id,'company', $single ); 

if(isset($_GET['cat'])) {
    // id index exists
    $cat = $_GET['cat'];
} else {
    $cat = '%';
}

$query="select pm.client, GROUP_CONCAT(DISTINCT pm.pname) as categories, l.id, l.session, l.state, l.type, l.number, l.title, l.sponsor_name, l.sponsor_url from profile_match pm inner join legislation l on l.id = pm.entity_id where pm.pname like '%$cat%' and pm.client like '$client[0]' group by pm.entity_id ";

$results = $wpdb->get_results($query, Object);

	$results = array(
			"sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData"=>$results);

echo json_encode($results);


?>