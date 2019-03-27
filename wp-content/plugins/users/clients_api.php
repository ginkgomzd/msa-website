<?php

require( '../../../wp-load.php' );
global $wpdb;
$client_id = $_GET["id"];
//$result = $wpdb->get_results("SELECT pm.id,pname,entity_type,entity_id,client_id,isFrontActive,isMailActive FROM `profile_match` AS pm LEFT JOIN `user_clients` AS us ON pm.client_id = us.id where us.id = {$client_id} GROUP BY pname");
$result = $wpdb->get_results("SELECT id,type,category,isfrontactive,ismailactive FROM client_settings WHERE client_id={$client_id}");
$x=json_encode($result);

echo $x;

?>