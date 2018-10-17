<?php
// Include the library
include( '../../../wp-load.php' );

global $wpdb;


$query = "SELECT entity_id FROM `profile_match` where client NOT like '%intu%' group by entity_id";
$results = $wpdb->get_results($query,OBJECT);



foreach($results as $result){
    
    $delete_query = "DELETE FROM `legislation` WHERE `legislation`.`id` = $result->entity_id ";
//    $results = $wpdb->get_results($delete_query,OBJECT);
}
?>




