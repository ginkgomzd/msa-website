<?php

require( '../../../wp-load.php' );
global $wpdb;
$client_name = $_GET["name"];
//var_dump($wpdb);

$result = $wpdb->get_results("SELECT * FROM `profile_match` where client LIKE '%$client_name%' GROUP by pname");
$x=json_encode($result);

echo $x;

?>