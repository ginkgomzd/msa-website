<?php

require( '../../../wp-load.php' );
global $wpdb;
$user_id = $_GET["id"];
//var_dump($wpdb);

$result = $wpdb->get_results("SELECT * FROM `user_profile` where user_id='$user_id'");
$x=json_encode($result);

echo $x;

?>