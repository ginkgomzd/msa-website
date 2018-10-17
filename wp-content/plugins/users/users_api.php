<?php

require( '../../../wp-load.php' );
global $wpdb;
$user_id = $_GET["id"];
//var_dump($wpdb);

$result = $wpdb->get_results("SELECT *,(SELECT meta_value FROM `wp_usermeta` where user_id = wp_users.ID and meta_key like '%company%') as company  FROM wp_users where id = $user_id");
$x=json_encode($result);

echo $x;

?>